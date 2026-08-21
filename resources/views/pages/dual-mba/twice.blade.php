{{-- ===== S4: TWICE MARKETING — FULL-SCREEN SCROLL STACK ===== --}}
@php
    $slides = collect($twice->slides ?? [])->filter(fn ($slide) => filled($slide['title'] ?? null) || filled($slide['title_italic'] ?? null) || filled($slide['image'] ?? null));
@endphp
@if($slides->isNotEmpty())
<section class="dmba-twice" aria-label="Twice the Value" data-testid="dmba-twice-section">
  <div class="dmba-twice__slides" data-testid="dmba-twice-slides">
    @foreach($slides as $slide)
    <div class="dmba-twice__slide" style="z-index: {{ $loop->iteration }}" data-testid="dmba-twice-slide-{{ $loop->iteration }}">
      @if(filled($slide['image'] ?? null))
      <img
        src="{{ media_url($slide['image']) }}"
        alt="" class="dmba-twice__slide-bg" loading="lazy" />
      @endif
      <div class="dmba-twice__slide-overlay"></div>
      <div class="dmba-twice__slide-content">
        @if(filled($slide['label'] ?? null))
        <span class="dmba-twice__label">{{ $slide['label'] }}</span>
        @endif
        @if(filled($slide['title'] ?? null) || filled($slide['title_italic'] ?? null))
        <h2 class="dmba-twice__title">
          {{ $slide['title'] ?? '' }}
          @if(filled($slide['title_italic'] ?? null))
            <em>{{ $slide['title_italic'] }}</em>@if(! str_ends_with($slide['title_italic'], '.')).@endif
          @endif
        </h2>
        @endif
      </div>
    </div>
    @endforeach
  </div>
</section>
@endif
