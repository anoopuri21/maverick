<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_core_sections_and_single_seo_description(): void
    {
        $response = $this->get('/');

        $response->assertOk();
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
}
