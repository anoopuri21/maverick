@extends('layouts.app')

@section('title', ($gbpSeo->meta_title ?? "Global Bachelor's Pathway Programme | Study Bachelor's in Europe"))
@section('meta_description', ($gbpSeo->meta_description ?? "Start your Bachelor's journey with Maverick Business Academy London and progress to partner universities in Hungary, Romania, or Moldova. Explore affordable European Bachelor's pathways with credit transfer, visa support, and career guidance."))

@push('head')
    @include('partials.seo-meta', ['seo' => $gbpSeo])
@endpush

@if(!empty($gbpSeo->custom_body_scripts))
@push('scripts')
    {!! $gbpSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/global-bachelors-pathway.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
<div class="page-gbp">
    @include('pages.global-bachelors-pathway.hero')
    @include('pages.global-bachelors-pathway.snapshot')
    @include('pages.global-bachelors-pathway.intro')
    @include('pages.global-bachelors-pathway.overview')
    @include('pages.global-bachelors-pathway.why')
    @include('pages.global-bachelors-pathway.explore')
    @include('pages.global-bachelors-pathway.destinations')
    @include('pages.global-bachelors-pathway.cost')
    @include('pages.global-bachelors-pathway.comparison')
    @include('pages.global-bachelors-pathway.areas')
    @include('pages.global-bachelors-pathway.partners')
    @include('pages.global-bachelors-pathway.admission')
    @include('pages.global-bachelors-pathway.documents')
    @include('pages.global-bachelors-pathway.final-cta')
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/pages/global-bachelors-pathway.js') }}" defer></script>
@endpush
