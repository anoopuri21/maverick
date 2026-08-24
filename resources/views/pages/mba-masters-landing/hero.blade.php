{{-- §1 Hero + enquiry — Admissions Threshold direction --}}
@php
  $bg = mlp_image_url($hero->background_image, ['w' => 1600, 'fallback' => 'assets/images/edutainment/hero-cinematic.jpg']);
  $headline = $hero->headline ?? 'Affordable Online MBA & Master\'s Programs in UAE';
  // Keep the kinetic treatment data-driven while allowing a deliberate editorial break.
  if (str_contains($headline, ' & ')) {
      [$line1, $line2] = explode(' & ', $headline, 2);
      $line2 = '& ' . $line2;
  } elseif (preg_match('/^(.+?\s(?:MBA|Master\'s|Masters))\s+(.+)$/iu', $headline, $m)) {
      $line1 = $m[1];
      $line2 = $m[2];
  } else {
      $line1 = $headline;
      $line2 = null;
  }
@endphp
@push('head')
  @if($bg)
  <link rel="preload" as="image" href="{{ $bg }}" fetchpriority="high">
  @endif
@endpush
<section class="mlp-hero mlp-hero--threshold" id="mlp-hero" aria-label="Online MBA and Master's programmes">
  <div class="mlp-hero__stage" aria-hidden="true">
    <img class="mlp-hero__bg" src="{{ $bg }}" alt="" width="1600" height="900" loading="eager" fetchpriority="high" decoding="async">
    <div class="mlp-hero__veil"></div>
    <div class="mlp-hero__beam"></div>
    <div class="mlp-hero__grain"></div>
    <div class="mlp-threshold__signal"></div>
    <p class="mlp-hero__mark">Maverick</p>
  </div>

  <div class="container mlp-hero__grid mlp-threshold__grid">
    <div class="mlp-hero__copy mlp-threshold__copy" data-mlp-reveal="hero-copy">
      <p class="mlp-hero__brand">
        <span class="mlp-hero__brand-name">Maverick Business Academy</span>
        <span class="mlp-hero__brand-sep" aria-hidden="true"></span>
        <span class="mlp-hero__brand-loc">London</span>
      </p>

      @if(filled($hero->eyebrow))
      <p class="mlp-hero__eyebrow">{{ $hero->eyebrow }}</p>
      @endif

      <p class="mlp-threshold__overline">A considered next step for working professionals</p>
      <h1 class="mlp-hero__title mlp-threshold__title" data-mlp-kinetic>
        <span class="mlp-hero__line">{{ $line1 }}</span>
        @if($line2)
        <span class="mlp-hero__line mlp-hero__line--accent">{{ $line2 }}</span>
        @endif
      </h1>

      @if(filled($hero->subheading))
      <p class="mlp-hero__sub">{{ $hero->subheading }}</p>
      @endif

      <div class="mlp-hero__ctas">
        @if(filled($hero->cta_primary_label))
        <a href="{{ edu_href($hero->cta_primary_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--primary">{{ $hero->cta_primary_label }}<span class="mlp-threshold__cta-mark" aria-hidden="true">↗</span></a>
        @endif
        @if(filled($hero->cta_secondary_label))
        <a href="{{ edu_href($hero->cta_secondary_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--ghost">{{ $hero->cta_secondary_label }}</a>
        @endif
        @if(filled($hero->cta_tertiary_label))
        <a href="{{ edu_href($hero->cta_tertiary_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--text">{{ $hero->cta_tertiary_label }}</a>
        @endif
      </div>

      <p class="mlp-threshold__promise">
        <span class="mlp-threshold__promise-line" aria-hidden="true"></span>
        Admissions guidance on eligibility, fees and your start date.
      </p>
    </div>

    <aside class="mlp-hero__form-wrap mlp-threshold__form-wrap" id="mlp-enquire" data-mlp-reveal="hero-form" aria-label="Enquiry form">
      <div class="mlp-form mlp-form--panel mlp-form--on-void mlp-form--threshold">
        <header class="mlp-form__head">
          <div class="mlp-form__index" aria-hidden="true">01 / NEXT STEP</div>
          <div>
            <h2 class="mlp-form__title">{{ $hero->form_title ?? 'Start your enquiry' }}</h2>
            <p class="mlp-form__hint">Eligibility, fees &amp; payment — admissions will guide you.</p>
          </div>
        </header>

        @include('pages.mba-masters-landing.partials.enquire-form')
      </div>
    </aside>
  </div>

  <div class="mlp-threshold__footer container" aria-hidden="true">
    <span>Online MBA</span>
    <span class="mlp-threshold__footer-rule"></span>
    <span>Master's pathways</span>
    <span class="mlp-threshold__footer-rule"></span>
    <span>UAE · Global</span>
  </div>
</section>
