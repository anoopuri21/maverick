@extends('layouts.app')

@section('title', 'Programmes | Maverick Business Academy')
@section('meta_description', 'Explore Maverick Business Academy programmes — Bachelors, Masters, MBA, Diplomas and professional courses.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/program-listing.css') }}">
@endpush

@section('content')
<div class="page-pl">

    <section class="cinematic-hero cinematic-hero--short pl-hero" aria-label="Programmes" data-testid="pl-hero">
        <div class="cinematic-hero__bg" aria-hidden="true">
            <div class="cinematic-hero__bg-image" style="background-image: url('{{ asset('assets/images/homepage/mba.jpg') }}')"></div>
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
                MAVERICK PROGRAMMES
            </span>
            <h1 class="cinematic-hero__title">Explore Your <em>Programme</em></h1>
            <p class="cinematic-hero__description">Globally recognised qualifications designed to move your career forward.</p>
            <div class="pl-hero__meta">
                <span class="pl-hero__meta-item">{{ $programs->count() }} programmes</span>
                <span class="pl-hero__meta-rule"></span>
                <span class="pl-hero__meta-item">{{ $categories->count() }} categories</span>
            </div>
            <div class="pl-hero__ctas">
                <a href="#programmes" class="btn btn--outline">Browse Programmes</a>
            </div>
        </div>
    </section>

    <section id="programmes" class="pl-list" aria-label="Programme list">
        @if($categories->count())
            <div class="pl-filter" role="group" aria-label="Filter programmes" data-pl-filter>
                <button type="button" class="pl-filter__btn is-active" data-filter="all" aria-pressed="true">All ({{ $programs->count() }})</button>
                @foreach($categories as $cat)
                    <button type="button" class="pl-filter__btn" data-filter="{{ $cat->slug }}" aria-pressed="false">{{ $cat->name }} ({{ $cat->programs_count }})</button>
                @endforeach
            </div>
        @endif

        <div class="pl-grid" data-pl-grid>
            @forelse($programs as $program)
                @php $catSlug = $program->programCategory->slug ?? 'all'; @endphp
                <a href="{{ route('programs.show', $program->slug) }}" class="pl-card" data-category="{{ $catSlug }}">
                    @if($program->image_url)
                        <div class="pl-card__media">
                            <img src="{{ $program->image_url }}" alt="{{ $program->title }}" loading="lazy" width="800" height="540">
                        </div>
                    @else
                        <div class="pl-card__media pl-card__media--empty" aria-hidden="true"></div>
                    @endif
                    <div class="pl-card__body">
                        @if($program->programCategory)<span class="pl-card__cat">{{ $program->programCategory->name }}</span>@endif
                        @if($program->universityPartner)<span class="pl-card__uni">{{ $program->universityPartner->name }}</span>@endif
                        <h2 class="pl-card__title">{{ $program->title }}</h2>
                        @if($program->duration || $program->level)
                            <span class="pl-card__meta">
                                @if($program->duration){{ $program->duration }}@endif
                                @if($program->duration && $program->level)<span class="pl-card__meta-rule">·</span>@endif
                                @if($program->level){{ $program->level }}@endif
                            </span>
                        @endif
                        @if($program->short_description)<p class="pl-card__desc">{{ $program->short_description }}</p>@endif
                        <span class="pl-card__link">View Programme <span aria-hidden="true">→</span></span>
                    </div>
                </a>
            @empty
                <p class="pl-empty">Programmes coming soon.</p>
            @endforelse
        </div>
    </section>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/pages/program-listing.js') }}" defer></script>
@endpush
