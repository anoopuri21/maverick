@extends('layouts.app')

@section('title', 'Latest Articles & Insights | Maverick Business Academy')
@section('meta_description', 'Explore cutting-edge business articles, corporate strategies, academic research, and leadership advice from the expert faculty at Maverick Business Academy.')

@push('head')
    @include('partials.seo-meta', ['seo' => (object) [
        'meta_title' => 'Latest Articles & Insights | Maverick Business Academy',
        'meta_description' => 'Explore cutting-edge business articles, corporate strategies, academic research, and leadership advice from the expert faculty at Maverick Business Academy.',
        'og_type' => 'website',
    ]])
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('css/pages/blog.css') }}">
@endpush

@section('content')
@php
    $blogHero = $blogHero ?? safe_settings(\App\Settings\BlogHeroSettings::class);
    $topTags = collect($topTags ?? []);
@endphp
<div class="blog-page blog-listing">

    {{-- ═══════════════════════════════════════════
         1. CINEMATIC HERO — Our Story pattern
         Managed from Admin Panel via BlogHeroSettings
    ════════════════════════════════════════════ --}}
    <section class="blog-hero" aria-label="Blog Hero">
        <div class="blog-hero__bg" aria-hidden="true">
            @if($blogHero->image_url)
            <div class="blog-hero__bg-image" style="background-image: url('{{ $blogHero->image_url }}')"></div>
            @endif
            <div class="blog-hero__gradient"></div>
            <div class="blog-hero__noise"></div>
            <div class="blog-hero__grid-overlay"></div>
            <div class="blog-hero__shapes" aria-hidden="true">
                <svg class="blog-hero__shape blog-hero__shape--1" viewBox="0 0 200 200" fill="none">
                    <circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.12)" stroke-width="1"/>
                </svg>
                <svg class="blog-hero__shape blog-hero__shape--2" viewBox="0 0 300 300" fill="none">
                    <circle cx="150" cy="150" r="120" stroke="rgba(178,2,2,0.18)" stroke-width="1"/>
                </svg>
                <svg class="blog-hero__shape blog-hero__shape--3" viewBox="0 0 100 100" fill="none">
                    <rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.12)" stroke-width="1" transform="rotate(20 50 50)"/>
                </svg>
            </div>
            <div class="blog-hero__particles" aria-hidden="true">
                @for($i = 0; $i < 6; $i++)
                    <div class="blog-hero__particle"></div>
                @endfor
            </div>
            <div class="blog-hero__scanline"></div>
            <div class="blog-hero__corners">
                <div class="blog-hero__corner blog-hero__corner--tl"></div>
                <div class="blog-hero__corner blog-hero__corner--tr"></div>
                <div class="blog-hero__corner blog-hero__corner--bl"></div>
                <div class="blog-hero__corner blog-hero__corner--br"></div>
            </div>
        </div>

        <div class="container blog-hero__content">
            <span class="blog-hero__eyebrow">
                <span class="blog-hero__eyebrow-line" aria-hidden="true"></span>
                {{ $blogHero->eyebrow ?? 'BLOGS' }}
            </span>
            <h1 class="blog-hero__heading">
                {{ $blogHero->heading ?? 'Latest Articles & Insights' }}
            </h1>
            <div class="blog-hero__description">
                {!! html_filled($blogHero->description ?? null) ? rich_html($blogHero->description ?? null) : 'Cutting-edge academic research, practical leadership strategy, and student success narratives curated specifically for future global business leaders.' !!}
            </div>
            <div class="blog-hero__scroll-hint" aria-hidden="true">
                <span class="blog-hero__scroll-text">Scroll to explore</span>
                <span class="blog-hero__scroll-arrow" data-lucide="chevron-down"></span>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         2. LISTING — Two column (main + sidepanel)
    ════════════════════════════════════════════ --}}
    <div class="container blog-listing__main-wrap">
        <div class="blog-listing__grid">

            {{-- ── MAIN CONTENT ── --}}
            <div class="blog-listing__main">

                {{-- Featured Post --}}
                @if($featuredPost && $paginatedPosts->currentPage() === 1 && empty($searchQuery))
                <div class="blog-featured">
                    <div class="blog-featured__grid">
                        <div class="blog-featured__image">
                            <div class="blog-featured__image-accent" aria-hidden="true"></div>
                            @if($featuredPost->hasImage())
                                <img src="{{ $featuredPost->featured_image_url }}"
                                     alt="{{ $featuredPost->featured_image_alt ?? $featuredPost->title }}"
                                     loading="lazy">
                            @else
                                <div class="blog-featured__image blog-featured__image--fallback">
                                    <span class="blog-featured__image-initial">
                                        {{ strtoupper(mb_substr($featuredPost->title, 0, 2)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="blog-featured__content">
                            <!-- <div class="blog-featured__badge">Featured Article</div> -->
                            <h2 class="blog-featured__title">
                                <a href="{{ route('insights.show', $featuredPost->slug) }}">
                                    {{ $featuredPost->title }}
                                </a>
                            </h2>
                            @if(!empty($featuredPost->excerpt))
                                <p class="blog-featured__excerpt">{{ $featuredPost->excerpt }}</p>
                            @endif
                            <div class="blog-featured__meta">
                                <div class="blog-featured__author">
                                    @if($url = media_url($featuredPost->author_avatar_url ?? null))
                                        <img src="{{ $url }}"
                                             alt="{{ $featuredPost->author_name }}"
                                             class="blog-featured__author-avatar"
                                             width="32" height="32" loading="lazy">
                                    @else
                                        <span class="blog-featured__author-avatar" aria-hidden="true">
                                            {{ strtoupper(mb_substr($featuredPost->author_name ?? 'M', 0, 1)) }}
                                        </span>
                                    @endif
                                    <span class="blog-featured__author-name">
                                        {{ $featuredPost->author_name ?? 'Maverick Business Academy' }}
                                    </span>
                                </div>
                                @if($featuredPost->published_at)
                                    <span class="blog-featured__divider" aria-hidden="true">|</span>
                                    <time class="blog-featured__date" datetime="{{ $featuredPost->published_at }}">
                                        {{ \Carbon\Carbon::parse($featuredPost->published_at)->format('M d, Y') }}
                                    </time>
                                @endif
                            </div>
                            <a href="{{ route('insights.show', $featuredPost->slug) }}"
                               class="blog-featured__cta"
                               aria-label="Read: {{ $featuredPost->title }}">
                                Read Article
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Article Grid Header --}}
                @if($paginatedPosts->count() > 0)
                <div class="blog-grid-section__header">
                    <h2 class="blog-grid-section__title">
                        @if(!empty($searchQuery))
                            Search Results
                        @else
                            Latest <span class="blog-grid-section__title-em">Articles</span>
                        @endif
                    </h2>
                </div>

                <div class="blog-grid">
                    @foreach($paginatedPosts as $post)
                        <article class="blog-card">
                            <div class="blog-card__image">
                                <div class="blog-card__image-accent" aria-hidden="true"></div>
                                <x-blog.thumbnail :post="$post" aspect="16/10" />
                                <span class="blog-card__category">Blog</span>
                            </div>
                            <div class="blog-card__body">
                                <h3 class="blog-card__title">
                                    <a href="{{ route('insights.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                @if(!empty($post->excerpt))
                                    <p class="blog-card__excerpt">{{ $post->excerpt }}</p>
                                @endif
                                <div class="blog-card__author">
                                    @if($url = media_url($post->author_avatar_url ?? null))
                                        <img src="{{ $url }}"
                                             alt="{{ $post->author_name }}"
                                             class="blog-card__author-avatar"
                                             width="28" height="28" loading="lazy">
                                    @else
                                        <span class="blog-card__author-avatar" aria-hidden="true">
                                            {{ strtoupper(mb_substr($post->author_name ?? 'M', 0, 1)) }}
                                        </span>
                                    @endif
                                    <div class="blog-card__author-info">
                                        <span class="blog-card__author-name">
                                            {{ $post->author_name ?? 'Maverick Business Academy' }}
                                        </span>
                                        <div class="blog-card__author-meta">
                                            @if($post->published_at)
                                                <time datetime="{{ $post->published_at }}">
                                                    {{ \Carbon\Carbon::parse($post->published_at)->format('M d, Y') }}
                                                </time>
                                                <span class="blog-card__author-meta-sep" aria-hidden="true">·</span>
                                            @endif
                                            <span>{{ $post->reading_time_minutes ?? 1 }} min read</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-card__footer">
                                <a href="{{ route('insights.show', $post->slug) }}" class="blog-card__cta">
                                    Read Article
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14" aria-hidden="true">
                                        <path d="M5 12h14M12 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($paginatedPosts->hasPages())
                <nav class="blog-pagination" aria-label="Article pagination">
                    <ul style="list-style: none; margin: 0; padding: 0; display: flex; align-items: center; gap: 6px;">
                        {{-- Previous --}}
                        @if ($paginatedPosts->onFirstPage())
                            <li class="blog-pagination__item blog-pagination__item--disabled" aria-disabled="true" aria-label="Previous page">
                                <span class="blog-pagination__link" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                                        <path d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </span>
                            </li>
                        @else
                            <li class="blog-pagination__item">
                                <a class="blog-pagination__link" href="{{ $paginatedPosts->previousPageUrl() }}" rel="prev" aria-label="Previous page">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                                        <path d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </a>
                            </li>
                        @endif

                        {{-- Page numbers --}}
                        @foreach ($paginatedPosts->getUrlRange(1, $paginatedPosts->lastPage()) as $page => $url)
                            @if ($page == $paginatedPosts->currentPage())
                                <li class="blog-pagination__item blog-pagination__item--active" aria-current="page">
                                    <span class="blog-pagination__link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="blog-pagination__item">
                                    <a class="blog-pagination__link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($paginatedPosts->hasMorePages())
                            <li class="blog-pagination__item">
                                <a class="blog-pagination__link" href="{{ $paginatedPosts->nextPageUrl() }}" rel="next" aria-label="Next page">
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
                {{-- No Results --}}
                <div class="blog-no-results">
                    <svg class="blog-no-results__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="64" height="64" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        <line x1="8" y1="11" x2="14" y2="11"></line>
                    </svg>
                    <h3 class="blog-no-results__title">No Articles Found</h3>
                    <p class="blog-no-results__desc">
                        @if(!empty($searchQuery))
                            We couldn't find any articles matching "<strong>{{ $searchQuery }}</strong>". Try different keywords.
                        @else
                            There are no articles published yet. Check back soon.
                        @endif
                    </p>
                    <a href="{{ route('blogs.index') }}" class="blog-no-results__link">
                        View All Articles
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                @endif
            </div>

            {{-- ── SIDEPANEL ── --}}
            <aside class="blog-listing__sidebar" aria-label="Blog filters and tags">
                <div class="blog-sidepanel">

                    {{-- Search --}}
                    <div class="blog-sidepanel__section">
                        <span class="blog-sidepanel__label">Search Articles</span>
                        <form action="{{ route('blogs.index') }}" method="GET" class="blog-search-form" role="search">
                            @if($searchQuery)
                                <button type="submit" class="blog-search-clear visible"
                                        onclick="event.preventDefault(); window.location.href=`{{ route('blogs.index') }}`"
                                        aria-label="Clear search">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            @endif
                            <input type="search"
                                   name="search"
                                   class="blog-search-input"
                                   placeholder="Search articles..."
                                   value="{{ $searchQuery }}"
                                   aria-label="Search articles">
                            <button type="submit" class="blog-search-submit" aria-label="Search">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- Categories --}}
                    <div class="blog-sidepanel__section">
                        <span class="blog-sidepanel__label">Categories</span>
                        <div class="blog-categories">
                            <a href="{{ route('blogs.index') }}"
                               class="blog-category-pill {{ empty($searchQuery) && $activeCategory === 'All' ? 'blog-category-pill--active' : '' }}">
                                All
                            </a>
                            @foreach(['Business Strategy', 'Leadership', 'Innovation', 'Finance', 'Technology'] as $cat)
                                <a href="{{ route('blogs.index', array_merge(request()->except('category', 'page', 'search'), ['search' => $cat])) }}"
                                   class="blog-category-pill">
                                    {{ $cat }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Top Tags --}}
                    @if($topTags->isNotEmpty())
                    <div class="blog-sidepanel__section">
                        <span class="blog-sidepanel__label">Top Tags</span>
                        <div class="blog-tags">
                            @foreach($topTags as $tag)
                                <a href="{{ route('blogs.index', array_merge(request()->except('category', 'page', 'search'), ['search' => $tag])) }}"
                                   class="blog-tag"
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
    <script src="{{ cached_asset('assets/js/blog.js') }}" defer></script>
@endpush
