{{-- ===== S2: WHAT IS EDUTAINMENT ===== --}}
@php
    $whatIsItems = collect($whatIs->items ?? [])->filter(fn ($item) => filled(is_array($item) ? ($item['item'] ?? null) : $item));
    $showWhatIs = filled($whatIs->label ?? null)
        || filled($whatIs->title ?? null)
        || filled($whatIs->title_italic ?? null)
        || filled($whatIs->wordmark_line1 ?? null)
        || filled($whatIs->wordmark_line2 ?? null)
        || html_filled($whatIs->lead ?? null)
        || html_filled($whatIs->body ?? null)
        || $whatIsItems->isNotEmpty()
        || html_filled($whatIs->quote ?? null)
        || filled($whatIs->quote ?? null);
@endphp
@if($showWhatIs)
<section id="edu-what-is" class="edu-what-is section--light section-wrapper" aria-label="What Is Edutainment">
  <div class="container">
    <div class="edu-section-header edu-section-header--left">
      @include('pages.edutainment._section-heading', [
        'label' => $whatIs->label,
        'title' => $whatIs->title,
        'titleLine2' => null,
        'titleItalic' => $whatIs->title_italic,
        'titleBreak' => $whatIs->title_break,
      ])
    </div>

    @if(filled($whatIs->wordmark_line1) || filled($whatIs->wordmark_line2) || html_filled($whatIs->lead ?? null) || html_filled($whatIs->body ?? null))
    <div class="edu-what-is__frame">
      @if(filled($whatIs->wordmark_line1) || filled($whatIs->wordmark_line2))
      <div class="edu-what-is__wordmark fade-up">
        {{ $whatIs->wordmark_line1 }}@if(filled($whatIs->wordmark_plus))<em>{{ $whatIs->wordmark_plus }}</em>@endif
        @if(filled($whatIs->wordmark_line2))<br>{{ $whatIs->wordmark_line2 }}@endif
        @if(filled($whatIs->wordmark_sub))
        <span class="edu-what-is__wordmark-sub">{{ $whatIs->wordmark_sub }}</span>
        @endif
      </div>
      @endif

      @if(html_filled($whatIs->lead ?? null) || html_filled($whatIs->body ?? null))
      <div class="edu-what-is__copy">
        @if(html_filled($whatIs->lead ?? null))
        <div class="edu-what-is__lead fade-up edu-richtext">{!! $whatIs->lead !!}</div>
        @endif
        @if(html_filled($whatIs->body ?? null))
        <div class="edu-richtext fade-up">{!! rich_html($whatIs->body ?? null) !!}</div>
        @endif
      </div>
      @endif
    </div>
    @endif

    @if($whatIsItems->isNotEmpty())
    <div class="edu-what-is__list-wrapper fade-up">
      @if(filled($whatIs->list_title))
      <h3 class="edu-what-is__list-title">{{ $whatIs->list_title }}</h3>
      @endif
      <div class="edu-what-is__list">
        @foreach($whatIsItems as $item)
        <div class="edu-what-is__list-item fade-up">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
          <span>{{ is_array($item) ? ($item['item'] ?? '') : $item }}</span>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    @if(html_filled($whatIs->quote ?? null) || filled($whatIs->quote ?? null))
    <div class="edu-what-is__quote fade-up">
      @if(strip_tags($whatIs->quote) !== $whatIs->quote)
        {!! rich_html($whatIs->quote ?? null) !!}
      @else
        <div>&ldquo;{!! rich_html($whatIs->quote ?? null) !!}&rdquo;</div>
      @endif
    </div>
    @endif
  </div>
</section>
@endif
