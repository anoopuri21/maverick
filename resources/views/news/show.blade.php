@extends('layouts.app')

@section('title', ($article->meta_title ?? $article->title) . ' | Maverick Business Academy')
@section('meta_description', $article->meta_description ?? $article->excerpt)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/news.css') }}">
@endpush

@section('content')
<!-- READING PROGRESS BAR -->
<div id="blog-progress-bar" class="blog-progress-bar" aria-hidden="true" style="position: fixed; top: 0; left: 0; width: 100%; height: 4px; background: rgba(0,0,0,0.05); z-index: 9999;">
    <div id="blog-progress-fill" class="blog-progress-fill" style="width: 0%; height: 100%; background: var(--color-accent); transition: width 0.1s ease;"></div>
</div>

<article class="news-page news-page--detail">

    <!-- INSTITUTIONAL DOUBLE RULE HEADER -->
    <header class="news-detail-header">
        <div class="container">
            <div class="news-detail-header__inner">
                <div class="news-detail-header__badge-row">
                    <span class="news-badge">Newsroom Bulletin</span>
                </div>

                <h1 class="news-detail-header__title">{{ $article->title }}</h1>

                <div class="news-detail-header__byline">
                    <span>By {{ $article->author_name }}</span>
                    <span class="news-detail-header__byline-divider">|</span>
                    <time datetime="{{ $article->published_at }}">
                        {{ \Carbon\Carbon::parse($article->published_at)->format('F d, Y') }}
                    </time>
                    <span class="news-detail-header__byline-divider">|</span>
                    <span>{{ $article->reading_time_minutes }} min read</span>

                    @if($article->author_bio)
                        <span class="news-detail-header__byline-divider">|</span>
                        <span style="font-style: italic;">{{ $article->author_bio }}</span>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Contained Featured Image Box -->
    @if($article->hasImage())
        <div class="container">
            <div class="news-detail-image-box">
                <img src="{{ $article->featured_image_url }}" alt="{{ $article->featured_image_alt ?? $article->title }}">
                @if($article->featured_image_alt)
                    <div class="news-detail-image-box__caption">
                        {{ $article->featured_image_alt }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- CENTERED COLUMN GRID -->
    <div class="news-detail-body">
        <div class="container">
            <div class="news-detail-layout">

                <!-- SHARE BAR (Desktop left / Mobile bottom fixed) -->
                <aside class="news-detail-layout__share">
                    <div class="blog-detail-share-container">
                        <x-blog.share-bar :url="request()->url()" :title="$article->title" />
                    </div>
                </aside>

                <!-- MAIN ARTICLE CONTENT -->
                <div class="news-detail-layout__content">
                    <div class="news-detail-content rich-text">
                        {!! $article->content !!}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MORE UPDATES LIST -->
    @if($moreUpdates->count() > 0)
        <section class="news-more-updates" aria-labelledby="more-updates-heading">
            <div class="container">
                <h2 id="more-updates-heading" class="news-more-updates__title">Recent Announcements</h2>
                <div class="news-feed" style="margin-top: 0; border: none;">
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
