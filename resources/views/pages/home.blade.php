@extends('layouts.app')

@section('title', ($homeSeo->meta_title ?? 'Maverick Business Academy | Transforming Learners into Global Leaders'))
@section('meta_description', ($homeSeo->meta_description ?? 'Globally recognized qualifications, international pathways, and career-focused learning.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $homeSeo])
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('assets/css/global-access-points.css') }}" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/d3@7" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/topojson-client@3" defer></script>
    <script src="{{ cached_asset('assets/js/global-access-points.js') }}" defer></script>
@endpush

@if(!empty($homeSeo->custom_body_scripts))
@push('scripts')
    {!! $homeSeo->custom_body_scripts !!}
@endpush
@endif

@section('content')

    @php
        $homeSeo = $homeSeo ?? safe_settings(\App\Settings\HomepageSeoSettings::class);
        $events = collect($events ?? []);
        $testimonialsJson = collect($testimonialsJson ?? []);
        $homepageFaqs = collect($homepageFaqs ?? []);
    @endphp

    @include('sections.hero')
    @include('sections.numbers')
    @include('sections.what-is-maverick')
    @include('sections.who-we-are')
    @include('sections.ceo-message')
    @include('sections.what-we-do')
    @include('sections.how-we-do-it')
    @include('sections.alumni-network')
    @include('sections.featured-programs')
    @include('sections.accreditations')
    @include('sections.global-access-points')
    @include('sections.why-maverick')
    @include('sections.global-opportunities')
    @include('sections.university-partners')
    @include('sections.faculty-insights')
    @include('sections.upcoming-events')
    @include('sections.video-testimonials')
    @include('sections.faq')
    @include('sections.final-cta')

@endsection