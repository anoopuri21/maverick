<?php

namespace Tests\Feature;

use App\Models\PartnerLogo;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\Testimonial;
use App\Models\UniversityPartner;
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

        Testimonial::query()->create([
            'name' => 'Cached testimonial',
            'video_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'is_active' => true,
        ]);

        $this->get('/')->assertSuccessful();

        $homepage = Cache::get(PublicContentCache::HOMEPAGE);
        $this->assertIsArray($homepage);
        $this->assertArrayNotHasKey('alumniLogos', $homepage);
        $this->assertArrayNotHasKey('testimonialsJson', $homepage);
        $this->assertSame('Cached testimonial', $homepage['testimonials'][0]['name']);
        $this->assertArrayHasKey('thumbnail_url', $homepage['testimonials'][0]);
        $this->assertArrayHasKey('embed_url', $homepage['testimonials'][0]);
        $this->assertArrayNotHasKey('video_url', $homepage['testimonials'][0]);
        $this->assertArrayNotHasKey('video_type', $homepage['testimonials'][0]);

        $alumni = Cache::get(PublicContentCache::ALUMNI_LOGOS);
        $this->assertTrue(is_array($alumni) || $alumni === null);
    }

    public function test_public_content_cache_reuses_a_cached_non_null_value(): void
    {
        $key = 'public-content-cache-test.cached-value';
        Cache::put($key, ['cached' => true], 60);
        $callbackCalled = false;

        $value = PublicContentCache::remember($key, function () use (&$callbackCalled) {
            $callbackCalled = true;

            return ['rebuilt' => true];
        });

        $this->assertSame(['cached' => true], $value);
        $this->assertFalse($callbackCalled);
        PublicContentCache::forget($key);
    }

    public function test_public_content_cache_treats_null_as_a_miss(): void
    {
        $key = 'public-content-cache-test.null-value';
        Cache::put($key, null, 60);
        $callbackCalled = false;

        $value = PublicContentCache::remember($key, function () use (&$callbackCalled) {
            $callbackCalled = true;

            return ['rebuilt' => true];
        });

        $this->assertSame(['rebuilt' => true], $value);
        $this->assertTrue($callbackCalled);
        PublicContentCache::forget($key);
    }

    public function test_hydrate_rows_sets_relations_from_camel_or_snake_keys(): void
    {
        $snakeHydrated = PublicContentCache::hydrateRows(Program::class, [[
            'id' => 1,
            'title' => 'MBA',
            'slug' => 'mba',
            'program_category' => ['name' => 'Masters', 'slug' => 'masters'],
            'university_partner' => ['name' => 'Example University'],
        ]], [
            'program_category' => ProgramCategory::class,
            'university_partner' => UniversityPartner::class,
        ]);

        $camelHydrated = PublicContentCache::hydrateRows(Program::class, [[
            'id' => 2,
            'title' => 'BBA',
            'slug' => 'bba',
            'programCategory' => ['name' => 'Bachelors', 'slug' => 'bachelors'],
            'universityPartner' => ['name' => 'Partner University'],
        ]], [
            'program_category' => ProgramCategory::class,
            'university_partner' => UniversityPartner::class,
        ]);

        $this->assertSame('Masters', $snakeHydrated->first()->programCategory->name);
        $this->assertSame('masters', $snakeHydrated->first()->programCategory->slug);
        $this->assertSame('Example University', $snakeHydrated->first()->universityPartner->name);
        $this->assertSame('Bachelors', $camelHydrated->first()->programCategory->name);
        $this->assertSame('bachelors', $camelHydrated->first()->programCategory->slug);
        $this->assertSame('Partner University', $camelHydrated->first()->universityPartner->name);
        $this->assertFalse(is_array($camelHydrated->first()->programCategory));
        $this->assertFalse(is_array($camelHydrated->first()->universityPartner));
    }
}
