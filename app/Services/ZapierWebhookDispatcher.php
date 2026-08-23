<?php

namespace App\Services;

use App\Models\ZapierWebhook;
use Illuminate\Support\Facades\Http;
use Throwable;

class ZapierWebhookDispatcher
{
    public function dispatch(string $eventKey, array $payload): void
    {
        $webhooks = ZapierWebhook::query()
            ->forEvent($eventKey)
            ->where('is_enabled', true)
            ->get();

        foreach ($webhooks as $webhook) {
            $this->send($webhook, $payload);
        }
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public function test(ZapierWebhook $webhook): array
    {
        return $this->send($webhook, [
            '_test' => true,
        ]);
    }

    /**
     * @return array{ok: bool, message: string}
     */
    private function send(ZapierWebhook $webhook, array $payload): array
    {
        try {
            $response = Http::timeout(5)->post($webhook->url, array_merge($payload, [
                '_event' => $webhook->event_key,
                '_triggered_at' => now()->toIso8601String(),
            ]));

            $webhook->update([
                'last_triggered_at' => now(),
                'last_status' => $response->successful() ? 'success' : 'failed',
                'last_response' => (string) $response->status(),
            ]);

            return [
                'ok' => $response->successful(),
                'message' => 'HTTP '.$response->status(),
            ];
        } catch (Throwable $e) {
            $webhook->update([
                'last_triggered_at' => now(),
                'last_status' => 'failed',
                'last_response' => $e->getMessage(),
            ]);

            report($e);

            return [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
