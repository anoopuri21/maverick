<?php

namespace Tests\Feature;

use App\Models\ZapierWebhook;
use App\Services\ZapierWebhookDispatcher;
use App\Support\ZapierEvents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ZapierWebhookDispatcherTest extends TestCase
{
    use RefreshDatabase;

    public function test_dispatch_sends_payload_to_enabled_webhook(): void
    {
        Http::fake([
            'hooks.zapier.com/*' => Http::response('ok', 200),
        ]);

        $webhook = ZapierWebhook::create([
            'event_key' => ZapierEvents::CONTACT_SUBMITTED,
            'label' => 'CRM',
            'url' => 'https://hooks.zapier.com/hooks/catch/test',
            'is_enabled' => true,
        ]);

        app(ZapierWebhookDispatcher::class)->dispatch(ZapierEvents::CONTACT_SUBMITTED, [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://hooks.zapier.com/hooks/catch/test'
                && $request['name'] === 'Jane Doe'
                && $request['email'] === 'jane@example.com'
                && $request['_event'] === ZapierEvents::CONTACT_SUBMITTED
                && filled($request['_triggered_at']);
        });

        $webhook->refresh();
        $this->assertSame('success', $webhook->last_status);
        $this->assertNotNull($webhook->last_triggered_at);
    }

    public function test_dispatch_skips_disabled_webhooks(): void
    {
        Http::fake();

        ZapierWebhook::create([
            'event_key' => ZapierEvents::CONTACT_SUBMITTED,
            'url' => 'https://hooks.zapier.com/hooks/catch/disabled',
            'is_enabled' => false,
        ]);

        app(ZapierWebhookDispatcher::class)->dispatch(ZapierEvents::CONTACT_SUBMITTED, [
            'name' => 'Jane Doe',
        ]);

        Http::assertNothingSent();
    }

    public function test_dispatch_sends_to_all_enabled_webhooks_for_event(): void
    {
        Http::fake([
            'hooks.zapier.com/*' => Http::response('ok', 200),
        ]);

        ZapierWebhook::create([
            'event_key' => ZapierEvents::NEWSLETTER_SUBSCRIBED,
            'url' => 'https://hooks.zapier.com/hooks/catch/one',
            'is_enabled' => true,
        ]);

        ZapierWebhook::create([
            'event_key' => ZapierEvents::NEWSLETTER_SUBSCRIBED,
            'url' => 'https://hooks.zapier.com/hooks/catch/two',
            'is_enabled' => true,
        ]);

        app(ZapierWebhookDispatcher::class)->dispatch(ZapierEvents::NEWSLETTER_SUBSCRIBED, [
            'email' => 'user@example.com',
        ]);

        Http::assertSentCount(2);
    }

    public function test_dispatch_does_not_throw_on_failure_and_records_status(): void
    {
        Http::fake([
            'hooks.zapier.com/*' => Http::response('Error', 500),
        ]);

        $webhook = ZapierWebhook::create([
            'event_key' => ZapierEvents::PROGRAM_ENQUIRY_SUBMITTED,
            'url' => 'https://hooks.zapier.com/hooks/catch/fail',
            'is_enabled' => true,
        ]);

        app(ZapierWebhookDispatcher::class)->dispatch(ZapierEvents::PROGRAM_ENQUIRY_SUBMITTED, [
            'name' => 'Test User',
        ]);

        $webhook->refresh();
        $this->assertSame('failed', $webhook->last_status);
        $this->assertSame('500', $webhook->last_response);
    }

    public function test_test_sends_sample_payload(): void
    {
        Http::fake([
            'hooks.zapier.com/*' => Http::response('ok', 200),
        ]);

        $webhook = ZapierWebhook::create([
            'event_key' => ZapierEvents::CONTACT_SUBMITTED,
            'url' => 'https://hooks.zapier.com/hooks/catch/test-action',
            'is_enabled' => true,
        ]);

        $result = app(ZapierWebhookDispatcher::class)->test($webhook);

        $this->assertTrue($result['ok']);
        Http::assertSent(function ($request) {
            return $request['_test'] === true
                && $request['_event'] === ZapierEvents::CONTACT_SUBMITTED;
        });
    }
}
