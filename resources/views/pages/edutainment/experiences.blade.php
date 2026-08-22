{{-- ===== S7: WHAT STUDENTS CAN EXPERIENCE ===== --}}
@php
    $experienceCategories = collect($experiences->categories ?? [])
        ->map(function ($category) {
            $category['items'] = collect($category['items'] ?? [])
                ->filter(fn ($item) => filled(is_array($item) ? ($item['item'] ?? null) : $item))
                ->values();

            return $category;
        })
        ->filter(fn ($category) => filled($category['title'] ?? null) || $category['items']->isNotEmpty());
    $showExperiences = filled($experiences->label ?? null)
        || filled($experiences->title ?? null)
        || filled($experiences->title_italic ?? null)
        || html_filled($experiences->intro ?? null)
        || $experienceCategories->isNotEmpty();
@endphp
@if($showExperiences)
<section id="edu-experiences" class="edu-experiences section-wrapper section--light" aria-label="What Students Can Experience">
  <div class="container">
    <div class="edu-experiences__header">
      @include('pages.edutainment._section-heading', [
        'label' => $experiences->label,
        'title' => $experiences->title,
        'titleLine2' => $experiences->title_line2,
        'titleItalic' => $experiences->title_italic,
        'titleBreak' => $experiences->title_break,
      ])
      @if(html_filled($experiences->intro ?? null))
      <div class="edu-richtext fade-up">{!! $experiences->intro !!}</div>
      @endif
    </div>

    @if($experienceCategories->isNotEmpty())
    <div class="edu-experiences__grid">
      @foreach($experienceCategories as $category)
      <div class="edu-experiences__card fade-up">
        <div class="edu-experiences__card-header">
          @if(filled($category['icon_key'] ?? null))
          <div class="edu-experiences__card-icon">
            <x-edu.icon :name="$category['icon_key']" :size="28" />
          </div>
          @endif
          @if(filled($category['title'] ?? null))
          <h3 class="edu-experiences__card-title">{{ $category['title'] }}</h3>
          @endif
        </div>
        @if($category['items']->isNotEmpty())
        <ul class="edu-experiences__card-list">
          @foreach($category['items'] as $item)
            <li>{{ is_array($item) ? ($item['item'] ?? '') : $item }}</li>
          @endforeach
        </ul>
        @endif
      </div>
      @endforeach
    </div>
    @endif

    @if(filled($experiences->note))
    <p class="edu-experiences__note body-text fade-up">{{ $experiences->note }}</p>
    @endif
  </div>
</section>
@endif
