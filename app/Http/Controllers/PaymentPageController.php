<?php

namespace App\Http\Controllers;

use App\Models\PaymentInstallment;
use App\Models\PaymentPlan;
use App\Models\PaymentTransaction;
use App\Payments\PaymentGatewayManager;
use App\Payments\PaymentPlanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentPageController extends Controller
{
    public function __construct(
        private readonly PaymentGatewayManager $gateways,
        private readonly PaymentPlanService $plans,
    ) {}

    public function show(string $token): View
    {
        $plan = $this->findPlan($token);

        return view('pay.show', [
            'plan' => $plan,
            'installments' => $plan->installments()->get(),
            'next' => $plan->nextInstallment(),
            'paidMinor' => $plan->paidMinor(),
            'remainingMinor' => $plan->remainingMinor(),
            'percent' => $plan->completionPercent(),
            'gatewayReady' => $this->gateways->isReady(),
            'currency' => $plan->currency,
        ]);
    }

    /**
     * Start a hosted checkout. The amount is computed here from the plan, never
     * taken from the request, so a tampered form cannot change what is charged.
     */
    public function checkout(Request $request, string $token): RedirectResponse
    {
        $plan = $this->findPlan($token);

        if (! $plan->isOpen()) {
            return redirect()
                ->route('pay.show', $plan->token)
                ->with('pay_error', 'This plan is closed. Contact admissions if you still need to pay.');
        }

        if ($plan->isExpired()) {
            return redirect()
                ->route('pay.show', $plan->token)
                ->with('pay_error', 'This payment link has expired. Contact admissions and they will send a fresh one.');
        }

        if (! $this->gateways->isReady()) {
            Log::warning('Payment checkout attempted before gateway configuration', ['plan' => $plan->id]);

            return redirect()
                ->route('pay.show', $plan->token)
                ->with('pay_error', 'Card payments are being set up. Please contact admissions to pay in the meantime.');
        }

        $mode = $request->string('mode', 'installment')->toString();

        [$amountMinor, $installment] = $this->amountFor($plan, $mode);

        if ($amountMinor <= 0) {
            return redirect()
                ->route('pay.show', $plan->token)
                ->with('pay_error', 'There is nothing left to pay on this plan.');
        }

        $gateway = $this->gateways->gateway();

        $transaction = $plan->transactions()->create([
            'payment_installment_id' => $installment?->id,
            'gateway' => $gateway->name(),
            'amount_minor' => $amountMinor,
            'currency' => $plan->currency,
            'status' => PaymentTransaction::STATUS_PENDING,
        ]);

        $description = $installment
            ? $plan->program_title.' — installment '.$installment->sequence.' of '.$plan->installment_count
            : $plan->program_title.' — remaining balance';

        try {
            $session = $gateway->createCheckout($plan, $transaction, [
                'amount_minor' => $amountMinor,
                'description' => $description,
                'success_url' => route('pay.success', $plan->token).'?session={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('pay.cancelled', $plan->token),
            ]);
        } catch (\Throwable $e) {
            $transaction->forceFill([
                'status' => PaymentTransaction::STATUS_FAILED,
                'payload' => ['error' => $e->getMessage()],
            ])->save();

            return redirect()
                ->route('pay.show', $plan->token)
                ->with('pay_error', $e->getMessage());
        }

        $transaction->forceFill(['gateway_session_id' => $session['session_id']])->save();

        return redirect()->away($session['url']);
    }

    /**
     * Return page. The webhook is the source of truth, but a student should not
     * stare at "pending" because a webhook was slow, so the session is checked
     * directly with the provider as a second, independent confirmation.
     */
    public function success(Request $request, string $token): View
    {
        $plan = $this->findPlan($token);
        $sessionId = $request->query('session');

        if (is_string($sessionId) && $sessionId !== '') {
            $this->reconcileSession($plan, $sessionId);
        }

        $transaction = is_string($sessionId) && $sessionId !== ''
            ? $plan->transactions()->where('gateway_session_id', $sessionId)->first()
            : null;

        // Only a settled transaction earns a receipt. A student who lands here
        // with a stale or invented session id is told what is actually true.
        $status = $transaction?->status === PaymentTransaction::STATUS_SUCCEEDED ? 'paid' : 'pending';

        return view('pay.result', [
            'plan' => $plan->refresh(),
            'status' => $status,
            'next' => $plan->nextInstallment(),
        ]);
    }

    public function cancelled(string $token): View
    {
        $plan = $this->findPlan($token);

        return view('pay.result', [
            'plan' => $plan,
            'status' => 'cancelled',
            'next' => $plan->nextInstallment(),
        ]);
    }

    /**
     * Stripe webhook. Signature verified before anything is read; the handler is
     * idempotent so a replayed event cannot pay an installment twice.
     */
    public function webhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $gateway = $this->gateways->gateway();

        if (! $gateway->verifyWebhook($payload, $request->header('Stripe-Signature'))) {
            Log::warning('Rejected payment webhook: bad signature', ['ip' => $request->ip()]);

            return response()->json(['error' => 'invalid signature'], 400);
        }

        $event = json_decode($payload, true);

        if (! is_array($event)) {
            return response()->json(['error' => 'invalid payload'], 400);
        }

        $type = (string) ($event['type'] ?? '');
        $session = $event['data']['object'] ?? [];

        if ($type === 'checkout.session.completed') {
            // Card payments are settled immediately, but a session can complete
            // before the money clears. Only a paid session may settle the plan.
            if (($session['payment_status'] ?? null) === 'paid') {
                $this->settleSession($session);
            }
        } elseif ($type === 'checkout.session.expired') {
            $this->failSession($session);
        }

        return response()->json(['received' => true]);
    }

    /** @param array<string, mixed> $session */
    private function settleSession(array $session): void
    {
        $sessionId = (string) ($session['id'] ?? '');

        if ($sessionId === '') {
            return;
        }

        $transaction = PaymentTransaction::query()
            ->where('gateway_session_id', $sessionId)
            ->first();

        if (! $transaction || $transaction->status === PaymentTransaction::STATUS_SUCCEEDED) {
            return; // Unknown session, or this event was already applied.
        }

        // The gateway's own amount is checked against what we asked for; a
        // mismatch is a bug or an attempt, and is never silently accepted.
        $paidMinor = (int) ($session['amount_total'] ?? 0);
        $paidCurrency = strtoupper((string) ($session['currency'] ?? ''));

        if ($paidMinor !== $transaction->amount_minor || $paidCurrency !== strtoupper($transaction->currency)) {
            Log::error('Payment amount mismatch', [
                'transaction_id' => $transaction->id,
                'expected' => $transaction->amount_minor.' '.$transaction->currency,
                'received' => $paidMinor.' '.$paidCurrency,
            ]);

            $transaction->forceFill(['status' => PaymentTransaction::STATUS_FAILED])->save();

            return;
        }

        $this->applyPayment($transaction, $session['payment_intent'] ?? $sessionId, $session);
    }

    /** @param array<string, mixed> $session */
    private function failSession(array $session): void
    {
        PaymentTransaction::query()
            ->where('gateway_session_id', (string) ($session['id'] ?? ''))
            ->where('status', PaymentTransaction::STATUS_PENDING)
            ->update(['status' => PaymentTransaction::STATUS_FAILED]);
    }

    private function reconcileSession(PaymentPlan $plan, string $sessionId): void
    {
        $transaction = $plan->transactions()->where('gateway_session_id', $sessionId)->first();

        if (! $transaction || $transaction->status === PaymentTransaction::STATUS_SUCCEEDED) {
            return;
        }

        $result = $this->gateways->gateway()->verifySession($sessionId);

        if (! $result['paid']) {
            return;
        }

        if ($result['amount_minor'] !== null && $result['amount_minor'] !== $transaction->amount_minor) {
            Log::error('Return-page amount mismatch', ['transaction_id' => $transaction->id]);

            return;
        }

        $this->applyPayment($transaction, $result['reference'], ['source' => 'return_page', 'session' => $sessionId]);
    }

    /** @param array<string, mixed> $raw */
    private function applyPayment(PaymentTransaction $transaction, ?string $reference, array $raw): void
    {
        DB::transaction(function () use ($transaction, $reference, $raw) {
            $fresh = PaymentTransaction::query()->lockForUpdate()->find($transaction->id);

            if (! $fresh || $fresh->status === PaymentTransaction::STATUS_SUCCEEDED) {
                return;
            }

            $fresh->forceFill([
                'status' => PaymentTransaction::STATUS_SUCCEEDED,
                'gateway_reference' => $reference,
                'paid_at' => now(),
                'payload' => $raw,
            ])->save();

            $installment = $fresh->installment;

            if ($installment !== null) {
                if (! $installment->isPaid()) {
                    $installment->forceFill([
                        'status' => PaymentInstallment::STATUS_PAID,
                        'paid_at' => now(),
                        'paid_via' => 'card',
                        'reference' => $reference,
                    ])->save();
                }
            } else {
                // A whole-balance payment has no single installment: settle the
                // oldest unpaid ones until the amount is used up.
                $this->allocateAcrossInstallments($fresh, $reference);
            }

            $this->plans->syncPlanStatus($fresh->plan);
        });
    }

    /**
     * @return array{0:int, 1:?PaymentInstallment}
     */
    private function amountFor(PaymentPlan $plan, string $mode): array
    {
        if ($mode === 'full') {
            return [$plan->remainingMinor(), null];
        }

        $next = $plan->nextInstallment();

        return [$next?->amount_minor ?? 0, $next];
    }

    /**
     * Settle pending installments oldest-first from a balance payment, stopping
     * when the paid amount is exhausted. Partial coverage is not invented: an
     * installment is marked paid only when it is fully covered.
     */
    private function allocateAcrossInstallments(PaymentTransaction $transaction, ?string $reference): void
    {
        $remaining = $transaction->amount_minor;

        $pending = $transaction->plan->installments()
            ->where('status', PaymentInstallment::STATUS_PENDING)
            ->orderBy('sequence')
            ->get();

        foreach ($pending as $installment) {
            if ($remaining < $installment->amount_minor) {
                break;
            }

            $installment->forceFill([
                'status' => PaymentInstallment::STATUS_PAID,
                'paid_at' => now(),
                'paid_via' => 'card',
                'reference' => $reference,
            ])->save();

            $remaining -= $installment->amount_minor;
        }

        if ($remaining > 0) {
            Log::warning('Balance payment exceeded the outstanding schedule', [
                'transaction_id' => $transaction->id,
                'unallocated_minor' => $remaining,
            ]);
        }
    }

    private function findPlan(string $token): PaymentPlan
    {
        $plan = PaymentPlan::query()->where('token', $token)->first();

        abort_if($plan === null, 404);

        return $plan;
    }
}
