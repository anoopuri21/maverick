<?php

namespace Tests\Feature;

use App\Models\PartnerLogo;
use App\Settings\HeroSettings;
use App\Support\PublicContentCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublicContentCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_writes_and_flushes_public_cache_on_model_save(): void
    {
        Cache::flush();

        $this->get('/')->assertSuccessful();
        $this->assertTrue(Cache::has(PublicContentCache::HOMEPAGE));

        PartnerLogo::query()->create([
            'name' => 'Cache bust',
            'logo_url' => 'https://example.com/logo.png',
            'type' => 'alumni',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->assertFalse(Cache::has(PublicContentCache::HOMEPAGE));
    }

    public function test_homepage_cache_flushes_when_settings_are_saved(): void
    {
        Cache::flush();

        $this->get('/')->assertSuccessful();
        $this->assertTrue(Cache::has(PublicContentCache::HOMEPAGE));

        $hero = app(HeroSettings::class);
        $hero->eyebrow = 'Cached eyebrow';
        $hero->save();

        $this->assertFalse(Cache::has(PublicContentCache::HOMEPAGE));
    }

    public function test_homepage_and_alumni_cache_store_plain_arrays(): void
    {
        Cache::flush();

        $this->get('/')->assertSuccessful();

        $homepage = Cache::get(PublicContentCache::HOMEPAGE);
        $this->assertIsArray($homepage);
        $this->assertIsArray($homepage['alumniLogos'] ?? null);

        $alumni = Cache::get(PublicContentCache::ALUMNI_LOGOS);
        $this->assertTrue(is_array($alumni) || $alumni === null);
    }
}
