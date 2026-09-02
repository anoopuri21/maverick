@extends('layouts.app')

@section('title', ($privacySeo->meta_title ?? 'Privacy Policy | Maverick Business Academy London'))
@section('meta_description', ($privacySeo->meta_description ?? 'Learn how Maverick Business Academy London collects, uses, and protects your personal information.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $privacySeo])
@endpush

@if(!empty($privacySeo->custom_body_scripts))
@push('scripts')
    {!! $privacySeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('assets/css/components/cinematic-hero.css') }}">
    <link rel="stylesheet" href="{{ cached_asset('assets/css/pages/legal-page.css') }}">
@endpush

@section('content')
<div class="legal-page">

    <section class="cinematic-hero cinematic-hero--short" aria-label="Privacy Policy">
        <div class="cinematic-hero__bg" aria-hidden="true">
            @if(filled(settings_media_url($privacyPage, 'background_image')))
            <div class="cinematic-hero__bg-image" style="background-image: url('{{ settings_media_url($privacyPage, 'background_image') }}')"></div>
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
            @if(filled($privacyPage->tag ?? null))
            <span class="cinematic-hero__eyebrow">
                <span class="cinematic-hero__eyebrow-line"></span>
                {{ $privacyPage->tag }}
            </span>
            @endif

            @if(filled($privacyPage->heading_line1 ?? null) || filled($privacyPage->heading_italic ?? null))
            <h1 class="cinematic-hero__title">
                @if(filled($privacyPage->heading_line1 ?? null)){{ $privacyPage->heading_line1 }}@endif
                @if(filled($privacyPage->heading_italic ?? null)) <em>{{ $privacyPage->heading_italic }}</em>@endif
            </h1>
            @endif

            @if(html_filled($privacyPage->description ?? null))
            <div class="cinematic-hero__description">
                {!! rich_html($privacyPage->description) !!}
            </div>
            @endif
        </div>
    </section>

    @if(filled(settings_media_url($privacyPage, 'center_image')))
    <section class="legal-center" aria-label="Privacy Policy visual">
        <div class="container">
            <figure class="legal-center__figure">
                <img
                    src="{{ settings_media_url($privacyPage, 'center_image') }}"
                    alt="{{ $privacyPage->center_image_alt ?: 'Privacy Policy' }}"
                    class="legal-center__img"
                    loading="lazy"
                    decoding="async"
                >
            </figure>
        </div>
    </section>
    @endif

    @if(html_filled($privacyPage->body ?? null))
    <section class="legal-body" aria-label="Privacy Policy content">
        <div class="container">
            <div class="legal-prose">
                {!! rich_html($privacyPage->body) !!}
            </div>
        </div>
    </section>
    @endif

</div>
@endsection
