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

        // Probe the real API: fetch mailing lists to verify endpoint, scope and list key.
        try {
            $settings = safe_settings(ZohoCampaignsSettings::class);
            $base = $this->apiBaseUrl((string) ($settings->region ?: 'com'));
            $path = $this->usesMarketingAutomation()
                ? '/api/v1/getmailinglists'
                : '/api/v1.1/getmailinglists';

            $response = Http::asForm()
                ->timeout(8)
                ->withHeaders(['Authorization' => 'Zoho-oauthtoken '.$token])
                ->get("{$base}{$path}", [
                    'resfmt' => 'JSON',
                    'sort' => 'asc',
                    'fromindex' => 1,
                    'range' => 100,
                ]);

            $body = $response->json();

            if (! is_array($body) || ($body['status'] ?? '') !== 'success') {
                $code = $body['code'] ?? $body['Code'] ?? null;

                if ($code === 'INVALID_OAUTHSCOPE' || $code === 'INVALID_OAUTH_SCOPE') {
                    return [
                        'ok' => false,
                        'message' => $this->usesMarketingAutomation()
                            ? 'Token scope is not valid for the Marketing Automation endpoint. Generate a new token with scope ZohoMarketingAutomation.lead.ALL.'
                            : 'Token scope is not valid for the classic Campaigns endpoint. New Zoho accounts (new Campaigns UI) must switch API endpoint to Marketing Automation and use a ZohoMarketingAutomation scope.',
                    ];
                }

                return [
                    'ok' => false,
                    'message' => 'Token obtained, but the API test call failed'
                        .($code ? ' (code '.$code.')' : '')
                        .'. Check region and API endpoint.',
                ];
            }

            $lists = is_array($body['list_of_details'] ?? null) ? $body['list_of_details'] : [];
            $count = count($lists);
            $listKey = (string) ($settings->list_key ?: '');

            foreach ($lists as $list) {
                if ($listKey !== '' && ($list['listkey'] ?? '') === $listKey) {
                    $name = $list['listname'] ?? 'saved list';

                    return [
                        'ok' => true,
                        'message' => "Connected successfully — mailing list '{$name}' matches the saved list key ({$count} lists visible).",
                    ];
                }
            }

            return [
                'ok' => true,
                'message' => "Connected successfully ({$count} mailing lists visible), but the saved list key was not found in this account. Copy the list key again from the list's setup page.",
            ];
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'message' => 'Token refreshed, but the API test call failed: '.$e->getMessage(),
            ];
        }
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
            $base = $this->apiBaseUrl((string) ($settings->region ?: 'com'));
            $path = $this->usesMarketingAutomation()
                ? '/api/v1/json/listsubscribe'
                : '/api/v1.1/json/listsubscribe';

            $response = Http::asForm()
                ->timeout(8)
                ->withHeaders(['Authorization' => 'Zoho-oauthtoken '.$token])
                ->post("{$base}{$path}", $this->usesMarketingAutomation() ? [
                    'resfmt' => 'JSON',
                    'listkey' => $settings->list_key,
                    'leadinfo' => json_encode(array_merge(['Lead Email' => $email], $extra)),
                    'sources' => $settings->source ?: 'Website Footer',
                ] : [
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

            if (! is_array($body) || ($body['status'] ?? '') !== 'success') {
                logger()->warning('Zoho Campaigns listsubscribe failed', [
                    'code' => $body['code'] ?? $body['Code'] ?? null,
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

    private function usesMarketingAutomation(): bool
    {
        $settings = safe_settings(ZohoCampaignsSettings::class);

        return ($settings->api_stack ?? 'campaigns') === 'marketing_automation';
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

    private function apiBaseUrl(string $region): string
    {
        $host = $this->regionHost($region);

        return $this->usesMarketingAutomation()
            ? "https://marketingautomation.{$host}"
            : "https://campaigns.{$host}";
    }
}
