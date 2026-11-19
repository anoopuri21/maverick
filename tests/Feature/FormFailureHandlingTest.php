<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FormFailureHandlingTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_reports_mail_failure_instead_of_success(): void
    {
        Mail::shouldReceive('mailer')->andThrow(new \RuntimeException('SMTP unavailable'));

        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'message' => 'Please call me about the MBA.',
        ]);

        $response->assertRedirectToRoute('contact');
        $response->assertSessionHas('error');
        $response->assertSessionHasInput('email', 'ada@example.com');
    }

    public function test_newsletter_reports_mail_failure_as_json(): void
    {
        Mail::shouldReceive('mailer')->andThrow(new \RuntimeException('SMTP unavailable'));

        $response = $this->postJson('/newsletter', [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertStatus(503);
        $response->assertJson(['ok' => false]);
    }
}
