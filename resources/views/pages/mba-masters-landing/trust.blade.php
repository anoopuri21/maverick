{{-- §2 Trust — The Signal Atlas graphical trust record --}}
@php
  $stats = collect($trust->stats ?? [])
      ->filter(fn ($s) => filled($s['value'] ?? null) || filled($s['label'] ?? null))
      ->values();
  $heading = filled($trust->label) ? $trust->label : 'Trusted by learners across the GCC & beyond';
  $quote = filled($trust->quote) ? $trust->quote : 'Every number is a person who chose to keep moving.';
  $attribution = $trust->quote_attribution ?? null;
  $statCount = max(1, $stats->count());
  $checklist = $stats->isNotEmpty() && $stats->every(function ($stat) {
      return preg_replace('/[^0-9.]/', '', (string) ($stat['value'] ?? '')) === '';
  });
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
      @if(filled($attribution))
      <blockquote class="signal-atlas__quote">
        <span class="signal-atlas__quote-mark" aria-hidden="true">“</span>
        <div class="signal-atlas__quote-body">
          <p>{{ $quote }}</p>
          <footer class="signal-atlas__quote-attr">{{ $attribution }}</footer>
        </div>
      </blockquote>
      @else
      <p class="signal-atlas__note">{{ $quote }}</p>
      @endif
    </header>

    <div class="signal-atlas__graph" data-signal-atlas>
      <ol class="signal-atlas__records{{ $checklist ? ' signal-atlas__records--checklist' : '' }}" aria-label="{{ $checklist ? 'Written confirmation' : 'Trust statistics' }}" style="--signal-count: {{ $statCount }}">
        @foreach($stats as $i => $stat)
        @php
          $rawValue = (string) ($stat['value'] ?? '');
          $numericValue = preg_replace('/[^0-9.]/', '', $rawValue);
          $suffix = preg_replace('/[0-9.,\s]/', '', $rawValue);
        @endphp
        <li class="signal-atlas__record{{ $i === 0 ? ' signal-atlas__record--lead' : '' }}" data-signal-record style="--signal-index: {{ $i }}">
          <span class="signal-atlas__node" aria-hidden="true"></span>
          @if($numericValue !== '')
          <span
            class="signal-atlas__value"
            data-mlp-count="{{ $numericValue }}"
            data-mlp-suffix="{{ $suffix }}"
          >{{ $rawValue }}</span>
          @endif
          <span class="signal-atlas__label">{{ $stat['label'] ?? '' }}</span>
        </li>
        @endforeach
      </ol>
    </div>
  </div>
</section>
@endif
