@extends('layouts.app')

@section('title', ($article->meta_title ?? $article->title) . ' | Maverick Business Academy')
@section('meta_description', $article->meta_description ?? $article->excerpt)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/news.css') }}">
@endpush

@section('content')
{{-- READING PROGRESS BAR (id/class preserved for blog.js) --}}
<div id="blog-progress-bar" class="blog-progress-bar news-progress-bar" aria-hidden="true">
    <div id="blog-progress-fill" class="blog-progress-fill news-progress-fill"></div>
</div>

<article class="news-page news-page--detail">

    {{-- ══════════════════════════════════════════════════════════
         ARTICLE HEADER — Editorial masthead style
         ══════════════════════════════════════════════════════════ --}}
    <header class="news-detail-header">
        {{-- Subtle floating icons for editorial atmosphere --}}
        <div class="news-floating-icons news-floating-icons--detail" aria-hidden="true">
            <svg class="news-floating-icon news-floating-icon--paper" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 22V6a2 2 0 0 1 2-2h11l3 3v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/>
                <line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="16" y2="14"/><line x1="8" y1="18" x2="13" y2="18"/>
            </svg>
            <svg class="news-floating-icon news-floating-icon--book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5v14z"/>
                <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/>
            </svg>
            <svg class="news-floating-icon news-floating-icon--pen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 19 7 21l1.5-5L18 6a2.1 2.1 0 0 1 3 3z"/><path d="m15 6 3 3"/>
            </svg>
        </div>

        <div class="container">
            <div class="news-detail-header__inner">

                <div class="news-detail-header__badge-row">
                    <span class="news-badge">
                        <span class="news-badge__dot" aria-hidden="true"></span>
                        Newsroom Bulletin
                    </span>
                    @if($article->published_at)
                        <span class="news-detail-header__badge-date">
                            {{ \Carbon\Carbon::parse($article->published_at)->format('M d, Y') }}
                        </span>
                    @endif
                </div>

                <h1 class="news-detail-header__title">{{ $article->title }}</h1>

                @if(!empty($article->excerpt))
                    <p class="news-detail-header__excerpt">{{ $article->excerpt }}</p>
                @endif

                <div class="news-detail-header__byline">
                    <span class="news-detail-header__author">
                        <span class="news-detail-header__author-avatar" aria-hidden="true">
                            {{ strtoupper(substr($article->author_name ?? 'M', 0, 1)) }}
                        </span>
                        <span class="news-detail-header__author-name">
                            By <strong>{{ $article->author_name ?? 'Maverick Business Academy' }}</strong>
                        </span>
                    </span>

                    @if($article->published_at)
                        <span class="news-detail-header__byline-divider" aria-hidden="true">&bull;</span>
                        <time datetime="{{ $article->published_at }}">
                            {{ \Carbon\Carbon::parse($article->published_at)->format('F d, Y') }}
                        </time>
                    @endif

                    <span class="news-detail-header__byline-divider" aria-hidden="true">&bull;</span>
                    <span class="news-detail-header__read-time">
                        <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        {{ $article->reading_time_minutes ?? 1 }} min read
                    </span>

                    @if(!empty($article->author_bio))
                        <span class="news-detail-header__byline-divider" aria-hidden="true">&bull;</span>
                        <span class="news-detail-header__author-bio">{{ $article->author_bio }}</span>
                    @endif
                </div>
            </div>
        </div>
    </header>

    {{-- FEATURED IMAGE --}}
    @if($article->hasImage())
        <div class="container">
            <figure class="news-detail-image-box">
                <img src="{{ $article->featured_image_url }}" alt="{{ $article->featured_image_alt ?? $article->title }}">
                @if($article->featured_image_alt)
                    <figcaption class="news-detail-image-box__caption">
                        <span class="news-detail-image-box__caption-mark">&#9678;</span>
                        {{ $article->featured_image_alt }}
                    </figcaption>
                @endif
            </figure>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════
         ARTICLE BODY — Sidebar (share) + Main content
         ══════════════════════════════════════════════════════════ --}}
    <div class="news-detail-body">
        <div class="container">
            <div class="news-detail-layout">

                {{-- SHARE BAR (component preserved) --}}
                <aside class="news-detail-layout__share">
                    <div class="blog-detail-share-container news-detail-share-container">
                        <x-blog.share-bar :url="request()->url()" :title="$article->title" />
                    </div>
                </aside>

                {{-- MAIN CONTENT --}}
                <div class="news-detail-layout__content">
                    <div class="news-detail-content rich-text">
                        {!! $article->content !!}
                    </div>

                    {{-- Editorial signature --}}
                    <div class="news-detail-signature" aria-hidden="true">
                        <span class="news-detail-signature__rule"></span>
                        <span class="news-detail-signature__mark">MBA &mdash; Newsroom</span>
                        <span class="news-detail-signature__rule"></span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MORE UPDATES
         ══════════════════════════════════════════════════════════ --}}
    @if($moreUpdates->count() > 0)
        <section class="news-more-updates" aria-labelledby="more-updates-heading">
            <div class="news-ambient" aria-hidden="true">
                <span class="news-ambient__blob news-ambient__blob--1"></span>
            </div>

            <div class="container">
                <div class="news-section-heading">
                    <span class="news-section-heading__rule"></span>
                    <span class="news-section-heading__label">Continue Reading</span>
                </div>

                <h2 id="more-updates-heading" class="news-more-updates__title">
                    Recent <em class="news-more-updates__title-em">Announcements</em>
                </h2>

                <div class="news-feed news-feed--compact">
                    @foreach($moreUpdates as $item)
                        <x-news.headline-row :post="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('sections.final-cta')

</article>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/blog.js') }}" defer></script>
@endpush
