<?php

namespace Tests\Feature;

use App\Mail\GenericFormMail;
use App\Settings\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertSee("Let's Start a Conversation");
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

        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '+971 50 000 0000',
            'subject' => 'Admissions',
            'message' => 'Hello, I would like to inquire about Executive MBA programs.',
            'website' => '', // Empty honeypot
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Thank you! We\'ll get back to you within 24 hours.');

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

        $response = $this->post('/contact', [
            'name' => 'Spam Bot',
            'email' => 'spambot@example.com',
            'phone' => '123456',
            'subject' => 'General Inquiry',
            'message' => 'Spam content',
            'website' => 'http://spam.com', // Filled honeypot
        ]);

        $response->assertStatus(302);
        // User should still see standard success response
        $response->assertSessionHas('success', 'Thank you! We\'ll get back to you within 24 hours.');

        // But no mail should be sent
        Mail::assertNotSent(GenericFormMail::class);
    }

    /**
     * Test contact form submits successfully to Zapier when URL configured.
     */
    public function test_contact_form_zapier_success(): void
    {
        Mail::fake();
        \Illuminate\Support\Facades\Http::fake();

        config(['services.zapier.contact_webhook_url' => 'https://hooks.zapier.com/hooks/catch/test']);

        $response = $this->post('/contact', [
            'name' => 'John Zapier',
            'email' => 'zapier@example.com',
            'phone' => '+1234567890',
            'subject' => 'General Inquiry',
            'message' => 'Hello Zapier!',
            'website' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

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
        \Illuminate\Support\Facades\Http::fake([
            '*' => \Illuminate\Support\Facades\Http::response('Error', 500),
        ]);

        config(['services.zapier.contact_webhook_url' => 'https://hooks.zapier.com/hooks/catch/test']);

        $response = $this->post('/contact', [
            'name' => 'John Failure',
            'email' => 'failure@example.com',
            'phone' => '+1234567890',
            'subject' => 'General Inquiry',
            'message' => 'Hello Failure!',
            'website' => '',
        ]);

        // Flow should still be fully successful for user
        $response->assertStatus(302);
        $response->assertSessionHas('success');
    }
}
