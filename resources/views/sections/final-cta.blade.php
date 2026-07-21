<section id="final-cta" class="final-cta section-wrapper section--dark" aria-label="Final Call to Action">
  <div class="container final-cta__inner">
    <div class="section-label"><span>Take The Next Step</span></div>
    <h2 class="final-cta__heading section-title">
      <span class="text-reveal-wrapper">
        <span class="text-reveal-inner">{{ $finalCta->heading }}</span>
      </span>
    </h2>
    <p class="final-cta__subtitle body-text">
      {{ $finalCta->subtitle }}
    </p>
    <div class="final-cta__buttons">
      <a href="{{ $finalCta->btn_primary_url }}" class="final-cta__btn final-cta__btn--primary">
        {{ $finalCta->btn_primary_text }}
      </a>
      <a href="{{ $finalCta->btn_secondary_url }}" class="final-cta__btn final-cta__btn--secondary">
        {{ $finalCta->btn_secondary_text }}
      </a>
    </div>
    <div class="final-cta__phone">
      {{ $finalCta->phone_text }}
      <a href="tel:{{ $finalCta->phone_number }}">{{ $finalCta->phone_number }}</a>
    </div>
  </div>
</section>
