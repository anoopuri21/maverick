@extends('layouts.app')

@section('title', ($ourStorySeo->meta_title ?? 'Our Story | Maverick Business Academy'))
@section('meta_description', ($ourStorySeo->meta_description ?? 'Discover the journey of Maverick Business Academy from inception to becoming a global leader in business education.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $ourStorySeo])
@endpush

@if(!empty($ourStorySeo->custom_body_scripts))
@push('scripts')
    {!! $ourStorySeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/our-story.css') }}">
@endpush

@section('content')
<div class="page-our-story our-story">

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     SECTION 1: HERO — "Our Story"
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
@if($hero ?? false)
<section id="story-hero" class="os-hero" aria-label="Our Story Hero">
  <div class="os-hero__bg" aria-hidden="true">
    @if($hero->image_url)
    <div class="os-hero__bg-image" style="background-image: url('{{ $hero->image_url }}')"></div>
    @endif
    <div class="os-hero__gradient"></div>
    <div class="os-hero__noise"></div>
    <div class="os-hero__shapes">
      <svg class="os-hero__shape os-hero__shape--1" viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.15)" stroke-width="1"/></svg>
      <svg class="os-hero__shape os-hero__shape--2" viewBox="0 0 300 300" fill="none"><circle cx="150" cy="150" r="120" stroke="rgba(220,38,38,0.2)" stroke-width="1"/></svg>
      <svg class="os-hero__shape os-hero__shape--3" viewBox="0 0 100 100" fill="none"><rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.15)" stroke-width="1" transform="rotate(20 50 50)"/></svg>
    </div>
    <div class="os-hero__grid-overlay"></div>
    <div class="os-hero__particles">
      @for($i = 0; $i < 6; $i++)
        <div class="os-hero__particle"></div>
      @endfor
    </div>
    <div class="os-hero__scanline"></div>
    <div class="os-hero__corners">
      <div class="os-hero__corner os-hero__corner--tl"></div>
      <div class="os-hero__corner os-hero__corner--tr"></div>
      <div class="os-hero__corner os-hero__corner--bl"></div>
      <div class="os-hero__corner os-hero__corner--br"></div>
    </div>
  </div>
  <div class="container os-hero__content">
    <span class="os-hero__eyebrow fade-up" data-testid="hero-eyebrow">
      <span class="os-hero__eyebrow-line"></span>
      A Legacy of Global Impact
    </span>
    <h1 class="os-hero__title fade-up" data-testid="hero-title">
      {!! $hero->heading !!}
    </h1>
    <div class="os-hero__description fade-up" data-testid="hero-desc">
      {!! $hero->description !!}
    </div>
    <div class="os-hero__scroll-hint fade-up" aria-hidden="true">
      <span class="os-hero__scroll-text">Scroll to explore</span>
      <span class="os-hero__scroll-arrow" data-lucide="chevron-down"></span>
    </div>
  </div>
</section>
@endif

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     SECTION 2: HOW IT STARTED ($beginning)
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
@if($beginning ?? false)
<section id="beginning" class="os-beginning" aria-label="Where It All Began">
  <div class="os-beginning__decor" aria-hidden="true">
    <div class="os-beginning__dot-grid"></div>
  </div>
  <div class="container">
    <div class="os-beginning__grid">
      <div class="os-beginning__text">
        <span class="os-section-label fade-up">{{ $beginning->badge ?? 'Our Beginning' }}</span>
        <h2 class="os-section-heading fade-up">
          <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $beginning->heading ?? '' }}</span></span>
        </h2>
        <p class="os-body-text fade-up">{{ $beginning->paragraph_1 ?? '' }}</p>
        <p class="os-body-text fade-up">{{ $beginning->paragraph_2 ?? '' }}</p>
      </div>
      <div class="os-beginning__image-col">
        <div class="os-beginning__image-wrap fade-up">
          <div class="os-beginning__image-accent" aria-hidden="true"></div>
          <img src="{{ $beginning->image_url ?? '' }}" alt="Where It All Began" class="os-beginning__image" loading="lazy" />
        </div>
      </div>
    </div>
  </div>
