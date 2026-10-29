<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_core_sections_and_single_seo_description(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('assets/css/mba-partners.css', false);
        $response->assertDontSee('assets/css/pages/mba-masters-landing.css', false);
        $response->assertDontSee('class="mlp-page mlp-page--polished"', false);
        $this->assertSame(1, substr_count($response->getContent(), 'name="description"'));

        foreach ([
            'hero',
            'numbers',
            'what-is-maverick',
            'ask-quotient',
            'who-we-are',
            'dei-matrix',
            'ceo-message',
            'what-we-do',
            'how-we-do-it',
            'global-access-points',
            'why-maverick',
            'global-opportunities',
            'university-partners',
            'video-testimonials',
            'final-cta',
        ] as $sectionId) {
            $response->assertSee('id="'.$sectionId.'"', false);
        }

        $content = $response->getContent();
        $partnersPos = strpos($content, 'archive-partners');
        $askPos = strpos($content, 'id="ask-quotient"');
        $this->assertNotFalse($partnersPos);
        $this->assertNotFalse($askPos);
        $this->assertLessThan($askPos, $partnersPos);

        $response->assertDontSee('id="homepage-popup"', false);
        $response->assertSee('window.universityPartnersData', false);
        $response->assertSee('window.testimonialsData', false);
        $response->assertSee('window.globalAccessPointsCountries', false);
        $response->assertSee('gap-countries-json', false);
        $response->assertDontSee('debug-216c24', false);
        $response->assertDontSee('127.0.0.1:7261', false);
        $response->assertDontSee('assets/images/placeholder.jpg', false);
        $response->assertDontSee('img.magnific.com', false);
        $response->assertDontSee('No featured programs yet.', false);
        $response->assertDontSee('No insights available yet.', false);
        $response->assertDontSee('No upcoming events.', false);
    }

    public function test_homepage_event_cards_link_to_details_not_external_registration(): void
    {
        $event = Event::query()->create([
            'title' => 'Homepage Event Card Test',
            'event_date' => now()->addDays(7)->toDateString(),
            'link_url' => 'https://registration.example.com/homepage-event',
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('id="upcoming-events"', false);
        $response->assertSee('data-testid="homepage-event-card"', false);
        $response->assertSee('class="events__card-link"', false);
        $response->assertSee('href="'.route('events.show', $event->slug).'"', false);
        $response->assertSee('aria-label="View event details for Homepage Event Card Test"', false);
        $response->assertDontSee('href="https://registration.example.com/homepage-event"', false);

        $this->get(route('events.show', $event->slug))
            ->assertOk()
            ->assertSee('Homepage Event Card Test');
    }
}
