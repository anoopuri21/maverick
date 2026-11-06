<?php

namespace Tests\Feature;

use App\Mail\GenericFormMail;
use App\Models\ZapierWebhook;
use App\Settings\SiteSettings;
use App\Support\ZapierEvents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /**
     * Test contact page renders.
     */
    public function test_contact_page_renders(): void
    {
        config(['services.recaptcha.site_key' => 'test-site-key']);

        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertSee("Let's Start a Conversation");
        $response->assertSee('class="g-recaptcha" data-sitekey="test-site-key"', false);
        $response->assertSee("Sharjah");
        $response->assertSee("UAE Campus");
        $response->assertSee("UK Campus");
        $response->assertSee("Ruislip");
    }

    /**
     * Test contact form validation constraints.
     */
    public function test_contact_form_validation(): void
    {
        $response = $this->post('/contact', [
            'name' => '',
            'email' => 'invalid-email',
            'message' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }

    /**
     * Test successful contact submission.
     */
    public function test_contact_form_submission(): void
    {
        Mail::fake();
        $this->fakeRecaptcha();

        $response = $this->from('/contact')->post('/contact', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '+971 50 000 0000',
            'subject' => 'Admissions',
            'message' => 'Hello, I would like to inquire about Executive MBA programs.',
            'website' => '', // Empty honeypot
            'g-recaptcha-response' => 'test-token',
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('thank-you');
        $response->assertSessionHas('thank_you_previous_url', 'http://localhost/contact');

        Mail::assertSent(GenericFormMail::class, function ($mail) {
            $values = collect($mail->rows)->pluck('value', 'label');

            return $mail->hasTo('admissions@mbalondon.org.uk') &&
                   $values->get('Name') === 'John Doe' &&
                   $values->get('Subject') === 'Admissions';
        });
    }

    /**
     * Test honeypot silent failure behavior.
     */
    public function test_contact_form_honeypot(): void
    {
        Mail::fake();

        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Spam Bot',
            'email' => 'spambot@example.com',
            'phone' => '123456',
            'subject' => 'General Inquiry',
            'message' => 'Spam content',
            'website' => 'http://spam.com', // Filled honeypot
        ]);

        $response->assertStatus(302);
        // Bot should still get the standard success flow (thank-you redirect)
        $response->assertRedirectToRoute('thank-you');

        // But no mail should be sent
        Mail::assertNotSent(GenericFormMail::class);
    }

    /**
     * Test contact form submits successfully to Zapier when URL configured.
     */
    public function test_contact_form_zapier_success(): void
    {
        Mail::fake();
        $this->fakeRecaptcha(true, [
            'hooks.zapier.com/*' => Http::response('ok', 200),
        ]);

        ZapierWebhook::create([
            'event_key' => ZapierEvents::CONTACT_SUBMITTED,
            'url' => 'https://hooks.zapier.com/hooks/catch/test',
            'is_enabled' => true,
        ]);

        $response = $this->from('/contact')->post('/contact', [
            'name' => 'John Zapier',
            'email' => 'zapier@example.com',
            'phone' => '+1234567890',
            'subject' => 'General Inquiry',
            'message' => 'Hello Zapier!',
            'website' => '',
            'g-recaptcha-response' => 'test-token',
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('thank-you');

        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            return $request->url() === 'https://hooks.zapier.com/hooks/catch/test' &&
                   $request['name'] === 'John Zapier' &&
                   $request['message'] === 'Hello Zapier!';
        });
    }

    /**
     * Test contact form doesn't block user flow when Zapier request fails/timeouts.
     */
    public function test_contact_form_zapier_failure_does_not_block(): void
    {
        Mail::fake();
        $this->fakeRecaptcha(true, [
            'hooks.zapier.com/*' => Http::response('Error', 500),
        ]);

        ZapierWebhook::create([
            'event_key' => ZapierEvents::CONTACT_SUBMITTED,
            'url' => 'https://hooks.zapier.com/hooks/catch/test',
            'is_enabled' => true,
        ]);

        $response = $this->from('/contact')->post('/contact', [
            'name' => 'John Failure',
            'email' => 'failure@example.com',
            'phone' => '+1234567890',
            'subject' => 'General Inquiry',
            'message' => 'Hello Failure!',
            'website' => '',
            'g-recaptcha-response' => 'test-token',
        ]);

        // Flow should still be fully successful for user
        $response->assertStatus(302);
        $response->assertRedirectToRoute('thank-you');
    }

    public function test_invalid_recaptcha_blocks_contact_mail_and_zapier_dispatch(): void
    {
        Mail::fake();
        ZapierWebhook::create([
            'event_key' => ZapierEvents::CONTACT_SUBMITTED,
            'url' => 'https://hooks.zapier.com/hooks/catch/test',
            'is_enabled' => true,
        ]);
        $this->fakeRecaptcha(false, [
            'hooks.zapier.com/*' => Http::response('ok', 200),
        ]);

        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Invalid CAPTCHA',
            'email' => 'invalid@example.com',
            'phone' => '+1234567890',
            'subject' => 'General Inquiry',
            'message' => 'This should not be sent.',
            'website' => '',
            'g-recaptcha-response' => 'invalid-token',
        ]);

        $response->assertSessionHasErrors('recaptcha');
        Mail::assertNothingSent();
        Http::assertNotSent(fn ($request) => $request->url() === 'https://hooks.zapier.com/hooks/catch/test');
    }

    private function fakeRecaptcha(bool $success = true, array $additionalResponses = []): void
    {
        config([
            'services.recaptcha.site_key' => 'test-site-key',
            'services.recaptcha.secret_key' => 'test-secret-key',
            'services.recaptcha.allowed_hostnames' => ['localhost'],
        ]);

        Http::fake(array_merge([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => $success,
                'hostname' => 'localhost',
            ], 200),
        ], $additionalResponses));
    }
}