</section>
@endif

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     SECTION 3: WHAT WE DO TODAY ($today)
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
@if($today ?? false)
<section id="today" class="os-today" aria-label="What We Do Today">
  <div class="os-today__decor" aria-hidden="true">
    <div class="os-today__blob"></div>
  </div>
  <div class="container">
    <div class="os-today__grid">
      <div class="os-today__image-col">
        <div class="os-today__image-wrap fade-up">
          <img src="{{ $today->image_url ?? '' }}" alt="What We Do Today" class="os-today__image" loading="lazy" />
        </div>
      </div>
      <div class="os-today__text">
        <span class="os-section-label fade-up">{{ $today->badge ?? 'Today' }}</span>
        <h2 class="os-section-heading fade-up">
          <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $today->heading ?? '' }}</span></span>
        </h2>
        <p class="os-body-text fade-up">{{ $today->description ?? '' }}</p>
      </div>
    </div>
    <!-- <div class="os-today__pills fade-up" aria-label="Programme categories">
      <span class="os-today__pill">Undergraduate</span>
      <span class="os-today__pill">Postgraduate</span>
      <span class="os-today__pill">Doctoral</span>
      <span class="os-today__pill">Executive Education</span>
      <span class="os-today__pill">Professional Development</span>
    </div> -->
  </div>
</section>
@endif

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     SECTION 4: OUR IMPACT ($impact)
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
@if($impact ?? false)
<section id="impact" class="os-impact" aria-label="Our Impact">
  <div class="os-impact__decor" aria-hidden="true">
    <span class="os-impact__watermark">IMPACT</span>
    <div class="os-impact__lines"></div>
    <svg class="os-impact__geo os-impact__geo--1" viewBox="0 0 120 120" fill="none"><polygon points="60,5 115,95 5,95" stroke="rgba(220,38,38,0.1)" stroke-width="1"/></svg>
    <svg class="os-impact__geo os-impact__geo--2" viewBox="0 0 80 80" fill="none"><circle cx="40" cy="40" r="35" stroke="rgba(255,255,255,0.06)" stroke-width="1"/></svg>
  </div>
  <div class="container">
    <div class="os-impact__header">
      <span class="os-section-label os-section-label--light fade-up">Our Impact</span>
      <h2 class="os-section-heading os-section-heading--light fade-up">
        <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $impact->heading ?? '' }}</span></span>
      </h2>
      <p class="os-body-text os-body-text--light fade-up">{{ $impact->description ?? '' }}</p>
    </div>
    <div class="os-impact__stats">
      @if($impact->stat_1_value && $impact->stat_1_label)
      <div class="os-impact__stat fade-up" data-counter-target="{{ preg_replace('/[^0-9]/', '', $impact->stat_1_value) }}">
        <span class="os-impact__stat-value" data-counter>{{ $impact->stat_1_value }}</span>
        <span class="os-impact__stat-line" aria-hidden="true"></span>
        <span class="os-impact__stat-label">{{ $impact->stat_1_label }}</span>
      </div>
      @endif
      @if($impact->stat_2_value && $impact->stat_2_label)
      <div class="os-impact__stat fade-up" data-counter-target="{{ preg_replace('/[^0-9]/', '', $impact->stat_2_value) }}">
        <span class="os-impact__stat-value" data-counter>{{ $impact->stat_2_value }}</span>
        <span class="os-impact__stat-line" aria-hidden="true"></span>
        <span class="os-impact__stat-label">{{ $impact->stat_2_label }}</span>
      </div>
      @endif
      @if($impact->stat_3_value && $impact->stat_3_label)
      <div class="os-impact__stat fade-up" data-counter-target="{{ preg_replace('/[^0-9]/', '', $impact->stat_3_value) }}">
        <span class="os-impact__stat-value" data-counter>{{ $impact->stat_3_value }}</span>
        <span class="os-impact__stat-line" aria-hidden="true"></span>
        <span class="os-impact__stat-label">{{ $impact->stat_3_label }}</span>
      </div>
      @endif
      @if($impact->stat_4_value && $impact->stat_4_label)
      <div class="os-impact__stat fade-up" data-counter-target="{{ preg_replace('/[^0-9]/', '', $impact->stat_4_value) }}">
        <span class="os-impact__stat-value" data-counter>{{ $impact->stat_4_value }}</span>
        <span class="os-impact__stat-line" aria-hidden="true"></span>
        <span class="os-impact__stat-label">{{ $impact->stat_4_label }}</span>
      </div>
      @endif
    </div>
  </div>
