@extends('layouts.app')

@section('title', 'Latest Articles & Insights | Maverick Business Academy')
@section('meta_description', 'Explore cutting-edge business articles, corporate strategies, academic research, and leadership advice from the expert faculty at Maverick Business Academy.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/blog.css') }}">
@endpush

@section('content')
<div class="blog-page blog-page--listing">

    <!-- PAGE HERO -->
    <section class="blog-hero">
        <x-blog.decoration-layer variant="hero" />
        <div class="container">
            <div class="blog-hero__inner">
                <span class="blog-hero__eyebrow caption-text accent-text">Blogs</span>
                <h1 class="blog-hero__title display-text">Latest Articles & Insights</h1>
                <p class="blog-hero__subtitle section-subtitle">
                    Cutting-edge academic research, practical leadership strategy, and student success narratives curated specifically for future global business leaders.
                </p>
            </div>
        </div>
    </section>

    <!-- HORIZONTAL SEARCH & FILTER BAR (Sticky / Glassmorphism) -->
    <div class="blog-filter-bar-wrapper">
        <div class="container">
            <nav class="blog-filter-bar" aria-label="Article categories and search">
                <!-- Horizontally Scrollable Category Pills -->
                <!-- <div class="blog-filter-bar__pills-container">
                    <ul class="blog-filter-bar__pills" role="tablist">
                        @foreach($categories as $category)
                            <li role="none">
                                <a href="{{ route('blogs.index', array_merge(request()->except('category', 'page'), $category === 'All' ? [] : ['category' => $category])) }}"
                                   class="blog-filter-bar__pill {{ $activeCategory === $category ? 'blog-filter-bar__pill--active' : '' }}"
                                   role="tab"
                                   aria-selected="{{ $activeCategory === $category ? 'true' : 'false' }}">
                                    {{ $category }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div> -->

                <!-- Debounced Client/Server Search Input -->
                <form action="{{ route('blogs.index') }}" method="GET" class="blog-filter-bar__search-form">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="blog-filter-bar__search-group">
                        <svg class="blog-filter-bar__search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="search"
                               name="search"
                               class="blog-filter-bar__search-input"
                               placeholder="Search articles..."
                               value="{{ $searchQuery }}"
                               aria-label="Search articles">
                        @if(!empty($searchQuery))
                            <a href="{{ route('blogs.index', request()->except('search', 'page')) }}" class="blog-filter-bar__clear-btn" aria-label="Clear search">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </nav>
        </div>
    </div>

    <!-- MAIN BODY CONTENT -->
    <div class="blog-main-content">
        <div class="container">

            <!-- FEATURED POST (Large card on top, first page only, and when not strictly searching) -->
            @if($featuredPost && $paginatedPosts->currentPage() === 1)
                <div class="blog-featured-section">
                    <x-blog.hero-featured :post="$featuredPost" />
                </div>
            @endif

            <!-- ARTICLE GRID -->
            <section class="blog-grid-section" aria-label="Latest articles">
                <x-blog.decoration-layer variant="grid-bg" />
                @if($paginatedPosts->count() > 0)
                    <h2 class="blog-grid-section__title">
                        @if(!empty($searchQuery))
                            Search Results for "{{ $searchQuery }}"
                        @elseif($activeCategory !== 'All')
                            Latest in {{ $activeCategory }}
                        @else
                            Latest Articles
                        @endif
                    </h2>

                    <div class="blog-grid">
                        @foreach($paginatedPosts as $post)
                            <x-blog.card :post="$post" />
                        @endforeach
                    </div>

                    <!-- PAGINATION -->
                    @if($paginatedPosts->hasPages())
                        <nav class="blog-pagination" aria-label="Pagination">
                            <ul class="blog-pagination__list">
                                {{-- Previous Page Link --}}
                                @if ($paginatedPosts->onFirstPage())
                                    <li class="blog-pagination__item blog-pagination__item--disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                        <span class="blog-pagination__link" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16"><path d="M15 19l-7-7 7-7"/></svg>
                                        </span>
                                    </li>
                                @else
                                    <li class="blog-pagination__item">
                                        <a class="blog-pagination__link" href="{{ $paginatedPosts->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16"><path d="M15 19l-7-7 7-7"/></svg>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
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

                                {{-- Next Page Link --}}
                                @if ($paginatedPosts->hasMorePages())
                                    <li class="blog-pagination__item">
                                        <a class="blog-pagination__link" href="{{ $paginatedPosts->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16"><path d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </li>
                                @else
                                    <li class="blog-pagination__item blog-pagination__item--disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                                        <span class="blog-pagination__link" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16"><path d="M9 5l7 7-7 7"/></svg>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    @endif

                @else
                    <div class="blog-no-results">
                        <svg class="blog-no-results__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="64" height="64" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            <line x1="8" y1="11" x2="14" y2="11"></line>
                        </svg>
                        <h3 class="blog-no-results__title">No Articles Found</h3>
                        <p class="blog-no-results__desc">We couldn't find any articles matching your search criteria. Try modifying your search keywords or switching categories.</p>
                        <a href="{{ route('blogs.index') }}" class="btn btn--secondary blog-no-results__btn">
                            Reset All Filters
                        </a>
                    </div>
                @endif
            </section>
        </div>
    </div>

    @include('sections.final-cta')
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/blog.js') }}" defer></script>
@endpush
