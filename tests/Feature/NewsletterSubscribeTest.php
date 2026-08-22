<?php

namespace Tests\Feature;

use App\Mail\GenericFormMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewsletterSubscribeTest extends TestCase
{
    use RefreshDatabase;

    public function test_newsletter_requires_email(): void
    {
        $response = $this->postJson('/newsletter', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_newsletter_sends_notification_email(): void
    {
        Mail::fake();

        $response = $this->postJson('/newsletter', [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertOk();
        $response->assertJson(['ok' => true]);

        Mail::assertSent(GenericFormMail::class, function (GenericFormMail $mail) {
            $values = collect($mail->rows)->pluck('value', 'label');

            return $mail->hasTo('admissions@mbalondon.org.uk')
                && $values->get('Email') === 'subscriber@example.com'
                && $values->has('Submitted at');
        });
    }
}
