@extends('layouts.app')

@section('title', ($termsSeo->meta_title ?? 'Terms of Use | Maverick Business Academy London'))
@section('meta_description', ($termsSeo->meta_description ?? 'Terms of Use for the Maverick Business Academy London website and related services.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $termsSeo])
@endpush

@if(!empty($termsSeo->custom_body_scripts))
@push('scripts')
    {!! $termsSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('assets/css/components/cinematic-hero.css') }}">
    <link rel="stylesheet" href="{{ cached_asset('assets/css/pages/legal-page.css') }}">
@endpush

@section('content')
<div class="legal-page">

    <section class="cinematic-hero cinematic-hero--short" aria-label="Terms of Use">
        <div class="cinematic-hero__bg" aria-hidden="true">
            @if(filled($termsPage->background_image ?? null))
            <div class="cinematic-hero__bg-image" style="background-image: url('{{ media_url($termsPage->background_image) }}')"></div>
            @endif
            <div class="cinematic-hero__gradient"></div>
            <div class="cinematic-hero__noise"></div>
            <div class="cinematic-hero__scanline"></div>
            <div class="cinematic-hero__corners">
                <div class="cinematic-hero__corner cinematic-hero__corner--tl"></div>
                <div class="cinematic-hero__corner cinematic-hero__corner--tr"></div>
                <div class="cinematic-hero__corner cinematic-hero__corner--bl"></div>
                <div class="cinematic-hero__corner cinematic-hero__corner--br"></div>
            </div>
        </div>

        <div class="container cinematic-hero__content">
            @if(filled($termsPage->tag ?? null))
            <span class="cinematic-hero__eyebrow">
                <span class="cinematic-hero__eyebrow-line"></span>
                {{ $termsPage->tag }}
            </span>
            @endif

            @if(filled($termsPage->heading_line1 ?? null) || filled($termsPage->heading_italic ?? null))
            <h1 class="cinematic-hero__title">
                @if(filled($termsPage->heading_line1 ?? null)){{ $termsPage->heading_line1 }}@endif
                @if(filled($termsPage->heading_italic ?? null)) <em>{{ $termsPage->heading_italic }}</em>@endif
            </h1>
            @endif

            @if(html_filled($termsPage->description ?? null))
            <div class="cinematic-hero__description">
                {!! rich_html($termsPage->description) !!}
            </div>
            @endif
        </div>
    </section>

    @if(filled($termsPage->center_image ?? null))
    <section class="legal-center" aria-label="Terms of Use visual">
        <div class="container">
            <figure class="legal-center__figure">
                <img
                    src="{{ media_url($termsPage->center_image) }}"
                    alt="{{ $termsPage->center_image_alt ?: 'Terms of Use' }}"
                    class="legal-center__img"
                    loading="lazy"
                    decoding="async"
                >
            </figure>
        </div>
    </section>
    @endif

    @if(html_filled($termsPage->body ?? null))
    <section class="legal-body" aria-label="Terms of Use content">
        <div class="container">
            <div class="legal-prose">
                {!! rich_html($termsPage->body) !!}
            </div>
        </div>
    </section>
    @endif

</div>
@endsection
