@extends('layouts.app')

@section('title', ($facultyVoiceSeo->meta_title ?? 'Faculty Voice | Maverick Business Academy'))
@section('meta_description', ($facultyVoiceSeo->meta_description ?? 'Perspectives from Maverick faculty and industry experts.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $facultyVoiceSeo])
@endpush

@if(!empty($facultyVoiceSeo->custom_body_scripts))
@push('scripts')
    {!! $facultyVoiceSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/faculty-voice.css') }}">
@endpush

@section('content')
<div class="page-fv">

    <section class="cinematic-hero cinematic-hero--short fv-hero" aria-label="Faculty Voice">
        <div class="cinematic-hero__bg" aria-hidden="true">
            <div class="cinematic-hero__bg-image" @style(['background-image: url(' . asset('assets/images/homepage/mba.jpg') . ')'])></div>
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
            <span class="cinematic-hero__eyebrow">
                <span class="cinematic-hero__eyebrow-line"></span>
                Faculty Voice
            </span>
            <h1 class="cinematic-hero__title">Insights from <em>industry experts</em></h1>
            <p class="cinematic-hero__description">Real-world perspectives from the minds shaping global business education.</p>
            <div class="fv-hero__meta">
                <span class="fv-hero__meta-item">{{ $voices->total() }} {{ $voices->total() === 1 ? 'voice' : 'voices' }}</span>
            </div>
        </div>
    </section>

    <section id="voices" class="fv-list" aria-label="Faculty Voices">
        <div class="fv-grid">
            @forelse($voices as $insight)
                <x-faculty-voice.card :insight="$insight" />
            @empty
                <p class="fv-empty">Faculty voices coming soon.</p>
            @endforelse
        </div>

        @if($voices->hasPages())
            <nav class="fv-pagination" aria-label="Faculty Voice pagination">
                <ul>
                    @if ($voices->onFirstPage())
                        <li class="is-disabled" aria-disabled="true"><span aria-hidden="true">‹</span></li>
                    @else
                        <li><a href="{{ $voices->previousPageUrl() }}" rel="prev" aria-label="Previous page">‹</a></li>
                    @endif

                    @foreach ($voices->getUrlRange(1, $voices->lastPage()) as $page => $url)
                        @if ($page == $voices->currentPage())
                            <li class="is-active" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    @if ($voices->hasMorePages())
                        <li><a href="{{ $voices->nextPageUrl() }}" rel="next" aria-label="Next page">›</a></li>
                    @else
                        <li class="is-disabled" aria-disabled="true"><span aria-hidden="true">›</span></li>
                    @endif
                </ul>
            </nav>
        @endif
    </section>

    @include('sections.final-cta')
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/pages/faculty-voice.js') }}" defer></script>
@endpush
