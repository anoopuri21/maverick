{{-- §7 Master's programmes — The Prospectus Ledger
     Light, clean, professional directory of every Master's programme
     (all universities combined). No university names, no counts. --}}
@php
  $programs = collect($masters->universities ?? [])
      ->flatMap(fn ($uni) => collect($uni['programs'] ?? []))
      ->map(fn ($program) => trim((string) ($program['title'] ?? '')))
      ->filter(fn ($title) => $title !== '')
      ->unique(fn ($title) => mb_strtolower($title))
      ->values();
  $plate = mlp_image_url(settings_media_url($masters, 'stage_image'), [
    'w' => 1920,
    'fallback' => 'assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg',
  ]);
  $heading = filled($masters->heading) ? $masters->heading : "Master's Programs";
  $label = filled($masters->label) ? $masters->label : 'Programme directory';
@endphp
@if($programs->isNotEmpty() || filled($masters->heading))
<section class="mlp-masters mlp-masters--prospectus" id="mlp-masters" aria-label="Master's programmes">
  <div class="container mlp-masters__inner">
    <header class="mlp-masters__head mlp-intro-grid" data-mlp-reveal="masters-head">
      <div>
        <p class="mlp-masters__label mlp-eyebrow">{{ $label }}</p>
        <h2 class="mlp-masters__heading mlp-h2">{{ $heading }}</h2>
      </div>
      @if(filled($masters->intro))
      <p class="mlp-masters__intro">{{ $masters->intro }}</p>
      @endif
    </header>

    <div class="mlp-masters__split" data-mlp-reveal="masters-split">
      @if($programs->isNotEmpty())
      <ol class="mlp-masters__ledger" data-mlp-reveal="masters-list" aria-label="All Master's programmes">
        @foreach($programs as $title)
        <li class="mlp-masters__item">
          <span class="mlp-masters__item-mark" aria-hidden="true"></span>
          <span class="mlp-masters__item-title">{{ $title }}</span>
        </li>
        @endforeach
      </ol>
      @endif

      <aside class="mlp-trending" aria-label="Trending specialisations">
        <h3 class="mlp-trending__title">
          <span class="mlp-trending__title-dark">Trending</span>
          <span class="mlp-trending__title-gold">Specialisations</span>
        </h3>
        <ul class="mlp-trending__list">
          <li class="mlp-trending__row" style="--trend: 55%">
            <span class="mlp-trending__label">BA (Hons) Management</span>
            <span class="mlp-trending__track" aria-hidden="true">
              <span class="mlp-trending__fill"><span class="mlp-trending__value">55%</span></span>
            </span>
          </li>
          <li class="mlp-trending__row" style="--trend: 82%">
            <span class="mlp-trending__label">MBA (Regular &amp; Top-up)</span>
            <span class="mlp-trending__track" aria-hidden="true">
              <span class="mlp-trending__fill"><span class="mlp-trending__value">82%</span></span>
            </span>
          </li>
          <li class="mlp-trending__row" style="--trend: 64%">
            <span class="mlp-trending__label">MBA in Healthcare Management</span>
            <span class="mlp-trending__track" aria-hidden="true">
              <span class="mlp-trending__fill"><span class="mlp-trending__value">64%</span></span>
            </span>
          </li>
          <li class="mlp-trending__row" style="--trend: 48%">
            <span class="mlp-trending__label">MBA in Quality Management</span>
            <span class="mlp-trending__track" aria-hidden="true">
              <span class="mlp-trending__fill"><span class="mlp-trending__value">48%</span></span>
            </span>
          </li>
          <li class="mlp-trending__row" style="--trend: 70%">
            <span class="mlp-trending__label">MBA in Finance</span>
            <span class="mlp-trending__track" aria-hidden="true">
              <span class="mlp-trending__fill"><span class="mlp-trending__value">70%</span></span>
            </span>
          </li>
          <li class="mlp-trending__row" style="--trend: 52%">
            <span class="mlp-trending__label">MBA in Project &amp; Operations Management</span>
            <span class="mlp-trending__track" aria-hidden="true">
              <span class="mlp-trending__fill"><span class="mlp-trending__value">52%</span></span>
            </span>
          </li>
          <li class="mlp-trending__row" style="--trend: 45%">
            <span class="mlp-trending__label">MBA in Strategic HRM &amp; Leadership</span>
            <span class="mlp-trending__track" aria-hidden="true">
              <span class="mlp-trending__fill"><span class="mlp-trending__value">45%</span></span>
            </span>
          </li>
          <li class="mlp-trending__row" style="--trend: 60%">
            <span class="mlp-trending__label">Executive MBA</span>
            <span class="mlp-trending__track" aria-hidden="true">
              <span class="mlp-trending__fill"><span class="mlp-trending__value">60%</span></span>
            </span>
          </li>
        </ul>
      </aside>
    </div>

    <div class="mlp-masters__cta-row">
      <a href="#mlp-enquire" class="mlp-masters__cta mlp-cta mlp-cta--primary">Check eligibility <span aria-hidden="true">↗</span></a>
      <p class="mlp-masters__cta-note">Every programme above is open to enquiry — admissions team will confirm eligibility and next steps.</p>
    </div>
  </div>
</section>
@endif
