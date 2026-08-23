{{-- §5 Admission Journey — graphic spine path (redesign) --}}
@php
  $steps = collect($journey->steps ?? [])->filter(fn ($s) => filled($s['title'] ?? null))->values();
  $count = $steps->count();
@endphp
@if($steps->isNotEmpty() || filled($journey->heading))
<section class="mlp-journey" id="mlp-journey" aria-label="Admission journey">
  <div class="mlp-journey__graphic" aria-hidden="true">
    <div class="mlp-journey__orb mlp-journey__orb--a"></div>
    <div class="mlp-journey__orb mlp-journey__orb--b"></div>
    <svg class="mlp-journey__rings" viewBox="0 0 600 600" fill="none">
      <circle cx="300" cy="300" r="180" stroke="currentColor" stroke-width="1" opacity="0.2"/>
      <circle cx="300" cy="300" r="240" stroke="currentColor" stroke-width="1" opacity="0.12"/>
      <circle cx="300" cy="300" r="300" stroke="currentColor" stroke-width="1" opacity="0.08"/>
    </svg>
  </div>

  <div class="container mlp-journey__layout">
    <header class="mlp-journey__head" data-mlp-reveal="journey-head">
      <div class="mlp-journey__meta">
        @if(filled($journey->label))
        <p class="mlp-journey__label mlp-meta">{{ $journey->label }}</p>
        @endif
      </div>
      @if(filled($journey->heading))
      <h2 class="mlp-journey__heading mlp-headline">{{ $journey->heading }}</h2>
      @endif
      @if(filled($journey->intro))
      <p class="mlp-journey__intro mlp-lede">{{ $journey->intro }}</p>
      @endif
      @if(filled($journey->cta_label))
      <div class="mlp-journey__cta mlp-journey__cta--head" data-mlp-reveal="journey-cta">
        <a href="{{ edu_href($journey->cta_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--primary">{{ $journey->cta_label }}</a>
      </div>
      @endif
    </header>

    @if($steps->isNotEmpty())
    <div class="mlp-journey__board" data-mlp-reveal="journey-track">
      <div class="mlp-journey__spine" aria-hidden="true">
        <span class="mlp-journey__spine-track"></span>
        <span class="mlp-journey__spine-fill"></span>
      </div>

      <ol class="mlp-journey__steps">
        @foreach($steps as $i => $step)
        <li class="mlp-journey__step" style="--mlp-i: {{ $i }}">
          <div class="mlp-journey__marker">
            <span class="mlp-journey__num">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
            <span class="mlp-journey__pulse"></span>
          </div>
          <div class="mlp-journey__panel">
            <h3 class="mlp-journey__title">{{ $step['title'] }}</h3>
            @if(filled($step['text'] ?? null))
            <div class="mlp-prose mlp-journey__text">{!! \App\Support\MlpProse::html($step['text']) !!}</div>
            @endif
          </div>
        </li>
        @endforeach
      </ol>
    </div>
    @endif
  </div>
</section>
@endif
