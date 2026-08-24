{{-- §2 Trust — Evidence Constellation direction --}}
@php
  $stats = collect($trust->stats ?? [])
      ->filter(fn ($s) => filled($s['value'] ?? null) || filled($s['label'] ?? null))
      ->values();

  $featuredIndex = $stats->search(function ($s) {
      $label = strtolower((string) ($s['label'] ?? ''));
      $value = (string) ($s['value'] ?? '');

      return str_contains($label, 'rating') || str_contains($label, 'review') || preg_match('/^\d\.\d$/', $value);
  });

  if ($featuredIndex === false) {
      $featuredIndex = $stats->count() > 0 ? $stats->count() - 1 : null;
  }

  $featured = $featuredIndex !== null ? $stats->get($featuredIndex) : null;
  $rail = $stats->when($featuredIndex !== null, fn ($c) => $c->forget($featuredIndex))->values();

  $featuredNum = preg_replace('/[^0-9.]/', '', $featured['value'] ?? '') ?: '4.8';
  $featuredSuffix = preg_replace('/[0-9.,\s]/', '', $featured['value'] ?? '');
@endphp
@if($stats->isNotEmpty())
<section class="mlp-trust mlp-trust--constellation" id="mlp-trust" aria-label="Trust statistics">
  <div class="mlp-trust__graphic mlp-evidence__graphic" aria-hidden="true">
    <div class="mlp-trust__orb mlp-trust__orb--a"></div>
    <div class="mlp-trust__orb mlp-trust__orb--b"></div>
    <svg class="mlp-trust__fan-svg mlp-evidence__orbit" viewBox="0 0 1000 520" preserveAspectRatio="xMidYMid meet" fill="none">
      <g class="mlp-evidence__orbit-rings" stroke="currentColor">
        <path d="M90 420 A430 430 0 0 1 910 420" stroke-width="1" opacity="0.32"/>
        <path d="M180 420 A340 340 0 0 1 820 420" stroke-width="1" opacity="0.2"/>
        <path d="M300 420 A220 220 0 0 1 700 420" stroke-width="1" opacity="0.14"/>
      </g>
      <g class="mlp-evidence__orbit-lines" stroke="currentColor" stroke-width="1" opacity="0.18">
        <line x1="500" y1="420" x2="150" y2="190"/>
        <line x1="500" y1="420" x2="330" y2="90"/>
        <line x1="500" y1="420" x2="670" y2="90"/>
        <line x1="500" y1="420" x2="850" y2="190"/>
      </g>
      <circle cx="500" cy="420" r="7" fill="currentColor" opacity="0.45"/>
    </svg>
  </div>

  <div class="container mlp-trust__content mlp-evidence__content">
    <header class="mlp-evidence__head" data-mlp-reveal="trust-stage">
      <div>
        <p class="mlp-evidence__eyebrow">Promise, then proof</p>
        @if(filled($trust->label))
        <h2 class="mlp-evidence__heading">{{ $trust->label }}</h2>
        @endif
      </div>
      <p class="mlp-evidence__note">A snapshot of the community behind the next step.</p>
    </header>

    <div class="mlp-trust__stage mlp-evidence__stage">
      @if($featured)
      <div class="mlp-trust__featured mlp-evidence__anchor" data-mlp-reveal="trust-featured">
        <span class="mlp-evidence__anchor-label">A signal of confidence</span>
        <span
          class="mlp-trust__score mlp-evidence__score"
          data-mlp-count="{{ $featuredNum }}"
          data-mlp-suffix="{{ $featuredSuffix }}"
        >{{ $featured['value'] ?? '4.8' }}</span>
        <p class="mlp-trust__score-caption">{{ $featured['label'] ?? 'Rating · 400+ Reviews' }}</p>
      </div>
      @endif

      @if($rail->isNotEmpty())
      <ul class="mlp-trust__rail mlp-evidence__rail" data-mlp-reveal="trust-fan" aria-label="Additional trust statistics">
        @foreach($rail as $i => $stat)
        <li class="mlp-trust__stat mlp-evidence__stat" style="--mlp-i: {{ $i }}">
          <span class="mlp-evidence__stat-index" aria-hidden="true">0{{ $i + 1 }}</span>
          <span
            class="mlp-trust__value mlp-evidence__value"
            data-mlp-count="{{ preg_replace('/[^0-9.]/', '', $stat['value'] ?? '') }}"
            data-mlp-suffix="{{ preg_replace('/[0-9.,\s]/', '', $stat['value'] ?? '') }}"
          >{{ $stat['value'] ?? '' }}</span>
          <span class="mlp-trust__caption mlp-evidence__caption">{{ $stat['label'] ?? '' }}</span>
        </li>
        @endforeach
      </ul>
      @endif
    </div>
  </div>
</section>
@endif
