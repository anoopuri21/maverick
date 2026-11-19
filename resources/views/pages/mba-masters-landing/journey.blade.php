{{-- §5 Admission Journey — bordered step list with Lucide icons --}}
@php
  $steps = collect($journey->steps ?? [])->filter(fn ($s) => filled($s['title'] ?? null))->values();
  $stepIcons = [
    'eligib' => 'clipboard-check',
    'check' => 'clipboard-check',
    'confirm' => 'mail-check',
    '24 hour' => 'mail-check',
    'reserve' => 'bookmark',
    'seat' => 'bookmark',
    'enrol' => 'key-round',
    'induction' => 'key-round',
    'class' => 'play-circle',
    'begin' => 'play-circle',
  ];
@endphp
@if($steps->isNotEmpty() || filled($journey->heading))
<section class="mlp-journey" id="mlp-journey" aria-label="Admission journey">
  <div class="container mlp-journey__layout">
    <header class="mlp-journey__head" data-mlp-reveal="journey-head">
      @if(filled($journey->label))
      <p class="mlp-journey__label mlp-meta">{{ $journey->label }}</p>
      @endif
      @if(filled($journey->heading))
      <h2 class="mlp-journey__heading mlp-headline">{{ $journey->heading }}</h2>
      @endif
      @if(filled($journey->intro))
      <p class="mlp-journey__intro mlp-lede">{{ $journey->intro }}</p>
      @endif
    </header>

    @if($steps->isNotEmpty())
    <ol class="mlp-journey__steps" data-mlp-reveal="journey-steps">
      @foreach($steps as $i => $step)
      @php
        $titleKey = strtolower((string) $step['title']);
        $icon = 'circle-dot';
        foreach ($stepIcons as $keyword => $iconName) {
          if (str_contains($titleKey, $keyword)) {
            $icon = $iconName;
            break;
          }
        }
      @endphp
      <li class="mlp-journey__step" style="--mlp-i: {{ $i }}">
        <span class="mlp-journey__icon" aria-hidden="true"><i data-lucide="{{ $icon }}"></i></span>
        <div class="mlp-journey__panel">
          <h3 class="mlp-journey__title">{{ $step['title'] }}</h3>
          @if(filled($step['text'] ?? null))
          <div class="mlp-prose mlp-journey__text">{!! \App\Support\MlpProse::html($step['text']) !!}</div>
          @endif
        </div>
      </li>
      @endforeach
    </ol>
    @endif
  </div>
</section>
@endif
