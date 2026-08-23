@extends('layouts.app')

@section('title', ($article->meta_title ?? $article->title) . ' | Maverick Business Academy')
@section('meta_description', $article->meta_description ?? $article->excerpt)

@push('head')
    @include('partials.seo-meta', ['seo' => (object) [
        'meta_title' => ($article->meta_title ?? $article->title) . ' | Maverick Business Academy',
        'meta_description' => $article->meta_description ?? $article->excerpt,
        'og_image_url' => $article->featured_image_url,
        'og_type' => 'article',
    ]])
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('css/pages/news.css') }}">
@endpush

@section('content')
<div class="news-detail">

    {{-- ═══════════════════════════════════════════
         READING PROGRESS BAR
    ════════════════════════════════════════════ --}}
    <div id="news-progress-bar" class="news-progress-bar" aria-hidden="true">
        <div id="news-progress-fill" class="news-progress-fill"></div>
    </div>

    {{-- ═══════════════════════════════════════════
         SHORT CINEMATIC HERO
    ════════════════════════════════════════════ --}}
    <section class="news-detail-hero" aria-label="News Article Hero">
        <div class="news-detail-hero__bg" aria-hidden="true">
            <div class="news-detail-hero__gradient"></div>
            <div class="news-detail-hero__noise"></div>
            <div class="news-detail-hero__corners">
                <div class="news-detail-hero__corner news-detail-hero__corner--tl"></div>
                <div class="news-detail-hero__corner news-detail-hero__corner--tr"></div>
                <div class="news-detail-hero__corner news-detail-hero__corner--bl"></div>
                <div class="news-detail-hero__corner news-detail-hero__corner--br"></div>
            </div>
        </div>
        <div class="container news-detail-hero__content">
            <span class="news-detail-hero__eyebrow">
                <span class="news-detail-hero__eyebrow-line" aria-hidden="true"></span>
                NEWSROOM BULLETIN
            </span>
            <h1 class="news-detail-hero__title">
                {{ $article->title }}
            </h1>
            <div class="news-detail-hero__scroll-hint" aria-hidden="true">
                <span class="news-detail-hero__scroll-text">Scroll to read</span>
                <span class="news-detail-hero__scroll-arrow" data-lucide="chevron-down"></span>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         EDITORIAL MASTHEAD (white section)
    ════════════════════════════════════════════ --}}
    <header class="news-editorial-header">
        <div class="container">
            <div class="news-editorial-header__badge-row">
                <span class="news-badge">
                    <span class="news-badge__dot" aria-hidden="true"></span>
                    Newsroom Bulletin
                </span>
                @if($article->published_at)
                    <span class="news-editorial-header__badge-date">
                        {{ \Carbon\Carbon::parse($article->published_at)->format('M d, Y') }}
                    </span>
                @endif
            </div>

            <h1 class="news-editorial-header__title">{{ $article->title }}</h1>

            @if(!empty($article->excerpt))
                <p class="news-editorial-header__excerpt">{{ $article->excerpt }}</p>
            @endif

            <div class="news-editorial-header__byline">
                <div class="news-editorial-header__author">
                    @if($url = media_url($article->author_avatar_url ?? null))
                        <img src="{{ $url }}"
                             alt="{{ $article->author_name }}"
                             class="news-editorial-header__author-avatar"
                             width="34" height="34" loading="lazy">
                    @else
                        <span class="news-editorial-header__author-avatar" aria-hidden="true">
                            {{ strtoupper(mb_substr($article->author_name ?? 'M', 0, 1)) }}
                        </span>
                    @endif
                    <span class="news-editorial-header__author-name">
                        By <strong>{{ $article->author_name ?? 'Maverick Business Academy' }}</strong>
                    </span>
                </div>

                @if($article->published_at)
                    <span class="news-editorial-header__byline-divider" aria-hidden="true">&bull;</span>
                    <time class="news-editorial-header__date" datetime="{{ $article->published_at }}">
                        {{ \Carbon\Carbon::parse($article->published_at)->format('F d, Y') }}
                    </time>
                @endif

                <span class="news-editorial-header__byline-divider" aria-hidden="true">&bull;</span>
                <span class="news-editorial-header__read-time">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    {{ $article->reading_time_minutes ?? 1 }} min read
                </span>

                @if(!empty($article->author_bio))
                    <span class="news-editorial-header__byline-divider" aria-hidden="true">&bull;</span>
                    <span class="news-editorial-header__author-bio">{!! rich_html($article->author_bio ?? null) !!}</span>
                @endif
            </div>
        </div>
    </header>

    {{-- ═══════════════════════════════════════════
         FEATURED IMAGE
    ════════════════════════════════════════════ --}}
    @if($article->hasImage())
    <div class="news-detail-image-box">
        <figure style="margin: 0;">
            <img src="{{ $article->featured_image_url }}"
                 alt="{{ $article->featured_image_alt ?? $article->title }}"
                 loading="lazy"
                 width="900" height="500">
            @if($article->featured_image_alt)
                <figcaption class="news-detail-image-box__caption">
                    <span class="news-detail-image-box__caption-mark" aria-hidden="true">◆</span>
                    {{ $article->featured_image_alt }}
                </figcaption>
            @endif
        </figure>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════
         TWO-COLUMN LAYOUT: Body + Sidebar
    ════════════════════════════════════════════ --}}
    <div class="container">
        <div class="news-detail-layout">

            {{-- ── MAIN: Article Body ── --}}
            <div class="news-detail-main">
                <div class="news-article-body">
                    {!! $article->content !!}
                </div>

                {{-- Editorial Signature --}}
                <div class="news-editorial-signature" aria-hidden="true">
                    <span class="news-editorial-signature__rule"></span>
                    <span class="news-editorial-signature__mark">MBA &mdash; Newsroom</span>
                    <span class="news-editorial-signature__rule"></span>
                </div>
            </div>

            {{-- ── SIDEBAR: Share Bar ── --}}
            <aside class="news-detail-sidebar" aria-label="Share this article">
                <div class="blog-share-bar">
                    <span class="blog-share-bar__title">Share This Story</span>
                    <div class="blog-share-bar__links">
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                           class="blog-share-bar__link"
                           target="_blank"
                           rel="noopener noreferrer"
                           aria-label="Share on LinkedIn">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true">
                                <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}"
                           class="blog-share-bar__link"
                           target="_blank"
                           rel="noopener noreferrer"
                           aria-label="Share on X (Twitter)">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true">
                                <path d="M18.244 2.25h3.308l-7.227 7.56 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.085L1.254 2.25h6.816l4.7 6.222 5.474-6.222zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z"/>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           class="blog-share-bar__link"
                           target="_blank"
                           rel="noopener noreferrer"
                           aria-label="Share on Facebook">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true">
                                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                            </svg>
                        </a>
                        <button class="blog-share-bar__link"
                                data-copy-url="{{ request()->url() }}"
                                aria-label="Copy link to clipboard"
                                title="Copy Link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16" aria-hidden="true">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </aside>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MORE UPDATES
    ════════════════════════════════════════════ --}}
    @if(($moreUpdates ?? collect())->isNotEmpty())
    <section class="news-more-updates" aria-labelledby="more-updates-heading">
        <div class="news-ambient" aria-hidden="true">
            <span class="news-ambient__blob news-ambient__blob--1"></span>
        </div>
        <div class="container news-more-updates__inner">
            <div class="news-section-heading">
                <span class="news-section-heading__rule"></span>
                <span class="news-section-heading__label">Continue Reading</span>
            </div>

            <h2 id="more-updates-heading" class="news-more-updates__title">
                Recent <em class="news-more-updates__title-em">Announcements</em>
            </h2>

            <div class="news-feed news-feed--compact">
                @foreach($moreUpdates as $item)
                    <article class="news-row">
                        <time class="news-row__date" datetime="{{ $item->published_at }}">
                            {{ \Carbon\Carbon::parse($item->published_at)->format('M d, Y') }}
                        </time>
                        <div class="news-row__content">
                            <div class="news-row__meta">
                                <span class="news-row__author">
                                    {{ $item->author_name ?? 'Maverick Business Academy' }}
                                </span>
                                @if($item->published_at)
                                    <span class="news-row__divider" aria-hidden="true">|</span>
                                    <span class="news-row__read-time">
                                        {{ $item->reading_time_minutes ?? 1 }} min read
                                    </span>
                                @endif
                            </div>
                            <h3 class="news-row__title">
                                <a href="{{ route('insights.show', $item->slug) }}">
                                    {{ $item->title }}
                                </a>
                            </h3>
                            @if(!empty($item->excerpt))
                                <p class="news-row__excerpt">{{ $item->excerpt }}</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('sections.final-cta')
</div>
@endsection

@push('scripts')
    <script src="{{ cached_asset('assets/js/blog.js') }}" defer></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Copy-to-clipboard for share bar
        var copyBtns = document.querySelectorAll('[data-copy-url]');
        copyBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var url = btn.getAttribute('data-copy-url');
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(function () {
                        var original = btn.innerHTML;
                        btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>';
                        btn.style.background = 'var(--color-mba-blue, #0f2983)';
                        btn.style.color = '#fff';
                        setTimeout(function () {
                            btn.innerHTML = original;
                            btn.style.background = '';
                            btn.style.color = '';
                        }, 2000);
                    });
                }
            });
        });
    });
    </script>
@endpush
