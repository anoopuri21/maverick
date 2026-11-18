@extends('pay.layout')

@section('title', $status === 'paid' ? 'Payment received' : ($status === 'pending' ? 'Payment confirming' : 'Payment cancelled'))

@section('content')
    <section class="card">
        @if ($status === 'paid')
            <div class="notice notice--ok" role="status">
                Payment received. A receipt is on its way to the email address on your plan.
            </div>
            <h1>Thank you — your payment is in.</h1>
            <p class="lede">{{ $plan->student_name }} · {{ $plan->program_title }}</p>

            <dl class="meta">
                <div>
                    <dt>Plan total</dt>
                    <dd class="amount">{{ $plan->amountForDisplay($plan->total_minor) }}</dd>
                </div>
                <div>
                    <dt>Paid so far</dt>
                    <dd class="amount">{{ $plan->amountForDisplay($plan->paidMinor()) }}</dd>
                </div>
                <div>
                    <dt>Remaining</dt>
                    <dd class="amount">{{ $plan->amountForDisplay($plan->remainingMinor()) }}</dd>
                </div>
                <div>
                    <dt>Installments paid</dt>
                    <dd>{{ $plan->paidCount() }} of {{ $plan->installment_count }}</dd>
                </div>
            </dl>

            @if ($next)
                <p class="fine">
                    Next installment: <strong class="amount">{{ $plan->amountForDisplay($next->amount_minor) }}</strong>
                    due {{ $next->due_date->format('d M Y') }}.
                </p>
            @else
                <p class="fine">Your plan is fully paid. Nothing further is due.</p>
            @endif

            <p style="margin-top: 20px;">
                <a class="btn btn--ghost" href="{{ route('pay.show', $plan->token) }}">Back to your payment plan</a>
            </p>
        @elseif ($status === 'pending')
            <div class="notice notice--info" role="status">
                We are confirming this payment. Nothing else is needed from you.
            </div>
            <h1>Payment is being confirmed</h1>
            <p class="lede">{{ $plan->student_name }} · {{ $plan->program_title }}</p>

            <p class="fine">
                Your bank has not sent the confirmation yet. This usually takes a few minutes, and the schedule on
                your plan updates as soon as it arrives. Please do not pay again in the meantime.
            </p>

            <p style="margin-top: 20px;">
                <a class="btn btn--primary" href="{{ route('pay.show', $plan->token) }}">Check your payment plan</a>
            </p>
        @else
            <div class="notice notice--error" role="status">
                No payment was taken. Your plan is unchanged.
            </div>
            <h1>Payment cancelled</h1>
            <p class="lede">
                You closed the payment page before completing the card details. Nothing has been charged.
            </p>

            <p class="fine">
                You can return to your plan whenever you are ready and try again — the link stays the same.
            </p>

            <p style="margin-top: 20px;">
                <a class="btn btn--primary" href="{{ route('pay.show', $plan->token) }}">Return to your payment plan</a>
            </p>
        @endif

        @if ($status !== 'cancelled')
            <p class="fine">
                Any question about this payment? Contact admissions at
                <a href="mailto:{{ config('payments.page.support_email') }}">{{ config('payments.page.support_email') }}</a>.
            </p>
        @endif
    </section>
@endsection
