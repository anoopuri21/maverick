<?php

namespace Tests\Feature;

use App\Models\Insight;
use App\Models\Program;
use App\Settings\HomepageSeoSettings;
use App\Support\SchemaJson;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_program_description_and_social_fallback_are_rendered_once(): void
    {
        $program = Program::query()->create([
            'title' => 'SEO Programme', 'slug' => 'seo-programme',
            'short_description' => 'Programme & leadership details', 'is_active' => true,
        ]);

        $this->assertDescriptions('/programs/'.$program->slug, 'Programme & leadership details');

        $program->seo()->create(['meta_description' => 'Custom course description']);
        $this->assertDescriptions('/programs/'.$program->slug, 'Custom course description');
    }

    public function test_explicit_social_descriptions_remain_independent_of_the_page_description(): void
    {
        $program = Program::query()->create([
            'title' => 'Social Overrides', 'slug' => 'social-overrides',
            'short_description' => 'Visible programme description', 'is_active' => true,
        ]);
        $program->seo()->create([
            'meta_description' => 'Search description',
            'og_description' => 'Custom OG description',
            'twitter_description' => 'Custom Twitter description',
        ]);

        $xpath = $this->xpath($this->get('/programs/'.$program->slug)->assertOk()->getContent());
        $this->assertCount(1, $xpath->query('//meta[@name="description"]'));
        $this->assertSame('Search description', $xpath->query('//meta[@name="description"]')->item(0)->getAttribute('content'));
        $this->assertSame('Custom OG description', $xpath->query('//meta[@property="og:description"]')->item(0)->getAttribute('content'));
        $this->assertSame('Custom Twitter description', $xpath->query('//meta[@name="twitter:description"]')->item(0)->getAttribute('content'));
    }

    public function test_article_and_listing_descriptions_are_not_duplicated(): void
    {
        $insight = Insight::query()->create([
            'title' => 'SEO Test Article', 'slug' => 'seo-test-article',
            'content' => '<p>Article text.</p>', 'excerpt' => 'Useful article excerpt',
            'categories' => ['blogs'], 'published_at' => now()->subDay(),
        ]);

        $this->assertDescriptions('/'.$insight->slug, 'Useful article excerpt');
        $this->assertDescriptions('/blogs', 'Explore cutting-edge business articles, corporate strategies, academic research, and leadership advice from the expert faculty at Maverick Business Academy.');
        $this->assertDescriptions('/news', 'Stay updated with the latest institutional news, campus announcements, and academic milestones from Maverick Business Academy.');
    }

    public function test_homepage_contact_and_mba_landing_keep_one_description(): void
    {
        foreach (['/', '/contact', '/online-mba-masters-uae', '/thank-you'] as $uri) {
            $xpath = $this->xpath($this->get($uri)->assertOk()->getContent());
            $descriptions = $xpath->query('//meta[@name="description"]');
            $this->assertCount(1, $descriptions, $uri);
            $this->assertNotSame('', $descriptions->item(0)->getAttribute('content'), $uri);
        }
    }

    public function test_homepage_schema_is_safe_and_valid_json(): void
    {
        $seo = app(HomepageSeoSettings::class);
        $seo->schema_json = json_encode(['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => '</script><script>alert(1)</script>']);
        $seo->save();

        $html = $this->get('/')->assertOk()->getContent();
        $xpath = $this->xpath($html);
        $scripts = $xpath->query('//script[@type="application/ld+json"]');
        $this->assertCount(1, $scripts);
        $this->assertSame('</script><script>alert(1)</script>', json_decode($scripts->item(0)->textContent, true, 512, JSON_THROW_ON_ERROR)['name']);
        $this->assertStringNotContainsString('</script><script>alert(1)</script>', $html);
    }

    public function test_invalid_schema_is_not_rendered(): void
    {
        $seo = app(HomepageSeoSettings::class);
        $seo->schema_json = '<script>{"@type":"Organization"}</script>';
        $seo->save();
        $xpath = $this->xpath($this->get('/')->assertOk()->getContent());
        $this->assertCount(0, $xpath->query('//script[@type="application/ld+json"]'));
    }

    public function test_schema_encoder_preserves_valid_objects_and_arrays_and_rejects_invalid_values(): void
    {
        $this->assertSame(['@type' => 'Organization'], json_decode(SchemaJson::encode('{"@type":"Organization"}'), true));
        $this->assertSame([['@type' => 'Article']], json_decode(SchemaJson::encode('[{"@type":"Article"}]'), true));
        $this->assertNull(SchemaJson::encode('{broken'));
        $this->assertNull(SchemaJson::encode('"not an object"'));
        $this->assertNull(SchemaJson::encode(null));
    }

    private function assertDescriptions(string $uri, string $expected): void
    {
        $xpath = $this->xpath($this->get($uri)->assertOk()->getContent());
        $descriptions = $xpath->query('//meta[@name="description"]');
        $this->assertCount(1, $descriptions, $uri);
        $this->assertSame($expected, $descriptions->item(0)->getAttribute('content'));
        $this->assertSame($expected, $xpath->query('//meta[@property="og:description"]')->item(0)->getAttribute('content'));
    }

    private function xpath(string $html): DOMXPath
    {
        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return new DOMXPath($document);
    }
}
