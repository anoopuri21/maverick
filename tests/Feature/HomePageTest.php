<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\FacultyInsight;
use App\Models\MediaAsset;
use App\Models\Testimonial;
use App\Models\UniversityPartner;
use App\Support\PublicContentCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_core_sections_and_single_seo_description(): void
    {
        UniversityPartner::query()->create([
            'name' => 'Homepage Dynamic Partner',
            'logo_url' => 'https://cdn.example.com/homepage-partner.png',
            'sort_order' => 1,
            'is_active' => true,
        ]);

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
            'mlp-partners',
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

    public function test_shared_partner_wall_renders_every_active_partner_from_admin_records_in_sort_order(): void
    {
        $logoAsset = MediaAsset::query()->create([
            'hash' => str_repeat('d', 64),
            'original_name' => 'admin-managed-partner-logo.png',
            'mime_type' => 'image/png',
            'cloudinary_public_id' => 'university-partners/admin-managed-logo',
            'url' => 'https://cdn.example.com/admin-managed-partner-logo.png',
            'folder' => 'university-partners',
            'disk_env' => 'testing',
        ]);

        $partners = [
            'Active Partner 04' => 40,
            'Active Partner 01' => 10,
            'Active Partner 07' => 70,
            'Active Partner 03' => 30,
            'Active Partner 06' => 60,
            'Active Partner 02' => 20,
            'Active Partner 05' => 50,
        ];

        foreach ($partners as $name => $sortOrder) {
            UniversityPartner::query()->create([
                'name' => $name,
                'logo_url' => $name === 'Active Partner 01'
                    ? 'https://cdn.example.com/stale-partner-logo.png'
                    : 'https://cdn.example.com/'.strtolower(str_replace(' ', '-', $name)).'.png',
                'logo_url_asset_id' => $name === 'Active Partner 01' ? $logoAsset->id : null,
                'sort_order' => $sortOrder,
                'is_active' => true,
            ]);
        }

        UniversityPartner::query()->create([
            'name' => 'Inactive Partner Must Stay Hidden',
            'logo_url' => 'https://cdn.example.com/inactive-partner.png',
            'sort_order' => 0,
            'is_active' => false,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        preg_match(
            '~<section class="mlp-partners archive-partners" id="mlp-partners".*?</section>~s',
            $response->getContent(),
            $matches
        );
        $section = $matches[0] ?? '';

        $this->assertNotSame('', $section);
        $this->assertSame(7, substr_count($section, 'class="archive-partners__logo-item"'));
        $this->assertStringNotContainsString('Inactive Partner Must Stay Hidden', $section);
        $this->assertStringContainsString('src="https://cdn.example.com/admin-managed-partner-logo.png"', $section);
        $this->assertStringNotContainsString('stale-partner-logo.png', $section);
        $this->assertStringNotContainsString('rushford.ch/wp-content', $section);
        $this->assertStringNotContainsString('www.gau.edu.tr/template', $section);
        $this->assertStringNotContainsString('is-marquee', $section);
        $this->assertStringNotContainsString('logo-list--clone', $section);

        $lastPosition = -1;
        foreach ([
            'Active Partner 01',
            'Active Partner 02',
            'Active Partner 03',
            'Active Partner 04',
            'Active Partner 05',
            'Active Partner 06',
            'Active Partner 07',
        ] as $name) {
            $position = strpos($section, $name);
            $this->assertNotFalse($position);
            $this->assertGreaterThan($lastPosition, $position);
            $lastPosition = $position;
        }
    }

    public function test_single_university_partner_wall_stays_static_without_a_clone(): void
    {
        UniversityPartner::query()->create([
            'name' => 'Only Active Partner',
            'logo_url' => 'https://cdn.example.com/only-partner.png',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        preg_match(
            '~<section class="mlp-partners archive-partners" id="mlp-partners".*?</section>~s',
            $response->getContent(),
            $matches
        );
        $section = $matches[0] ?? '';

        $this->assertNotSame('', $section);
        $this->assertSame(1, substr_count($section, 'class="archive-partners__logo-item"'));
        $this->assertStringNotContainsString('is-marquee', $section);
        $this->assertStringNotContainsString('logo-list--clone', $section);
    }

    public function test_homepage_dynamic_sections_are_server_rendered_without_client_injection(): void
    {
        Event::query()->create([
            'title' => 'Server Rendered Upcoming Event',
            'event_date' => now()->addDays(7)->toDateString(),
            'is_active' => true,
        ]);

        FacultyInsight::query()->create([
            'title' => 'Server Rendered Faculty Voice',
            'content' => 'Faculty content is present in the initial response.',
            'faculty_role' => 'Professor',
            'country' => 'India',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Testimonial::query()->create([
            'name' => 'Server Rendered Student Story',
            'designation' => 'MBA Graduate',
            'company' => 'Student',
            'video_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('data-testid="homepage-event-card"', false);
        $response->assertSee('Server Rendered Upcoming Event');
        $response->assertSee('id="faculty-insights"', false);
        $response->assertSee('Server Rendered Faculty Voice');
        $response->assertSee('data-testid="homepage-testimonial-card"', false);
        $response->assertSee('Server Rendered Student Story');
        $response->assertSee('https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0', false);
        $response->assertDontSee('window.testimonialsData', false);
    }

    public function test_homepage_event_cards_link_to_details_not_external_registration(): void
    {
        $event = Event::query()->create([
            'title' => 'Homepage Event Card Test',
            'event_date' => now()->addDays(7)->toDateString(),
            'link_url' => 'https://registration.example.com/homepage-event',
            'is_active' => true,
        ]);
        $pastEvent = Event::query()->create([
            'title' => 'Homepage Past Event Card Test',
            'event_date' => now()->subDay()->toDateString(),
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

        preg_match_all(
            '~<article class="events__card fade-up" data-testid="homepage-event-card">.*?</article>~s',
            $response->getContent(),
            $eventCardMatches
        );
        $findEventCard = static function (string $title) use ($eventCardMatches): ?string {
            foreach ($eventCardMatches[0] as $card) {
                if (str_contains($card, $title)) {
                    return $card;
                }
            }

            return null;
        };

        $futureCard = $findEventCard($event->title);
        $this->assertIsString($futureCard);
        $this->assertStringContainsString('<span class="events__upcoming-badge">Upcoming</span>', $futureCard);

        $pastCard = $findEventCard($pastEvent->title);
        $this->assertIsString($pastCard);
        $this->assertStringNotContainsString('events__upcoming-badge', $pastCard);

        $this->get(route('events.show', $event->slug))
            ->assertOk()
            ->assertSee('Homepage Event Card Test');
    }

    public function test_homepage_event_without_slug_uses_its_id_for_event_routes(): void
    {
        $event = Event::query()->create([
            'title' => 'Legacy Homepage Event',
            'event_date' => now()->addDays(7)->toDateString(),
            'is_active' => true,
        ]);

        Event::query()->whereKey($event->id)->update(['slug' => null]);
        PublicContentCache::forget(PublicContentCache::HOMEPAGE);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('href="'.route('events.show', $event->id).'"', false);

        $details = $this->get(route('events.show', $event->id));
        $details->assertOk();
        $details->assertSee('Legacy Homepage Event');
        $details->assertSee('href="'.route('events.register', $event->id).'"', false);

        $registration = $this->get(route('events.register', $event->id));
        $registration->assertOk();
        $registration->assertSee('action="'.route('events.register.submit', $event->id).'"', false);
    }
}
