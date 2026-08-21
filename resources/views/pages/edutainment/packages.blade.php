{{-- ===== S9: WHAT CAN BE INCLUDED IN AN EDUTAINMENT PACKAGE? ===== --}}
@php
    $packageItems = collect($packages->items ?? [])->filter(fn ($item) => filled(is_array($item) ? ($item['item'] ?? null) : $item));
    $packageCtas = collect($packages->ctas ?? [])->filter(fn ($cta) => filled($cta['label'] ?? null) && filled($cta['url'] ?? null));
    $showPackages = filled($packages->label ?? null)
        || filled($packages->title ?? null)
        || filled($packages->title_italic ?? null)
        || html_filled($packages->intro ?? null)
        || $packageItems->isNotEmpty();
@endphp
@if($showPackages)
<section id="edu-packages" class="edu-packages section-wrapper section--light" aria-label="Package Inclusions">
  <div class="container">
    <div class="edu-packages__header">
      @include('pages.edutainment._section-heading', [
        'label' => $packages->label,
        'title' => $packages->title,
        'titleLine2' => $packages->title_line2,
        'titleItalic' => $packages->title_italic,
        'titleBreak' => $packages->title_break,
      ])
      @if(html_filled($packages->intro ?? null))
      <div class="edu-richtext fade-up">{!! $packages->intro !!}</div>
      @endif
    </div>

    @if($packageItems->isNotEmpty())
    <div class="edu-packages__grid">
      @foreach($packageItems as $item)
      <div class="edu-packages__item fade-up">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
        <span>{{ is_array($item) ? ($item['item'] ?? '') : $item }}</span>
      </div>
      @endforeach
    </div>
    @endif

    @if(filled($packages->note))
    <p class="edu-packages__note body-text fade-up">{{ $packages->note }}</p>
    @endif

    @if($packageCtas->isNotEmpty())
    <div class="edu-packages__cta fade-up">
      @foreach($packageCtas as $cta)
        <a href="{{ edu_href($cta['url']) }}" class="{{ edu_cta_class($cta['style'] ?? 'primary') }}">{{ $cta['label'] }}</a>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
