@php
    $ctaPrimaryHref = edu_href($finalCta->btn_primary_url ?? null);
    $ctaSecondaryHref = edu_href($finalCta->btn_secondary_url ?? null);
    $ctaPhone = $finalCta->phone_number ?? null;
@endphp
<section id="final-cta" class="final-cta section-wrapper section--dark" aria-label="Final Call to Action">
  @isset($finalCta)
  <div class="container final-cta__inner">
    <div class="section-label"><span>Take The Next Step</span></div>
    @if(filled($finalCta->heading ?? null))
    <h2 class="final-cta__heading section-title">
      <span class="text-reveal-wrapper">
        <span class="text-reveal-inner">{{ $finalCta->heading }}</span>
      </span>
    </h2>
    @endif
    @if(filled($finalCta->subtitle ?? null))
    <p class="final-cta__subtitle body-text">
      {{ $finalCta->subtitle }}
    </p>
    @endif
    @if(($ctaPrimaryHref && filled($finalCta->btn_primary_text ?? null)) || ($ctaSecondaryHref && filled($finalCta->btn_secondary_text ?? null)))
    <div class="final-cta__buttons">
      @if($ctaPrimaryHref && filled($finalCta->btn_primary_text ?? null))
      <a href="{{ $ctaPrimaryHref }}" class="final-cta__btn final-cta__btn--primary">
        {{ $finalCta->btn_primary_text }}
      </a>
      @endif
      @if($ctaSecondaryHref && filled($finalCta->btn_secondary_text ?? null))
      <a href="{{ $ctaSecondaryHref }}" class="final-cta__btn final-cta__btn--secondary">
        {{ $finalCta->btn_secondary_text }}
      </a>
      @endif
    </div>
    @endif
    @if(filled($finalCta->phone_text ?? null) || filled($ctaPhone))
    <div class="final-cta__phone">
      {{ $finalCta->phone_text ?? '' }}
      @if(filled($ctaPhone))
      <a href="tel:{{ $ctaPhone }}">{{ $ctaPhone }}</a>
      @endif
    </div>
    @endif
  </div>
  @endisset
</section>
