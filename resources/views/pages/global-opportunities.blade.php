@extends('layouts.app')

@section('title', ($globalOpportunitiesSeo->meta_title ?? 'Global Opportunities | Maverick Business Academy London'))
@section('meta_description', ($globalOpportunitiesSeo->meta_description ?? 'Explore global opportunities at Maverick Business Academy London — study abroad, student exchange, international internships and European partnership programmes. Build experience the world recognises.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $globalOpportunitiesSeo])
@endpush

@if(!empty($globalOpportunitiesSeo->custom_body_scripts))
@push('scripts')
    {!! $globalOpportunitiesSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/global-opportunities.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
<div class="go-page">

    {{-- ═══════════════════════════════════════════
         HERO — Cinematic
    ═══════════════════════════════════════════ --}}
    <section class="cinematic-hero" aria-label="Global Opportunities Hero" data-testid="go-hero">
        <div class="cinematic-hero__bg" aria-hidden="true">
            @if(filled($hero->background_image))
            <div class="cinematic-hero__bg-image" style="background-image: url('{{ $hero->background_image }}')"></div>
            @endif
            <div class="cinematic-hero__gradient"></div>
            <div class="cinematic-hero__noise"></div>
            <div class="cinematic-hero__shapes">
                <svg class="cinematic-hero__shape cinematic-hero__shape--1" viewBox="0 0 200 200" fill="none">
                    <circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.15)" stroke-width="1"/>
                </svg>
                <svg class="cinematic-hero__shape cinematic-hero__shape--2" viewBox="0 0 300 300" fill="none">
                    <circle cx="150" cy="150" r="120" stroke="rgba(220,38,38,0.2)" stroke-width="1"/>
                </svg>
                <svg class="cinematic-hero__shape cinematic-hero__shape--3" viewBox="0 0 100 100" fill="none">
                    <rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.15)" stroke-width="1" transform="rotate(20 50 50)"/>
                </svg>
            </div>
            <div class="cinematic-hero__particles">
                @for($i = 0; $i < 6; $i++)
                    <div class="cinematic-hero__particle"></div>
                @endfor
            </div>
            <div class="cinematic-hero__scanline"></div>
            <div class="cinematic-hero__corners">
                <div class="cinematic-hero__corner cinematic-hero__corner--tl"></div>
                <div class="cinematic-hero__corner cinematic-hero__corner--tr"></div>
                <div class="cinematic-hero__corner cinematic-hero__corner--bl"></div>
                <div class="cinematic-hero__corner cinematic-hero__corner--br"></div>
            </div>
        </div>

        <div class="container cinematic-hero__content">
            @if(filled($hero->tag))
            <span class="cinematic-hero__eyebrow">
                <span class="cinematic-hero__eyebrow-line"></span>
                {{ $hero->tag }}
            </span>
            @endif
            <h1 class="cinematic-hero__title">
                {{ $hero->heading }}<br>
                <em>{{ $hero->heading_italic }}</em>
            </h1>
            @if(filled($hero->description))
            <p class="cinematic-hero__description">{{ $hero->description }}</p>
            @endif
            <div class="cinematic-hero__scroll-hint" aria-hidden="true">
                <span class="cinematic-hero__scroll-text">Scroll to explore</span>
                <span class="cinematic-hero__scroll-arrow" data-lucide="chevron-down"></span>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         OVERVIEW — editorial, human, SEO
    ═══════════════════════════════════════════ --}}
    <section class="go-overview section-wrapper section--light" aria-label="Global Opportunities Overview" data-testid="go-overview">
        <div class="container">
            <div class="go-overview__inner">
                @if(filled($pageSettings->overview_label))
                <span class="section-label go-overview__label"><span>{{ $pageSettings->overview_label }}</span></span>
                @endif
                <h2 class="go-overview__heading section-title">
                    {{ $pageSettings->overview_heading }}
                    <em>{{ $pageSettings->overview_heading_italic }}</em>
                </h2>
                @if(filled($pageSettings->overview_body))
                <div class="go-overview__body">
                    @foreach(explode("\n\n", $pageSettings->overview_body) as $paragraph)
                        @if(trim($paragraph))
                        <p class="body-text">{{ $paragraph }}</p>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         OPPORTUNITIES — premium card grid
    ═══════════════════════════════════════════ --}}
    <section class="go-cards-section section-wrapper" aria-label="Global Opportunities" data-testid="go-cards">
        <div class="container">
            <div class="go-cards__header">
                @if(filled($pageSettings->cards_label))
                <span class="section-label"><span>{{ $pageSettings->cards_label }}</span></span>
                @endif
                <h2 class="go-cards__title section-title">
                    {{ $pageSettings->cards_heading }}
                    <em>{{ $pageSettings->cards_heading_italic }}</em>
                </h2>
            </div>

            @if(count($opportunityItems))
                <div class="go-cards__grid">
                    @foreach($opportunityItems as $i => $item)
                    <a href="{{ $item['url'] ?? '#' }}" class="go-card" data-testid="go-card-{{ $loop->iteration }}">
                        <div class="go-card__media">
                            @if(!empty($item['image']) || !empty($item['image_url']))
                            <img class="go-card__img" src="{{ $item['image'] ?? $item['image_url'] }}" alt="{{ $item['title'] ?? 'Global opportunity' }}" loading="lazy">
                            @else
                            <div class="go-card__img go-card__img--placeholder">
                                <span data-lucide="globe" aria-hidden="true"></span>
                            </div>
                            @endif
                            <span class="go-card__badge">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="go-card__body">
                            <h3 class="go-card__title">{{ $item['title'] ?? '' }}</h3>
                            @if(!empty($item['desc']))
                            <p class="go-card__desc">{{ $item['desc'] }}</p>
                            @endif
                            <span class="go-card__cta">
                                <span>Discover</span>
                                <span class="go-card__cta-icon" data-lucide="arrow-right" aria-hidden="true"></span>
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="go-cards__empty">
                    <p>Global opportunities will be listed here soon. Please check back shortly.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         FINAL CTA
    ═══════════════════════════════════════════ --}}
    @include('sections.final-cta')
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof AnimationUtils !== 'undefined') {
        if (AnimationUtils.cards) AnimationUtils.cards('.go-card', { stagger: 0.1, y: 30 });
        if (AnimationUtils.sectionLabel) AnimationUtils.sectionLabel('.go-overview');
    }
});
</script>
@endpush
