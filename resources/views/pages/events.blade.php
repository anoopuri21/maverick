@extends('layouts.app')

@section('title', ($eventsSeo->meta_title ?? 'Events - Maverick Business Academy'))
@section('meta_description', ($eventsSeo->meta_description ?? 'Explore upcoming events, webinars, workshops and masterclasses from Maverick Business Academy.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $eventsSeo])
@endpush

@if(!empty($eventsSeo->custom_body_scripts))
@push('scripts')
    {!! $eventsSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('assets/css/components/cinematic-hero.css') }}">
    <link rel="stylesheet" href="{{ cached_asset('css/pages/editorial-pages.css') }}">
@endpush

@section('content')
@php
    $events = collect($events ?? []);
    $eventsPage = $eventsPage ?? safe_settings(\App\Settings\EventsPageSettings::class);
@endphp
<div class="ep">

    {{-- Cinematic Hero (same structure as other pages) --}}
    <section class="cinematic-hero" aria-label="Events Hero">
        <div class="cinematic-hero__bg" aria-hidden="true">
            @php $eventsHeroBg = media_url($eventsPage->hero_background_image ?? null, 'https://images.pexels.com/photos/2774556/pexels-photo-2774556.jpeg?auto=compress&cs=tinysrgb&w=1920'); @endphp
            <div class="cinematic-hero__bg-image" @if($eventsHeroBg) style="background-image: url('{{ $eventsHeroBg }}')" @endif></div>
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
            <span class="cinematic-hero__eyebrow"><span class="cinematic-hero__eyebrow-line"></span>{{ $eventsPage->hero_tag ?? 'Upcoming Events' }}</span>
            <h1 class="cinematic-hero__title">{{ $eventsPage->hero_heading ?? 'Discover Our' }} <em>{{ $eventsPage->hero_heading_italic ?? 'Events' }}</em></h1>
            <div class="cinematic-hero__description">{!! html_filled($eventsPage->hero_description ?? null) ? rich_html($eventsPage->hero_description ?? null) : 'Webinars, workshops and masterclasses designed to keep you learning, connected and ahead.' !!}</div>
            <div class="cinematic-hero__scroll-hint" aria-hidden="true"><span class="cinematic-hero__scroll-text">Scroll to explore</span><span class="cinematic-hero__scroll-arrow" data-lucide="chevron-down"></span></div>
        </div>
    </section>

    {{-- Editorial events list --}}
    <section class="section">
        <div class="container">
            <div class="sec-head">
                <div class="sec-label">{{ $eventsPage->section_label ?? "What's On" }}</div>
                <h2 class="sec-title">{{ $eventsPage->section_heading ?? 'Upcoming' }} <em>{{ $eventsPage->section_heading_italic ?? 'Events' }}</em></h2>
                <p class="sec-sub">{{ $eventsPage->section_subheading ?? 'Save the date — opportunities to learn, connect and grow with the Maverick community.' }}</p>
            </div>
            <div class="ep-events">
                @forelse($events as $e)
                <div class="ep-event">
                    <div class="date">
                        <div class="d">{{ $e->event_date ? $e->event_date->format('d') : '00' }}</div>
                        <div class="m">{{ $e->event_date ? $e->event_date->format('M') : '---' }}</div>
                    </div>
                    <div class="info">
                        @if(filled($e->title))
                        <h3>{{ $e->title }}</h3>
                        @endif
                        @if(filled($e->description))
                        <div>{!! rich_html($e->description ?? null) !!}</div>
                        @endif
                    </div>
                    @if(filled($e->event_type))
                    <span class="tag">{{ $e->event_type }}</span>
                    @endif
                </div>
                @empty
                <div class="ep-events-empty">
                    <p>No events currently scheduled. Check back soon!</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
</div>

@include('sections.final-cta')
@endsection
