{{-- ===== S6: EDUCATIONAL TOUR THEMES ===== --}}
@php
    $themeCards = collect($themes->cards ?? [])->filter(fn ($card) => filled($card['title'] ?? null));
    $showThemes = filled($themes->label ?? null)
        || filled($themes->title ?? null)
        || filled($themes->title_italic ?? null)
        || html_filled($themes->intro ?? null)
        || $themeCards->isNotEmpty();
@endphp
@if($showThemes)
<section id="edu-themes" class="edu-themes section--light section--warm section-wrapper" aria-label="Educational Tour Themes">
  <div class="container">
    <div class="edu-themes__header">
      @include('pages.edutainment._section-heading', [
        'label' => $themes->label,
        'title' => $themes->title,
        'titleLine2' => $themes->title_line2,
        'titleItalic' => $themes->title_italic,
        'titleBreak' => $themes->title_break,
      ])
      @if(html_filled($themes->intro ?? null))
      <div class="edu-richtext fade-up">{!! $themes->intro !!}</div>
      @endif
    </div>

    @if($themeCards->isNotEmpty())
    <div class="edu-themes__grid">
      @foreach($themeCards as $card)
      <div class="edu-themes__card fade-up">
        @if(filled($card['icon_key'] ?? null))
        <div class="edu-themes__card-icon">
          <x-edu.icon :name="$card['icon_key']" :size="24" />
        </div>
        @endif
        <h3 class="edu-themes__card-title">{{ $card['title'] }}</h3>
        @if(filled($card['description'] ?? null))
        <div class="edu-themes__card-desc">{!! rich_html($card['description'] ?? null) !!}</div>
        @endif
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
