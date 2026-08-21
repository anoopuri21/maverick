@extends('layouts.app')

@section('title', ($mpSeo->meta_title ?? "International Master's Pathway Program | Maverick Business Academy London"))
@section('meta_description', ($mpSeo->meta_description ?? "Start your international Master's journey with Maverick Business Academy London. Complete a Level 7 Diploma and progress to partner universities in Hungary, Moldova or Romania for final-stage Master's completion."))

@push('head')
    @include('partials.seo-meta', ['seo' => $mpSeo])
@endpush

@if(!empty($mpSeo->custom_body_scripts))
@push('scripts')
    {!! $mpSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/masters-pathway.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
<div class="page-mp">
    @include('pages.masters-pathway.hero')
    @include('pages.masters-pathway.overview')
    @include('pages.masters-pathway.how')
    @include('pages.masters-pathway.destinations')
    @include('pages.masters-pathway.why')
    @include('pages.masters-pathway.audience')
    @include('pages.masters-pathway.requirements')
    @include('pages.masters-pathway.process')
    @include('pages.masters-pathway.notice')
    @include('sections.faculty-insights')
    @include('pages.masters-pathway.final-cta')
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/pages/masters-pathway.js') }}" defer></script>
@endpush
