@extends('layouts.app')

@section('title', 'Our Story | Maverick Business Academy')
@section('meta_description', 'Discover the journey of Maverick Business Academy from inception to becoming a global leader in business education.')

@push('styles')
<style>
    /* Our Story Page CSS */
.story-hero { position: relative; color: #fff; min-height: 80vh; display: flex; align-items: center; overflow: hidden; }
.story-hero__bg { position: absolute; inset: 0; background-size: cover; z-index: 0; }
.story-hero__overlay { position: absolute; inset: 0; background: linear-gradient(90deg, rgba(15,41,131,0.85), rgba(15,41,131,0.6)); z-index: 1; }
.story-hero__content {
    position: relative;
    z-index: 2;
    max-width: 1000px;
    align-self: end;
    margin-left: 0;
}
.section-label {
    letter-spacing: 0.15em;
    font-size: 5rem;
    text-transform: uppercase;
    font-weight: 700;
}
.story-hero.section--light .section-label::before {
    background: var(--color-white);
}
.our-story-hero__description p {
    margin-bottom: 18px;
}
.section-title { color: #fff !important; }
.font-white { color: #fff !important; }

</style>
@endpush

@section('content')

{{-- 1. Hero — full-bleed centered banner --}}
@if($hero ?? false)
<section class="section-wrapper section--light story-hero">
  <div class="story-hero__bg" style="background-image: url('{{ $hero->image_url ?? '' }}')"></div>
  <div class="story-hero__overlay"></div>
  <div class="container story-hero__content">
    <span class="section-label">{{ $hero->heading ?? 'OUR STORY' }}</span>
    <div class="our-story-hero__description font-white">
    {!! $hero->description !!}
</div>
  </div>
</section>
    
    @endif

    {{-- 2. Where It All Began --}}
    @if($beginning ?? false)
    <section id="beginning" class="beginning section--light section-wrapper" aria-label="Where It All Began">
        <div class="container">
            <div class="beginning__grid grid-2">
                <div class="beginning__content">
                    <div class="section-label fade-up">
                        <span>{{ $beginning->badge ?? '' }}</span>
                    </div>
                    <h2 class="beginning__heading section-title">
                        <span class="beginning__heading-line">
                            <span class="text-reveal-wrapper">
                                <span class="text-reveal-inner">{{ $beginning->heading ?? '' }}</span>
                            </span>
                        </span>
                    </h2>
                    <p class="beginning__paragraph body-text fade-up">
                        {{ $beginning->paragraph_1 ?? '' }}
                    </p>
                    <p class="beginning__paragraph body-text fade-up">
                        {{ $beginning->paragraph_2 ?? '' }}
                    </p>
                </div>
                <div class="beginning__image-col">
                    <div class="beginning__image-wrapper fade-up">
                        <img src="{{ $beginning->image_url ?? asset('assets/images/placeholder.jpg') }}" alt="Where It All Began" class="beginning__image" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- 3. What We Do Today --}}
    @if($today ?? false)
    <section id="today" class="today section--light section-wrapper" aria-label="What We Do Today">
        <div class="container">
            <div class="today__grid grid-2">
                <div class="today__image-col">
                    <div class="today__image-wrapper fade-up">
                        <img src="{{ $today->image_url ?? asset('assets/images/placeholder.jpg') }}" alt="What We Do Today" class="today__image" />
                    </div>
                </div>
                <div class="today__content">
                    <div class="section-label fade-up">
                        <span>{{ $today->badge ?? '' }}</span>
                    </div>
                    <h2 class="today__heading section-title">
                        <span class="today__heading-line">
                            <span class="text-reveal-wrapper">
                                <span class="text-reveal-inner">{{ $today->heading ?? '' }}</span>
                            </span>
                        </span>
                    </h2>
                    <p class="today__description body-text fade-up">
                        {{ $today->description ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- 4. Our Impact --}}
    @if($impact ?? false)
    <section id="impact" class="impact section--light section-wrapper" aria-label="Our Impact">
        <div class="container">
            <div class="impact__header">
                <div class="section-label fade-up">
                    <span>Impact</span>
                </div>
                <h2 class="impact__heading section-title">
                    <span class="impact__heading-line">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner">{{ $impact->heading ?? '' }}</span>
                        </span>
                    </span>
                </h2>
                <p class="impact__description body-text fade-up">
                    {{ $impact->description ?? '' }}
                </p>
            </div>
            <div class="impact__grid">
                @if($impact->stat_1_value && $impact->stat_1_label)
                <div class="impact__stat fade-up">
                    <span class="impact__stat-value accent-text">{{ $impact->stat_1_value }}</span>
                    <span class="impact__stat-label">{{ $impact->stat_1_label }}</span>
                </div>
                @endif
                @if($impact->stat_2_value && $impact->stat_2_label)
                <div class="impact__stat fade-up">
                    <span class="impact__stat-value accent-text">{{ $impact->stat_2_value }}</span>
                    <span class="impact__stat-label">{{ $impact->stat_2_label }}</span>
                </div>
                @endif
                @if($impact->stat_3_value && $impact->stat_3_label)
                <div class="impact__stat fade-up">
                    <span class="impact__stat-value accent-text">{{ $impact->stat_3_value }}</span>
                    <span class="impact__stat-label">{{ $impact->stat_3_label }}</span>
                </div>
                @endif
                @if($impact->stat_4_value && $impact->stat_4_label)
                <div class="impact__stat fade-up">
                    <span class="impact__stat-value accent-text">{{ $impact->stat_4_value }}</span>
                    <span class="impact__stat-label">{{ $impact->stat_4_label }}</span>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- 5. Our Journey (Cinematic Timeline) --}}
    @if($timelines ?? false && $timelines->count() > 0)
    <section id="journey" class="journey section--light section-wrapper" aria-label="Our Journey">
        <div class="container">
            
            {{-- Header --}}
            <div class="journey__header">
                <div class="section-label fade-up">
                    <span>Journey</span>
                </div>
                <h2 class="journey__heading section-title fade-up">
                    Our Journey
                </h2>
                <p class="journey__subheading fade-up">
                    A story of vision, growth, and global impact
                </p>
            </div>

            {{-- Timeline --}}
            <div class="journey__timeline">
                
                {{-- Center vertical line --}}
                <div class="journey__line"></div>

                @foreach($timelines as $index => $timeline)
                <div class="journey__item journey__item--{{ $index % 2 === 0 ? 'left' : 'right' }} fade-up">
                    
                    {{-- Center dot with icon --}}
                    <div class="journey__dot">
                        @if(!empty($timeline->icon_url))
                            <img src="{{ $timeline->icon_url }}" alt="{{ $timeline->title }}" class="journey__dot-icon">
                        @else
                            <span class="journey__dot-year">{{ $timeline->year ?? '' }}</span>
                        @endif
                    </div>

                    {{-- Content Card --}}
                    <div class="journey__card">
                        <div class="journey__year-badge">{{ $timeline->year ?? '' }}</div>
                        <h3 class="journey__title">{{ $timeline->title ?? '' }}</h3>
                        <p class="journey__description">{{ $timeline->description ?? '' }}</p>
                    </div>

                </div>
                @endforeach

            </div>
        </div>
    </section>
    @endif

    {{-- 6. CEO Message (shared with homepage) --}}
    @include('sections.ceo-message')

    {{-- 7. Vision for the Future --}}
    @if($vision ?? false)
    <section id="vision" class="vision section--dark section-wrapper" aria-label="Vision for the Future" style="background-image: url('{{ $vision->background_image_url ?? asset(`assets/images/placeholder.jpg`) }}'); background-size: cover; background-position: center; position:relative;">
        <div class="vision__overlay" style="position: absolute; inset: 0; background: rgba(0, 0, 0, 0.7);"></div>
        <div class="container" style="position: relative; z-index: 1;">
            <div class="vision__content">
                <div class="section-label fade-up">
                    <span>Vision</span>
                </div>
                <h2 class="vision__heading section-title">
                    <span class="vision__heading-line">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner">{{ $vision->heading ?? '' }}</span>
                        </span>
                    </span>
                </h2>
                <p class="vision__description body-text fade-up">
                    {{ $vision->description ?? '' }}
                </p>
                @if($vision->cta_label && $vision->cta_url)
                <a href="{{ $vision->cta_url }}" class="btn btn--primary vision__cta fade-up">
                    {{ $vision->cta_label }}
                </a>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- 8. Accreditations (shared with homepage) --}}
    @include('sections.accreditations')

    {{-- 9. Awards & Recognition --}}
