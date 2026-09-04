@extends('layouts.app')

@section('title', ($programsListingSeo->meta_title ?? 'Programmes | Maverick Business Academy'))
@section('meta_description', ($programsListingSeo->meta_description ?? 'Explore Maverick Business Academy programmes — Bachelors, Masters, MBA, Diplomas and professional courses.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $programsListingSeo])
@endpush

@if(!empty($programsListingSeo->custom_body_scripts))
@push('scripts')
    {!! $programsListingSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('assets/css/components/cinematic-hero.css') }}">
    <link rel="stylesheet" href="{{ cached_asset('css/pages/program-listing.css') }}">
@endpush

@section('content')
@php
    $programs = collect($programs ?? []);
    $categories = collect($categories ?? []);
@endphp
<div class="page-pl">

    <section class="cinematic-hero cinematic-hero--short pl-hero" aria-label="Programmes" data-testid="pl-hero">
        <div class="cinematic-hero__bg" aria-hidden="true">
            <div class="cinematic-hero__bg-image" @style(['background-image: url(' . ($programsListingPage->hero_background_image ?? cached_asset('assets/images/homepage/mba.jpg')) . ')'])></div>
            <div class="cinematic-hero__gradient"></div>
            <div class="cinematic-hero__noise"></div>
            <div class="cinematic-hero__scanline"></div>
            <div class="cinematic-hero__corners">
                <div class="cinematic-hero__corner cinematic-hero__corner--tl"></div>
                <div class="cinematic-hero__corner cinematic-hero__corner--tr"></div>
                <div class="cinematic-hero__corner cinematic-hero__corner--bl"></div>
                <div class="cinematic-hero__corner cinematic-hero__corner--br"></div>
            </div>
        </div>

        <div class="container cinematic-hero__content">
            <span class="cinematic-hero__eyebrow">
                <span class="cinematic-hero__eyebrow-line"></span>
                {{ $programsListingPage->hero_tag ?? 'MAVERICK PROGRAMMES' }}
            </span>
            <h1 class="cinematic-hero__title">{{ $programsListingPage->hero_heading ?? 'Explore Your' }} <em>{{ $programsListingPage->hero_heading_italic ?? 'Programme' }}</em></h1>
            <div class="cinematic-hero__description">{!! html_filled($programsListingPage->hero_description ?? null) ? rich_html($programsListingPage->hero_description ?? null) : 'Globally recognised qualifications designed to move your career forward.' !!}</div>
            <div class="pl-hero__meta">
                <span class="pl-hero__meta-item">{{ $programs->count() }} programmes</span>
                <span class="pl-hero__meta-rule"></span>
                <span class="pl-hero__meta-item">{{ $categories->count() }} categories</span>
            </div>
            <div class="pl-hero__ctas">
                <a href="#programmes" class="btn btn--outline">{{ $programsListingPage->cta_label ?? 'Browse Programmes' }}</a>
            </div>
        </div>
    </section>

    <section id="programmes" class="pl-list" aria-label="Programme list">
        @if($categories->count())
            <div class="pl-filter" role="group" aria-label="Filter programmes" data-pl-filter>
                <button type="button" class="pl-filter__btn is-active" data-filter="all" aria-pressed="true">All ({{ $programs->count() }})</button>
                @foreach($categories as $cat)
                    <button type="button" class="pl-filter__btn" data-filter="{{ $cat->slug }}" aria-pressed="false">{{ $cat->name }} ({{ $cat->programs_count }})</button>
                @endforeach
            </div>
        @endif

        <div class="pl-grid" data-pl-grid>
            @forelse($programs as $program)
                @php $catSlug = $program->programCategory?->slug ?? 'all'; @endphp
                <a href="{{ filled($program->slug) ? route('programs.show', $program->slug) : '#' }}" class="pl-card" data-category="{{ $catSlug }}" @if(! filled($program->slug)) role="group" @endif>
                    @if($url = media_url($program->image_url ?? null))
                        <div class="pl-card__media">
                            <img src="{{ $url }}" alt="{{ $program->title }}" loading="lazy" width="800" height="540">
                        </div>
                    @else
                        <div class="pl-card__media pl-card__media--empty" aria-hidden="true"></div>
                    @endif
                    <div class="pl-card__body">
                        @if($program->programCategory)<span class="pl-card__cat">{{ $program->programCategory->name }}</span>@endif
                        @if($program->universityPartner)<span class="pl-card__uni">{{ $program->universityPartner->name }}</span>@endif
                        <h2 class="pl-card__title">{{ $program->title }}</h2>
                        @if($program->duration || $program->level)
                            <span class="pl-card__meta">
                                @if($program->duration){{ $program->duration }}@endif
                                @if($program->duration && $program->level)<span class="pl-card__meta-rule">·</span>@endif
                                @if($program->level){{ $program->level }}@endif
                            </span>
                        @endif
                        @if($program->short_description)<p class="pl-card__desc">{{ $program->short_description }}</p>@endif
                        <span class="pl-card__link">{{ $programsListingPage->card_cta_label ?? 'View Programme' }} <span aria-hidden="true">→</span></span>
                    </div>
                </a>
            @empty
                <p class="pl-empty">{{ $programsListingPage->empty_message ?? 'Programmes coming soon.' }}</p>
            @endforelse
        </div>
    </section>

</div>
@endsection
