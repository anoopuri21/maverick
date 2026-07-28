@extends('layouts.app')

@section('title', 'Official University Newsroom & Updates | Maverick Business Academy')
@section('meta_description', 'Read the latest institutional announcements, academic bulletins, research updates, and official press releases from Maverick Business Academy.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/news.css') }}">
@endpush

@section('content')
<div class="news-page news-page--listing">

    <!-- NEWS HEADLINES TICKER -->
    <x-news.ticker :items="$ticker" />

    <!-- MASTHEAD HEADER (Institutional double rule) -->
    <header class="news-masthead" aria-label="Newsroom masthead">
        <div class="container">
            <div class="news-masthead__inner">
                <!-- Watermark SVG (institutional cap) -->
                <svg class="news-masthead__watermark" viewBox="0 0 24 24" width="160" height="160" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/>
                </svg>

                <span class="news-masthead__eyebrow">Official University Updates</span>
                <h1 class="news-masthead__title">News & Announcements</h1>
                <div class="news-masthead__timestamp">
                    Updated {{ now()->format('F d, Y') }}
                </div>
            </div>
        </div>
    </header>

    <!-- HORIZONTAL FILTER BAR (Sticky / Result Count & Search) -->
    <div class="news-filter-wrapper">
        <div class="container">
            <div class="news-filter">
                <div class="news-filter__count">
                    Showing {{ $articles->total() + ($featured ? 1 : 0) }} official updates
                </div>
                <div class="news-filter__search-group">
                    <svg class="news-filter__search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="search"
                           id="news-search"
                           class="news-filter__search-input"
                           placeholder="Search news bulletin..."
                           aria-label="Search news bulletin">
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN NEWS LISTING -->
    <div class="news-main-body section-wrapper section--light">
        <div class="container">

            <!-- FEATURED STORY -->
            @if($featured && $articles->currentPage() === 1)
                <x-news.featured-story :post="$featured" />
            @endif

            <!-- DENSE NEWSFEED -->
            <section class="news-feed" aria-label="News list">
                @if($articles->count() > 0)
                    @foreach($articles as $article)
                        <x-news.headline-row :post="$article" />
                    @endforeach

                    <!-- NUMBERS PAGINATION -->
                    @if($articles->hasPages())
                        <nav class="news-pagination" aria-label="Pagination">
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($articles->onFirstPage())
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $articles->previousPageUrl() }}" rel="prev">&laquo;</a>
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
                                        <a class="page-link" href="{{ $articles->nextPageUrl() }}" rel="next">&raquo;</a>
                                    </li>
                                @else
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link">&raquo;</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    @endif

                @elseif(!$featured)
                    <div class="news-no-results">
                        <h3 class="news-no-results__title">No News Found</h3>
                        <p>There are currently no institutional updates published. Check back later.</p>
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
