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

    public function test_thank_you_page_renders_with_conversion_snippet(): void
    {
        $response = $this->get('/thank-you');

        $response->assertOk();
        $response->assertSee('Thank You!', false);
        $response->assertSee('Submission Received', false);
        $response->assertSee('Back to Homepage', false);
        $response->assertSee('Back to Previous Page', false);
        $response->assertSee('data-thank-you-previous-link', false);
        $response->assertSee("const storageKey = 'maverick:thank-you:previous-url';", false);
        $response->assertSee("window.addEventListener('pagehide', clearPreviousUrl)", false);
        $response->assertSee('href="'.route('home').'"', false);
        $response->assertSee('content="noindex, nofollow"', false);

        $conversionSnippet = <<<'HTML'
<!-- Event snippet for Sign-up conversion page -->
<script>
  gtag('event', 'conversion', {
      'send_to': 'AW-17992316259/CpjZCM6y-bEdEOPqs4ND',
      'value': 1.0,
      'currency': 'AED'
  });
</script>
HTML;

        $response->assertSee($conversionSnippet, false);

        $contactResponse = $this->get('/contact');
        $contactResponse->assertDontSee('AW-17992316259/CpjZCM6y-bEdEOPqs4ND', false);
    }

    public function test_thank_you_uses_same_host_previous_url_from_session_flash(): void
    {
        $previousUrl = 'http://localhost/xyz-retro-check?from=form';

        $response = $this->withSession([
            'thank_you_previous_url' => $previousUrl,
        ])->get('/thank-you');

        $response->assertOk();
        $response->assertSee('href="'.$previousUrl.'"', false);
        $response->assertDontSee('?return=', false);
    }

    public function test_thank_you_rejects_external_previous_url(): void
    {
        $response = $this->withSession([
            'thank_you_previous_url' => 'https://evil.example.com/phish',
        ])->get('/thank-you');

        $response->assertOk();
        $response->assertDontSee('evil.example.com', false);
        $response->assertSee('href="'.route('home').'" class="thank-you__cta thank-you__cta--back"', false);
    }

    public function test_thank_you_rejects_previous_url_with_different_origin_port(): void
    {
        $response = $this->withSession([
            'thank_you_previous_url' => 'http://localhost:9999/phish',
        ])->get('/thank-you');

        $response->assertOk();
        $response->assertDontSee('localhost:9999', false);
        $response->assertSee('href="'.route('home').'" class="thank-you__cta thank-you__cta--back"', false);
    }

    public function test_thank_you_rejects_previous_url_loop_to_itself(): void
    {
        $response = $this->withSession([
            'thank_you_previous_url' => 'http://localhost/thank-you?loop-marker=1',
        ])->get('/thank-you');

        $response->assertOk();
        $response->assertDontSee('loop-marker', false);
        $response->assertSee('href="'.route('home').'" class="thank-you__cta thank-you__cta--back"', false);
    }

    public function test_legacy_return_query_redirects_to_clean_thank_you_url(): void
    {
        $response = $this->get('/thank-you?return='.urlencode('http://localhost/xyz-retro-check'));

        $response->assertRedirectToRoute('thank-you');
        $this->assertSame(route('thank-you'), $response->headers->get('Location'));
    }
}
