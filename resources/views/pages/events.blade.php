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
    $upcomingEvents = collect($upcomingEvents ?? []);
    $pastEvents = collect($pastEvents ?? []);
    $eventsPage = $eventsPage ?? safe_settings(\App\Settings\EventsPageSettings::class);
@endphp
<div class="ep">

    {{-- Cinematic Hero --}}
    <section class="cinematic-hero" aria-label="Events Hero">
        <div class="cinematic-hero__bg" aria-hidden="true">
            @php $eventsHeroBg = settings_media_url($eventsPage, 'hero_background_image') ?: 'https://images.pexels.com/photos/2774556/pexels-photo-2774556.jpeg?auto=compress&cs=tinysrgb&w=1920'; @endphp
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
            <span class="cinematic-hero__eyebrow"><span class="cinematic-hero__eyebrow-line"></span>{{ $eventsPage->hero_tag ?? 'Upcoming & Past Events' }}</span>
            <h1 class="cinematic-hero__title">{{ $eventsPage->hero_heading ?? 'Discover Our' }} <em>{{ $eventsPage->hero_heading_italic ?? 'Events' }}</em></h1>
            <div class="cinematic-hero__description">{!! html_filled($eventsPage->hero_description ?? null) ? rich_html($eventsPage->hero_description ?? null) : 'Webinars, workshops and masterclasses designed to keep you learning, connected and ahead.' !!}</div>
            <div class="cinematic-hero__scroll-hint" aria-hidden="true"><span class="cinematic-hero__scroll-text">Scroll to explore</span><span class="cinematic-hero__scroll-arrow" data-lucide="chevron-down"></span></div>
        </div>
    </section>

    {{-- Upcoming Events Section --}}
    <section class="section events-section events-section--upcoming" id="upcoming-section">
        <div class="container">
            <div class="sec-head">
                <div class="sec-label">Upcoming Calendar</div>
                <h2 class="sec-title">Upcoming <em>Events</em></h2>
                <p class="sec-sub">Save the date — join our upcoming masterclasses, webinars, and interactive sessions.</p>
            </div>

            <div class="ep-grid" id="upcoming-events-grid" data-type="upcoming" data-next-offset="{{ $upcomingEvents->count() }}" data-has-more="{{ $upcomingHasMore ? 'true' : 'false' }}">
                @include('pages._event-cards', ['events' => $upcomingEvents])
            </div>

            <div class="ep-empty" id="upcoming-events-empty" style="{{ $upcomingEvents->isEmpty() ? 'display: block;' : 'display: none;' }}">
                <div class="ep-empty__icon"><i data-lucide="calendar-x"></i></div>
                <h3>No Upcoming Events</h3>
                <p>There are currently no scheduled upcoming events. Check back soon for updates!</p>
            </div>

            <div class="ep-loader" id="upcoming-events-loader" style="display: none;">
                <div class="ep-loader__spinner"></div>
                <span>Loading more upcoming events...</span>
            </div>

            <div id="upcoming-events-sentinel"></div>
        </div>
    </section>

    {{-- Past Events Section --}}
    <section class="section events-section events-section--past" id="past-section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); padding-top: 4rem;">
        <div class="container">
            <div class="sec-head">
                <div class="sec-label">Event Archives</div>
                <h2 class="sec-title">Past <em>Events</em></h2>
                <p class="sec-sub">Browse highlights, photo galleries, and video keynotes from our previous events.</p>
            </div>

            <div class="ep-grid" id="past-events-grid" data-type="past" data-next-offset="{{ $pastEvents->count() }}" data-has-more="{{ $pastHasMore ? 'true' : 'false' }}">
                @include('pages._event-cards', ['events' => $pastEvents])
            </div>

            <div class="ep-empty" id="past-events-empty" style="{{ $pastEvents->isEmpty() ? 'display: block;' : 'display: none;' }}">
                <div class="ep-empty__icon"><i data-lucide="history"></i></div>
                <h3>No Past Events Found</h3>
                <p>No past events recorded in the archive yet.</p>
            </div>

            <div class="ep-loader" id="past-events-loader" style="display: none;">
                <div class="ep-loader__spinner"></div>
                <span>Loading more past events...</span>
            </div>

            <div id="past-events-sentinel"></div>
        </div>
    </section>
</div>

@include('sections.final-cta')

@push('scripts')
<script src="{{ cached_asset('assets/js/pages/events-listing.js') }}" defer></script>
@endpush
@endsection