</section>
@endif

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     SECTION 5: VISION ($vision)
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
@if($vision ?? false)
<section id="vision" class="os-vision" aria-label="Vision for the Future">
  
  <div class="os-vision__decor" aria-hidden="true">
    <svg class="os-vision__horizon" viewBox="0 0 1200 2" preserveAspectRatio="none"><line x1="0" y1="1" x2="1200" y2="1" stroke="rgba(220,38,38,0.15)" stroke-width="1" stroke-dasharray="8 6"/></svg>
    <svg class="os-vision__arrow" viewBox="0 0 60 60" fill="none"><path d="M10 30h35M35 20l10 10-10 10" stroke="rgba(26,43,122,0.12)" stroke-width="2" stroke-linecap="round"/></svg>
  </div>
  <div class="container">
    <div class="os-beginning__grid">
      <div class="os-beginning__text">
        <span class="os-section-label fade-up">Vision for the Future</span>
        <h2 class="os-section-heading fade-up">
          <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $vision->heading ?? 'Looking Ahead' }}</span></span>
        </h2>
        <p class="os-body-text fade-up">{{ $vision->description ?? '' }}</p>
        @if(($vision->cta_label ?? false) && ($vision->cta_url ?? false))
      <a href="{{ $vision->cta_url }}" class="os-vision__cta btn btn--primary fade-up">{{ $vision->cta_label }}</a>
      @endif
      </div>
      <div class="os-beginning__image-col">
        <div class="os-beginning__image-wrap fade-up">
          <div class="os-beginning__image-accent" aria-hidden="true"></div>
        @if($vision->background_image_url)
          <img src="{{ $vision->background_image_url }}" alt="Where It All Began" class="os-beginning__image" loading="lazy" />
        @endif
        </div>
      </div>
    </div>
  </div>
