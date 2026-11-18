<?php

namespace App\Payments;

use App\Models\PaymentPlan;
use App\Models\PaymentTransaction;

/**
 * A hosted-checkout gateway. The card is entered on the provider's page, so
 * this application never sees or stores card data.
 */
interface PaymentGateway
{
    public function name(): string;

    public function isConfigured(): bool;

    /**
     * Create a provider-side checkout for one transaction and return the page
     * the student must be sent to.
     *
     * @param  array{amount_minor:int, description:string, success_url:string, cancel_url:string}  $payload
     * @return array{url:string, session_id:string}
     *
     * @throws \RuntimeException when the provider refuses the request.
     */
    public function createCheckout(PaymentPlan $plan, PaymentTransaction $transaction, array $payload): array;

    /**
     * Confirm the outcome of a checkout session directly with the provider.
     * Used on the return page so a delayed webhook never shows a stale state.
     *
     * @return array{paid:bool, reference:?string, amount_minor:?int, currency:?string, status:string}
     */
    public function verifySession(string $sessionId): array;

    /**
     * Verify an incoming webhook against its signature header.
     */
    public function verifyWebhook(string $payload, ?string $signatureHeader): bool;
}
