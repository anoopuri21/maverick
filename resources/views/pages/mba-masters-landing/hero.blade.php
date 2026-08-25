{{-- §1 Hero + enquiry — The Living Prospectus / Cinematic Hero Assembly --}}
@php
  $bg = mlp_image_url($hero->background_image, ['w' => 1600, 'fallback' => 'assets/images/edutainment/hero-cinematic.jpg']);
  $headline = $hero->headline ?? 'Affordable Online MBA & Master\'s Programs in UAE';

  // Keep the approved editorial line break and left-side content alignment.
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
<section class="mlp-hero prospectus-cover" id="mlp-hero" data-prospectus data-hero-assembly aria-label="Online MBA and Master's programmes">
  <div class="prospectus-cover__stage" aria-hidden="true">
    <img class="prospectus-cover__image" data-hero-image src="{{ $bg }}" alt="" width="1600" height="900" loading="eager" fetchpriority="high" decoding="async">
    <span class="prospectus-cover__wash" data-hero-wash></span>
    <span class="prospectus-cover__registration" data-hero-registration></span>
    <span class="prospectus-cover__grain"></span>
  </div>

  <div class="prospectus-cover__frame container">
    <header class="prospectus-cover__masthead" data-hero-masthead>
      <span class="prospectus-cover__edition">Admissions / 2026</span>
      <span class="prospectus-cover__masthead-line" aria-hidden="true"></span>
      <span class="prospectus-cover__academy">Maverick Business Academy</span>
      <span class="prospectus-cover__location">London · UAE · Global</span>
    </header>

    <div class="prospectus-cover__body">
      <div class="prospectus-cover__statement" data-hero-statement>
        @if(filled($hero->eyebrow))
        <p class="prospectus-cover__eyebrow">{{ $hero->eyebrow }}</p>
        @endif
        <p class="prospectus-cover__kicker">A prospectus for your next move</p>

        <h1 class="prospectus-cover__title">
          <span class="prospectus-cover__title-line" data-hero-title-line>{{ $line1 }}</span>
          @if($line2)
          <span class="prospectus-cover__title-line prospectus-cover__title-line--accent" data-hero-title-line>{{ $line2 }}</span>
          @endif
        </h1>

        @if(filled($hero->subheading))
        <p class="prospectus-cover__intro">{{ $hero->subheading }}</p>
        @endif

        <div class="prospectus-cover__actions">
          @if(filled($hero->cta_primary_label))
          <a href="#mlp-enquire" class="prospectus-cover__primary" data-prospectus-open>
            <span>{{ $hero->cta_primary_label }}</span>
            <span class="prospectus-cover__primary-mark" aria-hidden="true">↗</span>
          </a>
          @else
          <a href="#mlp-enquire" class="prospectus-cover__primary" data-prospectus-open>
            <span>Start your enquiry</span>
            <span class="prospectus-cover__primary-mark" aria-hidden="true">↗</span>
          </a>
          @endif
          <a href="#mlp-trust" class="prospectus-cover__secondary">Read the evidence</a>
        </div>
      </div>

      <aside class="prospectus-cover__enquiry" id="mlp-enquire" data-hero-enquiry aria-labelledby="prospectus-enquiry-title">
        <div class="prospectus-cover__enquiry-head">
          <span class="prospectus-cover__enquiry-index">Your enquiry</span>
          <h2 id="prospectus-enquiry-title">{{ $hero->form_title ?? 'Start your enquiry' }}</h2>
          <p>Eligibility, fees &amp; payment — admissions will guide you.</p>
        </div>
        <div class="mlp-form mlp-form--prospectus">
          @include('pages.mba-masters-landing.partials.enquire-form')
        </div>
      </aside>
    </div>

    <footer class="prospectus-cover__folio" data-hero-folio aria-hidden="true">
      <span>Online MBA</span>
      <span class="prospectus-cover__folio-rule"></span>
      <span>Master's pathways</span>
    </footer>
  </div>
</section>