</section>
@endif

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     SECTION 6: OUR JOURNEY — TIMELINE ($timelines)
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
@if(($timelines ?? collect())->count() > 0)
<section id="journey" class="os-journey" aria-label="Our Journey Timeline">
  <div class="os-journey__noise" aria-hidden="true"></div>

  {{-- Animated Background Geometric Shapes --}}
  <div class="os-journey__bg-shapes" aria-hidden="true">
    <div class="os-journey__bg-shape os-journey__bg-shape--circle os-journey__bg-shape--1"></div>
    <div class="os-journey__bg-shape os-journey__bg-shape--circle os-journey__bg-shape--2"></div>
    <div class="os-journey__bg-shape os-journey__bg-shape--circle os-journey__bg-shape--3"></div>
    <div class="os-journey__bg-shape os-journey__bg-shape--triangle os-journey__bg-shape--4"></div>
    <div class="os-journey__bg-shape os-journey__bg-shape--triangle os-journey__bg-shape--5"></div>
    <div class="os-journey__bg-shape os-journey__bg-shape--square os-journey__bg-shape--6"></div>
    <div class="os-journey__bg-shape os-journey__bg-shape--square os-journey__bg-shape--7"></div>
    <div class="os-journey__bg-shape os-journey__bg-shape--dot os-journey__bg-shape--8"></div>
    <div class="os-journey__bg-shape os-journey__bg-shape--dot os-journey__bg-shape--9"></div>
    <div class="os-journey__bg-shape os-journey__bg-shape--dot os-journey__bg-shape--10"></div>
    <div class="os-journey__bg-shape os-journey__bg-shape--dot os-journey__bg-shape--11"></div>
    <div class="os-journey__bg-shape os-journey__bg-shape--dot os-journey__bg-shape--12"></div>
  </div>

  {{-- Desktop: Horizontal Pinned Scroll --}}
  <div class="os-journey__pin-wrap" data-journey-pin>
    
    <div class="os-journey__scroll-hint" data-journey-hint>
      <span>Scroll</span>
      <span data-lucide="arrow-right"></span>
    </div>

    <div class="os-journey__track" data-journey-track @style(['width: '.($timelines->count() * 100).'vw'])>
      @foreach($timelines as $index => $item)
      @php
        $yearStr = $item->year ?? '';
        $isNumeric = is_numeric($yearStr);
        $shortYear = $isNumeric ? substr($yearStr, -2) : $yearStr;
      @endphp
      <div class="os-journey__slide" data-journey-slide="{{ $index }}">
        <div class="os-journey__slide-line" aria-hidden="true"></div>
        <div class="os-journey__slide-inner">
          <div class="os-journey__slide-left">
            <span class="os-journey__year-badge">{{ $yearStr }}</span>
            <div class="os-journey__marker" aria-hidden="true">
              <span class="os-journey__marker-dot"></span>
              <span class="os-journey__marker-line"></span>
            </div>
            <h3 class="os-journey__slide-title">{{ $item->title ?? '' }}</h3>
            @if($item->description)
            <p class="os-journey__slide-desc">{{ $item->description }}</p>
            @endif
          </div>
          <div class="os-journey__slide-right">
            <span class="os-journey__big-year" aria-hidden="true">{{ $yearStr }}</span>
            @if($item->icon_url)
            <div class="os-journey__slide-image">
              <img src="{{ $item->icon_url }}" alt="{{ $item->title ?? $yearStr }}" loading="lazy" />
            </div>
            @else
            <div class="os-journey__slide-image os-journey__slide-image--fallback">
              <span class="os-journey__fallback-year">{{ $shortYear }}</span>
            </div>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  {{-- Mobile: Vertical Stacked Cards --}}
  <div class="os-journey__mobile" data-journey-mobile>
    <div class="container">
      <div class="os-journey__mobile-header">
        <span class="os-section-label fade-up">Journey</span>
        <h2 class="os-section-heading fade-up">Our <em>Journey</em></h2>
      </div>
      @foreach($timelines as $index => $item)
      @php
        $yearStr = $item->year ?? '';
        $shortYear = is_numeric($yearStr) ? substr($yearStr, -2) : $yearStr;
      @endphp
      <div class="os-journey__mobile-card fade-up">
        <div class="os-journey__mobile-card-year" aria-hidden="true">{{ $shortYear }}</div>
        <span class="os-journey__year-badge">{{ $yearStr }}</span>
        <h3 class="os-journey__mobile-card-title">{{ $item->title ?? '' }}</h3>
        @if($item->description)
        <p class="os-journey__mobile-card-desc">{{ $item->description }}</p>
        @endif
        @if($item->icon_url)
        <div class="os-journey__mobile-card-image">
          <img src="{{ $item->icon_url }}" alt="{{ $item->title ?? $yearStr }}" loading="lazy" />
        </div>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     SECTION 7: CEO MESSAGE (shared)
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
@include('sections.ceo-message')

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     SECTION 8: IMAGE SLIDESHOW
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
@include('sections.our-story-gallery')

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     SECTION 9: OUR STORY TESTIMONIAL SLIDER
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
@include('sections.our-story-testimonials')

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     FINAL CTA
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
@include('sections.final-cta')

</div>
@endsection
