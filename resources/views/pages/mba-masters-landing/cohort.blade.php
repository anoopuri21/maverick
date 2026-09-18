{{-- §9 Cohort - Your Classmates: UAE and GCC Professionals - The GCC Atlas --}}
@php
  $regions = collect($class->regions ?? [])
      ->filter(fn ($r) => filled($r['name'] ?? null))
      ->values();
  $industries = collect($class->industries ?? [])
      ->filter(fn ($i) => filled($i['name'] ?? null))
      ->values();
  $audience = $class->audience ?? 'The average student on this page works full time in the UAE or the wider Gulf and studies in the evenings and on weekends. Most join to move into senior roles without taking a career break.';
  // Fallback to PDF exact if DB empty
  if ($regions->isEmpty()) {
      $regions = collect([
          ['name' => 'UAE', 'note' => 'around half the cohort · Dubai, Abu Dhabi, and Sharjah'],
          ['name' => 'Saudi Arabia', 'note' => 'about one quarter · Riyadh, Jeddah, and the NEOM region'],
          ['name' => 'Oman', 'note' => 'growing every intake · Muscat'],
          ['name' => 'Qatar', 'note' => 'steady presence · Doha'],
      ]);
  }
  if ($industries->isEmpty()) {
      $industries = collect([
          ['name' => 'Energy & Oil', 'share' => '15'],
          ['name' => 'Logistics & Trade', 'share' => '18'],
          ['name' => 'Banking & Finance', 'share' => '20'],
          ['name' => 'Government', 'share' => '12'],
          ['name' => 'Healthcare', 'share' => '10'],
          ['name' => 'Education', 'share' => '8'],
          ['name' => 'Real Estate', 'share' => '9'],
          ['name' => 'Tech & Consulting', 'share' => '8'],
      ]);
  }
  $industryIcons = [
      'Energy & Oil' => 'zap',
      'Logistics & Trade' => 'truck',
      'Banking & Finance' => 'landmark',
      'Government' => 'building-2',
      'Healthcare' => 'heart-pulse',
      'Education' => 'graduation-cap',
      'Real Estate' => 'home',
      'Tech & Consulting' => 'code-2',
  ];
@endphp

@if(filled($class->heading) || $regions->isNotEmpty() || $industries->isNotEmpty())
<section class="mlp-cohort gcc-atlas" id="mlp-cohort" aria-labelledby="gcc-atlas-title">
  <div class="gcc-atlas__background" aria-hidden="true">
    <span class="gcc-atlas__wash mlp-wash"></span>
    <span class="gcc-atlas__contour gcc-atlas__contour--one mlp-contour"></span>
    <span class="gcc-atlas__contour gcc-atlas__contour--two mlp-contour"></span>
    <svg class="gcc-atlas__map" viewBox="0 0 800 500" fill="none" aria-hidden="true">
      <path d="M200 150 Q300 100 400 150 T600 150 Q650 250 600 350 T400 400 Q300 380 200 350 Z" stroke="currentColor" stroke-width="0.5" stroke-dasharray="4 6" opacity="0.15"/>
      <circle cx="280" cy="180" r="3" class="gcc-atlas__dot gcc-atlas__dot--uae"/>
      <circle cx="350" cy="220" r="3" class="gcc-atlas__dot gcc-atlas__dot--sa"/>
      <circle cx="420" cy="280" r="3" class="gcc-atlas__dot gcc-atlas__dot--om"/>
      <circle cx="380" cy="200" r="3" class="gcc-atlas__dot gcc-atlas__dot--qa"/>
    </svg>
  </div>

  <div class="gcc-atlas__frame container">
    <header class="gcc-atlas__intro mlp-intro-grid" data-mlp-reveal="cohort-head">
      <div>
        @if(filled($class->label))
        <p class="gcc-atlas__label mlp-eyebrow mlp-meta">{{ $class->label }}</p>
        @endif
        <h2 class="gcc-atlas__heading mlp-h2" id="gcc-atlas-title">{{ $class->heading ?? 'Your Classmates: UAE and GCC Professionals' }}</h2>
      </div>
      <div class="gcc-atlas__copy">
        <p class="gcc-atlas__audience mlp-lede">{{ $audience }}</p>
      </div>
    </header>

    @if($regions->isNotEmpty())
    <div class="gcc-atlas__regions" data-mlp-reveal="cohort-regions" aria-label="Cohort regions">
      <div class="gcc-atlas__regions-head">
        <span class="gcc-atlas__regions-icon" aria-hidden="true"><i data-lucide="globe-2"></i></span>
        <h3>Where the room comes from</h3>
      </div>
      <div class="gcc-atlas__region-grid">
        @foreach($regions as $ri => $region)
        <article class="gcc-atlas__region mlp-hairline" style="--gcc-i: {{ $ri }}" data-archive-element>
          <div class="gcc-atlas__region-main">
            <h4 class="gcc-atlas__region-name">{{ $region['name'] }}</h4>
            @if(filled($region['note'] ?? null))
            <p class="gcc-atlas__region-note">{{ $region['note'] }}</p>
            @endif
          </div>
          <span class="gcc-atlas__region-marker" aria-hidden="true">
            <span class="gcc-atlas__region-dot"></span>
            <span class="gcc-atlas__region-pulse"></span>
          </span>
        </article>
        @endforeach
      </div>
    </div>
    @endif

    @if($industries->isNotEmpty())
    <div class="gcc-atlas__industries" data-mlp-reveal="cohort-industries" aria-label="Cohort industries">
      <div class="gcc-atlas__industries-head">
        <h3>Cohort industries reflect the Gulf's strongest sectors</h3>
        <p>Energy to tech — the room is bigger than one background.</p>
      </div>
      <ul class="gcc-atlas__industry-list" role="list">
        @foreach($industries as $ii => $industry)
        @php
          $name = $industry['name'] ?? 'Industry';
          $icon = $industryIcons[$name] ?? 'briefcase';
          $share = $industry['share'] ?? null;
        @endphp
        <li class="gcc-atlas__industry mlp-hairline" style="--gcc-i: {{ $ii }}">
          <span class="gcc-atlas__industry-icon" aria-hidden="true"><i data-lucide="{{ $icon }}"></i></span>
          <span class="gcc-atlas__industry-name">{{ $name }}</span>
          @if(filled($share))
          <span class="gcc-atlas__industry-share">{{ $share }}%</span>
          @endif
        </li>
        @endforeach
      </ul>
    </div>
    @endif
  </div>
</section>
@endif
