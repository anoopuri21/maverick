<?php

namespace Tests\Concerns;

use Illuminate\Support\Facades\Http;

trait FakesRecaptcha
{
    /**
     * @param  array<string, mixed>  $additionalResponses
     */
    private function fakeRecaptcha(bool $success = true, array $additionalResponses = []): void
    {
        config([
            'services.recaptcha.enabled' => true,
            'services.recaptcha.site_key' => 'test-site-key',
            'services.recaptcha.secret_key' => 'test-secret-key',
            'services.recaptcha.allowed_hostnames' => ['localhost'],
        ]);

        Http::fake(array_merge([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => $success,
                'hostname' => 'localhost',
            ], 200),
        ], $additionalResponses));
    }
}
