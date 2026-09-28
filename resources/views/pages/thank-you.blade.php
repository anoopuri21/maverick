@extends('layouts.app')

@section('title', 'Thank You | Maverick Business Academy London')
@section('meta_description', 'Thank you for reaching out to Maverick Business Academy London. Our admissions team will get back to you within 24 hours.')

@push('head')
    <meta name="robots" content="noindex, nofollow" />
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('assets/css/pages/thank-you.css') }}" />
@endpush

@section('content')
<section class="thank-you" aria-labelledby="thank-you-heading">
    <div class="container thank-you__inner">

        <div class="thank-you__card">
            {{-- Animated success check --}}
            <div class="thank-you__icon" aria-hidden="true">
                <svg class="thank-you__icon-svg" viewBox="0 0 72 72" fill="none">
                    <circle class="thank-you__icon-circle" cx="36" cy="36" r="33" stroke="currentColor" stroke-width="3" />
                    <path class="thank-you__icon-check" d="M23 37.5 32 46.5 49 28" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>

            <p class="thank-you__kicker">Submission Received</p>
            <h1 id="thank-you-heading" class="thank-you__title">Thank You!</h1>
            <p class="thank-you__note">
                Your details have been submitted successfully. Our admissions team
                will get back to you within 24 hours at the email address or phone
                number you provided.
            </p>

            <div class="thank-you__ctas">
                <a href="{{ route('home') }}" class="btn btn--primary thank-you__cta thank-you__cta--home">
                    <span>Back to Homepage</span>
                </a>
                <a href="{{ $returnUrl ?? route('home') }}" class="thank-you__cta thank-you__cta--back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 12H5" />
                        <path d="M12 19l-7-7 7-7" />
                    </svg>
                    <span>Back to Previous Page</span>
                </a>
            </div>
        </div>

    </div>
</section>
@endsection
