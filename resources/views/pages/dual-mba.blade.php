@extends('layouts.app')

@if(filled($dualMbaSeo->meta_title ?? null))
@section('title', $dualMbaSeo->meta_title)
@endif
@if(filled($dualMbaSeo->meta_description ?? null))
@section('meta_description', $dualMbaSeo->meta_description)
@endif

@push('head')
    @include('partials.seo-meta', ['seo' => $dualMbaSeo])
@endpush

@if(!empty($dualMbaSeo->custom_body_scripts))
@push('scripts')
    {!! $dualMbaSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
<link rel="stylesheet" href="{{ cached_asset('assets/css/dual-mba.css') }}" />
@endpush

@section('content')

@include('pages.dual-mba.hero')
@include('pages.dual-mba.overview')
@include('pages.dual-mba.twice')
@include('pages.dual-mba.why')
@include('pages.dual-mba.specs')
@include('pages.dual-mba.employers')
@include('pages.dual-mba.testimonials')
@include('pages.dual-mba.process')
@include('pages.dual-mba.faq')
@include('pages.dual-mba.final-cta')

@endsection

@push('scripts')
<script src="{{ cached_asset('assets/js/dual-mba.js') }}" defer></script>
@endpush
