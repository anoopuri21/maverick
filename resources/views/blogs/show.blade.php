@extends('layouts.app')

@section('title', ($post->meta_title ?? $post->title) . ' | Maverick Business Academy')
@section('meta_description', $post->meta_description ?? $post->excerpt)

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('css/pages/blog.css') }}">
@endpush

@section('content')
<div class="blog-detail">

    {{-- ═══════════════════════════════════════════
         READING PROGRESS BAR
    ════════════════════════════════════════════ --}}
    <div id="blog-progress-bar" class="blog-progress-bar" aria-hidden="true">
        <div id="blog-progress-fill" class="blog-progress-fill"></div>
    </div>

    {{-- ═══════════════════════════════════════════
         SHORT CINEMATIC HERO
    ════════════════════════════════════════════ --}}
    <section class="blog-detail-hero" aria-label="Article Hero">
        <div class="blog-detail-hero__bg" aria-hidden="true">
            <div class="blog-detail-hero__gradient"></div>
            <div class="blog-detail-hero__noise"></div>
            <div class="blog-detail-hero__corners">
                <div class="blog-detail-hero__corner blog-detail-hero__corner--tl"></div>
                <div class="blog-detail-hero__corner blog-detail-hero__corner--tr"></div>
                <div class="blog-detail-hero__corner blog-detail-hero__corner--bl"></div>
                <div class="blog-detail-hero__corner blog-detail-hero__corner--br"></div>
            </div>
        </div>
        <div class="container blog-detail-hero__content">
            <span class="blog-detail-hero__eyebrow">
                <span class="blog-detail-hero__eyebrow-line" aria-hidden="true"></span>
                BLOGS
            </span>
            <h1 class="blog-detail-hero__title">
                {{ $post->title }}
            </h1>
            @if(!empty($post->excerpt))
                <p class="blog-detail-hero__excerpt">{{ $post->excerpt }}</p>
            @endif
            <div class="blog-detail-hero__scroll-hint" aria-hidden="true">
                <span class="blog-detail-hero__scroll-text">Scroll to read</span>
                <span class="blog-detail-hero__scroll-arrow" data-lucide="chevron-down"></span>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         ARTICLE HEADER (white section)
    ════════════════════════════════════════════ --}}
    <header class="blog-article-header">
        <div class="container">
            <div class="blog-article-header__inner">
                <div class="blog-article-header__meta">
                    <div class="blog-article-header__author">
                        @if($url = media_url($post->author_avatar_url ?? null))
                            <img src="{{ $url }}"
                                 alt="{{ $post->author_name }}"
                                 class="blog-article-header__author-avatar"
                                 width="32" height="32" loading="lazy">
                        @else
                            <span class="blog-article-header__author-avatar" aria-hidden="true">
                                {{ strtoupper(mb_substr($post->author_name ?? 'M', 0, 1)) }}
                            </span>
                        @endif
                        <span class="blog-article-header__author-name">
                            {{ $post->author_name ?? 'Maverick Business Academy' }}
                        </span>
                    </div>
                    @if($post->published_at)
                        <span class="blog-article-header__divider" aria-hidden="true">|</span>
                        <time class="blog-article-header__date" datetime="{{ $post->published_at }}">
                            {{ \Carbon\Carbon::parse($post->published_at)->format('M d, Y') }}
                        </time>
                    @endif
                    <span class="blog-article-header__divider" aria-hidden="true">|</span>
                    <span class="blog-article-header__reading-time">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        {{ $post->reading_time_minutes ?? 1 }} min read
                    </span>
                </div>
            </div>
        </div>
    </header>

    {{-- ═══════════════════════════════════════════
         FEATURED IMAGE
    ════════════════════════════════════════════ --}}
    @if($post->hasImage())
    <div class="blog-featured-image-wrap">
        <div class="container">
            <div class="blog-featured-image-box--inner">
                <figure class="blog-featured-image-box">
                    <img src="{{ $post->featured_image_url }}"
                         alt="{{ $post->featured_image_alt ?? $post->title }}"
                         loading="lazy"
                         width="900" height="500">
                    @if($post->featured_image_alt)
                        <figcaption class="blog-featured-image-caption">
                            <span class="blog-featured-image-caption-mark" aria-hidden="true">◆</span>
                            {{ $post->featured_image_alt }}
                        </figcaption>
                    @endif
                </figure>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════
         TWO-COLUMN LAYOUT: Body + Sidebar
    ════════════════════════════════════════════ --}}
    <div class="container">
        <div class="blog-detail-layout">

            {{-- ── MAIN: Article Body ── --}}
            <div class="blog-detail-main">
                <div class="blog-article-body">
                    {!! $post->content !!}
                </div>

                {{-- Editorial Signature --}}
                <div class="blog-article-signature" aria-hidden="true">
                    <span class="blog-article-signature__rule"></span>
                    <span class="blog-article-signature__mark">MBA &mdash; Blog</span>
                    <span class="blog-article-signature__rule"></span>
                </div>
            </div>

            {{-- ── SIDEBAR ── --}}
            <aside class="blog-detail-sidebar" aria-label="Article navigation">

                {{-- Table of Contents --}}
                @if(count($headings ?? []) > 0)
                <nav class="blog-toc" aria-label="Table of contents">
                    <div class="blog-toc__header">
                        <h3 class="blog-toc__title">Table of Contents</h3>
                        <button class="blog-toc__toggle" aria-expanded="true" aria-controls="blog-toc-list" type="button">
                            <span class="blog-toc__toggle-text">Hide</span>
                            <svg class="blog-toc__toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true">
                                <path d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                    <ul id="blog-toc-list" class="blog-toc__list">
                        @foreach($headings as $heading)
                            <li class="blog-toc__item blog-toc__item--level-{{ $heading->level }}">
                                <a href="#{{ $heading->anchor }}" class="blog-toc__link">
                                    {{ $heading->text }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
                @endif

                {{-- Share Bar --}}
                <div class="blog-share-bar">
                    <span class="blog-share-bar__title">Share This Article</span>
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
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
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
         RELATED POSTS
    ════════════════════════════════════════════ --}}
    @if($relatedPosts->isNotEmpty())
    <section class="blog-related" aria-labelledby="related-posts-heading">
        <div class="container">
            <div class="blog-related__header">
                <h2 class="blog-related__title" id="related-posts-heading">
                    Continue Reading <span class="blog-related__title-em">More Insights</span>
                </h2>
            </div>
            <div class="blog-related__grid">
                @foreach($relatedPosts as $relatedPost)
                    <article class="blog-card">
                        <div class="blog-card__image">
                            <div class="blog-card__image-accent" aria-hidden="true"></div>
                            @if($relatedPost->hasImage())
                                <img src="{{ $relatedPost->featured_image_url }}"
                                     alt="{{ $relatedPost->featured_image_alt ?? $relatedPost->title }}"
                                     loading="lazy"
                                     width="400" height="250">
                            @else
                                <div class="blog-card__thumb-fallback" aria-hidden="true">
                                    {{ strtoupper(mb_substr($relatedPost->title, 0, 2)) }}
                                </div>
                            @endif
                            <span class="blog-card__category">Blog</span>
                        </div>
                        <div class="blog-card__body">
                            <h3 class="blog-card__title">
                                <a href="{{ route('insights.show', $relatedPost->slug) }}">
                                    {{ $relatedPost->title }}
                                </a>
                            </h3>
                            @if(!empty($relatedPost->excerpt))
                                <p class="blog-card__excerpt">{{ $relatedPost->excerpt }}</p>
                            @endif
                            <div class="blog-card__author">
                                    @if($url = media_url($relatedPost->author_avatar_url ?? null))
                                    <img src="{{ $url }}"
                                         alt="{{ $relatedPost->author_name }}"
                                         class="blog-card__author-avatar"
                                         width="28" height="28" loading="lazy">
                                @else
                                    <span class="blog-card__author-avatar" aria-hidden="true">
                                        {{ strtoupper(mb_substr($relatedPost->author_name ?? 'M', 0, 1)) }}
                                    </span>
                                @endif
                                <div class="blog-card__author-info">
                                    <span class="blog-card__author-name">
                                        {{ $relatedPost->author_name ?? 'Maverick Business Academy' }}
                                    </span>
                                    <div class="blog-card__author-meta">
                                        @if($relatedPost->published_at)
                                            <time datetime="{{ $relatedPost->published_at }}">
                                                {{ \Carbon\Carbon::parse($relatedPost->published_at)->format('M d, Y') }}
                                            </time>
                                            <span class="blog-card__author-meta-sep" aria-hidden="true">·</span>
                                        @endif
                                        <span>{{ $relatedPost->reading_time_minutes ?? 1 }} min read</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="blog-card__footer">
                            <a href="{{ route('insights.show', $relatedPost->slug) }}" class="blog-card__cta">
                                Read Article
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14" aria-hidden="true">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </a>
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
