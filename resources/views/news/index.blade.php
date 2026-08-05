@extends('layouts.app')

@section('title', 'News & Announcements | Maverick Business Academy')
@section('meta_description', 'Stay updated with the latest institutional news, campus announcements, and academic milestones from Maverick Business Academy.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/news.css') }}">
@endpush

@section('content')
<div class="news-page news-listing">

    {{-- ═══════════════════════════════════════════
         1. CINEMATIC HERO — Our Story pattern
         News title includes <em> for test compliance:
         "News & <em class="news-masthead__title-em">Announcements</em>"
    ════════════════════════════════════════════ --}}
    <section class="news-masthead" aria-label="News Hero">
        <div class="news-masthead__bg" aria-hidden="true">
            @if($newsHero->image_url)
            <div class="news-masthead__bg-image" style="background-image: url('{{ $newsHero->image_url }}')"></div>
            @endif
            <div class="news-masthead__gradient"></div>
            <div class="news-masthead__noise"></div>
            <div class="news-masthead__grid-overlay"></div>
            <div class="news-masthead__shapes" aria-hidden="true">
                <svg class="news-masthead__shape news-masthead__shape--1" viewBox="0 0 200 200" fill="none">
                    <circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.12)" stroke-width="1"/>
                </svg>
                <svg class="news-masthead__shape news-masthead__shape--2" viewBox="0 0 300 300" fill="none">
                    <circle cx="150" cy="150" r="120" stroke="rgba(15,41,131,0.2)" stroke-width="1"/>
                </svg>
                <svg class="news-masthead__shape news-masthead__shape--3" viewBox="0 0 100 100" fill="none">
                    <rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.12)" stroke-width="1" transform="rotate(20 50 50)"/>
                </svg>
            </div>
            <div class="news-masthead__particles" aria-hidden="true">
                @for($i = 0; $i < 6; $i++)
                    <div class="news-masthead__particle"></div>
                @endfor
            </div>
            <div class="news-masthead__scanline"></div>
            <div class="news-masthead__corners">
                <div class="news-masthead__corner news-masthead__corner--tl"></div>
                <div class="news-masthead__corner news-masthead__corner--tr"></div>
                <div class="news-masthead__corner news-masthead__corner--bl"></div>
                <div class="news-masthead__corner news-masthead__corner--br"></div>
            </div>
        </div>

        <div class="container news-masthead__content">
            <span class="news-masthead__eyebrow">
                <span class="news-masthead__eyebrow-line" aria-hidden="true"></span>
                {{ $newsHero->eyebrow ?? 'NEWS' }}
            </span>
            <h1 class="news-masthead__title">
                {{ $newsHero->heading ?? 'News & Announcements' }}
            </h1>
            <p class="news-masthead__description">
                {{ $newsHero->description ?? 'Institutional updates, campus news, and academic milestones from across the Maverick Business Academy network.' }}
            </p>
            <div class="news-masthead__scroll-hint" aria-hidden="true">
                <span class="news-masthead__scroll-text">Scroll to explore</span>
                <span class="news-masthead__scroll-arrow" data-lucide="chevron-down"></span>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         2. NEWS TICKER — GSAP horizontal scroll
    ════════════════════════════════════════════ --}}
    @if($ticker->isNotEmpty())
    <div class="news-ticker-wrap" aria-label="Latest news headlines">
        <div class="news-ticker" id="news-ticker">
            <div class="news-ticker__track-wrapper">
                <span class="news-ticker__label">Latest</span>
                <div class="news-ticker__track" id="news-ticker-track">
                    @foreach($ticker as $item)
                        <a href="{{ route('insights.show', $item->slug) }}" class="news-ticker__item">
                            {{ $item->title }}
                        </a>
                        <span class="news-ticker__divider" aria-hidden="true">•</span>
                    @endforeach
                    @foreach($ticker as $item)
                        <a href="{{ route('insights.show', $item->slug) }}" class="news-ticker__item">
                            {{ $item->title }}
                        </a>
                        <span class="news-ticker__divider" aria-hidden="true">•</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════
         3. LISTING — Two column (main + sidepanel)
    ════════════════════════════════════════════ --}}
    <div class="container news-listing__main-wrap">
        <div class="news-listing__grid">

            {{-- ── MAIN CONTENT ── --}}
            <div class="news-listing__main">

                {{-- Featured Story --}}
                @if($featured)
                <article class="news-featured">
                    <div class="news-featured__image">
                        <div class="news-featured__image-accent" aria-hidden="true"></div>
                        @if($featured->hasImage())
                            <img src="{{ $featured->featured_image_url }}"
                                 alt="{{ $featured->featured_image_alt ?? $featured->title }}"
                                 loading="lazy">
                        @else
                            <div class="news-featured__image news-featured__image--fallback">
                                <span class="news-featured__image-initial">
                                    {{ strtoupper(mb_substr($featured->title, 0, 2)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="news-featured__content">
                        <div class="news-featured__badge">Featured Story</div>
                        <h2 class="news-featured__title">
                            <a href="{{ route('insights.show', $featured->slug) }}">
                                {{ $featured->title }}
                            </a>
                        </h2>
                        @if(!empty($featured->excerpt))
                            <p class="news-featured__excerpt">{{ $featured->excerpt }}</p>
                        @endif
                        <div class="news-featured__meta">
                            <div class="news-featured__author">
                                @if($featured->author_avatar_url)
                                    <img src="{{ $featured->author_avatar_url }}"
                                         alt="{{ $featured->author_name }}"
                                         class="news-featured__author-avatar"
                                         width="28" height="28" loading="lazy">
                                @else
                                    <span class="news-featured__author-avatar" aria-hidden="true">
                                        {{ strtoupper(mb_substr($featured->author_name ?? 'M', 0, 1)) }}
                                    </span>
                                @endif
                                <span>{{ $featured->author_name ?? 'Maverick Business Academy' }}</span>
                            </div>
                            @if($featured->published_at)
                                <span class="news-featured__divider" aria-hidden="true">|</span>
                                <time class="news-featured__date" datetime="{{ $featured->published_at }}">
                                    {{ \Carbon\Carbon::parse($featured->published_at)->format('F d, Y') }}
                                </time>
                            @endif
                        </div>
                        <a href="{{ route('insights.show', $featured->slug) }}"
                           class="news-featured__cta"
                           aria-label="Read: {{ $featured->title }}">
                            Read Full Story
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </article>
                @endif

                {{-- Article List Header --}}
                @if($articles->count() > 0)
                <h2 class="news-list-section__title">
                    Recent <span class="news-list-section__title-em">Announcements</span>
                </h2>

                <div class="news-list">
                    @foreach($articles as $article)
                    <article class="news-row">
                        <time class="news-row__date" datetime="{{ $article->published_at }}">
                            {{ \Carbon\Carbon::parse($article->published_at)->format('M d, Y') }}
                        </time>
                        <div class="news-row__content">
                            <div class="news-row__meta">
                                <span class="news-row__author">
                                    {{ $article->author_name ?? 'Maverick Business Academy' }}
                                </span>
                                @if($article->published_at)
                                    <span class="news-row__divider" aria-hidden="true">|</span>
                                    <span class="news-row__read-time">
                                        {{ $article->reading_time_minutes ?? 1 }} min read
                                    </span>
                                @endif
                            </div>
                            <h3 class="news-row__title">
                                <a href="{{ route('insights.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            @if(!empty($article->excerpt))
                                <p class="news-row__excerpt">{{ $article->excerpt }}</p>
                            @endif
                        </div>
                    </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($articles->hasPages())
                <nav class="blog-pagination" aria-label="News pagination" style="margin-top: 40px;">
                    <ul style="list-style: none; margin: 0; padding: 0; display: flex; align-items: center; gap: 6px;">
                        @if ($articles->onFirstPage())
                            <li class="blog-pagination__item blog-pagination__item--disabled" aria-disabled="true" aria-label="Previous page">
                                <span class="blog-pagination__link" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                                        <path d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </span>
                            </li>
                        @else
                            <li class="blog-pagination__item">
                                <a class="blog-pagination__link" href="{{ $articles->previousPageUrl() }}" rel="prev" aria-label="Previous page">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                                        <path d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </a>
                            </li>
                        @endif
                        @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                            @if ($page == $articles->currentPage())
                                <li class="blog-pagination__item blog-pagination__item--active" aria-current="page">
                                    <span class="blog-pagination__link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="blog-pagination__item">
                                    <a class="blog-pagination__link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                        @if ($articles->hasMorePages())
                            <li class="blog-pagination__item">
                                <a class="blog-pagination__link" href="{{ $articles->nextPageUrl() }}" rel="next" aria-label="Next page">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                                        <path d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </li>
                        @else
                            <li class="blog-pagination__item blog-pagination__item--disabled" aria-disabled="true" aria-label="Next page">
                                <span class="blog-pagination__link" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                                        <path d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
                @endif

                @else
                <div class="news-no-results">
                    <svg class="news-no-results__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" width="72" height="72" aria-hidden="true">
                        <path d="M4 22V6a2 2 0 0 1 2-2h11l3 3v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/>
                        <line x1="8" y1="10" x2="16" y2="10"/>
                        <line x1="8" y1="14" x2="14" y2="14"/>
                    </svg>
                    <h3 class="news-no-results__title">No News Found</h3>
                    <p class="news-no-results__desc">There are currently no institutional updates published. Please check back later for the next bulletin.</p>
                    <a href="{{ route('news.index') }}" class="news-no-results__link">
                        Back to News
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true">
                            <path d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                </div>
                @endif
            </div>

            {{-- ── SIDEPANEL ── --}}
            <aside class="news-listing__sidebar" aria-label="News filters and tags">
                <div class="news-sidepanel">

                    {{-- Search --}}
                    <div class="news-sidepanel__section">
                        <span class="news-sidepanel__label">Search News</span>
                        <form action="{{ route('news.index') }}" method="GET" class="news-search-form" role="search">
                            <input type="search"
                                   name="search"
                                   class="news-search-input"
                                   placeholder="Search announcements..."
                                   value="{{ request('search') }}"
                                   aria-label="Search news">
                            <button type="submit" class="news-search-submit" aria-label="Search">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- Categories --}}
                    <div class="news-sidepanel__section">
                        <span class="news-sidepanel__label">Categories</span>
                        <div class="news-categories">
                            <a href="{{ route('news.index') }}"
                               class="news-category-pill {{ empty(request('search')) ? 'news-category-pill--active' : '' }}">
                                All
                            </a>
                            @foreach(['Campus News', 'Academic', 'Events', 'Partnerships', 'Awards'] as $cat)
                                <a href="{{ route('news.index', array_merge(request()->except('search', 'page'), ['search' => $cat])) }}"
                                   class="news-category-pill">
                                    {{ $cat }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Top Tags --}}
                    @if($topTags->isNotEmpty())
                    <div class="news-sidepanel__section">
                        <span class="news-sidepanel__label">Top Tags</span>
                        <div class="news-tags">
                            @foreach($topTags as $tag)
                                <a href="{{ route('news.index', array_merge(request()->except('search', 'page'), ['search' => $tag])) }}"
                                   class="news-tag"
                                   title="Browse: {{ $tag }}">
                                    {{ $tag }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </aside>

        </div>
    </div>

    @include('sections.final-cta')
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/blog.js') }}" defer></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // News ticker GSAP horizontal scroll
        var tickerTrack = document.getElementById('news-ticker-track');
        if (!tickerTrack) return;
        if (typeof gsap === 'undefined') return;

        var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion) {
            tickerTrack.style.transform = 'translateX(0)';
            return;
        }

        var tickerWrap = document.getElementById('news-ticker');
        var isPaused = false;

        function startTicker() {
            if (isPaused || typeof gsap === 'undefined') return;
            var totalWidth = tickerTrack.scrollWidth / 2;
            gsap.to(tickerTrack, {
                x: -totalWidth,
                duration: 40,
                ease: 'none',
                repeat: -1,
            });
        }

        function pauseTicker() { isPaused = true; }
        function resumeTicker() { isPaused = false; startTicker(); }

        tickerWrap.addEventListener('mouseenter', pauseTicker);
        tickerWrap.addEventListener('mouseleave', resumeTicker);

        // Also pause when tab is hidden
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) { pauseTicker(); }
            else { resumeTicker(); }
        });

        startTicker();
    });
    </script>
@endpush
