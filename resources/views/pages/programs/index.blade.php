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
    <link rel="preload" href="{{ cached_asset('assets/css/components/cinematic-hero.css') }}" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ cached_asset('assets/css/components/cinematic-hero.css') }}"></noscript>
    <link rel="preload" href="{{ cached_asset('css/pages/program-listing.css') }}" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ cached_asset('css/pages/program-listing.css') }}"></noscript>
@endpush

@section('content')
@php
    $programs = $programs ?? collect();
    $categories = collect($categories ?? []);
    $requestedCategory = request()->query('category');
    $activeCategory = ($requestedCategory && $categories->contains(fn ($cat) => ($cat->slug ?? null) === $requestedCategory))
        ? $requestedCategory
        : 'all';
@endphp
<div class="page-pl">

    <section class="cinematic-hero pl-hero" aria-label="Programmes" data-testid="pl-hero">
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

            <div class="pl-hero__bottom">
                <div class="pl-hero__meta">
                    <span class="pl-hero__meta-item">{{ $totalProgramCount }} programmes</span>
                    <span class="pl-hero__meta-rule"></span>
                    <span class="pl-hero__meta-item">{{ $categories->count() }} categories</span>
                </div>
                <div class="pl-hero__ctas">
                    <a href="#programmes" class="btn btn--primary">{{ $programsListingPage->cta_label ?? 'Browse Programmes' }}</a>
                </div>
            </div>
        </div>
    </section>

    <section id="programmes" class="pl-list" aria-label="Programme list">
        @if($categories->count())
            <div class="pl-filter" role="group" aria-label="Filter programmes" data-pl-filter>
                <button type="button" class="pl-filter__btn {{ $activeCategory === 'all' ? 'is-active' : '' }}" data-filter="all" aria-pressed="{{ $activeCategory === 'all' ? 'true' : 'false' }}">All ({{ $totalProgramCount }})</button>
                @foreach($categories as $cat)
                    <button type="button" class="pl-filter__btn {{ $activeCategory === $cat->slug ? 'is-active' : '' }}" data-filter="{{ $cat->slug }}" aria-pressed="{{ $activeCategory === $cat->slug ? 'true' : 'false' }}">{{ $cat->name }} ({{ $cat->programs_count }})</button>
                @endforeach
            </div>
        @endif

        {{-- Program cards partial (server-rendered for page 1) --}}
        @include('pages.programs.partials._cards', [
            'programs' => $programs,
            'programsListingPage' => $programsListingPage,
            'activeCategory' => $activeCategory,
            'nextPage' => $paginationNextPage ?? null,
            'lastPage' => $paginationLastPage ?? null,
        ])

        {{-- Mobile infinite scroll sentinel + loading --}}
        <div class="pl-scroll-sentinel" data-pl-sentinel @isset($paginationNextPage) data-next-page="{{ $paginationNextPage }}" @endif @isset($paginationLastPage) data-last-page="{{ $paginationLastPage }}" @endif></div>

        <div class="pl-load-more" data-pl-load-more>
            <div class="pl-spinner"></div>
            <span class="pl-load-text">Loading more programmes...</span>
        </div>

        {{-- Pagination: desktop only --}}
        @if($programs->hasPages())
            @include('pagination.programs', ['paginator' => $programs])
        @endif
    </section>

</div>
@endsection
