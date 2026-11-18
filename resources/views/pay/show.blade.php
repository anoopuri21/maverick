@extends('pay.layout')

@section('title', 'Payment plan')

@section('content')
    @if (session('pay_error'))
        <div class="notice notice--error" role="alert">{{ session('pay_error') }}</div>
    @endif

    @if ($plan->status === \App\Models\PaymentPlan::STATUS_CANCELLED)
        <div class="notice notice--error" role="status">
            This payment plan has been cancelled. Please contact admissions if you believe this is a mistake.
        </div>
    @elseif ($plan->isSettled())
        <div class="notice notice--ok" role="status">
            Your plan is fully paid. Nothing further is due — thank you.
        </div>
    @endif

    <section class="card" aria-labelledby="plan-heading">
        <h1 id="plan-heading">Payment plan</h1>
        <p class="lede">
            {{ $plan->student_name }} · {{ $plan->program_title }}
        </p>

        <dl class="meta">
            <div>
                <dt>Plan total</dt>
                <dd class="amount">{{ $plan->amountForDisplay($plan->total_minor) }}</dd>
            </div>
            <div>
                <dt>Each installment</dt>
                <dd class="amount">{{ $plan->amountForDisplay($plan->installment_minor) }}</dd>
            </div>
            <div>
                <dt>Installments</dt>
                <dd>{{ $plan->installment_count }} monthly</dd>
            </div>
            <div>
                <dt>Paid so far</dt>
                <dd class="amount">{{ $plan->amountForDisplay($paidMinor) }}</dd>
            </div>
        </dl>

        <div class="progress">
            <div class="bar"><i style="width: {{ $percent }}%"></i></div>
            <div class="progress-row">
                <span>{{ $plan->paidCount() }} of {{ $plan->installment_count }} paid</span>
                <span class="amount">{{ $plan->amountForDisplay($remainingMinor) }} remaining</span>
            </div>
        </div>
    </section>

    <section class="card" aria-labelledby="schedule-heading">
        <h2 id="schedule-heading">Schedule</h2>
        <table>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Due date</th>
                    <th scope="col" class="num">Amount</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($installments as $installment)
                    <tr>
                        <td>{{ $installment->sequence }}</td>
                        <td>{{ $installment->due_date->format('d M Y') }}</td>
                        <td class="num amount">{{ $plan->amountForDisplay($installment->amount_minor) }}</td>
                        <td>
                            @if ($installment->isPaid())
                                <span class="tag tag--paid">Paid {{ $installment->paid_at?->format('d M Y') }}</span>
                            @elseif ($next && $next->id === $installment->id)
                                <span class="tag tag--next">Next due</span>
                            @elseif ($installment->isOverdue())
                                <span class="tag tag--due">Overdue</span>
                            @else
                                <span class="tag">Scheduled</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    @if ($plan->isOpen())
        <section class="card" aria-labelledby="pay-heading">
            <h2 id="pay-heading">Pay now</h2>

            @if ($next)
                <div class="pay-box">
                    <div class="pay-total">
                        Next installment
                        <strong class="amount">{{ $plan->amountForDisplay($next->amount_minor) }}</strong>
                        Due {{ $next->due_date->format('d M Y') }}
                    </div>

                    <form class="pay-form" method="POST" action="{{ route('pay.checkout', $plan->token) }}">
                        @csrf
                        <input type="hidden" name="mode" value="installment">
                        <button class="btn btn--primary" type="submit" @disabled(! $gatewayReady)>
                            Pay {{ $plan->amountForDisplay($next->amount_minor) }}
                        </button>
                    </form>
                </div>
            @endif

            @if ($remainingMinor > ($next->amount_minor ?? 0))
                <div class="pay-box" style="margin-top: 18px; padding-top: 18px; border-top: 1px solid var(--line);">
                    <div class="pay-total">
                        Or settle everything at once
                        <strong class="amount">{{ $plan->amountForDisplay($remainingMinor) }}</strong>
                        {{ $plan->installment_count - $plan->paidCount() }} installments remaining
                    </div>

                    <form class="pay-form" method="POST" action="{{ route('pay.checkout', $plan->token) }}">
                        @csrf
                        <input type="hidden" name="mode" value="full">
                        <button class="btn btn--ghost" type="submit" @disabled(! $gatewayReady)>
                            Pay full balance
                        </button>
                    </form>
                </div>
            @endif

            @unless ($gatewayReady)
                <p class="notice notice--info" style="margin-top: 18px;">
                    Card payments are not switched on yet. Please contact admissions to pay, and quote your name and programme.
                </p>
            @endunless

            <div class="trust">
                <span>Card details are entered on the payment provider's secure page — never on this site.</span>
                <span>3-D Secure verified where your bank requires it.</span>
            </div>
        </section>
    @endif

    <p class="fine">
        Keep this page private: anyone with the link can see the plan. For a receipt, a change to your schedule,
        or a bank transfer, contact admissions at
        <a href="mailto:{{ config('payments.page.support_email') }}">{{ config('payments.page.support_email') }}</a>@if (config('payments.page.support_phone')) or call {{ config('payments.page.support_phone') }}@endif.
    </p>
@endsection
