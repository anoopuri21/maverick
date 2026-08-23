{{-- §2 Trust — Phase 2 cinematic scoreboard --}}
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
<section class="mlp-trust" id="mlp-trust" aria-label="Trust statistics">
  <div class="mlp-trust__graphic" aria-hidden="true">
    <div class="mlp-trust__orb mlp-trust__orb--a"></div>
    <div class="mlp-trust__orb mlp-trust__orb--b"></div>
    <svg class="mlp-trust__fan-svg" viewBox="0 0 1000 520" preserveAspectRatio="xMidYMid meet" fill="none">
      <g class="mlp-trust__fan-rings" stroke="currentColor">
        <path d="M120 480 A380 380 0 0 1 880 480" stroke-width="1" opacity="0.4"/>
        <path d="M180 480 A320 320 0 0 1 820 480" stroke-width="1" opacity="0.28"/>
        <path d="M250 480 A250 250 0 0 1 750 480" stroke-width="1" opacity="0.18"/>
      </g>
      <g class="mlp-trust__fan-rays" stroke="currentColor" stroke-width="1" opacity="0.22">
        <line x1="500" y1="480" x2="160" y2="160"/>
        <line x1="500" y1="480" x2="300" y2="90"/>
        <line x1="500" y1="480" x2="500" y2="60"/>
        <line x1="500" y1="480" x2="700" y2="90"/>
        <line x1="500" y1="480" x2="840" y2="160"/>
      </g>
      <circle cx="500" cy="480" r="10" fill="currentColor" opacity="0.35"/>
    </svg>
  </div>

  <div class="container mlp-trust__content">
    <div class="mlp-trust__top">
      @if(filled($trust->label))
      <p class="mlp-trust__label">{{ $trust->label }}</p>
      @endif
    </div>

    <div class="mlp-trust__stage" data-mlp-reveal="trust-stage">
      @if($featured)
      <div class="mlp-trust__featured" data-mlp-reveal="trust-featured">
        <span
          class="mlp-trust__score"
          data-mlp-count="{{ $featuredNum }}"
          data-mlp-suffix="{{ $featuredSuffix }}"
        >{{ $featured['value'] ?? '4.8' }}</span>
        <p class="mlp-trust__score-caption">{{ $featured['label'] ?? 'Rating · 400+ Reviews' }}</p>
      </div>
      @endif

      @if($rail->isNotEmpty())
      <ul class="mlp-trust__rail" data-mlp-reveal="trust-fan">
        @foreach($rail as $i => $stat)
        <li class="mlp-trust__stat" style="--mlp-i: {{ $i }}">
          <span
            class="mlp-trust__value"
            data-mlp-count="{{ preg_replace('/[^0-9.]/', '', $stat['value'] ?? '') }}"
            data-mlp-suffix="{{ preg_replace('/[0-9.,\s]/', '', $stat['value'] ?? '') }}"
          >{{ $stat['value'] ?? '' }}</span>
          <span class="mlp-trust__caption">{{ $stat['label'] ?? '' }}</span>
        </li>
        @endforeach
      </ul>
      @endif
    </div>
  </div>
</section>
@endif
