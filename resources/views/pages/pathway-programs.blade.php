@extends('layouts.app')

@section('title', 'Pathway Programs | Maverick Business Academy London')
@section('meta_description', 'Explore Maverick Business Academy London\'s global pathway programmes — flexible, affordable routes that let you start your studies here and progress towards an internationally recognised qualification.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/pathway-programs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
<div class="page-pathways">

    {{-- ═══════════════════════════════════════════
         HERO — cinematic (same as global-bachelors-pathway)
    ═══════════════════════════════════════════ --}}
    <section class="cinematic-hero" aria-label="Pathway Programs Hero" data-testid="pathways-hero">
        <div class="cinematic-hero__bg" aria-hidden="true">
            <div class="cinematic-hero__bg-image" style="background-image: url('{{ $hero->background_image }}')"></div>
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
            <span class="cinematic-hero__eyebrow">
                <span class="cinematic-hero__eyebrow-line"></span>
                {{ $hero->tag }}
            </span>
            <h1 class="cinematic-hero__title">
                {{ $hero->heading }}<br>
                <em>{{ $hero->heading_italic }}</em>
            </h1>
            <p class="cinematic-hero__description">{{ $hero->description }}</p>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         OVERVIEW — SEO-friendly, human language
    ═══════════════════════════════════════════ --}}
    <section class="pw-overview section-wrapper section--light" aria-label="Pathway Programs Overview" data-testid="pathways-overview">
        <div class="container">
            <div class="pw-overview__label">
                <span class="pw-overview__label-line"></span>
                {{ $overview->overview_label }}
            </div>
            <h2 class="pw-overview__heading">
                {{ $overview->overview_heading }}
                <em>{{ $overview->overview_heading_italic }}</em>
            </h2>
            <div class="pw-overview__body">
                @foreach(explode("\n", trim($overview->overview_body ?? '')) as $paragraph)
                    @if(trim($paragraph) !== '')
                        <p>{{ $paragraph }}</p>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         CARDS — from admin Global Opportunities pathways
    ═══════════════════════════════════════════ --}}
    <section class="pw-cards" aria-label="Pathway Programs" data-testid="pathways-cards">
        <div class="container">
            <div class="pw-cards__header">
                <div class="section-label"><span>Our Programmes</span></div>
                <h2 class="pw-cards__title section-title">Choose Your <em>Pathway</em></h2>
            </div>

            @if(count($cards))
                <div class="pw-cards__grid">
                    @foreach($cards as $i => $item)
                    <a href="{{ $item['url'] ?? '#' }}" class="pw-card" data-testid="pathway-card-{{ $loop->iteration }}">
                        @if(!empty($item['image']))
                        <div class="pw-card__media">
                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] ?? '' }}" loading="lazy">
                        </div>
                        @else
                        <div class="pw-card__media pw-card__media--placeholder">
                            <span data-lucide="graduation-cap" aria-hidden="true"></span>
                        </div>
                        @endif
                        <div class="pw-card__body">
                            <span class="pw-card__index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3 class="pw-card__title">{{ $item['title'] ?? '' }}</h3>
                            @if(!empty($item['desc']))
                            <p class="pw-card__desc">{{ $item['desc'] }}</p>
                            @endif
                            <span class="pw-card__link">Explore <span aria-hidden="true">→</span></span>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <p class="pw-cards__empty">Pathway programmes will be listed here soon. Please check back shortly.</p>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         FINAL CTA
    ═══════════════════════════════════════════ --}}
    @include('sections.final-cta')

</div>
@endsection
