{{-- ===== S6: SPECIALISATIONS GRID ===== --}}
@php
    $specCards = collect($specs->cards ?? [])->filter(fn ($card) => filled($card['title'] ?? null));
    $showSpecs = filled($specs->label ?? null)
        || filled($specs->title ?? null)
        || filled($specs->title_highlight ?? null)
        || html_filled($specs->intro ?? null)
        || $specCards->isNotEmpty();
@endphp
@if($showSpecs)
<section class="dmba-specs section--light section-wrapper" aria-label="MBA Specialisations" data-testid="dmba-specs-section">
  <div class="container">
    <div class="dmba-specs__header">
      @if(filled($specs->label))
      <div class="section-label"><span>{{ $specs->label }}</span></div>
      @endif
      @if(filled($specs->title) || filled($specs->title_highlight))
      <h2 class="section-title">
        {{ $specs->title }}
        @if(!empty($specs->title_break) && filled($specs->title_highlight))<br>@elseif(filled($specs->title) && filled($specs->title_highlight)) @endif
        @if(filled($specs->title_highlight))
          <span class="highlight">{{ $specs->title_highlight }}</span>
        @endif
      </h2>
      @endif
      @if(html_filled($specs->intro ?? null))
      <div class="body-text dmba-richtext" style="margin-top: 16px; color: rgba(0,0,0,0.6);">{!! $specs->intro !!}</div>
      @endif
    </div>

    @if($specCards->isNotEmpty())
    <div class="dmba-specs__grid" data-testid="dmba-specs-grid">
      @foreach($specCards as $card)
      <div class="dmba-specs__card">
        @if(filled($card['icon_key'] ?? null))
        <div class="dmba-specs__card-icon">
          <x-dmba.icon :name="$card['icon_key']" :size="22" />
        </div>
        @endif
        <h3 class="dmba-specs__card-title">{{ $card['title'] }}</h3>
        @if(filled($card['tag'] ?? null))
        <span class="dmba-specs__card-tag">{{ $card['tag'] }}</span>
        @endif
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
