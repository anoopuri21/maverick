{{-- §2 Trust — The Signal Atlas graphical trust record --}}
@php
  $stats = collect($trust->stats ?? [])
      ->filter(fn ($s) => filled($s['value'] ?? null) || filled($s['label'] ?? null))
      ->values();
  $heading = filled($trust->label) ? $trust->label : 'Trusted by learners across the UAE & beyond';
  $quote = filled($trust->quote) ? $trust->quote : 'Every number is a person who chose to keep moving.';
@endphp
@if($stats->isNotEmpty())
<section class="mlp-trust signal-atlas" id="mlp-trust" aria-labelledby="signal-atlas-title">
  <div class="signal-atlas__background" aria-hidden="true">
    <span class="signal-atlas__wash"></span>
    <span class="signal-atlas__contour signal-atlas__contour--one"></span>
    <span class="signal-atlas__contour signal-atlas__contour--two"></span>
  </div>

  <div class="signal-atlas__frame container">
    <header class="signal-atlas__intro">
      <p class="signal-atlas__folio">02 / Trust record</p>
      <h2 class="signal-atlas__heading" id="signal-atlas-title">{{ $heading }}</h2>
      <blockquote class="signal-atlas__quote">
        <span class="signal-atlas__quote-mark" aria-hidden="true">“</span>
        <p>{{ $quote }}</p>
      </blockquote>
    </header>

    <div class="signal-atlas__graph" data-signal-atlas>
      <div class="signal-atlas__graph-label" aria-hidden="true">
        <span>Community signal</span>
        <span>01—{{ str_pad((string) $stats->count(), 2, '0', STR_PAD_LEFT) }}</span>
      </div>

      <svg class="signal-atlas__svg" viewBox="0 0 1200 360" preserveAspectRatio="none" aria-hidden="true">
        <g class="signal-atlas__grid-lines">
          <path d="M0 80H1200M0 180H1200M0 280H1200" />
          <path d="M120 0V360M360 0V360M600 0V360M840 0V360M1080 0V360" />
        </g>
        <path class="signal-atlas__signal-line" data-signal-path d="M36 282 C148 238 190 92 316 142 S486 308 594 206 S760 62 850 126 S1020 270 1164 72" />
        <path class="signal-atlas__signal-line signal-atlas__signal-line--secondary" d="M36 310 C178 270 246 198 348 214 S520 264 630 238 S780 156 900 188 S1058 228 1164 148" />
      </svg>

      <ol class="signal-atlas__records" aria-label="Trust statistics">
        @foreach($stats as $i => $stat)
        @php
          $rawValue = (string) ($stat['value'] ?? '');
          $numericValue = preg_replace('/[^0-9.]/', '', $rawValue);
          $suffix = preg_replace('/[0-9.,\s]/', '', $rawValue);
        @endphp
        <li class="signal-atlas__record{{ $i === 0 ? ' signal-atlas__record--lead' : '' }}" data-signal-record style="--signal-index: {{ $i }}">
          <span class="signal-atlas__node" aria-hidden="true"></span>
          <span class="signal-atlas__record-index" aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
          <span
            class="signal-atlas__value"
            @if($numericValue !== '')
            data-mlp-count="{{ $numericValue }}"
            data-mlp-suffix="{{ $suffix }}"
            @endif
          >{{ $rawValue }}</span>
          <span class="signal-atlas__label">{{ $stat['label'] ?? '' }}</span>
        </li>
        @endforeach
      </ol>
    </div>
  </div>
</section>
@endif
