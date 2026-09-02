{{-- ===== S4: WHO ARE OUR EDUCATIONAL TOURS DESIGNED FOR? ===== --}}
@php
    $whoForCards = collect($whoFor->cards ?? [])->filter(fn ($card) => filled($card['title'] ?? null));
    $whoForCtas = collect($whoFor->ctas ?? [])->filter(fn ($cta) => filled($cta['label'] ?? null) && filled($cta['url'] ?? null));
    $showWhoFor = filled($whoFor->label ?? null)
        || filled($whoFor->title ?? null)
        || filled($whoFor->title_italic ?? null)
        || html_filled($whoFor->intro ?? null)
        || $whoForCards->isNotEmpty();
@endphp
@if($showWhoFor)
<section id="edu-who-for" class="edu-who-for section--light section--warm section-wrapper" aria-label="Who Are Tours Designed For">
  <div class="container">
    <div class="edu-who-for__header">
      @include('pages.edutainment._section-heading', [
        'label' => $whoFor->label,
        'title' => $whoFor->title,
        'titleLine2' => $whoFor->title_line2,
        'titleItalic' => $whoFor->title_italic,
        'titleBreak' => $whoFor->title_break,
      ])
      @if(html_filled($whoFor->intro ?? null))
      <div class="edu-richtext fade-up">{!! $whoFor->intro !!}</div>
      @endif
    </div>

    @if($whoForCards->isNotEmpty())
    <div class="edu-who-for__grid">
      @foreach($whoForCards as $card)
      <div class="edu-who-for__card fade-up">
        @if(filled($card['icon_key'] ?? null))
        <div class="edu-who-for__card-icon">
          <x-edu.icon :name="$card['icon_key']" :size="28" />
        </div>
        @endif
        <h3 class="edu-who-for__card-title">{{ $card['title'] }}</h3>
        @if(filled($card['description'] ?? null))
        <div class="edu-who-for__card-desc">{!! rich_html($card['description'] ?? null) !!}</div>
        @endif
      </div>
      @endforeach
    </div>
    @endif

    @if($whoForCtas->isNotEmpty())
    <div class="edu-who-for__cta fade-up">
      @foreach($whoForCtas as $cta)
        <a href="{{ edu_href($cta['url']) }}" class="{{ edu_cta_class($cta['style'] ?? 'primary') }}">{{ $cta['label'] }}</a>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
