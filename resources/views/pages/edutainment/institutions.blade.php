{{-- ===== S10: EDUCATIONAL TOURS FOR SCHOOLS AND INSTITUTIONS ===== --}}
@php
    $institutionTiles = collect($institutions->tiles ?? [])->filter(fn ($tile) => filled($tile['label'] ?? null));
    $institutionCtas = collect($institutions->ctas ?? [])->filter(fn ($cta) => filled($cta['label'] ?? null) && filled($cta['url'] ?? null));
    $showInstitutions = filled($institutions->label ?? null)
        || filled($institutions->title ?? null)
        || filled($institutions->title_italic ?? null)
        || html_filled($institutions->intro ?? null)
        || $institutionTiles->isNotEmpty();
@endphp
@if($showInstitutions)
<section id="edu-institutions" class="edu-institutions section--light section--warm section-wrapper" aria-label="Educational Tours for Schools">
  <div class="container">
    <div class="edu-institutions__header">
      @include('pages.edutainment._section-heading', [
        'label' => $institutions->label,
        'title' => $institutions->title,
        'titleLine2' => $institutions->title_line2,
        'titleItalic' => $institutions->title_italic,
        'titleBreak' => $institutions->title_break,
      ])
      @if(html_filled($institutions->intro ?? null))
      <div class="edu-richtext fade-up">{!! $institutions->intro !!}</div>
      @endif
    </div>

    @if($institutionTiles->isNotEmpty())
    <div class="edu-institutions__grid">
      @foreach($institutionTiles as $tile)
      <div class="edu-institutions__card fade-up">
        @if(filled($tile['icon_key'] ?? null))
          <x-edu.icon :name="$tile['icon_key']" :size="22" />
        @endif
        <span>{{ $tile['label'] }}</span>
      </div>
      @endforeach
    </div>
    @endif

    @if(filled($institutions->note))
    <p class="edu-institutions__note body-text fade-up">{{ $institutions->note }}</p>
    @endif

    @if($institutionCtas->isNotEmpty())
    <div class="edu-institutions__cta fade-up">
      @foreach($institutionCtas as $cta)
        <a href="{{ edu_href($cta['url']) }}" class="{{ edu_cta_class($cta['style'] ?? 'primary') }}">{{ $cta['label'] }}</a>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
