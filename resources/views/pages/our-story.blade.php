@extends('layouts.app')

@section('title', 'Our Story | Maverick Business Academy')
@section('meta_description', 'Discover the journey of Maverick Business Academy from inception to becoming a global leader in business education.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/our-story.css') }}">
@endpush

@section('content')
<div class="page-our-story our-story">

    {{-- SECTION 1: Hero Statement --}}
    @if($hero ?? false)
    <section class="section-wrapper section--light story-hero">
        <div class="story-hero__bg" style="background-image: url('{{ $hero->image_url ?? url('') }}')"></div>
        <div class="story-hero__overlay"></div>
        <div class="container story-hero__content">
            <span class="section-label">{{ $hero->heading ?? 'OUR STORY' }}</span>
            <div class="our-story-hero__description font-white">
                {!! $hero->description !!}
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 2: How It Started --}}
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
                    <div class="beginning__paragraph body-text fade-up">
                        {!! $beginning->paragraph_1 ?? '' !!}
                    </div>
                    @if(!empty($beginning->paragraph_2))
                    <div class="beginning__paragraph body-text fade-up">
                        {!! $beginning->paragraph_2 !!}
                    </div>
                    @endif
                </div>
                <div class="beginning__image-col">
                    <div class="beginning__image-wrapper fade-up">
                        <img src="{{ $beginning->image_url ?? asset('') }}" alt="Where It All Began" class="beginning__image" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 3: What We Do Today --}}
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
                    <div class="today__description body-text fade-up">
                        {!! $today->description ?? '' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 4: Our Impact --}}
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
                <div class="impact__description body-text fade-up">
                    {!! $impact->description ?? '' !!}
                </div>
            </div>
            <div class="impact__grid">
                @if($impact->stat_1_value && $impact->stat_1_label)
                <div class="impact__stat fade-up">
                    <span class="impact__stat-value accent-text">{{ $impact->stat_1_value ?? '' }}</span>
                    <span class="impact__stat-label">{{ $impact->stat_1_label ?? '' }}</span>
                </div>
                @endif
                @if($impact->stat_2_value && $impact->stat_2_label)
                <div class="impact__stat fade-up">
                    <span class="impact__stat-value accent-text">{{ $impact->stat_2_value ?? '' }}</span>
                    <span class="impact__stat-label">{{ $impact->stat_2_label ?? '' }}</span>
                </div>
                @endif
                @if($impact->stat_3_value && $impact->stat_3_label)
                <div class="impact__stat fade-up">
                    <span class="impact__stat-value accent-text">{{ $impact->stat_3_value ?? '' }}</span>
                    <span class="impact__stat-label">{{ $impact->stat_3_label ?? '' }}</span>
                </div>
                @endif
                @if($impact->stat_4_value && $impact->stat_4_label)
                <div class="impact__stat fade-up">
                    <span class="impact__stat-value accent-text">{{ $impact->stat_4_value ?? '' }}</span>
                    <span class="impact__stat-label">{{ $impact->stat_4_label ?? '' }}</span>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 5: Vision for the Future --}}
    @if($vision ?? false)
    <section id="vision" class="vision section--dark section-wrapper" aria-label="Vision for the Future" style="background-image: url('{{ $vision->background_image_url ?? asset('') }}'); background-size: cover; background-position: center; position:relative;">
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
                <div class="vision__description body-text fade-up">
                    {!! $vision->description ?? '' !!}
                </div>
                @if($vision->cta_label && $vision->cta_url)
                <a href="{{ $vision->cta_url }}" class="btn btn--primary vision__cta fade-up">
                    {{ $vision->cta_label ?? '' }}
                </a>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 6: Our Journey (Cinematic Timeline) --}}
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
                            <img src="{{ $timeline->icon_url ?? '' }}" alt="{{ $timeline->title ?? '' }}" class="journey__dot-icon">
                        @else
                            <span class="journey__dot-year">{{ $timeline->year ?? '' }}</span>
                        @endif
                    </div>

                    {{-- Content Card --}}
                    <div class="journey__card">
                        <div class="journey__year-badge">{{ $timeline->year ?? '' }}</div>
                        <h3 class="journey__title">{{ $timeline->title ?? '' }}</h3>
                        <div class="journey__description">{!! $timeline->description ?? '' !!}</div>
                    </div>

                </div>
                @endforeach

            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 7: CEO Message (shared with homepage) --}}
    @include('sections.ceo-message')

    {{-- SECTION 8: Image Collage / Proof of Activity --}}
    @include('sections.our-story-gallery')

</div>

{{-- Final CTA (global closing section, retained as-is) --}}
@include('sections.final-cta')

@endsection
