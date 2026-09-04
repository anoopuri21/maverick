@extends('layouts.app')

@section('title', ($pathwayProgramsSeo->meta_title ?? 'Pathway Programs | Maverick Business Academy London'))
@section('meta_description', ($pathwayProgramsSeo->meta_description ?? 'Explore structured global pathway programmes at Maverick Business Academy London. Start closer to home, progress to internationally recognised partner universities, and build your future with flexible, career-focused learning.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $pathwayProgramsSeo])
@endpush

@if(!empty($pathwayProgramsSeo->custom_body_scripts))
@push('scripts')
    {!! $pathwayProgramsSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('css/pages/pathway-programs.css') }}">
    <link rel="stylesheet" href="{{ cached_asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
@php
    $cards = $cards ?? [];
    $pathwayPrograms = $pathwayPrograms ?? safe_settings(\App\Settings\PathwayProgramsSettings::class);
@endphp
<div class="pp-page">

    {{-- ═══════════════════════════════════════════
         HERO — Cinematic (same structure as Bachelor's page)
    ═══════════════════════════════════════════ --}}
    <section class="cinematic-hero" aria-label="Pathway Programs Hero" data-testid="pp-hero">
        <div class="cinematic-hero__bg" aria-hidden="true">
            @if($heroBackgroundUrl = settings_media_url($hero, 'background_image'))
            <div class="cinematic-hero__bg-image" style="background-image: url('{{ $heroBackgroundUrl }}')"></div>
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
                @if(filled($hero->heading ?? null)){{ $hero->heading }}@endif
                @if(filled($hero->heading_italic ?? null))<br><em>{{ $hero->heading_italic }}</em>@endif
            </h1>
            @if(filled($hero->description))
            <div class="cinematic-hero__description">{!! rich_html($hero->description ?? null) !!}</div>
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
                @if(html_filled($overview->overview_body ?? null))
                <div class="pp-overview__body body-text">
                    {!! rich_html($overview->overview_body ?? null) !!}
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
                <span class="section-label"><span>{{ $pathwayPrograms->pathways_label ?? 'Our Pathways' }}</span></span>
                <h2 class="pp-pathways__title section-title">
                    {{ $pathwayPrograms->pathways_heading ?? 'Choose the pathway' }}
                    @if($pathwayPrograms->pathways_heading_italic)
                        <em>{{ $pathwayPrograms->pathways_heading_italic }}</em>
                    @else
                        <em>that fits your goals</em>
                    @endif
                </h2>
            </div>

            @if(count($cards ?? []))
                @foreach($cards as $i => $item)
                @if(! is_array($item)) @continue @endif
                @php $isComingSoon = (bool) ($item['coming_soon'] ?? false); @endphp
                <article class="pp-row @if($loop->even) pp-row--reverse @endif"
                         data-testid="pp-row-{{ $loop->iteration }}">
                    <div class="pp-row__media">
                        @if($url = settings_media_url($item, 'image') ?: media_url($item['image_url'] ?? null))
                        <img class="pp-row__img" src="{{ $url }}" alt="{{ $item['title'] ?? 'Pathway programme' }}" loading="lazy">
                        @else
                        <div class="pp-row__img pp-row__img--placeholder">
                            <span data-lucide="graduation-cap" aria-hidden="true"></span>
                        </div>
                        @endif
                        <span class="pp-row__media-badge">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <div class="pp-row__content">
                        <span class="pp-row__kicker">Pathway {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        @if($isComingSoon)
                        <span class="pp-row__badge">Coming Soon</span>
                        @endif
                        <h3 class="pp-row__title">{{ $item['title'] ?? '' }}</h3>
                        @if(!empty($item['desc']))
                        <div class="pp-row__desc">{!! rich_html($item['desc'] ?? null) !!}</div>
                        @endif
                        @if($isComingSoon)
                        <span class="pp-row__cta pp-row__cta--disabled" aria-disabled="true" role="link" tabindex="-1">
                            <span>Coming Soon</span>
                        </span>
                        @elseif($href = slug_href($item['slug'] ?? null))
                        <a href="{{ $href }}" class="pp-row__cta">
                            <span>{{ $pathwayPrograms->pathways_cta_label ?? 'Explore Programme' }}</span>
                            <span class="pp-row__cta-icon" data-lucide="arrow-right" aria-hidden="true"></span>
                        </a>
                        @endif
                    </div>
                </article>
                @endforeach
            @else
                <div class="pp-pathways__empty">
                    <p>{{ $pathwayPrograms->pathways_empty_message ?? 'Pathway programmes will be listed here soon. Please check back shortly.' }}</p>
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
