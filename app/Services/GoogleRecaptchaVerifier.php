<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class GoogleRecaptchaVerifier
{
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function verify(mixed $token): bool
    {
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
}
