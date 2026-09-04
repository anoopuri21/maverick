{{-- §4 Why Choose Maverick — full-bleed scroll chapters --}}
@php
  $chapters = collect($why->chapters ?? [])->filter(fn ($c) => filled($c['title'] ?? null))->values();
  $chapterIcons = [
    'flexible' => 'calendar',
    'academic' => 'graduation-cap',
    'specialization' => 'compass',
    'payment' => 'wallet',
    'international' => 'globe',
    'community' => 'users',
  ];
@endphp
@if($chapters->isNotEmpty() || filled($why->heading))
<section class="mlp-why" id="mlp-why" aria-label="Why choose Maverick">
  <div class="mlp-why__pin mlp-hairline">
    <div class="container mlp-why__pin-inner" data-mlp-reveal="why-head">
      <div class="mlp-why__meta">
        @if(filled($why->label))
        <p class="mlp-why__label mlp-meta mlp-eyebrow">{{ $why->label }}</p>
        @endif
      </div>
      @if(filled($why->heading))
      <h2 class="mlp-why__heading mlp-headline mlp-h2">{{ $why->heading }}</h2>
      @endif
      @if(filled($why->intro))
      <p class="mlp-why__intro mlp-lede">{{ $why->intro }}</p>
      @endif
    </div>
  </div>

  <div class="mlp-why__chapters" data-mlp-reveal="why-chapters">
    @foreach($chapters as $i => $chapter)
    @php
      $tone = $i % 2 === 0 ? 'void' : 'paper';
      $titleKey = strtolower(trim((string) ($chapter['title'] ?? '')));
      $icon = 'compass';
      foreach ($chapterIcons as $keyword => $iconName) {
        if (str_contains($titleKey, $keyword)) {
          $icon = $iconName;
          break;
        }
      }
    @endphp
    <article class="mlp-why__chapter mlp-why__chapter--{{ $tone }}" style="--mlp-i: {{ $i }}">
      <div class="container mlp-why__chapter-grid">
        <span class="mlp-why__num" aria-hidden="true"><i data-lucide="{{ $icon }}"></i></span>
        <div class="mlp-why__copy">
          <h3 class="mlp-why__title">{{ $chapter['title'] }}</h3>
          @if(filled($chapter['text'] ?? null))
          <div class="mlp-prose mlp-why__text">{!! \App\Support\MlpProse::html($chapter['text']) !!}</div>
          @endif
        </div>
      </div>
    </article>
    @endforeach
  </div>
</section>
@endif
