{{-- §8 Fees + payment — The Closing Archive / Pricing Cards (3rd card fee block hidden) --}}
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
        if (str_contains(strtolower($payment), 'advisor')) {
            $payment = 'Details on request';
        }
      @endphp
      <article class="pricing-card" data-closing-element>
        <header class="pricing-card__head mlp-hairline">
          <span class="pricing-card__icon mlp-icon-box" aria-hidden="true"><i data-lucide="receipt-text"></i></span>
          <span class="pricing-card__eyebrow">Programme route</span>
        </header>

        <h3 class="pricing-card__program">{{ $row['program'] }}</h3>

        {{-- Fee block intentionally removed from all cards (client request) --}}

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

    <p class="pricing-cards__base" data-mlp-reveal="fees-base">
      <span class="pricing-cards__base-label">Fee structure starts from</span>
      <strong class="pricing-cards__base-price">AED 16,000–40,000*</strong>
    </p>

    @if(filled($fees->note))
    <p class="pricing-cards__note">{{ $fees->note }}</p>
    @endif

    @if(filled($fees->cta_primary_label) || filled($fees->cta_secondary_label))
    <div class="pricing-cards__actions">
      @if(filled($fees->cta_primary_label))
      <a href="{{ edu_href($fees->cta_primary_url) ?? '#mlp-enquire' }}" class="pricing-cards__primary mlp-cta mlp-cta--primary">{{ $fees->cta_primary_label }} <span aria-hidden="true">↗</span></a>
      @endif
      @if(filled($fees->cta_secondary_label))
      <a href="{{ edu_href($fees->cta_secondary_url) ?? '#mlp-enquire' }}" class="pricing-cards__secondary mlp-cta mlp-cta--ghost">{{ $fees->cta_secondary_label }}</a>
      @endif
    </div>
    @endif
  </div>
</section>
@endif
