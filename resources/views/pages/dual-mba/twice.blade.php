{{-- ===== S4: TWICE MARKETING — FULL-SCREEN SCROLL STACK ===== --}}
@php
    $slides = collect($twice->slides ?? [])->filter(function ($slide) {
        return filled($slide['title'] ?? null)
            || filled($slide['title_italic'] ?? null)
            || filled(settings_media_url($slide, 'image'));
    });
@endphp
@if($slides->isNotEmpty())
<section class="dmba-twice" aria-label="Twice the Value" data-testid="dmba-twice-section">
  <div class="dmba-twice__slides" data-testid="dmba-twice-slides">
    @foreach($slides as $slide)
    <div class="dmba-twice__slide" style="z-index: {{ $loop->iteration }}" data-testid="dmba-twice-slide-{{ $loop->iteration }}">
      @php $slideImage = settings_media_url($slide, 'image'); @endphp
      @if(filled($slideImage))
      <img
        src="{{ $slideImage }}"
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
            <em>{{ $slide['title_italic'] }}</em>
          @endif
        </h2>
        @endif
      </div>
    </div>
    @endforeach
  </div>
</section>
@endif
