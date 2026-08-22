@extends('layouts.app')

@section('title', ($studentSuccessSeo->meta_title ?? 'Student Success - Maverick Business Academy'))
@section('meta_description', ($studentSuccessSeo->meta_description ?? 'Real stories from Maverick students and graduates — their journeys, achievements and transformations.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $studentSuccessSeo])
@endpush

@if(!empty($studentSuccessSeo->custom_body_scripts))
@push('scripts')
    {!! $studentSuccessSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('assets/css/components/cinematic-hero.css') }}">
    <link rel="stylesheet" href="{{ cached_asset('css/pages/editorial-pages.css') }}">
@endpush

@section('content')
<div class="ep">

    {{-- Cinematic Hero --}}
    <section class="cinematic-hero" aria-label="Student Success Hero">
        <div class="cinematic-hero__bg" aria-hidden="true">
            @php $ssHeroBg = media_url($studentSuccessPage->hero_background_image ?? null, 'https://images.pexels.com/photos/3183150/pexels-photo-3183150.jpeg?auto=compress&cs=tinysrgb&w=1920'); @endphp
            <div class="cinematic-hero__bg-image" @if($ssHeroBg) style="background-image: url('{{ $ssHeroBg }}')" @endif></div>
            <div class="cinematic-hero__gradient"></div>
            <div class="cinematic-hero__noise"></div>
            <div class="cinematic-hero__shapes">
                <svg class="cinematic-hero__shape cinematic-hero__shape--1" viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.15)" stroke-width="1"/></svg>
                <svg class="cinematic-hero__shape cinematic-hero__shape--2" viewBox="0 0 300 300" fill="none"><circle cx="150" cy="150" r="120" stroke="rgba(220,38,38,0.2)" stroke-width="1"/></svg>
                <svg class="cinematic-hero__shape cinematic-hero__shape--3" viewBox="0 0 100 100" fill="none"><rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.15)" stroke-width="1" transform="rotate(20 50 50)"/></svg>
            </div>
            <div class="cinematic-hero__particles">@for($i=0;$i<6;$i++)<div class="cinematic-hero__particle"></div>@endfor</div>
            <div class="cinematic-hero__scanline"></div>
            <div class="cinematic-hero__corners">
                <div class="cinematic-hero__corner cinematic-hero__corner--tl"></div>
                <div class="cinematic-hero__corner cinematic-hero__corner--tr"></div>
                <div class="cinematic-hero__corner cinematic-hero__corner--bl"></div>
                <div class="cinematic-hero__corner cinematic-hero__corner--br"></div>
            </div>
        </div>
        <div class="container cinematic-hero__content">
            @if(filled($studentSuccessPage->hero_tag ?? null))
            <span class="cinematic-hero__eyebrow"><span class="cinematic-hero__eyebrow-line"></span>{{ $studentSuccessPage->hero_tag }}</span>
            @endif
            @if(filled($studentSuccessPage->hero_heading ?? null) || filled($studentSuccessPage->hero_heading_italic ?? null))
            <h1 class="cinematic-hero__title">{{ $studentSuccessPage->hero_heading }} @if(filled($studentSuccessPage->hero_heading_italic ?? null))<em>{{ $studentSuccessPage->hero_heading_italic }}</em>@endif</h1>
            @endif
            @if(filled($studentSuccessPage->hero_description ?? null))
            <p class="cinematic-hero__description">{!! rich_html($studentSuccessPage->hero_description ?? null) !!}</p>
            @endif
            <div class="cinematic-hero__scroll-hint" aria-hidden="true"><span class="cinematic-hero__scroll-text">Scroll to explore</span><span class="cinematic-hero__scroll-arrow" data-lucide="chevron-down"></span></div>
        </div>
    </section>

    @php $stories = collect($stories ?? []); $videoStories = collect($videoStories ?? []); @endphp

    <section class="section section--light">
        <div class="container">
            <div class="sec-head center">
                <div class="sec-label">{{ $studentSuccessPage->section_label ?? 'Success Stories' }}</div>
                <h2 class="sec-title">{{ $studentSuccessPage->section_heading ?? 'Students Who' }} <em>{{ $studentSuccessPage->section_heading_italic ?? 'Made It' }}</em></h2>
                <p class="sec-sub" style="margin-left:auto;margin-right:auto">{{ $studentSuccessPage->section_subheading ?? 'Hear from our graduates — where they came from, and where their Maverick qualification is taking them.' }}</p>
            </div>
            @if($stories->isNotEmpty())
            <div class="ep-success-grid" data-success-grid="stories">
                @include('pages.student-success._story-cards', ['stories' => $stories])
            </div>
            @if(($storyTotal ?? 0) > $stories->count())
            <div class="ep-success-more" data-load-more-wrap="stories">
                <button type="button" class="ep-success-more__btn" data-load-more="stories" data-url="{{ route('student-success.stories') }}" data-offset="{{ $stories->count() }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Load More
                </button>
            </div>
            @endif
            @endif
        </div>
    </section>

    @if(($videoTotal ?? 0) > 0)
    <section class="section section--light ep-success-videos" aria-label="Video success stories">
        <div class="container">
            <div class="sec-head center">
                <div class="sec-label">{{ $studentSuccessPage->video_section_label ?? 'Video Stories' }}</div>
                <h2 class="sec-title">{{ $studentSuccessPage->video_section_heading ?? 'Video Success' }} <em>{{ $studentSuccessPage->video_section_heading_italic ?? 'Stories' }}</em></h2>
                @if(filled($studentSuccessPage->video_section_subheading ?? null))
                <p class="sec-sub" style="margin-left:auto;margin-right:auto">{{ $studentSuccessPage->video_section_subheading }}</p>
                @endif
            </div>
            <div class="ep-success-grid" data-success-grid="videos">
                @include('pages.student-success._video-cards', ['videos' => $videoStories])
            </div>
            @if($videoTotal > $videoStories->count())
            <div class="ep-success-more" data-load-more-wrap="videos">
                <button type="button" class="ep-success-more__btn" data-load-more="videos" data-url="{{ route('student-success.videos') }}" data-offset="{{ $videoStories->count() }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Load More
                </button>
            </div>
            @endif
        </div>
    </section>

    <div class="ep-vmodal" id="successVideoModal" role="dialog" aria-modal="true" aria-label="Video player" hidden>
        <button type="button" class="ep-vmodal__close" data-video-close aria-label="Close video">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
        </button>
        <div class="ep-vmodal__frame" data-video-frame></div>
    </div>
    @endif

    @include('sections.final-cta')
</div>
@endsection

@push('scripts')
    <script src="{{ cached_asset('assets/js/pages/student-success.js') }}" defer></script>
@endpush
