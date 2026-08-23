<?php

namespace App\Services;

use App\Settings\ZohoCampaignsSettings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class ZohoCampaignsService
{
    private const TOKEN_CACHE_KEY = 'zoho_campaigns.access_token';

    private const TOKEN_CACHE_SECONDS = 3300;

    public function subscribe(string $email, array $extra = []): bool
    {
        if (! $this->isConfigured()) {
            return false;
        }

        return $this->attemptSubscribe($email, $extra, true);
    }

    public function isConfigured(): bool
    {
        $settings = safe_settings(ZohoCampaignsSettings::class);

        return ! empty($settings->enabled)
            && filled($settings->client_id)
            && filled($settings->client_secret)
            && filled($settings->refresh_token)
            && filled($settings->list_key);
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public function testConnection(): array
    {
        if (! $this->hasCredentials()) {
            return [
                'ok' => false,
                'message' => 'Fill client ID, client secret, and refresh token first.',
            ];
        }

        Cache::forget(self::TOKEN_CACHE_KEY);

        $token = $this->refreshAccessToken();

        if (! $token) {
            return [
                'ok' => false,
                'message' => 'Could not refresh access token. Check credentials and region.',
            ];
        }

        Cache::put(self::TOKEN_CACHE_KEY, $token, self::TOKEN_CACHE_SECONDS);

        return [
            'ok' => true,
            'message' => 'Connected to Zoho Campaigns successfully.',
        ];
    }

    public function accessToken(): ?string
    {
        if (! $this->hasCredentials()) {
            return null;
        }

        return Cache::remember(self::TOKEN_CACHE_KEY, self::TOKEN_CACHE_SECONDS, function () {
            return $this->refreshAccessToken();
        });
    }

    private function attemptSubscribe(string $email, array $extra, bool $allowRetry): bool
    {
        try {
            $token = $this->accessToken();

            if (! $token) {
                return false;
            }

            $settings = safe_settings(ZohoCampaignsSettings::class);
            $campaigns = $this->campaignsBaseUrl((string) ($settings->region ?: 'com'));

            $response = Http::asForm()
                ->timeout(8)
                ->withHeaders(['Authorization' => 'Zoho-oauthtoken '.$token])
                ->post("{$campaigns}/api/v1.1/json/listsubscribe", [
                    'resfmt' => 'JSON',
                    'listkey' => $settings->list_key,
                    'contactinfo' => json_encode(array_merge(['Contact Email' => $email], $extra)),
                    'source' => $settings->source ?: 'Website Footer',
                ]);

            if ($response->status() === 401 && $allowRetry) {
                Cache::forget(self::TOKEN_CACHE_KEY);

                return $this->attemptSubscribe($email, $extra, false);
            }

            $body = $response->json();

            if (($body['status'] ?? '') !== 'success') {
                logger()->warning('Zoho Campaigns listsubscribe failed', [
                    'code' => $body['code'] ?? null,
                    'message' => $body['message'] ?? $response->body(),
                    'email' => $email,
                ]);

                return false;
            }

            return true;
        } catch (Throwable $e) {
            logger()->error('Zoho Campaigns subscribe failed: '.$e->getMessage(), [
                'email' => $email,
            ]);

            return false;
        }
    }

    private function refreshAccessToken(): ?string
    {
        $settings = safe_settings(ZohoCampaignsSettings::class);
        $accounts = $this->accountsBaseUrl((string) ($settings->region ?: 'com'));

        $response = Http::asForm()
            ->timeout(8)
            ->post("{$accounts}/oauth/v2/token", [
                'client_id' => $settings->client_id,
                'client_secret' => $settings->client_secret,
                'refresh_token' => $settings->refresh_token,
                'grant_type' => 'refresh_token',
            ]);

        if (! $response->successful()) {
            logger()->error('Zoho Campaigns token refresh failed', [
                'body' => $response->body(),
            ]);

            return null;
        }

        return $response->json('access_token');
    }

    private function hasCredentials(): bool
    {
        $settings = safe_settings(ZohoCampaignsSettings::class);

        return filled($settings->client_id)
            && filled($settings->client_secret)
            && filled($settings->refresh_token);
    }

    private function regionHost(string $region): string
    {
        return match ($region) {
            'eu' => 'zoho.eu',
            'in' => 'zoho.in',
            'com.au' => 'zoho.com.au',
            'jp' => 'zoho.jp',
            default => 'zoho.com',
        };
    }

    private function accountsBaseUrl(string $region): string
    {
        return 'https://accounts.'.$this->regionHost($region);
    }

    private function campaignsBaseUrl(string $region): string
    {
        return 'https://campaigns.'.$this->regionHost($region);
    }
}
