@extends('layouts.app')

@section('title', 'Media Gallery - Maverick Business Academy')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/media-gallery.css') }}">
@endpush

@section('content')
<div class="page-gallery gallery-page">


@php
    // ═══════════════════════════════════════════
    // STATIC DATA (Future: Move to admin/database)
    // ═══════════════════════════════════════════

    $hero = (object)[
        'tag' => 'MEDIA GALLERY',
        'heading' => 'Life at Maverick,',
        'heading_italic' => 'In Pictures',
        'description' => 'Explore the moments that define our community — from graduation celebrations and campus life to global events and media spotlight.',
        'background_image' => asset('assets/images/gallery/hero-bg.jpg'),
    ];

    $categories = collect([
        (object)['slug' => 'all', 'name' => 'All', 'count' => 22],
        (object)['slug' => 'events', 'name' => 'Events', 'count' => 5],
        (object)['slug' => 'campus', 'name' => 'Campus', 'count' => 5],
        (object)['slug' => 'students', 'name' => 'Students', 'count' => 5],
        (object)['slug' => 'graduations', 'name' => 'Graduations', 'count' => 4],
        (object)['slug' => 'media-coverage', 'name' => 'Media Coverage', 'count' => 3],
    ]);

    $galleryItems = collect([
        (object)['category' => 'events', 'image' => asset('https://images.pexels.com/photos/9275222/pexels-photo-9275222.jpeg?auto=compress&cs=tinysrgb&w=800&q=80'), 'title' => 'Business Summit 2024', 'size' => 'small'],
        (object)['category' => 'campus', 'image' => asset('https://images.pexels.com/photos/11932106/pexels-photo-11932106.jpeg?auto=compress&cs=tinysrgb&w=800&q=80'), 'title' => 'Campus Library', 'size' => 'large'],
        (object)['category' => 'graduations', 'image' => asset('https://images.pexels.com/photos/29275615/pexels-photo-29275615.jpeg?auto=compress&cs=tinysrgb&w=800&q=80'), 'title' => 'Graduation Day', 'size' => 'small'],
        (object)['category' => 'campus', 'image' => asset('https://images.pexels.com/photos/8761536/pexels-photo-8761536.jpeg?auto=compress&cs=tinysrgb&w=800&q=80'), 'title' => 'Business School Building', 'size' => 'medium'],
        (object)['category' => 'students', 'image' => asset('https://images.pexels.com/photos/30562665/pexels-photo-30562665.jpeg?auto=compress&cs=tinysrgb&w=800&q=80'), 'title' => 'Student Life', 'size' => 'medium'],
        (object)['category' => 'students', 'image' => asset('https://images.pexels.com/photos/35314982/pexels-photo-35314982.jpeg?auto=compress&cs=tinysrgb&w=800&q=80'), 'title' => 'Campus Walk', 'size' => 'medium'],
        (object)['category' => 'graduations', 'image' => asset('https://images.pexels.com/photos/29229903/pexels-photo-29229903.jpeg?auto=compress&cs=tinysrgb&w=800&q=80'), 'title' => 'Graduation Ceremony', 'size' => 'medium'],
        (object)['category' => 'events', 'image' => asset('https://images.pexels.com/photos/10604063/pexels-photo-10604063.jpeg?auto=compress&cs=tinysrgb&w=800&q=80'), 'title' => 'Conference Hall', 'size' => 'small'],
        (object)['category' => 'media-coverage', 'image' => asset('https://images.pexels.com/photos/7648468/pexels-photo-7648468.jpeg'), 'title' => 'Boardroom Meeting', 'size' => 'medium'],
    ]);

    $featuredVideos = collect([
        (object)[
            'title' => 'Graduation Day 2024: The Highlights',
            'duration' => '1:02',
            'thumbnail' => asset('assets/images/gallery/videos/video-1.jpg'),
            'video_url' => '#',
        ],
        (object)[
            'title' => 'Class of 2024: A Celebration',
            'duration' => '0:17',
            'thumbnail' => asset('assets/images/gallery/videos/video-2.jpg'),
            'video_url' => '#',
        ],
        (object)[
            'title' => 'Inside the Boardroom: Executive Education',
            'duration' => '0:13',
            'thumbnail' => asset('assets/images/gallery/videos/video-3.jpg'),
            'video_url' => '#',
        ],
    ]);

    $recentEvents = collect([
        (object)[
            'date' => '12 Mar 2024',
            'title' => 'Global Business Summit 2024',
            'location' => 'London Campus',
            'image' => asset('assets/images/gallery/events/event-1.jpg'),
        ],
        (object)[
            'date' => '28 Feb 2024',
            'title' => 'Executive Leadership Forum',
            'location' => 'The Shard, London',
            'image' => asset('assets/images/gallery/events/event-2.jpg'),
        ],
        (object)[
            'date' => '15 Jan 2024',
            'title' => 'International Welcome Week',
            'location' => 'London Campus',
            'image' => asset('assets/images/gallery/events/event-3.jpg'),
        ],
        (object)[
            'date' => '18 Dec 2023',
            'title' => 'Winter Gala',
            'location' => 'Royal Albert Hall',
            'image' => asset('assets/images/gallery/events/event-4.jpg'),
        ],
    ]);

    $mediaCoverage = collect([
        (object)[
            'code' => 'FT',
            'publication' => 'FINANCIAL TIMES',
            'title' => 'The Academy Democratising Business Education in the UK',
            'date' => '14 Feb 2024',
            'url' => '#',
        ],
        (object)[
            'code' => 'TG',
            'publication' => 'THE GUARDIAN',
            'title' => 'How Maverick Is Closing the Skills Gap for Working Professionals',
            'date' => '03 Jan 2024',
            'url' => '#',
        ],
        (object)[
            'code' => 'FOR',
            'publication' => 'FORBES',
            'title' => 'Meet the Business School Betting Big on Ambitious Learners',
            'date' => '18 Nov 2023',
            'url' => '#',
        ],
        (object)[
            'code' => 'THE',
            'publication' => 'TIMES HIGHER EDUCATION',
            'title' => 'A New Model for Global Executive Learning Emerges in London',
            'date' => '30 Oct 2023',
            'url' => '#',
        ],
    ]);
