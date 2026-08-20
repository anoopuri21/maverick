@extends('layouts.app')

@section('title', 'Pathway Programs | Maverick Business Academy London')
@section('meta_description', 'Explore structured global pathway programmes at Maverick Business Academy London. Start closer to home, progress to internationally recognised partner universities, and build your future with flexible, career-focused learning.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/pathway-programs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
<div class="pp-page">

    {{-- ═══════════════════════════════════════════
         HERO — Cinematic (same structure as Bachelor's page)
    ═══════════════════════════════════════════ --}}
    <section class="cinematic-hero" aria-label="Pathway Programs Hero" data-testid="pp-hero">
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
         OVERVIEW — human, SEO-friendly intro
    ═══════════════════════════════════════════ --}}
    <section class="pp-overview section-wrapper section--light" aria-label="Pathway Programs Overview" data-testid="pp-overview">
        <div class="container">
            <div class="pp-overview__inner">
                @if(filled($overview->overview_label))
                <span class="section-label pp-overview__label"><span>{{ $overview->overview_label }}</span></span>
                @endif
                <h2 class="pp-overview__heading section-title">
                    {{ $overview->overview_heading }}
                    <em>{{ $overview->overview_heading_italic }}</em>
                </h2>
                @if(filled($overview->overview_body))
                <div class="pp-overview__body">
                    @foreach(explode("\n\n", $overview->overview_body) as $paragraph)
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
         PATHWAYS — alternating image/content rows
         Row 1: image left / content right
         Row 2: content left / image right ... and so on
    ═══════════════════════════════════════════ --}}
    <section class="pp-pathways section-wrapper section--light" aria-label="Pathway Programmes" data-testid="pp-pathways">
        <div class="container">
            <div class="pp-pathways__header">
                <span class="section-label"><span>Our Pathways</span></span>
                <h2 class="pp-pathways__title section-title">Choose the pathway <em>that fits your goals</em></h2>
            </div>

            @if(count($cards))
                @foreach($cards as $i => $item)
                <article class="pp-row @if($loop->even) pp-row--reverse @endif"
                         data-testid="pp-row-{{ $loop->iteration }}">
                    <div class="pp-row__media">
                        @if(!empty($item['image']) || !empty($item['image_url']))
                        <img class="pp-row__img" src="{{ $item['image'] ?? $item['image_url'] }}" alt="{{ $item['title'] ?? 'Pathway programme' }}" loading="lazy">
                        @else
                        <div class="pp-row__img pp-row__img--placeholder">
                            <span data-lucide="graduation-cap" aria-hidden="true"></span>
                        </div>
                        @endif
                        <span class="pp-row__media-badge">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <div class="pp-row__content">
                        <span class="pp-row__kicker">Pathway {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="pp-row__title">{{ $item['title'] ?? '' }}</h3>
                        @if(!empty($item['desc']))
                        <p class="pp-row__desc">{{ $item['desc'] }}</p>
                        @endif
                        @if(!empty($item['url']))
                        <a href="{{ $item['url'] }}" class="pp-row__cta">
                            <span>Explore Programme</span>
                            <span class="pp-row__cta-icon" data-lucide="arrow-right" aria-hidden="true"></span>
                        </a>
                        @endif
                    </div>
                </article>
                @endforeach
            @else
                <div class="pp-pathways__empty">
                    <p>Pathway programmes will be listed here soon. Please check back shortly.</p>
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
        if (AnimationUtils.fadeUp) AnimationUtils.fadeUp('.pp-row', { stagger: 0.12, y: 30 });
        if (AnimationUtils.sectionLabel) AnimationUtils.sectionLabel('.pp-overview');
    }
});
</script>
@endpush
