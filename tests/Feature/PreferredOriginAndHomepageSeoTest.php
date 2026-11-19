<?php

namespace Tests\Feature;

use App\Settings\HomepageSeoSettings;
use App\Support\PreferredOrigin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PreferredOriginAndHomepageSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_known_alternate_origins_redirect_with_path_and_query_intact(): void
    {
        config(['seo.origin_redirect_enabled' => true]);
        foreach (['http://mbalondon.org.uk', 'http://www.mbalondon.org.uk', 'https://www.mbalondon.org.uk'] as $origin) {
            $response = $this->get($origin.'/programs/example%20name?ref=a%2Bb&x=1');
            $response->assertStatus(301);
            $this->assertSame('https://mbalondon.org.uk/programs/example%20name?ref=a%2Bb&x=1', $response->headers->get('Location'));
        }
    }

    public function test_unsafe_requests_are_redirected_without_changing_method(): void
    {
        config(['seo.origin_redirect_enabled' => true]);
        $response = $this->post('http://www.mbalondon.org.uk/programs/enquire?source=test', ['name' => 'Example']);
        $response->assertStatus(308);
        $this->assertSame('https://mbalondon.org.uk/programs/enquire?source=test', $response->headers->get('Location'));
    }

    public function test_preferred_and_nonproduction_hosts_are_not_origin_redirected(): void
    {
        config(['seo.origin_redirect_enabled' => true]);
        $this->get('https://mbalondon.org.uk/')->assertOk();
        $this->get('http://localhost/')->assertOk();
        $this->get('https://preview.example.com/')->assertOk();
    }

    public function test_redirect_is_disabled_until_hosting_is_verified(): void
    {
        config(['seo.origin_redirect_enabled' => false]);
        $html = $this->get('http://www.mbalondon.org.uk/')->assertOk()->getContent();
        $this->assertStringContainsString('rel="canonical" href="https://mbalondon.org.uk/"', $html);
        $this->assertStringContainsString('property="og:url" content="https://mbalondon.org.uk/"', $html);
        $xml = $this->get('http://www.mbalondon.org.uk/sitemap.xml')->assertOk()->getContent();
        $this->assertStringContainsString('<loc>https://mbalondon.org.uk/</loc>', $xml);
        $this->assertStringNotContainsString('<loc>http://www.mbalondon.org.uk/', $xml);
    }

    public function test_preferred_root_has_one_trailing_slash_and_other_urls_keep_their_paths(): void
    {
        $request = Request::create('http://www.mbalondon.org.uk/');
        $this->assertSame('https://mbalondon.org.uk/', PreferredOrigin::normalize('http://www.mbalondon.org.uk', $request));
        $this->assertSame('https://mbalondon.org.uk/', PreferredOrigin::normalize('http://www.mbalondon.org.uk/', $request));
        $this->assertSame('https://mbalondon.org.uk/?ref=1', PreferredOrigin::normalize('http://www.mbalondon.org.uk?ref=1', $request));
        $this->assertSame('https://mbalondon.org.uk/programs?ref=1', PreferredOrigin::normalize('http://www.mbalondon.org.uk/programs?ref=1', $request));
        $this->assertSame('https://other.example', PreferredOrigin::normalize('https://other.example', $request));
    }

    public function test_forwarded_https_is_not_redirected_when_the_proxy_is_trusted(): void
    {
        config(['seo.origin_redirect_enabled' => true]);
        $this->withHeader('X-Forwarded-Proto', 'https')
            ->get('http://mbalondon.org.uk/')
            ->assertOk();
    }

    public function test_homepage_fallbacks_and_configured_social_values(): void
    {
        $seo = app(HomepageSeoSettings::class);
        $seo->meta_title = null;
        $seo->meta_description = null;
        $seo->og_title = null;
        $seo->twitter_title = null;
        $seo->save();

        $html = $this->get('https://mbalondon.org.uk/')->assertOk()->getContent();
        $this->assertStringContainsString('<title>Business Programmes &amp; Pathways | Maverick Business Academy</title>', $html);
        $this->assertStringContainsString('property="og:title" content="Business Programmes &amp; Pathways | Maverick Business Academy"', $html);
        $this->assertStringContainsString('name="twitter:title" content="Business Programmes &amp; Pathways | Maverick Business Academy"', $html);
        $this->assertSame(1, substr_count($html, 'name="description"'));

        $seo->meta_title = 'Custom title';
        $seo->meta_description = 'Custom description';
        $seo->og_title = 'Custom social title';
        $seo->twitter_title = 'Custom Twitter title';
        $seo->og_image_url = 'https://cdn.example.com/share.jpg';
        $seo->twitter_image_url = null;
        $seo->canonical_url = 'http://www.mbalondon.org.uk/';
        $seo->save();

        $html = $this->get('https://mbalondon.org.uk/')->assertOk()->getContent();
        $this->assertStringContainsString('<title>Custom title</title>', $html);
        $this->assertStringContainsString('property="og:title" content="Custom social title"', $html);
        $this->assertStringContainsString('name="twitter:title" content="Custom Twitter title"', $html);
        $this->assertStringContainsString('property="og:image" content="https://cdn.example.com/share.jpg"', $html);
        $this->assertStringContainsString('name="twitter:image" content="https://cdn.example.com/share.jpg"', $html);
        $this->assertStringContainsString('rel="canonical" href="https://mbalondon.org.uk/"', $html);
    }

    public function test_other_domain_canonical_override_is_not_rewritten(): void
    {
        $seo = app(HomepageSeoSettings::class);
        $seo->canonical_url = 'https://partner.example.org/page';
        $seo->save();

        $html = $this->get('https://mbalondon.org.uk/')->assertOk()->getContent();
        $this->assertStringContainsString('rel="canonical" href="https://partner.example.org/page"', $html);
    }

    public function test_canonical_host_is_always_treated_as_a_known_origin(): void
    {
        config([
            'site.preferred_origin.url' => 'https://example.org',
            'site.preferred_origin.hosts' => [],
        ]);

        $this->assertTrue(PreferredOrigin::appliesTo(Request::create('https://example.org/')));
    }

    public function test_custom_port_canonical_origin_is_normalized_without_duplicating_the_port(): void
    {
        config([
            'site.preferred_origin.url' => 'http://localhost:8000',
            'site.preferred_origin.hosts' => ['localhost'],
        ]);

        $request = Request::create('http://localhost:8000/programs');

        $this->assertSame(
            'http://localhost:8000/programs',
            PreferredOrigin::normalize('http://localhost:8000/programs', $request)
        );
    }

    public function test_http_canonical_origin_does_not_redirect_to_itself(): void
    {
        config([
            'seo.origin_redirect_enabled' => true,
            'site.preferred_origin.url' => 'http://localhost:8000',
            'site.preferred_origin.hosts' => ['localhost'],
        ]);

        $this->get('http://localhost:8000/programs')->assertOk();
    }

    public function test_preferred_origin_is_configurable(): void
    {
        config([
            'site.preferred_origin.url' => 'https://example.org',
            'site.preferred_origin.hosts' => ['example.org', 'www.example.org'],
        ]);

        $request = Request::create('http://www.example.org/programs?ref=1');

        $this->assertTrue(PreferredOrigin::appliesTo($request));
        $this->assertSame('https://example.org/programs?ref=1', PreferredOrigin::normalize('http://www.example.org/programs?ref=1', $request));
        $this->assertSame('https://mbalondon.org.uk', PreferredOrigin::URL);
    }

    public function test_sitemap_normalizes_known_origin_canonical_override(): void
    {
        $seo = app(HomepageSeoSettings::class);
        $seo->canonical_url = 'http://www.mbalondon.org.uk/';
        $seo->save();
        Cache::flush();

        $html = $this->get('https://mbalondon.org.uk/sitemap.xml')->assertOk()->getContent();
        $this->assertStringContainsString('<loc>https://mbalondon.org.uk/</loc>', $html);
        $this->assertStringNotContainsString('<loc>http://www.mbalondon.org.uk/', $html);
    }
}
