@extends('layouts.app')

@section('title', ($seo->meta_title ?? $insight->title) . ' | Maverick Business Academy')
@section('meta_description', $seo->meta_description ?? $insight->excerpt ?? '')

@push('head')
    @include('partials.seo-meta', ['seo' => $seo])
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('css/pages/faculty-voice.css') }}">
@endpush

@section('content')
<div class="page-fv page-fv--detail">

    <div id="fv-progress" class="fv-progress" aria-hidden="true">
        <div id="fv-progress-fill" class="fv-progress__fill"></div>
    </div>

    <section class="fv-detail-hero" aria-label="Faculty Voice">
        <div class="fv-detail-hero__bg" aria-hidden="true">
            @if($insight->heroImageUrl())
                <div class="fv-detail-hero__image" style="background-image: url('{{ $insight->heroImageUrl() }}')"></div>
            @endif
            <div class="fv-detail-hero__gradient"></div>
            <div class="fv-detail-hero__noise"></div>
            <div class="fv-detail-hero__corners">
                <div class="fv-detail-hero__corner fv-detail-hero__corner--tl"></div>
                <div class="fv-detail-hero__corner fv-detail-hero__corner--tr"></div>
                <div class="fv-detail-hero__corner fv-detail-hero__corner--bl"></div>
                <div class="fv-detail-hero__corner fv-detail-hero__corner--br"></div>
            </div>
        </div>
        <div class="container fv-detail-hero__content">
            <span class="fv-detail-hero__eyebrow">
                <span class="fv-detail-hero__eyebrow-line" aria-hidden="true"></span>
                Faculty Voice
            </span>
            <h1 class="fv-detail-hero__title">{{ $insight->title }}</h1>
            @if($insight->excerpt)
                <p class="fv-detail-hero__excerpt">{{ $insight->excerpt }}</p>
            @endif
        </div>
    </section>

    <header class="fv-identity">
        <div class="container">
            <div class="fv-identity__inner">
                @if($insight->avatarUrl())
                    <img src="{{ $insight->avatarUrl() }}" alt="{{ $insight->faculty_name }}" class="fv-identity__avatar" width="56" height="56" loading="lazy" decoding="async">
                @elseif($insight->faculty_name)
                    <span class="fv-identity__avatar fv-identity__avatar--fallback" aria-hidden="true">{{ strtoupper(mb_substr($insight->faculty_name, 0, 1)) }}</span>
                @endif
                <div class="fv-identity__copy">
                    @if($insight->faculty_name)
                        <p class="fv-identity__name">{{ $insight->faculty_name }}</p>
                    @endif
                    @if($insight->faculty_role)
                        <p class="fv-identity__role">{{ $insight->faculty_role }}</p>
                    @endif
                    @if(html_filled($insight->faculty_bio ?? null))
                        <div class="fv-identity__bio">{!! rich_html($insight->faculty_bio ?? null) !!}</div>
                    @endif
                </div>
                <div class="fv-identity__meta">
                    @if($insight->published_at)
                        <time datetime="{{ $insight->published_at->toDateString() }}">{{ $insight->published_at->format('M d, Y') }}</time>
                    @endif
                    <span>{{ $insight->reading_time_minutes ?? 1 }} min read</span>
                    @if($insight->badge)
                        <span class="fv-identity__badge">{{ $insight->badge }}</span>
                    @endif
                </div>
            </div>
        </div>
    </header>

    @if($insight->featuredImageUrl())
        <div class="fv-featured">
            <div class="container">
                <div class="fv-featured__stage">
                    <figure class="fv-featured__figure" data-fv-scale>
                        <img src="{{ $insight->featuredImageUrl() }}" alt="{{ $insight->title }}" width="960" height="600" loading="lazy" decoding="async">
                    </figure>
                </div>
            </div>
        </div>
    @endif

    @if(html_filled($insight->pull_quote ?? null))
        <blockquote class="fv-quote">
            <div class="container">
                {!! rich_html($insight->pull_quote ?? null) !!}
            </div>
        </blockquote>
    @endif

    @if(html_filled($insight->content))
        <article class="fv-article">
            <div class="container">
                <div class="fv-article__body">
                    {!! rich_html($insight->content ?? null) !!}
                </div>
            </div>
        </article>
    @endif

    @if(($relatedVoices ?? collect())->isNotEmpty())
        <section class="fv-related" aria-labelledby="related-voices-heading">
            <div class="container">
                <div class="fv-related__header">
                    <span class="fv-related__label">Faculty Voice</span>
                    <h2 id="related-voices-heading" class="fv-related__title">Other related <em>voices</em></h2>
                </div>
                <div class="fv-grid fv-grid--related">
                    @foreach($relatedVoices as $related)
                        <x-faculty-voice.card :insight="$related" heading="h3" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('sections.final-cta')
</div>
@endsection

@push('scripts')
    <script src="{{ cached_asset('assets/js/pages/faculty-voice.js') }}" defer></script>
@endpush
