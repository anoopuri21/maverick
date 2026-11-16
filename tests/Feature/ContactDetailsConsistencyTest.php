<?php

namespace Tests\Feature;

use App\Settings\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactDetailsConsistencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_and_both_footers_use_the_same_contact_settings(): void
    {
        $site = app(SiteSettings::class);
        $site->address = 'Test UAE Address';
        $site->phone = '+971 (50) 123-4567';
        $site->phone_secondary = '+971 (55) 987-6543';
        $site->email = 'uae@example.test';
        $site->uk_address = 'Test UK Address';
        $site->uk_phone = '+44 (20) 1234-5678';
        $site->uk_email = 'uk@example.test';
        $site->save();

        foreach (['/contact', '/', '/online-mba-masters-uae'] as $uri) {
            $response = $this->get($uri)->assertOk();
            foreach (['Test UAE Address', 'Test UK Address', 'uae@example.test', 'uk@example.test',
                'tel:+971501234567', 'tel:+971559876543', 'tel:+442012345678'] as $value) {
                $response->assertSee($value, false);
            }
        }
    }

    public function test_secondary_phone_is_visible_when_primary_is_blank(): void
    {
        $site = app(SiteSettings::class);
        $site->phone = null;
        $site->phone_secondary = '+971 (55) 987-6543';
        $site->save();

        $response = $this->get('/contact')->assertOk();
        $response->assertSee('+971 (55) 987-6543');
        $response->assertSee('tel:+971559876543', false);
        $this->assertGreaterThanOrEqual(2, substr_count($response->getContent(), 'tel:+971559876543'));
    }
}
