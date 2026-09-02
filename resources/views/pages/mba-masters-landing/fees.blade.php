{{-- §8 Fees + payment — The Closing Archive / Pricing Cards --}}
@php
  $rows = collect($fees->rows ?? [])
      ->filter(fn ($row) => filled($row['program'] ?? null))
      ->values();
@endphp

@if($rows->isNotEmpty() || filled($fees->heading))
<section class="mlp-fees pricing-cards" id="mlp-fees" aria-labelledby="pricing-cards-title">
  <div class="pricing-cards__frame container">
    <header class="pricing-cards__intro mlp-intro-grid">
      <div>
        @if(filled($fees->label))
        <p class="pricing-cards__label mlp-eyebrow">{{ $fees->label }}</p>
        @endif
        @if(filled($fees->heading))
        <h2 class="pricing-cards__heading mlp-h2" id="pricing-cards-title">{{ $fees->heading }}</h2>
        @endif
      </div>
      @if(filled($fees->intro))
      <p class="pricing-cards__intro-copy">{{ $fees->intro }}</p>
      @endif
    </header>

    @if($rows->isNotEmpty())
    <div class="pricing-cards__grid" aria-label="Programme fees and structure">
      @foreach($rows as $row)
      @php
        $payment = trim((string) ($row['payment'] ?? ''));
        $fee = trim((string) ($row['fee'] ?? '—'));
        $feeIsIndicative = str_contains($fee, 'XX,XXX')
          || str_contains($fee, 'On request')
          || str_contains($fee, 'Route-specific')
          || str_contains($fee, '*');
        if (str_contains(strtolower($payment), 'advisor')) {
            $payment = 'Details on request';
        }
      @endphp
      <article class="pricing-card" data-closing-element>
        <header class="pricing-card__head">
          <span class="pricing-card__icon" aria-hidden="true"><i data-lucide="receipt-text"></i></span>
          <span class="pricing-card__eyebrow">Programme route</span>
        </header>

        <h3 class="pricing-card__program">{{ $row['program'] }}</h3>

        <div class="pricing-card__price">
          <span>Fee</span>
          <strong>{{ $fee }}</strong>
          @if($feeIsIndicative)
          <small>Indicative · confirm current fee, VAT and payment terms with admissions</small>
          @endif
        </div>

        <dl class="pricing-card__details">
          <div>
            <dt><i data-lucide="clock" aria-hidden="true"></i>Duration</dt>
            <dd>{{ $row['duration'] ?? '—' }}</dd>
          </div>
          <div>
            <dt><i data-lucide="monitor" aria-hidden="true"></i>Mode</dt>
            <dd>{{ $row['mode'] ?? '—' }}</dd>
          </div>
          <div>
            <dt><i data-lucide="wallet" aria-hidden="true"></i>Payment</dt>
            <dd>{{ $payment !== '' ? $payment : '—' }}</dd>
          </div>
        </dl>
      </article>
      @endforeach
    </div>
    @endif

    @if(filled($fees->note))
    <p class="pricing-cards__note">{{ $fees->note }}</p>
    @endif

    @if(filled($fees->cta_primary_label) || filled($fees->cta_secondary_label))
    <div class="pricing-cards__actions">
      @if(filled($fees->cta_primary_label))
      <a href="{{ edu_href($fees->cta_primary_url) ?? '#mlp-enquire' }}" class="pricing-cards__primary">{{ $fees->cta_primary_label }} <span aria-hidden="true">↗</span></a>
      @endif
      @if(filled($fees->cta_secondary_label))
      <a href="{{ edu_href($fees->cta_secondary_url) ?? '#mlp-enquire' }}" class="pricing-cards__secondary">{{ $fees->cta_secondary_label }}</a>
      @endif
    </div>
    @endif
  </div>
</section>
@endif
