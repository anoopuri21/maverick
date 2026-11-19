<?php

namespace Tests\Unit;

use App\Services\GoogleRecaptchaVerifier;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GoogleRecaptchaVerifierTest extends TestCase
{
    public function test_unconfigured_recaptcha_does_not_block_submissions(): void
    {
        config([
            'services.recaptcha.enabled' => false,
            'services.recaptcha.site_key' => null,
            'services.recaptcha.secret_key' => null,
        ]);

        Http::fake();

        $this->assertTrue(app(GoogleRecaptchaVerifier::class)->verify('anything'));
        $this->assertFalse(recaptcha_enabled());
        Http::assertNothingSent();
    }

    public function test_enabled_recaptcha_accepts_an_allowed_hostname(): void
    {
        $this->configureRecaptcha(['hostname' => 'localhost']);

        $this->assertTrue(app(GoogleRecaptchaVerifier::class)->verify('valid-token'));
        $this->assertTrue(recaptcha_enabled());
    }

    public function test_enabled_recaptcha_rejects_an_unknown_hostname(): void
    {
        $this->configureRecaptcha(['hostname' => 'evil.example.com']);

        $this->assertFalse(app(GoogleRecaptchaVerifier::class)->verify('valid-token'));
    }

    public function test_enabled_recaptcha_rejects_failed_and_unreachable_verification(): void
    {
        config([
            'services.recaptcha.enabled' => true,
            'services.recaptcha.site_key' => 'site',
            'services.recaptcha.secret_key' => 'secret',
            'services.recaptcha.allowed_hostnames' => ['localhost'],
        ]);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([], 500),
        ]);
        $this->assertFalse(app(GoogleRecaptchaVerifier::class)->verify('token'));

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
            ]),
        ]);
        $this->assertFalse(app(GoogleRecaptchaVerifier::class)->verify('token'));
    }

    /**
     * @param  array<string, mixed>  $response
     */
    private function configureRecaptcha(array $response): void
    {
        config([
            'services.recaptcha.enabled' => true,
            'services.recaptcha.site_key' => 'site',
            'services.recaptcha.secret_key' => 'secret',
            'services.recaptcha.allowed_hostnames' => ['localhost'],
        ]);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(array_merge([
                'success' => true,
                'hostname' => 'localhost',
            ], $response)),
        ]);
    }
}
