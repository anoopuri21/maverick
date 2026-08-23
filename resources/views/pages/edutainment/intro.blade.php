{{-- ===== S1: INTRO — Explore the World ===== --}}
@php
    $introCtas = collect($intro->ctas ?? [])->filter(fn ($cta) => filled($cta['label'] ?? null) && filled($cta['url'] ?? null));
    $hasIntroTitle = filled($intro->title_line1 ?? null)
        || filled($intro->title_line2 ?? null)
        || filled($intro->title_line2_italic ?? null)
        || filled($intro->title_line3 ?? null)
        || filled($intro->title_line3_italic ?? null);
    $showIntro = filled($intro->label ?? null)
        || $hasIntroTitle
        || html_filled($intro->body ?? null)
        || filled($intro->emphasis ?? null)
        || $introCtas->isNotEmpty();
@endphp
@if($showIntro)
<section id="edu-intro" class="edu-intro section--light section-wrapper" aria-label="Explore the World. Experience New Cultures.">
  <div class="container">
    <div class="edu-intro__frame">
      @if(filled($intro->label))
      <span class="edu-intro__label fade-up">{{ $intro->label }}</span>
      @endif

      @if($hasIntroTitle)
      <h2 class="edu-intro__title fade-up">
        @if(filled($intro->title_line1)){{ $intro->title_line1 }}<br>@endif
        @if(filled($intro->title_line2) || filled($intro->title_line2_italic))
          {{ $intro->title_line2 }}@if(filled($intro->title_line2) && filled($intro->title_line2_italic)) @endif@if(filled($intro->title_line2_italic))<em>{{ $intro->title_line2_italic }}</em>@endif.<br>
        @endif
        @if(filled($intro->title_line3) || filled($intro->title_line3_italic))
          {{ $intro->title_line3 }}@if(filled($intro->title_line3) && filled($intro->title_line3_italic)) @endif@if(filled($intro->title_line3_italic))<em>{{ $intro->title_line3_italic }}</em>@endif.
        @endif
      </h2>
      @endif

      <div class="edu-intro__body">
        @if(html_filled($intro->body ?? null))
          <div class="edu-richtext">{!! rich_html($intro->body ?? null) !!}</div>
        @endif

        @if(filled($intro->emphasis))
        <p class="edu-intro__emphasis fade-up">
          <strong>{{ $intro->emphasis }}</strong>
        </p>
        @endif

        @if($introCtas->isNotEmpty())
        <div class="edu-intro__ctas fade-up">
          @foreach($introCtas as $cta)
            <a href="{{ edu_href($cta['url']) }}" class="{{ edu_cta_class($cta['style'] ?? 'primary') }}">{{ $cta['label'] }}</a>
          @endforeach
        </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endif
