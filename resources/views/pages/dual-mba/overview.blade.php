{{-- ===== S2: PROGRAMME OVERVIEW ===== --}}
@php
    $highlightCards = collect($overview->cards ?? [])->filter(fn ($card) => filled($card['title'] ?? null));
    $showOverview = filled($overview->label ?? null)
        || filled($overview->heading ?? null)
        || filled($overview->heading_highlight ?? null)
        || html_filled($overview->description ?? null)
        || $highlightCards->isNotEmpty();
@endphp
@if($showOverview)
<section id="dmba-overview" class="dmba-overview section--light section-wrapper" aria-label="Programme Overview" data-testid="dmba-overview-section">
  <div class="container">
    @if(filled($overview->label) || filled($overview->heading) || filled($overview->heading_highlight) || html_filled($overview->description ?? null))
    <div class="dmba-overview__header">
      @if(filled($overview->label))
      <div class="section-label"><span>{{ $overview->label }}</span></div>
      @endif
      @if(filled($overview->heading) || filled($overview->heading_highlight))
      <h2 class="dmba-overview__heading section-title">
        {{ $overview->heading }}
        @if(filled($overview->heading_highlight))
          <span class="highlight">{{ $overview->heading_highlight }}</span>
        @endif
      </h2>
      @endif
      @if(html_filled($overview->description ?? null))
      <div class="dmba-overview__desc body-text dmba-richtext">{!! rich_html($overview->description ?? null) !!}</div>
      @endif
    </div>
    @endif

    @if($highlightCards->isNotEmpty() || filled($overview->highlights_heading) || filled($overview->highlights_line))
    <div class="dmba-highlights" data-testid="dmba-highlights-section">
      @if(filled($overview->highlights_heading))
      <h3 class="dmba-highlights__block-heading">{{ $overview->highlights_heading }}</h3>
      @endif
      @if(filled($overview->highlights_line))
      <p class="dmba-highlights__block-line">{{ $overview->highlights_line }}</p>
      @endif

      @if($highlightCards->isNotEmpty())
      <div class="dmba-highlights__grid" data-testid="dmba-highlights-grid">
        @foreach($highlightCards as $card)
        <div class="dmba-highlights__card">
          @if(filled($card['icon_key'] ?? null))
          <div class="dmba-highlights__card-icon dmba-highlights__card-icon--{{ in_array($card['icon_tone'] ?? 'blue', ['red', 'blue'], true) ? $card['icon_tone'] : 'blue' }}">
            <x-dmba.icon :name="$card['icon_key']" :size="26" />
          </div>
          @endif
          <div class="dmba-highlights__card-body">
            <h3 class="dmba-highlights__card-title">{{ $card['title'] }}</h3>
            @if(html_filled($card['text'] ?? null))
            <div class="dmba-highlights__card-text">{!! rich_html($card['text'] ?? null) !!}</div>
            @endif
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>
    @endif
  </div>
</section>
@endif
