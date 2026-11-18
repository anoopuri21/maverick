<?php

namespace App\Payments;

use App\Models\PaymentInstallment;
use App\Models\PaymentPlan;
use App\Models\PaymentTransaction;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class PaymentPlanService
{
    /**
     * Create a plan and its installment schedule.
     *
     * The monthly amount is the total divided by the installment count. Any
     * remainder (an odd fil) is added to the final installment, so the schedule
     * always sums to exactly the plan total.
     *
     * @param  array{student_name:string, student_email:string, student_phone?:?string, program_title:string, total_minor:int, installment_count:int, first_due_date:string, notes?:?string, currency?:string}  $data
     */
    public function create(array $data): PaymentPlan
    {
        $count = (int) $data['installment_count'];
        $total = (int) $data['total_minor'];

        if ($count < 1 || $count > 24) {
            throw new \InvalidArgumentException('An installment count between 1 and 24 is required.');
        }

        if ($total < 1) {
            throw new \InvalidArgumentException('A plan total greater than zero is required.');
        }

        $base = intdiv($total, $count);
        $remainder = $total - ($base * $count);
        $firstDue = CarbonImmutable::parse($data['first_due_date'])->startOfDay();

        return DB::transaction(function () use ($data, $count, $total, $base, $remainder, $firstDue) {
            $plan = PaymentPlan::create([
                'student_name' => $data['student_name'],
                'student_email' => $data['student_email'],
                'student_phone' => $data['student_phone'] ?? null,
                'program_title' => $data['program_title'],
                'currency' => strtoupper($data['currency'] ?? config('payments.currency', 'AED')),
                'total_minor' => $total,
                'installment_minor' => $base,
                'installment_count' => $count,
                'first_due_date' => $firstDue->toDateString(),
                'status' => PaymentPlan::STATUS_ACTIVE,
                'notes' => $data['notes'] ?? null,
            ]);

            for ($i = 1; $i <= $count; $i++) {
                $amount = $base + ($i === $count ? $remainder : 0);

                $plan->installments()->create([
                    'sequence' => $i,
                    'amount_minor' => $amount,
                    'due_date' => $firstDue->addMonthsNoOverflow($i - 1)->toDateString(),
                    'status' => PaymentInstallment::STATUS_PENDING,
                ]);
            }

            return $plan->refresh();
        });
    }

    /**
     * Mark an installment paid and record how the money arrived.
     *
     * Card payments arrive through the gateway path; this method is for the
     * admin "mark as paid" action (bank transfer, cash, cheque).
     */
    public function markPaid(PaymentInstallment $installment, string $via = 'bank_transfer', ?string $reference = null): PaymentTransaction
    {
        return DB::transaction(function () use ($installment, $via, $reference) {
            $installment->refresh();

            if ($installment->isPaid()) {
                $existing = $installment->transactions()
                    ->where('status', PaymentTransaction::STATUS_SUCCEEDED)
                    ->latest('id')
                    ->first();

                if ($existing) {
                    return $existing;
                }
            }

            $transaction = $installment->transactions()->create([
                'payment_plan_id' => $installment->payment_plan_id,
                'gateway' => PaymentTransaction::GATEWAY_MANUAL,
                'amount_minor' => $installment->amount_minor,
                'currency' => $installment->plan->currency,
                'status' => PaymentTransaction::STATUS_SUCCEEDED,
                'paid_at' => now(),
                'payload' => ['recorded_by' => 'admin', 'via' => $via, 'reference' => $reference],
            ]);

            $installment->forceFill([
                'status' => PaymentInstallment::STATUS_PAID,
                'paid_at' => now(),
                'paid_via' => $via,
                'reference' => $reference,
            ])->save();

            $this->syncPlanStatus($installment->plan);

            return $transaction;
        });
    }

    public function markPending(PaymentInstallment $installment): void
    {
        $installment->forceFill([
            'status' => PaymentInstallment::STATUS_PENDING,
            'paid_at' => null,
            'paid_via' => null,
            'reference' => null,
        ])->save();

        $this->syncPlanStatus($installment->plan);
    }

    public function syncPlanStatus(PaymentPlan $plan): void
    {
        if ($plan->status === PaymentPlan::STATUS_CANCELLED) {
            return;
        }

        $plan->load('installments');
        $settled = $plan->isSettled();

        if ($settled && $plan->status !== PaymentPlan::STATUS_COMPLETED) {
            $plan->forceFill(['status' => PaymentPlan::STATUS_COMPLETED])->save();
        } elseif (! $settled && $plan->status === PaymentPlan::STATUS_COMPLETED) {
            $plan->forceFill(['status' => PaymentPlan::STATUS_ACTIVE])->save();
        }
    }
}
