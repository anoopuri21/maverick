{{-- ===== S5: WHY CHOOSE DUAL MBA ===== --}}
@php
    $whyCards = collect($why->cards ?? [])->filter(fn ($card) => filled($card['title'] ?? null));
    $showWhy = filled($why->label ?? null) || filled($why->title ?? null) || filled($why->title_highlight ?? null) || $whyCards->isNotEmpty();
@endphp
@if($showWhy)
<section class="dmba-why section--light section--warm section-wrapper" aria-label="Why Choose Dual MBA" data-testid="dmba-why-section">
  <div class="container">
    @if(filled($why->label) || filled($why->title) || filled($why->title_highlight))
    <div class="dmba-why__header">
      @if(filled($why->label))
      <div class="section-label"><span>{{ $why->label }}</span></div>
      @endif
      @if(filled($why->title) || filled($why->title_highlight))
      <h2 class="section-title">
        {{ $why->title }}
        @if(filled($why->title_highlight))
          <span class="highlight">{{ $why->title_highlight }}</span>
        @endif
      </h2>
      @endif
    </div>
    @endif

    @if($whyCards->isNotEmpty())
    <div class="dmba-why__grid" data-testid="dmba-why-grid">
      @foreach($whyCards as $card)
      <div class="dmba-why__card" data-testid="dmba-why-card-{{ $loop->iteration }}">
        @if(filled($card['icon_key'] ?? null))
        <div class="dmba-why__card-icon">
          <x-dmba.icon :name="$card['icon_key']" :size="24" />
        </div>
        @endif
        <h3 class="dmba-why__card-title">{{ $card['title'] }}</h3>
        @if(filled($card['description'] ?? null))
        <div class="dmba-why__card-desc">{!! rich_html($card['description'] ?? null) !!}</div>
        @endif
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