@endphp


{{-- ═══════════════════════════════════════════
     1. HERO SECTION
═══════════════════════════════════════════ --}}
<section class="gallery-hero" style="background-image: url('{{ $hero->background_image }}');">
    <div class="gallery-hero__overlay"></div>
    <div class="container gallery-hero__content">
        <span class="gallery-hero__tag">{{ $hero->tag }}</span>
        <h1 class="gallery-hero__heading">
            {{ $hero->heading }}
            <em class="gallery-hero__heading-italic">{{ $hero->heading_italic }}</em>
        </h1>
        <p class="gallery-hero__description">{{ $hero->description }}</p>
        <div class="gallery-hero__scroll">
            <span>SCROLL</span>
            <div class="gallery-hero__scroll-line"></div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     2. GALLERY WITH FILTERS
═══════════════════════════════════════════ --}}
<section class="gallery section-wrapper">
    <div class="container">
        
        {{-- Category Filters --}}
        <div class="gallery__filters">
            @foreach($categories as $category)
            <button class="gallery-filter {{ $category->slug === 'all' ? 'is-active' : '' }}" 
                    data-filter="{{ $category->slug }}">
                {{ $category->name }}
                <span class="gallery-filter__count">{{ $category->count }}</span>
            </button>
            @endforeach
        </div>

        {{-- Masonry Gallery --}}
        <div class="gallery__masonry">
            @foreach($galleryItems as $item)
            <div class="gallery-item gallery-item--{{ $item->size }}" 
                 data-category="{{ $item->category }}">
                <img src="{{ $item->image }}" 
                     alt="{{ $item->title }}" 
                     loading="lazy"
                     class="gallery-item__image">
                <div class="gallery-item__overlay">
                    <span class="gallery-item__title">{{ $item->title }}</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Load More Button --}}
        <div class="gallery__load-more">
            <button class="btn-outline" id="loadMorePhotos">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Load More Photos
            </button>
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════
     3. FEATURED VIDEOS
