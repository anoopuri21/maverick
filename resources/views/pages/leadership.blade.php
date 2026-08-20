@extends('layouts.app')

@section('title', ($leadershipSeo->meta_title ?? 'Leadership & Board - Maverick Business Academy'))
@section('meta_description', ($leadershipSeo->meta_description ?? 'Meet the distinguished leaders and board members guiding Maverick Business Academy.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $leadershipSeo])
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/leadership.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
<div class="page-leadership leadership">


@php
    // Admin-managed via LeadershipHeroSettings / LeadershipLeadersSettings.
    $hero = app(\App\Settings\LeadershipHeroSettings::class);
    $executiveTeam = collect($leaders->items ?? []);
@endphp


{{-- ═══════════════════════════════════════════
     1. HERO SECTION (Cinematic Design)
═══════════════════════════════════════════ --}}
<section class="cinematic-hero cinematic-hero--short" aria-label="Leadership Hero">
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
        @if(filled($hero->heading_line1) || filled($hero->heading_italic))
        <h1 class="cinematic-hero__title">
            {{ $hero->heading_line1 }}@if(filled($hero->heading_italic))<br><em>{{ $hero->heading_italic }}</em>@endif
        </h1>
        @endif
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
     2. EXECUTIVE LEADERSHIP TEAM
═══════════════════════════════════════════ --}}
@if($executiveTeam->count())
<section class="executive-team section-wrapper">
    <div class="container">

        @if(filled($leaders->label) || filled($leaders->heading) || filled($leaders->heading_italic) || filled($leaders->subheading))
        <div class="section-heading-block">
            @if(filled($leaders->label))
            <span class="section-label">{{ $leaders->label }}</span>
            @endif
            @if(filled($leaders->heading) || filled($leaders->heading_italic))
            <h2 class="section-heading">
                {{ $leaders->heading }}@if(filled($leaders->heading_italic))<em>{{ $leaders->heading_italic }}</em>@endif
            </h2>
            @endif
            @if(filled($leaders->subheading))
            <p class="section-subheading">
                {{ $leaders->subheading }}
            </p>
            @endif
        </div>
        @endif

        <div class="executive-team__grid">
            @foreach($executiveTeam as $member)
            @if(filled($member['name'] ?? null) || filled($member['designation'] ?? null) || filled($member['bio'] ?? null) || filled($member['image_url'] ?? null))
            <article class="team-card">
                @if(filled($member['image_url'] ?? null))
                <div class="team-card__image-wrapper">
                    <img src="{{ $member['image_url'] }}"
                         alt="{{ $member['name'] ?? '' }}"
                         class="team-card__image"
                         loading="lazy">
                </div>
                @endif
                <div class="team-card__content">
                    @if(filled($member['name'] ?? null))
                    <h3 class="team-card__name">{{ $member['name'] }}</h3>
                    @endif
                    @if(filled($member['designation'] ?? null))
                    <p class="team-card__designation">{{ strtoupper($member['designation']) }}</p>
                    @endif
                    @if(filled($member['bio'] ?? null))
                    <p class="team-card__bio">{{ $member['bio'] }}</p>
                    @endif
                    @if(filled($member['linkedin_url'] ?? null) && $member['linkedin_url'] !== '#')
                    <a href="{{ $member['linkedin_url'] }}" class="team-card__linkedin" target="_blank" rel="noopener">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20.5 2h-17A1.5 1.5 0 002 3.5v17A1.5 1.5 0 003.5 22h17a1.5 1.5 0 001.5-1.5v-17A1.5 1.5 0 0020.5 2zM8 19H5v-9h3zM6.5 8.25A1.75 1.75 0 118.3 6.5a1.78 1.78 0 01-1.8 1.75zM19 19h-3v-4.74c0-1.42-.6-1.93-1.38-1.93A1.74 1.74 0 0013 14.19a.66.66 0 000 .14V19h-3v-9h2.9v1.3a3.11 3.11 0 012.7-1.4c1.55 0 3.36.86 3.36 3.66z"/>
                        </svg>
                        Connect on LinkedIn
                    </a>
                    @endif
                </div>
            </article>
            @endif
            @endforeach
        </div>

    </div>
</section>
@endif
    @include('sections.alumni-network')


</div>

    @include('sections.final-cta')
@endsection