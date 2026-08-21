@extends('layouts.app')

@section('title', ($dualMbaSeo->meta_title ?? 'Dual MBA Programme | Maverick Business Academy London'))
@section('meta_description', ($dualMbaSeo->meta_description ?? 'Earn Two MBA Degrees in One Year. General MBA + Specialised MBA through one integrated programme. 100% Online, Weekend Classes. Apply Now.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $dualMbaSeo])
@endpush

@if(!empty($dualMbaSeo->custom_body_scripts))
@push('scripts')
    {!! $dualMbaSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/dual-mba.css') }}" />
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
<script src="{{ asset('assets/js/dual-mba.js') }}" defer></script>
@endpush
