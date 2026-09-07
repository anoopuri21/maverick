<?php

namespace Tests\Feature;

use App\Mail\GenericFormMail;
use App\Models\ZapierWebhook;
use App\Settings\ZohoCampaignsSettings;
use App\Support\ZapierEvents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
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
        Http::fake();

        $response = $this->postJson('/newsletter', [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertOk();
        $response->assertJson([
            'ok' => true,
            'message' => 'Thank you for subscribing.',
        ]);

        Mail::assertSent(GenericFormMail::class, function (GenericFormMail $mail) {
            $values = collect($mail->rows)->pluck('value', 'label');

            return $mail->hasTo('admissions@mbalondon.org.uk')
                && $values->get('Email') === 'subscriber@example.com'
                && $values->has('Submitted at');
        });

        Http::assertNothingSent();
    }

    public function test_newsletter_syncs_to_zoho_campaigns_when_enabled(): void
    {
        Mail::fake();
        $this->enableZohoCampaigns();

        Http::fake([
            'accounts.zoho.com/oauth/v2/token' => Http::response([
                'access_token' => 'mock-access-token',
                'expires_in' => 3600,
            ]),
            'campaigns.zoho.com/api/v1.1/json/listsubscribe' => Http::response([
                'status' => 'success',
                'code' => '0',
                'message' => 'A confirmation email is sent to the user.',
            ]),
        ]);

        $response = $this->postJson('/newsletter', [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertOk();
        $response->assertJson([
            'ok' => true,
            'message' => 'Almost there — please check your inbox and confirm your subscription.',
        ]);

        Http::assertSent(function ($request) {
            if (! str_contains($request->url(), 'listsubscribe')) {
                return false;
            }

            return $request['listkey'] === 'test-list-key'
                && str_contains((string) $request['contactinfo'], 'subscriber@example.com');
        });

        Mail::assertSent(GenericFormMail::class);
    }

    public function test_newsletter_still_succeeds_when_zoho_campaigns_fails(): void
    {
        Mail::fake();
        $this->enableZohoCampaigns();

        Http::fake([
            'accounts.zoho.com/oauth/v2/token' => Http::response([
                'access_token' => 'mock-access-token',
                'expires_in' => 3600,
            ]),
            'campaigns.zoho.com/api/v1.1/json/listsubscribe' => Http::response([
                'status' => 'error',
                'code' => '2501',
                'message' => 'Listkey is empty or invalid.',
            ]),
        ]);

        $response = $this->postJson('/newsletter', [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertOk();
        $response->assertJson([
            'ok' => true,
            'message' => 'Thank you for subscribing.',
        ]);

        Mail::assertSent(GenericFormMail::class);
    }

    public function test_newsletter_dispatches_zapier_webhook_when_configured(): void
    {
        Mail::fake();
        Http::fake([
            'hooks.zapier.com/*' => Http::response('ok', 200),
        ]);

        ZapierWebhook::create([
            'event_key' => ZapierEvents::NEWSLETTER_SUBSCRIBED,
            'url' => 'https://hooks.zapier.com/hooks/catch/newsletter',
            'is_enabled' => true,
        ]);

        $response = $this->postJson('/newsletter', [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertOk();

        Http::assertSent(function ($request) {
            return $request->url() === 'https://hooks.zapier.com/hooks/catch/newsletter'
                && $request['email'] === 'subscriber@example.com'
                && $request['_event'] === ZapierEvents::NEWSLETTER_SUBSCRIBED;
        });
    }

    public function test_newsletter_syncs_via_marketing_automation_stack_when_selected(): void
    {
        Mail::fake();
        $this->enableZohoCampaigns();
        $settings = app(ZohoCampaignsSettings::class);
        $settings->api_stack = 'marketing_automation';
        $settings->save();

        Http::fake([
            'accounts.zoho.com/oauth/v2/token' => Http::response([
                'access_token' => 'mock-access-token',
                'expires_in' => 3600,
            ]),
            'marketingautomation.zoho.com/api/v1/json/listsubscribe' => Http::response([
                'status' => 'success',
                'code' => '0',
                'message' => 'A confirmation email is sent to the user.',
            ]),
        ]);

        $response = $this->postJson('/newsletter', [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertOk();
        $response->assertJson([
            'ok' => true,
            'message' => 'Almost there — please check your inbox and confirm your subscription.',
        ]);

        Http::assertSent(function ($request) {
            if (! str_contains($request->url(), 'marketingautomation.zoho.com/api/v1/json/listsubscribe')) {
                return false;
            }

            return $request['listkey'] === 'test-list-key'
                && str_contains((string) $request['leadinfo'], 'subscriber@example.com')
                && str_contains((string) $request['leadinfo'], 'Lead Email');
        });

        Mail::assertSent(GenericFormMail::class);
    }

    private function enableZohoCampaigns(): void
    {
        $settings = app(ZohoCampaignsSettings::class);
        $settings->enabled = true;
        $settings->region = 'com';
        $settings->client_id = 'test-client-id';
        $settings->client_secret = 'test-client-secret';
        $settings->refresh_token = 'test-refresh-token';
        $settings->list_key = 'test-list-key';
        $settings->source = 'Website Footer';
        $settings->save();
    }
}
