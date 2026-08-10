@extends('layouts.app')

@section('title', 'Educational Tours for Students | Maverick Edutainment UAE')
@section('meta_description', 'Explore UAE and international educational tours for schools, universities and student groups. Custom study trips combining learning, culture and entertainment.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/edutainment.css') }}" />
@endpush

@section('content')

{{-- ===== HERO ===== --}}
@include('pages.edutainment.hero')

{{-- ===== S1: INTRO — EXPLORE THE WORLD ===== --}}
@include('pages.edutainment.intro')

{{-- ===== S2: WHAT IS EDUTAINMENT ===== --}}
@include('pages.edutainment.what-is')

{{-- ===== S3: LEARNING BEYOND THE CLASSROOM ===== --}}
@include('pages.edutainment.learning-beyond')

{{-- ===== S4: WHO ARE OUR TOURS DESIGNED FOR ===== --}}
@include('pages.edutainment.who-for')

{{-- ===== S5: OUR EDUTAINMENT PROGRAMMES ===== --}}
@include('pages.edutainment.programmes')

{{-- ===== S6: EDUCATIONAL TOUR THEMES ===== --}}
@include('pages.edutainment.themes')

{{-- ===== S7: WHAT STUDENTS CAN EXPERIENCE ===== --}}
@include('pages.edutainment.experiences')

{{-- ===== S8: WHY CHOOSE MAVERICK EDUTAINMENT ===== --}}
@include('pages.edutainment.why-choose')

{{-- ===== S9: WHAT CAN BE INCLUDED ===== --}}
@include('pages.edutainment.packages')

{{-- ===== S10: EDUCATIONAL TOURS FOR SCHOOLS ===== --}}
@include('pages.edutainment.institutions')

{{-- ===== S11: FREQUENTLY ASKED QUESTIONS ===== --}}
@include('pages.edutainment.faq')

{{-- ===== S12: FINAL CTA ===== --}}
@include('pages.edutainment.final-cta')

@endsection

@push('scripts')
<script src="{{ asset('assets/js/pages/edutainment.js') }}" defer></script>
@endpush
