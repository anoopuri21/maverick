@extends('layouts.app')

@section('title', ($edutainmentSeo->meta_title ?? 'Educational Tours for Students | Maverick Edutainment UAE'))
@section('meta_description', ($edutainmentSeo->meta_description ?? 'Explore UAE and international educational tours for schools, universities and student groups. Custom study trips combining learning, culture and entertainment.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $edutainmentSeo])
@endpush

@if(!empty($edutainmentSeo->custom_body_scripts))
@push('scripts')
    {!! $edutainmentSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/edutainment.css') }}" />
@endpush

@section('content')

@include('pages.edutainment.hero')
@include('pages.edutainment.intro')
@include('pages.edutainment.what-is')
@include('pages.edutainment.learning-beyond')
@include('pages.edutainment.who-for')
@include('pages.edutainment.programmes')
@include('pages.edutainment.themes')
@include('pages.edutainment.experiences')
@include('pages.edutainment.why-choose')
@include('pages.edutainment.packages')
@include('pages.edutainment.institutions')
@include('pages.edutainment.faq')
@include('pages.edutainment.final-cta')

@endsection

@push('scripts')
<script src="{{ asset('assets/js/pages/edutainment.js') }}" defer></script>
@endpush
