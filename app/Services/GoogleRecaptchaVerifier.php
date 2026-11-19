<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class GoogleRecaptchaVerifier
{
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    private static bool $skipLogged = false;

    public function verify(mixed $token): bool
    {
        // An unconfigured CAPTCHA must not make every public form unusable.
        // Callers hide the widget in this state, so there is nothing to verify.
        if (! $this->isEnabled()) {
            if (! self::$skipLogged) {
                logger()->warning('reCAPTCHA verification skipped: RECAPTCHA_ENABLED is false or keys are missing.');
                self::$skipLogged = true;
            }

            return true;
        }

        $secret = config('services.recaptcha.secret_key');

        if (! is_string($token) || trim($token) === '' || ! is_string($secret) || trim($secret) === '') {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post(self::VERIFY_URL, [
                    'secret' => $secret,
                    'response' => trim($token),
                ]);
        } catch (ConnectionException) {
            return false;
        }

        if (! $response->successful() || $response->json('success') !== true) {
            return false;
        }

        $hostname = strtolower(rtrim((string) $response->json('hostname'), '.'));
        $allowedHostnames = array_map(
            static fn (string $host): string => strtolower(rtrim(trim($host), '.')),
            config('services.recaptcha.allowed_hostnames', [])
        );

        return in_array($hostname, $allowedHostnames, true);
    }

    /** True only when the widget is rendered and server-side checks can run. */
    public function isEnabled(): bool
    {
        return (bool) config('services.recaptcha.enabled', false)
            && filled(config('services.recaptcha.site_key'))
            && filled(config('services.recaptcha.secret_key'));
    }
}
