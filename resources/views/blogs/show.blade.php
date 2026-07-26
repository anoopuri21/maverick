@extends('layouts.app')

@section('title', $post->meta_title . ' | Maverick Business Academy')
@section('meta_description', $post->meta_description)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/blog.css') }}">
@endpush

@section('content')
<!-- READING PROGRESS BAR -->
<div id="blog-progress-bar" class="blog-progress-bar" aria-hidden="true">
    <div id="blog-progress-fill" class="blog-progress-fill"></div>
</div>

<article class="blog-page blog-page--detail">

    <!-- CINEMATIC PARALLAX HERO -->
    <section class="blog-detail-hero" id="blog-detail-hero">
        <x-blog.decoration-layer variant="hero" />
        <div class="blog-detail-hero__image-wrapper" id="blog-detail-hero-img-wrapper">
            <picture>
                <source srcset="{{ $post->featured_image_url }}&w=800 800w, {{ $post->featured_image_url }}&w=1600 1600w" sizes="100vw">
                <img class="blog-detail-hero__image"
                     id="blog-detail-hero-img"
                     src="{{ $post->featured_image_url }}&w=1600"
                     alt="{{ $post->title }}"
                     fetchpriority="high">
            </picture>
            <div class="blog-detail-hero__overlay"></div>
        </div>

        <div class="container">
            <div class="blog-detail-hero__content">
                <div class="blog-detail-hero__tags">
                    <x-blog.category-pill :category="$post->category" />
                </div>

                <h1 class="blog-detail-hero__title display-text">{{ $post->title }}</h1>

                <div class="blog-detail-hero__meta">
                    <img class="blog-detail-hero__author-avatar" src="{{ $post->author_avatar_url }}" alt="{{ $post->author_name }}" width="48" height="48">
                    <div class="blog-detail-hero__meta-text">
                        <span class="blog-detail-hero__author-name">By {{ $post->author_name }}</span>
                        <div class="blog-detail-hero__meta-sub">
                            <time datetime="{{ $post->published_at }}">
                                {{ \Carbon\Carbon::parse($post->published_at)->format('F d, Y') }}
                            </time>
                            <span class="blog-detail-hero__meta-divider">•</span>
                            <span>{{ $post->reading_time_minutes }} min read</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ARTICLE LAYOUT GRID -->
    <div class="blog-detail-body section-wrapper section--light">
        <div class="container">
            <div class="blog-detail-layout">

                <!-- STICKY SHARE BAR (Desktop left side / Mobile bottom-fixed) -->
                <aside class="blog-detail-layout__share">
                    <div class="blog-detail-share-container">
                        <x-blog.share-bar :url="request()->url()" :title="$post->title" />
                    </div>
                </aside>

                <!-- CENTER ARTICLE COLUMN -->
                <div class="blog-detail-layout__content">

                    <!-- TABLE OF CONTENTS (Desktop side, collapsible on mobile) -->
                    @if(count($headings) > 0)
                        <div class="blog-detail-toc-wrapper">
                            <x-blog.toc :headings="$headings" />
                        </div>
                    @endif

                    <!-- RICH TEXT ARTICLE CONTENT -->
                    <div class="blog-article-content rich-text" id="blog-article-content">
                        {!! $post->content !!}
                    </div>

                    <!-- Row of Tag Pills below article -->
                    @if(count($post->tags) > 0)
                        <div class="blog-article-tags">
                            <span class="blog-article-tags__label">Filed under:</span>
                            <div class="blog-article-tags__list">
                                @foreach($post->tags as $tag)
                                    <span class="blog-article-tags__tag">#{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- AUTHOR CARD (Soft-morphic) -->
                    <section class="blog-author-card" aria-label="About the author">
                        <img class="blog-author-card__avatar" src="{{ $post->author_avatar_url }}" alt="{{ $post->author_name }}" width="80" height="80" loading="lazy">
                        <div class="blog-author-card__content">
                            <span class="blog-author-card__eyebrow">Written By</span>
                            <h3 class="blog-author-card__name">{{ $post->author_name }}</h3>
                            <p class="blog-author-card__bio">{{ $post->author_bio }}</p>
                        </div>
                    </section>

                </div>

            </div>
        </div>
    </div>

    <!-- RELATED ARTICLES SECTION (3-card grid) -->
    <section class="blog-related-section section-wrapper section--light section--warm" aria-labelledby="related-articles-heading">
        <x-blog.decoration-layer variant="grid-bg" />
        <div class="container">
            <div class="blog-related-section__header flex-between">
                <h2 id="related-articles-heading" class="blog-related-section__title section-title">You Might Also Like</h2>
                <a href="{{ route('blogs.index') }}" class="blog-related-section__view-all">
                    View All Articles <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="blog-grid blog-grid--related">
                @foreach($relatedPosts as $relatedPost)
                    <x-blog.card :post="$relatedPost" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- NEWSLETTER CTA STRIP -->
    <x-blog.newsletter-cta />

</article>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/blog.js') }}" defer></script>
@endpush
