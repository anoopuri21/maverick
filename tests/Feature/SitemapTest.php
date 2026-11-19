<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Insight;
use App\Models\Program;
use App\Settings\EventsSeoSettings;
use App\Settings\HomepageSeoSettings;
use App\Settings\OurStorySeoSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.url' => 'http://localhost']);
        Cache::flush();
    }

    public function test_sitemap_is_valid_xml_and_registered_before_the_insight_catch_all(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $this->assertSame('application/xml; charset=utf-8', $response->headers->get('Content-Type'));
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', $response->getContent());

        $document = new \DOMDocument();
        $this->assertTrue($document->loadXML($response->getContent()));
        $this->assertSame('urlset', $document->documentElement->localName);
        $this->assertSame('http://www.sitemaps.org/schemas/sitemap/0.9', $document->documentElement->namespaceURI);

        $routeNames = array_map(
            static fn ($route) => $route->getName(),
            Route::getRoutes()->getRoutes()
        );
        $sitemapPosition = array_search('sitemap', $routeNames, true);
        $catchAllPosition = array_search('insights.show', $routeNames, true);

        $this->assertNotFalse($sitemapPosition);
        $this->assertNotFalse($catchAllPosition);
        $this->assertLessThan($catchAllPosition, $sitemapPosition);
    }

    public function test_sitemap_lists_published_programs_events_blogs_and_news_with_lastmod(): void
    {
        $program = $this->createProgram('published-program');
        $event = $this->createEvent('published-event');
        $idFallbackEvent = Event::withoutEvents(fn () => Event::query()->create([
            'title' => 'ID fallback event',
            'slug' => '',
            'event_date' => now()->toDateString(),
            'is_active' => true,
        ]));
        $blog = $this->createInsight('published-blog', ['blogs'], now()->subDay());
        $news = $this->createInsight('published-news', ['news'], now()->subDay());

        $this->get(route('events.show', $idFallbackEvent->id))->assertOk();

        $response = $this->get('/sitemap.xml');
        $response->assertOk();
        $locs = $this->locs($response->getContent());
        $lastmods = $this->lastmods($response->getContent());

        foreach ([
            route('home'),
            route('programs.show', $program->slug),
            route('events.show', $event->slug ?: $event->id),
            route('events.show', $idFallbackEvent->slug ?: $idFallbackEvent->id),
            route('insights.show', $blog->slug),
            route('insights.show', $news->slug),
        ] as $expectedUrl) {
            $this->assertContains($expectedUrl, $locs);
        }

        foreach ([$program, $event, $idFallbackEvent, $blog, $news] as $record) {
            $this->assertContains($record->fresh()->updated_at->toAtomString(), $lastmods);
        }
    }

    public function test_sitemap_excludes_inactive_unpublished_noindex_and_non_page_urls(): void
    {
        $inactiveProgram = $this->createProgram('inactive-program', false);
        $noindexProgram = $this->createProgram('noindex-program');
        $noindexProgram->seo()->create(['robots' => 'NOINDEX, FOLLOW']);
        $inactiveEvent = $this->createEvent('inactive-event', false);
        $registrationEvent = $this->createEvent('registration-event');
        $draft = $this->createInsight('draft-insight', ['blogs'], null);
        $scheduled = $this->createInsight('scheduled-insight', ['news'], now()->addDay());
        $otherCategory = $this->createInsight('other-category-insight', ['student-stories'], now()->subDay());

        $homeSeo = app(HomepageSeoSettings::class);
        $homeSeo->robots = 'noindex, nofollow';
        $homeSeo->save();

        $eventsSeo = app(EventsSeoSettings::class);
        $eventsSeo->robots = 'noindex, nofollow';
        $eventsSeo->save();

        $response = $this->get('/sitemap.xml');
        $response->assertOk();
        $locs = $this->locs($response->getContent());

        foreach ([
            route('home'),
            route('programs.show', $inactiveProgram->slug),
            route('programs.show', $noindexProgram->slug),
            route('events.show', $inactiveEvent->slug ?: $inactiveEvent->id),
            route('events.show', $registrationEvent->slug ?: $registrationEvent->id),
            route('events.register', $registrationEvent->slug ?: $registrationEvent->id),
            route('insights.show', $draft->slug),
            route('insights.show', $scheduled->slug),
            route('insights.show', $otherCategory->slug),
            route('thank-you'),
            route('programs.enquire'),
            route('newsletter.subscribe'),
            route('student-success.stories'),
            route('student-success.videos'),
        ] as $excludedUrl) {
            $this->assertNotContains($excludedUrl, $locs);
        }

        $this->assertStringNotContainsString('/admin', implode("\n", $locs));
    }

    public function test_canonical_urls_are_used_only_when_their_host_matches_the_app_host(): void
    {
        $homeSeo = app(HomepageSeoSettings::class);
        $homeSeo->canonical_url = 'https://external.example/home';
        $homeSeo->save();

        $ourStorySeo = app(OurStorySeoSettings::class);
        $ourStorySeo->canonical_url = 'http://localhost/our-story?source=seo&language=en';
        $ourStorySeo->save();

        $sameHostProgram = $this->createProgram('same-host-program');
        $sameHostProgram->seo()->create([
            'canonical_url' => 'http://localhost/programs/same-host-program?source=seo',
        ]);

        $externalProgram = $this->createProgram('external-host-program');
        $externalProgram->seo()->create([
            'canonical_url' => 'https://external.example/programs/external-host-program',
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertOk();
        $locs = $this->locs($response->getContent());

        $this->assertContains('http://localhost/our-story?source=seo&language=en', $locs);
        $this->assertContains('http://localhost/programs/same-host-program?source=seo', $locs);
        $this->assertContains(route('programs.show', $externalProgram->slug), $locs);
        $this->assertContains(route('home'), $locs);
        $this->assertNotContains('https://external.example/home', $locs);
        $this->assertNotContains('https://external.example/programs/external-host-program', $locs);
    }

    private function createProgram(string $slug, bool $active = true): Program
    {
        return Program::query()->create([
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'is_active' => $active,
        ]);
    }

    private function createEvent(string $slug, bool $active = true): Event
    {
        return Event::query()->create([
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'event_date' => now()->toDateString(),
            'is_active' => $active,
        ]);
    }

    private function createInsight(string $slug, array $categories, ?Carbon $publishedAt): Insight
    {
        return Insight::query()->create([
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'categories' => $categories,
            'content' => '<p>Sitemap test content.</p>',
            'published_at' => $publishedAt,
        ]);
    }

    private function locs(string $xml): array
    {
        $document = new \DOMDocument();
        $this->assertTrue($document->loadXML($xml));

        $locs = [];
        foreach ($document->getElementsByTagNameNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'loc') as $node) {
            $locs[] = $node->textContent;
        }

        return $locs;
    }

    private function lastmods(string $xml): array
    {
        $document = new \DOMDocument();
        $this->assertTrue($document->loadXML($xml));

        $lastmods = [];
        foreach ($document->getElementsByTagNameNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'lastmod') as $node) {
            $lastmods[] = $node->textContent;
        }

        return $lastmods;
    }
}