═══════════════════════════════════════════ --}}
<section class="featured-videos section-wrapper section--light">
    <div class="container">
        
        <div class="section-heading-block">
            <span class="section-label">WATCH</span>
            <h2 class="section-heading">
                Featured <em>Videos</em>
            </h2>
            <p class="section-subheading">
                Relive our proudest moments through the lens — from graduation days to executive forums.
            </p>
        </div>

        <div class="featured-videos__grid">
            @foreach($featuredVideos as $video)
            <article class="video-card">
                <a href="{{ $video->video_url }}" class="video-card__link">
                    <div class="video-card__thumb">
                        <img src="{{ $video->thumbnail }}" 
                             alt="{{ $video->title }}" 
                             loading="lazy"
                             class="video-card__image">
                        <div class="video-card__play">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                        <span class="video-card__duration">{{ $video->duration }}</span>
                    </div>
                    <h3 class="video-card__title">{{ $video->title }}</h3>
                </a>
            </article>
            @endforeach
        </div>

        <div class="gallery__load-more">
            <button class="btn-outline" id="loadMoreVideos">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Load More Videos
            </button>
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════
     4. RECENT EVENTS
═══════════════════════════════════════════ --}}
<section class="recent-events section-wrapper">
    <div class="container">
        
        <div class="section-heading-block">
            <span class="section-label">HAPPENINGS</span>
            <h2 class="section-heading">
                Recent <em>Events</em>
            </h2>
            <p class="section-subheading">
                From summits to ceremonies — the gatherings that bring our global community together.
            </p>
        </div>

        <div class="recent-events__slider" data-events-slider>
            <div class="recent-events__track">
                @foreach($recentEvents as $event)
                <article class="event-card">
                    <div class="event-card__image-wrapper">
                        <img src="{{ $event->image }}" 
                             alt="{{ $event->title }}" 
                             loading="lazy"
                             class="event-card__image">
                        <span class="event-card__date">{{ $event->date }}</span>
                    </div>
                    <div class="event-card__content">
                        <h3 class="event-card__title">{{ $event->title }}</h3>
                        <p class="event-card__location">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            {{ $event->location }}
                        </p>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="recent-events__nav">
                <button class="events-nav-btn" data-events-prev aria-label="Previous">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </button>
                <button class="events-nav-btn" data-events-next aria-label="Next">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
            </div>
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════
     5. MEDIA COVERAGE
═══════════════════════════════════════════ --}}
<section id="how-we-do-it" class="hwdi media-coverage section-wrapper section--light">
    <div class="container">
        
        <div class="hwdi__header">
            
        <div class="section-label">
          <span>IN THE PRESS</span>
        </div>

        <h2 class="hwdi__heading section-title">
          <span class="hwdi__heading-line">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">
                Media
              </span>
            </span>
          </span>

          <span class="hwdi__heading-line hwdi__heading-line--red">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">
                Coverage
              </span>
            </span>
          </span>
        </h2>
      <p class="hwdi__subtitle body-text fade-up">
        What the world's leading publications are saying about the Maverick movement.
      </p>

        </div>

        <div class="media-coverage__grid">
            @foreach($mediaCoverage as $article)
            <a href="{{ $article->url }}" class="media-article" target="_blank" rel="noopener">
                <div class="media-article__logo">
                    <span>{{ $article->code }}</span>
                </div>
                <div class="media-article__content">
                    <span class="media-article__publication">{{ $article->publication }}</span>
                    <h3 class="media-article__title">{{ $article->title }}</h3>
                    <div class="media-article__meta">
                        <span class="media-article__date">{{ $article->date }}</span>
                        <span class="media-article__read">
                            Read Article 
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

    </div>
</section>

@include('sections.final-cta')

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/pages/media-gallery.js') }}" defer></script>
@endpush