{{-- §1 Hero + enquiry — The Living Prospectus / Prospectus Cover --}}
@php
  $bg = mlp_image_url($hero->background_image, ['w' => 1600, 'fallback' => 'assets/images/edutainment/hero-cinematic.jpg']);
  $headline = $hero->headline ?? 'Affordable Online MBA & Master\'s Programs in UAE';

  // Keep the editorial line break data-driven while avoiding a generic hero block.
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
<section class="mlp-hero prospectus-cover" id="mlp-hero" data-prospectus aria-label="Online MBA and Master's programmes">
  <div class="prospectus-cover__stage" aria-hidden="true">
    <img class="prospectus-cover__image" src="{{ $bg }}" alt="" width="1600" height="900" loading="eager" fetchpriority="high" decoding="async">
    <span class="prospectus-cover__wash"></span>
    <span class="prospectus-cover__registration"></span>
    <span class="prospectus-cover__grain"></span>
  </div>

  <div class="prospectus-cover__frame container">
    <header class="prospectus-cover__masthead">
      <span class="prospectus-cover__edition">Admissions / 2026</span>
      <span class="prospectus-cover__masthead-line" aria-hidden="true"></span>
      <span class="prospectus-cover__academy">Maverick Business Academy</span>
      <span class="prospectus-cover__location">London · UAE · Global</span>
    </header>

    <div class="prospectus-cover__body">
      <div class="prospectus-cover__statement">
        @if(filled($hero->eyebrow))
        <p class="prospectus-cover__eyebrow">{{ $hero->eyebrow }}</p>
        @endif
        <p class="prospectus-cover__kicker">A prospectus for your next move</p>

        <h1 class="prospectus-cover__title">
          <span class="prospectus-cover__title-line">{{ $line1 }}</span>
          @if($line2)
          <span class="prospectus-cover__title-line prospectus-cover__title-line--accent">{{ $line2 }}</span>
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

      <aside class="prospectus-cover__annotation" aria-label="Programme note">
        <span class="prospectus-cover__annotation-index">01</span>
        <span class="prospectus-cover__annotation-rule" aria-hidden="true"></span>
        <p class="prospectus-cover__annotation-label">The next move</p>
        <p class="prospectus-cover__annotation-copy">An admissions conversation about eligibility, fees and the route that fits your working life.</p>
        <span class="prospectus-cover__annotation-foot">Open / enquire / begin</span>
      </aside>
    </div>

    <footer class="prospectus-cover__folio" aria-hidden="true">
      <span>Online MBA</span>
      <span class="prospectus-cover__folio-rule"></span>
      <span>Master's pathways</span>
      <span class="prospectus-cover__folio-rule"></span>
      <span>Vol. 01</span>
    </footer>
  </div>
</section>

<section class="prospectus-admissions" id="mlp-enquire" aria-labelledby="prospectus-admissions-title">
  <div class="prospectus-admissions__frame container">
    <header class="prospectus-admissions__masthead">
      <p class="prospectus-admissions__folio">02 / Open admissions desk</p>
      <span class="prospectus-admissions__masthead-rule" aria-hidden="true"></span>
      <p class="prospectus-admissions__status">Always open · No commitment required</p>
    </header>

    <div class="prospectus-admissions__grid">
      <div class="prospectus-admissions__intro">
        <p class="prospectus-admissions__eyebrow">A considered next step</p>
        <h2 class="prospectus-admissions__heading" id="prospectus-admissions-title">Tell admissions where you are headed.</h2>
        <p class="prospectus-admissions__copy">Share a little context. The admissions team will help you understand eligibility, fees and the right pathway before you decide.</p>
        <ul class="prospectus-admissions__notes" aria-label="What admissions can help with">
          <li><span aria-hidden="true">01</span>Eligibility guidance</li>
          <li><span aria-hidden="true">02</span>Fees and payment clarity</li>
          <li><span aria-hidden="true">03</span>Programme and start-date guidance</li>
        </ul>
      </div>

      <div class="prospectus-admissions__form">
        <div class="prospectus-admissions__form-head">
          <span class="prospectus-admissions__form-index">Your enquiry</span>
          <h3 class="prospectus-admissions__form-title">{{ $hero->form_title ?? 'Start your enquiry' }}</h3>
        </div>
        <div class="mlp-form mlp-form--prospectus">
          @include('pages.mba-masters-landing.partials.enquire-form')
        </div>
      </div>
    </div>
  </div>
</section>
