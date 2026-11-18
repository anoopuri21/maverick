<?php

namespace App\Payments;

use App\Models\PaymentPlan;
use App\Models\PaymentTransaction;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Stripe hosted Checkout, called over the REST API.
 *
 * No SDK is installed on purpose: the surface used here is two endpoints and
 * one HMAC check, which is less code than an SDK integration and keeps the
 * dependency list unchanged.
 */
class StripeGateway implements PaymentGateway
{
    public function __construct(private readonly array $config) {}

    public function name(): string
    {
        return 'stripe';
    }

    public function isConfigured(): bool
    {
        return filled($this->config['secret'] ?? null)
            && filled($this->config['key'] ?? null);
    }

    public function createCheckout(PaymentPlan $plan, PaymentTransaction $transaction, array $payload): array
    {
        $this->ensureConfigured();

        $amount = (int) $payload['amount_minor'];

        $body = [
            'mode' => 'payment',
            'success_url' => $payload['success_url'],
            'cancel_url' => $payload['cancel_url'],
            'customer_email' => $plan->student_email,
            'client_reference_id' => $plan->token,
            'line_items[0][quantity]' => 1,
            'line_items[0][price_data][currency]' => strtolower($plan->currency),
            'line_items[0][price_data][unit_amount]' => $amount,
            'line_items[0][price_data][product_data][name]' => $payload['description'],
            'metadata[plan_id]' => (string) $plan->id,
            'metadata[transaction_id]' => (string) $transaction->id,
            'metadata[installment_id]' => (string) ($transaction->payment_installment_id ?? ''),
        ];

        try {
            $response = Http::withToken($this->config['secret'])
                ->withHeaders(['Idempotency-Key' => 'txn-'.$transaction->id])
                ->asForm()
                ->timeout(20)
                ->retry(2, 400)
                ->post(rtrim($this->config['api_base'], '/').'/v1/checkout/sessions', $body);
        } catch (ConnectionException $e) {
            Log::error('Stripe checkout connection failed', ['transaction_id' => $transaction->id, 'error' => $e->getMessage()]);

            throw new \RuntimeException('The payment provider is unreachable. Please try again in a minute.');
        }

        if ($response->failed()) {
            Log::error('Stripe checkout rejected', [
                'transaction_id' => $transaction->id,
                'status' => $response->status(),
                'error' => $response->json('error.message'),
            ]);

            throw new \RuntimeException('The payment provider could not start this payment.');
        }

        $data = $response->json();

        if (blank($data['url'] ?? null) || blank($data['id'] ?? null)) {
            Log::error('Stripe checkout response missing url', ['transaction_id' => $transaction->id]);

            throw new \RuntimeException('The payment provider returned an unexpected response.');
        }

        return ['url' => (string) $data['url'], 'session_id' => (string) $data['id']];
    }

    public function verifySession(string $sessionId): array
    {
        $this->ensureConfigured();

        try {
            $response = Http::withToken($this->config['secret'])
                ->timeout(20)
                ->get(rtrim($this->config['api_base'], '/').'/v1/checkout/sessions/'.$sessionId);
        } catch (ConnectionException) {
            return ['paid' => false, 'reference' => null, 'amount_minor' => null, 'currency' => null, 'status' => 'unreachable'];
        }

        if ($response->failed()) {
            return ['paid' => false, 'reference' => null, 'amount_minor' => null, 'currency' => null, 'status' => 'unknown'];
        }

        $data = $response->json();
        $paid = ($data['payment_status'] ?? null) === 'paid';

        return [
            'paid' => $paid,
            'reference' => $data['payment_intent'] ?? null,
            'amount_minor' => isset($data['amount_total']) ? (int) $data['amount_total'] : null,
            'currency' => isset($data['currency']) ? strtoupper((string) $data['currency']) : null,
            'status' => (string) ($data['status'] ?? 'unknown'),
        ];
    }

    /**
     * Stripe signs `{timestamp}.{payload}` with the endpoint secret and sends
     * `Stripe-Signature: t=...,v1=...`. One or more v1 values may be present
     * while a secret is being rotated.
     */
    public function verifyWebhook(string $payload, ?string $signatureHeader): bool
    {
        $secret = $this->config['webhook_secret'] ?? null;

        if (blank($secret) || blank($signatureHeader)) {
            return false;
        }

        $timestamp = null;
        $signatures = [];

        foreach (explode(',', $signatureHeader) as $part) {
            [$key, $value] = array_pad(explode('=', trim($part), 2), 2, null);

            if ($key === 't' && $value !== null) {
                $timestamp = $value;
            } elseif ($key === 'v1' && $value !== null) {
                $signatures[] = $value;
            }
        }

        if ($timestamp === null || $signatures === [] || ! ctype_digit($timestamp)) {
            return false;
        }

        $tolerance = (int) ($this->config['webhook_tolerance'] ?? 300);

        if ($tolerance > 0 && abs(time() - (int) $timestamp) > $tolerance) {
            Log::warning('Stripe webhook timestamp outside tolerance', ['timestamp' => $timestamp]);

            return false;
        }

        $expected = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);

        foreach ($signatures as $signature) {
            if (hash_equals($expected, $signature)) {
                return true;
            }
        }

        return false;
    }

    private function ensureConfigured(): void
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('Payments are not configured on this site yet.');
        }
    }
}
