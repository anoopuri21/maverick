@extends('layouts.app')

@section('title', 'Official University Newsroom & Updates | Maverick Business Academy')
@section('meta_description', 'Read the latest institutional announcements, academic bulletins, research updates, and official press releases from Maverick Business Academy.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/news.css') }}">
@endpush

@section('content')
<div class="news-page news-page--listing">

    {{-- NEWS HEADLINES TICKER (component preserved) --}}
    <x-news.ticker :items="$ticker" />

    {{-- ══════════════════════════════════════════════════════════
         MASTHEAD — Editorial hero with floating education icons
         ══════════════════════════════════════════════════════════ --}}
    <header class="news-masthead" aria-label="Newsroom masthead">
        {{-- Floating education icons (decorative) --}}
        <div class="news-floating-icons" aria-hidden="true">
            <svg class="news-floating-icon news-floating-icon--book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5v14z"/>
                <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/>
            </svg>
            <svg class="news-floating-icon news-floating-icon--cap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M22 10 12 5 2 10l10 5 10-5z"/>
                <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/>
            </svg>
            <svg class="news-floating-icon news-floating-icon--paper" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 22V6a2 2 0 0 1 2-2h11l3 3v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/>
                <line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="16" y2="14"/><line x1="8" y1="18" x2="13" y2="18"/>
            </svg>
            <svg class="news-floating-icon news-floating-icon--globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15 15 0 0 1 0 20a15 15 0 0 1 0-20z"/>
            </svg>
            <svg class="news-floating-icon news-floating-icon--pen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 19 7 21l1.5-5L18 6a2.1 2.1 0 0 1 3 3z"/><path d="m15 6 3 3"/>
            </svg>
            <svg class="news-floating-icon news-floating-icon--bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M9 18h6"/><path d="M10 22h4"/><path d="M12 2a7 7 0 0 0-4 12.7c.5.4.9.9 1 1.5V18h6v-1.8c.1-.6.5-1.1 1-1.5A7 7 0 0 0 12 2z"/>
            </svg>
            <svg class="news-floating-icon news-floating-icon--atom" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="12" r="1.5"/><ellipse cx="12" cy="12" rx="10" ry="4"/><ellipse cx="12" cy="12" rx="10" ry="4" transform="rotate(60 12 12)"/><ellipse cx="12" cy="12" rx="10" ry="4" transform="rotate(120 12 12)"/>
            </svg>
            <svg class="news-floating-icon news-floating-icon--compass" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88"/>
            </svg>
        </div>

        <div class="news-masthead__pattern" aria-hidden="true"></div>

        <div class="container">
            <div class="news-masthead__inner">
                <span class="news-masthead__eyebrow">
                    <span class="news-masthead__eyebrow-rule"></span>
                    Newsroom &nbsp;&middot;&nbsp; Official Updates
                    <span class="news-masthead__eyebrow-rule"></span>
                </span>

                <h1 class="news-masthead__title">
                    News &amp; <em class="news-masthead__title-em">Announcements</em>
                </h1>

                <p class="news-masthead__lede">
                    Institutional announcements, academic bulletins, and research bulletins
                    from the desks of the Maverick Business Academy faculty and administration.
                </p>

                <div class="news-masthead__timestamp">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span>Updated {{ now()->format('l, F d, Y') }}</span>
                </div>
            </div>
        </div>
    </header>

    {{-- ══════════════════════════════════════════════════════════
         STICKY FILTER BAR — Count + Live search
         ══════════════════════════════════════════════════════════ --}}
    <div class="news-filter-wrapper">
        <div class="container">
            <div class="news-filter">
                <div class="news-filter__count">
                    <span class="news-filter__count-dot" aria-hidden="true"></span>
                    Showing <strong>{{ $articles->total() + ($featured ? 1 : 0) }}</strong>
                    <span class="news-filter__count-label">official updates</span>
                </div>
                <div class="news-filter__search-group">
                    <svg class="news-filter__search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="search"
                           id="news-search"
                           class="news-filter__search-input"
                           placeholder="Search the bulletin…"
                           aria-label="Search news bulletin">
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MAIN BODY
         ══════════════════════════════════════════════════════════ --}}
    <div class="news-main-body section-wrapper section--light">
        {{-- Ambient background decoration --}}
        <div class="news-ambient" aria-hidden="true">
            <span class="news-ambient__blob news-ambient__blob--1"></span>
            <span class="news-ambient__blob news-ambient__blob--2"></span>
        </div>

        <div class="container">

            {{-- FEATURED STORY --}}
            @if($featured && $articles->currentPage() === 1)
                <div class="news-featured-wrap">
                    <div class="news-section-heading">
                        <span class="news-section-heading__rule"></span>
                        <span class="news-section-heading__label">Top Story</span>
                    </div>
                    <x-news.featured-story :post="$featured" />
                </div>
            @endif

            {{-- NEWSFEED --}}
            <section class="news-feed-section" aria-label="News list">
                @if($articles->count() > 0)
                    <div class="news-section-heading">
                        <span class="news-section-heading__rule"></span>
                        <span class="news-section-heading__label">Latest Bulletins</span>
                        <span class="news-section-heading__count">{{ $articles->count() }} in this issue</span>
                    </div>

                    <div class="news-feed" aria-label="Article feed">
                        @foreach($articles as $article)
                            <x-news.headline-row :post="$article" />
                        @endforeach
                    </div>

                    {{-- PAGINATION --}}
                    @if($articles->hasPages())
                        <nav class="news-pagination" aria-label="Pagination">
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($articles->onFirstPage())
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 19l-7-7 7-7"/></svg>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $articles->previousPageUrl() }}" rel="prev" aria-label="Previous">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 19l-7-7 7-7"/></svg>
                                        </a>
                                    </li>
                                @endif

                                {{-- Page numbers --}}
                                @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                                    @if ($page == $articles->currentPage())
                                        <li class="page-item active" aria-current="page">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($articles->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $articles->nextPageUrl() }}" rel="next" aria-label="Next">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    @endif

                @elseif(!$featured)
                    <div class="news-no-results">
                        <svg class="news-no-results__icon" viewBox="0 0 24 24" width="72" height="72" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                            <path d="M4 22V6a2 2 0 0 1 2-2h11l3 3v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/>
                            <line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="14" y2="14"/>
                        </svg>
                        <h3 class="news-no-results__title">No News Found</h3>
                        <p class="news-no-results__desc">There are currently no institutional updates published. Please check back later for the next bulletin.</p>
                    </div>
                @endif
            </section>

        </div>
    </div>

    @include('sections.final-cta')
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('news-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                const items = document.querySelectorAll('.news-row, .news-featured');
                items.forEach(item => {
                    const title = item.querySelector('.news-row__title, .news-featured__title').textContent.toLowerCase();
                    const excerpt = item.querySelector('.news-row__excerpt, .news-featured__excerpt')?.textContent.toLowerCase() || '';
                    if (title.includes(query) || excerpt.includes(query)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection
