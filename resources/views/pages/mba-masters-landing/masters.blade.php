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

    @if($plate)
    <figure class="mlp-masters__banner" data-mlp-masters-showcase>
      <!-- <span class="mlp-masters__banner-frame" aria-hidden="true"></span> -->
      <span class="mlp-masters__banner-rule" aria-hidden="true"></span>
      <!-- <img src="{{ $plate }}" alt="Master's programmes at a global standard" width="1920" height="823" loading="lazy" decoding="async"> -->
    </figure>
    @endif

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

    <div class="mlp-masters__cta-row">
      <a href="#mlp-enquire" class="mlp-masters__cta">Check eligibility <span aria-hidden="true">↗</span></a>
      <p class="mlp-masters__cta-note">Every programme above is open to enquiry — admissions team will confirm eligibility and next steps.</p>
    </div>
  </div>
</section>
@endif
