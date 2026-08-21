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
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/editorial-pages.css') }}">
@endpush

@section('content')
<div class="ep">

    {{-- Cinematic Hero (same structure as other pages) --}}
    <section class="cinematic-hero" aria-label="Events Hero">
        <div class="cinematic-hero__bg" aria-hidden="true">
            <div class="cinematic-hero__bg-image" style="background-image: url('https://images.pexels.com/photos/2774556/pexels-photo-2774556.jpeg?auto=compress&cs=tinysrgb&w=1920')"></div>
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
            <span class="cinematic-hero__eyebrow"><span class="cinematic-hero__eyebrow-line"></span>Upcoming Events</span>
            <h1 class="cinematic-hero__title">Discover Our <em>Events</em></h1>
            <p class="cinematic-hero__description">Webinars, workshops and masterclasses designed to keep you learning, connected and ahead.</p>
            <div class="cinematic-hero__scroll-hint" aria-hidden="true"><span class="cinematic-hero__scroll-text">Scroll to explore</span><span class="cinematic-hero__scroll-arrow" data-lucide="chevron-down"></span></div>
        </div>
    </section>

    {{-- Editorial events list --}}
    @php
        $events = collect([
            ['day'=>'28','month'=>'Aug','title'=>'Global Business Masterclass','desc'=>'An intensive masterclass on global business strategy led by industry leaders.','tag'=>'Webinar'],
            ['day'=>'12','month'=>'Sep','title'=>'Alumni Networking Evening','desc'=>'Connect with Maverick alumni and industry professionals over an evening of networking.','tag'=>'Networking'],
            ['day'=>'03','month'=>'Oct','title'=>'Study Abroad Information Session','desc'=>'Learn about our study-abroad semesters and dual-degree pathways.','tag'=>'Info Session'],
            ['day'=>'21','month'=>'Nov','title'=>'Digital Marketing Workshop','desc'=>'A hands-on workshop on modern digital marketing and growth strategy.','tag'=>'Workshop'],
        ]);
    @endphp
    <section class="section">
        <div class="container">
            <div class="sec-head">
                <div class="sec-label">What's On</div>
                <h2 class="sec-title">Upcoming <em>Events</em></h2>
                <p class="sec-sub">Save the date — opportunities to learn, connect and grow with the Maverick community.</p>
            </div>
            <div class="ep-events">
                @foreach($events as $e)
                <div class="ep-event">
                    <div class="date"><div class="d">{{ $e['day'] }}</div><div class="m">{{ $e['month'] }}</div></div>
                    <div class="info">
                        <h3>{{ $e['title'] }}</h3>
                        <p>{{ $e['desc'] }}</p>
                    </div>
                    <span class="tag">{{ $e['tag'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('sections.final-cta')
</div>
@endsection
