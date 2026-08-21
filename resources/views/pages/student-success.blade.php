@extends('layouts.app')

@section('title', ($studentSuccessSeo->meta_title ?? 'Student Success - Maverick Business Academy'))
@section('meta_description', ($studentSuccessSeo->meta_description ?? 'Real stories from Maverick students and graduates — their journeys, achievements and transformations.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $studentSuccessSeo])
@endpush

@if(!empty($studentSuccessSeo->custom_body_scripts))
@push('scripts')
    {!! $studentSuccessSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/editorial-pages.css') }}">
@endpush

@section('content')
<div class="ep">

    {{-- Cinematic Hero --}}
    <section class="cinematic-hero" aria-label="Student Success Hero">
        <div class="cinematic-hero__bg" aria-hidden="true">
            <div class="cinematic-hero__bg-image" style="background-image: url('https://images.pexels.com/photos/3183150/pexels-photo-3183150.jpeg?auto=compress&cs=tinysrgb&w=1920')"></div>
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
            <span class="cinematic-hero__eyebrow"><span class="cinematic-hero__eyebrow-line"></span>Student Success</span>
            <h1 class="cinematic-hero__title">Real Stories, Real <em>Impact</em></h1>
            <p class="cinematic-hero__description">The journeys and achievements of our students and graduates around the world.</p>
            <div class="cinematic-hero__scroll-hint" aria-hidden="true"><span class="cinematic-hero__scroll-text">Scroll to explore</span><span class="cinematic-hero__scroll-arrow" data-lucide="chevron-down"></span></div>
        </div>
    </section>

    {{-- Editorial success grid --}}
    @php
        $stories = collect([
            ['name'=>'Rahul S.','role'=>'Business Manager · UAE','quote'=>'The programme structure and support helped me balance work with study seamlessly.','stars'=>5],
            ['name'=>'Fatima A.','role'=>'Marketing Executive · UAE','quote'=>'The flexible learning approach let me grow my career while I studied.','stars'=>5],
            ['name'=>'Mohammed K.','role'=>'Entrepreneur · UAE','quote'=>'A solid international pathway with very helpful advisors along the way.','stars'=>4],
            ['name'=>'Priya N.','role'=>'Business Analyst · India','quote'=>'Globally recognised and career-focused — exactly what I needed.','stars'=>5],
            ['name'=>'Daniel O.','role'=>'Operations Manager · UK','quote'=>'Exceptional faculty and a truly international learning environment.','stars'=>5],
            ['name'=>'Sara E.','role'=>'Project Coordinator · UAE','quote'=>'The mentorship and support transformed my professional confidence.','stars'=>5],
        ]);
    @endphp
    <section class="section section--light">
        <div class="container">
            <div class="sec-head center">
                <div class="sec-label">Success Stories</div>
                <h2 class="sec-title">Students Who <em>Made It</em></h2>
                <p class="sec-sub" style="margin-left:auto;margin-right:auto">Hear from our graduates — where they came from, and where their Maverick qualification is taking them.</p>
            </div>
            <div class="ep-success-grid">
                @foreach($stories as $s)
                <div class="ep-success-card">
                    <div class="stars">{{ str_repeat('★', $s['stars']) }}{{ str_repeat('☆', 5 - $s['stars']) }}</div>
                    <p class="quote">“{{ $s['quote'] }}”</p>
                    <div class="who">
                        <span class="ava">{{ collect(preg_split('/\s+/', $s['name']))->map(fn($w)=>mb_substr($w,0,1))->take(2)->implode('') }}</span>
                        <div><div class="nm">{{ $s['name'] }}</div><div class="rl">{{ $s['role'] }}</div></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('sections.final-cta')
</div>
@endsection