@if(($awards ?? collect())->count() > 0)
<section id="awards" class="awards testimonials section-wrapper section--light" aria-label="Awards & Recognition">
    <div class="container testimonials__inner">
        <div class="testimonials__header">
            <div class="section-label"><span>Awards</span></div>
            <h2 class="testimonials__heading section-title">
                <span class="testimonials__heading-line">
                    <span class="text-reveal-wrapper">
                        <span class="text-reveal-inner">Awards &amp; Recognition</span>
                    </span>
                </span>
            </h2>
        </div>

        <div class="scroll-row scroll-row--light" data-scroll-row>
            <button class="scroll-row__btn scroll-row__btn--prev" aria-label="Scroll left" data-scroll-prev>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>
            <div class="testimonials__scroll" data-scroll-container data-lenis-prevent>
                <div class="testimonials__track">
                    @foreach($awards->where('is_active', true) as $award)
                    <article class="testimonials__card">
                            @if($award->image_url)
                            <div class="testimonials__card-thumb">
                                <img src="{{ $award->image_url }}" alt="{{ $award->title ?? 'Award' }}" loading="lazy" decoding="async" />
                            </div>
                            @endif
                            @if($award->title)
                            <h3 class="awards__card-title">{{ $award->title ?? '' }}</h3>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
            <button class="scroll-row__btn scroll-row__btn--next" aria-label="Scroll right" data-scroll-next>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>
        </div>
    </div>
</section>
@endif

    {{-- 12. Final CTA --}}
    @include('sections.final-cta')

@endsection
