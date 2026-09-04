{{-- §8 Fees + payment — The Closing Archive / Base fee + note --}}
@if(filled($fees->heading) || filled($fees->note) || filled($fees->cta_primary_label) || filled($fees->cta_secondary_label))
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
