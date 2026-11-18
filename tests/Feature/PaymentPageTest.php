<?php

namespace Tests\Feature;

use App\Models\PaymentInstallment;
use App\Models\PaymentPlan;
use App\Models\PaymentTransaction;
use App\Payments\PaymentPlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentPageTest extends TestCase
{
    use RefreshDatabase;

    private function plan(array $overrides = []): PaymentPlan
    {
        return app(PaymentPlanService::class)->create(array_merge([
            'student_name' => 'Ayesha Khan',
            'student_email' => 'ayesha@example.com',
            'student_phone' => '+971500000000',
            'program_title' => 'Global MBA',
            'total_minor' => 300000,
            'installment_count' => 6,
            'first_due_date' => now()->addDays(7)->toDateString(),
        ], $overrides));
    }

    private function configureStripe(): void
    {
        config([
            'payments.gateway' => 'stripe',
            'payments.currency' => 'AED',
            'payments.gateways.stripe.key' => 'pk_test_123',
            'payments.gateways.stripe.secret' => 'sk_test_123',
            'payments.gateways.stripe.webhook_secret' => 'whsec_test_123',
            'payments.gateways.stripe.api_base' => 'https://api.stripe.com',
        ]);
    }

    public function test_plan_schedule_sums_to_the_total_and_splits_monthly(): void
    {
        $plan = $this->plan(['total_minor' => 300050]);

        $this->assertCount(6, $plan->installments);
        $this->assertSame(300050, (int) $plan->installments()->sum('amount_minor'));
        $this->assertSame(50000, $plan->installments()->first()->amount_minor);
        $this->assertSame(50050, $plan->installments()->get()->last()->amount_minor);
        $this->assertSame(
            now()->addDays(7)->toDateString(),
            $plan->installments()->first()->due_date->toDateString(),
        );
    }

    public function test_payment_page_renders_the_schedule_and_is_not_indexable(): void
    {
        $plan = $this->plan();

        $response = $this->get('/pay/'.$plan->token);

        $response->assertOk();
        $response->assertSee('Global MBA');
        $response->assertSee('3,000.00 AED');
        $response->assertSee('6 monthly');
        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
        $response->assertSee('noindex', false);
    }

    public function test_unknown_token_is_not_found(): void
    {
        $this->get('/pay/not-a-real-token')->assertNotFound();
    }

    public function test_checkout_creates_a_hosted_session_and_pending_transaction(): void
    {
        $this->configureStripe();
        $plan = $this->plan();

        Http::fake([
            'api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_test_abc',
                'url' => 'https://checkout.stripe.com/c/pay/cs_test_abc',
            ], 200),
        ]);

        $response = $this->post('/pay/'.$plan->token.'/checkout', ['mode' => 'installment']);

        $response->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_abc');

        Http::assertSent(function ($request) {
            $body = $request->data();

            return $request->url() === 'https://api.stripe.com/v1/checkout/sessions'
                && $request->hasHeader('Authorization', 'Bearer sk_test_123')
                && (int) $body['line_items[0][price_data][unit_amount]'] === 50000
                && $body['line_items[0][price_data][currency]'] === 'aed'
                && $body['metadata[plan_id]'] === (string) PaymentPlan::query()->value('id');
        });

        $transaction = PaymentTransaction::query()->sole();
        $this->assertSame(PaymentTransaction::STATUS_PENDING, $transaction->status);
        $this->assertSame('cs_test_abc', $transaction->gateway_session_id);
        $this->assertSame(1, $transaction->installment->sequence);
    }

    public function test_checkout_refuses_when_the_gateway_is_not_configured(): void
    {
        config(['payments.gateways.stripe.secret' => null, 'payments.gateways.stripe.key' => null]);

        $plan = $this->plan();

        $this->post('/pay/'.$plan->token.'/checkout', ['mode' => 'installment'])
            ->assertRedirect(route('pay.show', $plan->token))
            ->assertSessionHas('pay_error');

        $this->assertSame(0, PaymentTransaction::query()->count());
    }

    public function test_webhook_marks_the_installment_paid_and_ignores_a_replay(): void
    {
        $this->configureStripe();
        $plan = $this->plan();

        Http::fake([
            'api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_test_paid',
                'url' => 'https://checkout.stripe.com/c/pay/cs_test_paid',
            ], 200),
        ]);

        $this->post('/pay/'.$plan->token.'/checkout', ['mode' => 'installment']);

        $event = [
            'type' => 'checkout.session.completed',
            'data' => ['object' => [
                'id' => 'cs_test_paid',
                'payment_status' => 'paid',
                'amount_total' => 50000,
                'currency' => 'aed',
                'payment_intent' => 'pi_test_1',
            ]],
        ];

        $payload = json_encode($event);
        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'whsec_test_123');

        $headers = [
            'Stripe-Signature' => 't='.$timestamp.',v1='.$signature,
            'CONTENT_TYPE' => 'application/json',
        ];

        $this->call('POST', '/payments/stripe/webhook', [], [], [], $headers, $payload)
            ->assertOk();

        $installment = $plan->installments()->first();
        $this->assertTrue($installment->isPaid());
        $this->assertSame('card', $installment->paid_via);
        $this->assertSame(50000, $plan->paidMinor());
        $this->assertSame(PaymentTransaction::STATUS_SUCCEEDED, PaymentTransaction::query()->sole()->status);

        // The same event delivered again must not pay a second installment.
        $this->call('POST', '/payments/stripe/webhook', [], [], [], $headers, $payload)->assertOk();

        $this->assertSame(1, $plan->installments()->where('status', PaymentInstallment::STATUS_PAID)->count());
        $this->assertSame(1, PaymentTransaction::query()->count());
    }

    public function test_webhook_rejects_a_bad_signature(): void
    {
        $this->configureStripe();
        $this->plan();

        $payload = json_encode(['type' => 'checkout.session.completed', 'data' => ['object' => ['id' => 'cs_x']]]);

        $this->call('POST', '/payments/stripe/webhook', [], [], [], [
            'Stripe-Signature' => 't='.time().',v1=deadbeef',
            'CONTENT_TYPE' => 'application/json',
        ], $payload)->assertStatus(400);
    }

    public function test_webhook_rejects_an_amount_that_does_not_match_the_transaction(): void
    {
        $this->configureStripe();
        $plan = $this->plan();

        Http::fake([
            'api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_test_mismatch',
                'url' => 'https://checkout.stripe.com/c/pay/cs_test_mismatch',
            ], 200),
        ]);

        $this->post('/pay/'.$plan->token.'/checkout', ['mode' => 'installment']);

        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => ['object' => [
                'id' => 'cs_test_mismatch',
                'payment_status' => 'paid',
                'amount_total' => 100,
                'currency' => 'aed',
                'payment_intent' => 'pi_test_bad',
            ]],
        ]);

        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'whsec_test_123');

        $this->call('POST', '/payments/stripe/webhook', [], [], [], [
            'Stripe-Signature' => 't='.$timestamp.',v1='.$signature,
            'CONTENT_TYPE' => 'application/json',
        ], $payload)->assertOk();

        $this->assertFalse($plan->installments()->first()->isPaid());
        $this->assertSame(PaymentTransaction::STATUS_FAILED, PaymentTransaction::query()->sole()->status);
    }

    public function test_return_page_confirms_a_paid_session_without_waiting_for_the_webhook(): void
    {
        $this->configureStripe();
        $plan = $this->plan();

        Http::fake([
            'api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_test_return',
                'url' => 'https://checkout.stripe.com/c/pay/cs_test_return',
            ], 200),
            'api.stripe.com/v1/checkout/sessions/cs_test_return' => Http::response([
                'id' => 'cs_test_return',
                'status' => 'complete',
                'payment_status' => 'paid',
                'amount_total' => 50000,
                'currency' => 'aed',
                'payment_intent' => 'pi_test_return',
            ], 200),
        ]);

        $this->post('/pay/'.$plan->token.'/checkout', ['mode' => 'installment']);

        $this->get('/pay/'.$plan->token.'/success?session=cs_test_return')
            ->assertOk()
            ->assertSee('Thank you', false);

        $this->assertTrue($plan->installments()->first()->isPaid());
    }

    public function test_an_expired_link_stops_accepting_card_payments(): void
    {
        $this->configureStripe();
        $plan = $this->plan([
            'total_minor' => 100000,
            'installment_count' => 2,
            'first_due_date' => now()->subDays(400)->toDateString(),
        ]);

        Http::fake();

        $this->post('/pay/'.$plan->token.'/checkout', ['mode' => 'installment'])
            ->assertRedirect(route('pay.show', $plan->token))
            ->assertSessionHas('pay_error');

        Http::assertNothingSent();
    }

    public function test_the_return_page_does_not_claim_payment_without_a_settled_session(): void
    {
        $plan = $this->plan();

        $response = $this->get('/pay/'.$plan->token.'/success');

        $response->assertOk();
        $response->assertSee('being confirmed');
        $response->assertDontSee('Thank you', false);
    }

    public function test_paying_the_full_balance_settles_every_installment(): void
    {
        $this->configureStripe();
        $plan = $this->plan();

        Http::fake([
            'api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_test_full',
                'url' => 'https://checkout.stripe.com/c/pay/cs_test_full',
            ], 200),
        ]);

        $this->post('/pay/'.$plan->token.'/checkout', ['mode' => 'full'])
            ->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_full');

        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => ['object' => [
                'id' => 'cs_test_full',
                'payment_status' => 'paid',
                'amount_total' => 300000,
                'currency' => 'aed',
                'payment_intent' => 'pi_test_full',
            ]],
        ]);

        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'whsec_test_123');

        $this->call('POST', '/payments/stripe/webhook', [], [], [], [
            'Stripe-Signature' => 't='.$timestamp.',v1='.$signature,
            'CONTENT_TYPE' => 'application/json',
        ], $payload)->assertOk();

        $this->assertSame(0, $plan->installments()->where('status', PaymentInstallment::STATUS_PENDING)->count());
        $this->assertSame(PaymentPlan::STATUS_COMPLETED, $plan->refresh()->status);
    }

    public function test_settling_the_last_installment_completes_the_plan(): void
    {
        $plan = $this->plan(['total_minor' => 100000, 'installment_count' => 2]);
        $service = app(PaymentPlanService::class);

        foreach ($plan->installments as $installment) {
            $service->markPaid($installment, 'bank_transfer', 'TRX-1');
        }

        $this->assertSame(PaymentPlan::STATUS_COMPLETED, $plan->refresh()->status);
        $this->assertTrue($plan->isSettled());
    }

    public function test_a_closed_plan_cannot_start_a_checkout(): void
    {
        $this->configureStripe();
        $plan = $this->plan();
        $plan->forceFill(['status' => PaymentPlan::STATUS_CANCELLED])->save();

        Http::fake();

        $this->post('/pay/'.$plan->token.'/checkout', ['mode' => 'installment'])
            ->assertRedirect(route('pay.show', $plan->token))
            ->assertSessionHas('pay_error');

        Http::assertNothingSent();
    }
}
