<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThankYouPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /**
     * The thank-you page renders with the note and both CTA buttons.
     */
    public function test_thank_you_page_renders(): void
    {
        $response = $this->get('/thank-you');

        $response->assertOk();
        $response->assertSee('Thank You!', false);
        $response->assertSee('Submission Received', false);
        $response->assertSee('Back to Homepage', false);
        $response->assertSee('Back to Previous Page', false);

        // Homepage CTA points home; the page must never be indexed by search engines.
        $response->assertSee('href="'.route('home').'"', false);
        $response->assertSee('content="noindex, nofollow"', false);
    }

    /**
     * A same-host ?return= URL is used for the "Back to Previous Page" CTA.
     */
    public function test_thank_you_page_honours_same_host_return_url(): void
    {
        $response = $this->get('/thank-you?return='.urlencode('http://localhost/xyz-retro-check'));

        $response->assertOk();
        $response->assertSee('href="http://localhost/xyz-retro-check"', false);
    }

    /**
     * An external ?return= URL is rejected so the parameter cannot be
     * abused as an open redirect — the CTA falls back to the homepage.
     */
    public function test_thank_you_page_rejects_external_return_url(): void
    {
        $response = $this->get('/thank-you?return='.urlencode('https://evil.example.com/phish'));

        $response->assertOk();
        $response->assertDontSee('evil.example.com', false);
    }

    /**
     * A ?return= that loops back to /thank-you itself is rejected.
     */
    public function test_thank_you_page_rejects_return_loop_to_itself(): void
    {
        $response = $this->get('/thank-you?return='.urlencode('http://localhost/thank-you?loop-marker=1'));

        $response->assertOk();
        $response->assertDontSee('loop-marker', false);
    }
}
