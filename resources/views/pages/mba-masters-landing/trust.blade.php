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
    <img class="signal-atlas__bg-image" src="{{ cached_asset('assets/images/mba-masters-landing/trust-bg.jpg') }}" alt="" width="1600" height="900" loading="eager" decoding="async">
    <span class="signal-atlas__wash mlp-wash"></span>
    <span class="signal-atlas__contour signal-atlas__contour--one mlp-contour"></span>
    <span class="signal-atlas__contour signal-atlas__contour--two mlp-contour"></span>
  </div>

  <div class="signal-atlas__frame container">
    <header class="signal-atlas__intro">
        <p class="signal-atlas__folio">Trust record</p>
      <h2 class="signal-atlas__heading mlp-h2" id="signal-atlas-title">{{ $heading }}</h2>
      <blockquote class="signal-atlas__quote">
        <span class="signal-atlas__quote-mark" aria-hidden="true">“</span>
        <p>{{ $quote }}</p>
      </blockquote>
    </header>

    <div class="signal-atlas__graph" data-signal-atlas>
      <ol class="signal-atlas__records" aria-label="Trust statistics">
        @foreach($stats as $i => $stat)
        @php
          $rawValue = (string) ($stat['value'] ?? '');
          $numericValue = preg_replace('/[^0-9.]/', '', $rawValue);
          $suffix = preg_replace('/[0-9.,\s]/', '', $rawValue);
        @endphp
        <li class="signal-atlas__record{{ $i === 0 ? ' signal-atlas__record--lead' : '' }}" data-signal-record style="--signal-index: {{ $i }}">
          <span class="signal-atlas__node" aria-hidden="true"></span>
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
