@extends('layouts.app')

@section('title', ($event->title ?? 'Event Detail') . ' - Maverick Business Academy')
@section('meta_description', Str::limit(strip_tags($event->description ?? 'Join our exclusive events and masterclasses.'), 160))

@push('head')
    @include('partials.seo-meta', ['seo' => $eventsSeo])
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('css/pages/event-detail.css') }}">
@endpush

@section('content')
<div class="ed-page">
    {{-- Background Surrealist Floating Shapes --}}
    <div class="ed-surreal-bg" aria-hidden="true">
        <div class="ed-blob ed-blob--1"></div>
        <div class="ed-blob ed-blob--2"></div>
        <div class="ed-blob ed-blob--3"></div>
        <div class="ed-grid-overlay"></div>
    </div>

    {{-- Hero Section --}}
    <section class="ed-hero">
        <div class="container">
            <div class="ed-hero__glass-card">
                <div class="ed-hero__meta">
                    @if($event->is_past)
                        <span class="ed-badge ed-badge--past"><i data-lucide="clock"></i> Past Event</span>
                    @else
                        <span class="ed-badge ed-badge--upcoming"><i data-lucide="sparkles"></i> Upcoming Event</span>
                    @endif

                    @if(!empty($event->event_type))
                        <span class="ed-badge" style="background: rgba(185,28,28,0.2); color: #f87171; border: 1px solid rgba(248,113,113,0.3); margin-left: 0.5rem;">
                            {{ $event->event_type }}
                        </span>
                    @endif
                </div>

                @if(!empty($event->title))
                    <h1 class="ed-hero__title">{{ $event->title }}</h1>
                @endif

                <div class="ed-hero__pills">
                    @if(!empty($event->event_date))
                        <div class="ed-pill">
                            <i data-lucide="calendar"></i>
                            <span>{{ $event->event_date->format('F d, Y') }}</span>
                        </div>
                    @endif

                    @if(!empty($event->formatted_time))
                        <div class="ed-pill">
                            <i data-lucide="clock"></i>
                            <span>{{ $event->formatted_time }}</span>
                        </div>
                    @endif

                    @if(!empty($event->location))
                        <div class="ed-pill">
                            <i data-lucide="map-pin"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="ed-body">
        <div class="container">
            <div class="ed-layout">
                {{-- Left Content Column --}}
                <div class="ed-main">
                    @if(!empty($event->display_image_url))
                        <div class="ed-featured-media">
                            <img src="{{ $event->display_image_url }}" alt="{{ $event->title }}">
                        </div>
                    @endif

                    {{-- Claymorphism Content Card --}}
                    @if(!empty($event->description))
                        <div class="ed-clay-card ed-clay-card--content">
                            <h2 class="ed-section-title">About <em>Event</em></h2>
                            <div class="ed-description rich-text">
                                {!! rich_html($event->description) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Photo Gallery Section (Supports single & multiple photos) --}}
                    @if(!empty($event->gallery_image_urls) && count($event->gallery_image_urls) > 0)
                        <div class="ed-clay-card ed-clay-card--gallery">
                            <div class="ed-gallery-header">
                                <div>
                                    <h2 class="ed-section-title">Photo <em>Gallery</em></h2>
                                    <p class="ed-gallery-sub">Highlights and memories captured during the event.</p>
                                </div>
                                <span class="ed-gallery-count"><i data-lucide="image"></i> {{ count($event->gallery_image_urls) }} {{ Str::plural('Photo', count($event->gallery_image_urls)) }}</span>
                            </div>

                            <div class="ed-gallery-grid @if(count($event->gallery_image_urls) === 1) ed-gallery-grid--single @endif">
                                @foreach($event->gallery_image_urls as $index => $imageUrl)
                                    <button type="button" class="ed-gallery-item" data-full-image="{{ $imageUrl }}" data-index="{{ $index }}" aria-label="View full image {{ $index + 1 }}">
                                        <img src="{{ $imageUrl }}" alt="{{ $event->title }} photo {{ $index + 1 }}" loading="lazy">
                                        <div class="ed-gallery-item__overlay">
                                            <i data-lucide="maximize-2"></i>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Video Gallery Section (Full frame directly on page) --}}
                    @if(!empty($event->formatted_videos) && count($event->formatted_videos) > 0)
                        <div class="ed-clay-card ed-clay-card--videos">
                            <div class="ed-gallery-header">
                                <div>
                                    <h2 class="ed-section-title">Video <em>Gallery</em></h2>
                                    <p class="ed-gallery-sub">Watch recorded sessions, keynotes, and event highlights directly.</p>
                                </div>
                                <span class="ed-gallery-count"><i data-lucide="video"></i> {{ count($event->formatted_videos) }} {{ Str::plural('Video', count($event->formatted_videos)) }}</span>
                            </div>

                            <div class="ed-videos-list">
                                @foreach($event->formatted_videos as $vid)
                                    <div class="ed-video-card">
                                        @if(!empty($vid['title']))
                                            <h3 class="ed-video-card__title"><i data-lucide="play-circle"></i> {{ $vid['title'] }}</h3>
                                        @endif
                                        <div class="ed-video-frame">
                                            @if(in_array($vid['type'], ['youtube', 'vimeo']))
                                                <iframe src="{{ $vid['embed_url'] }}"
                                                        title="{{ $vid['title'] ?: 'Event Video' }}"
                                                        frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                        allowfullscreen></iframe>
                                            @else
                                                <video controls preload="metadata">
                                                    <source src="{{ $vid['embed_url'] }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right Sidebar Column --}}
                <aside class="ed-sidebar">
                    {{-- Glass & Clay Info Box --}}
                    <div class="ed-glass-sidebar">
                        <h3 class="ed-sidebar__title">Event Details</h3>

                        <div class="ed-info-list">
                            @if(!empty($event->event_type))
                                <div class="ed-info-item">
                                    <div class="ed-info-item__icon"><i data-lucide="tag"></i></div>
                                    <div>
                                        <span class="ed-info-item__label">Event Type</span>
                                        <span class="ed-info-item__val">{{ $event->event_type }}</span>
                                    </div>
                                </div>
                            @endif

                            @if(!empty($event->event_date))
                                <div class="ed-info-item">
                                    <div class="ed-info-item__icon"><i data-lucide="calendar"></i></div>
                                    <div>
                                        <span class="ed-info-item__label">Date</span>
                                        <span class="ed-info-item__val">{{ $event->event_date->format('l, M d, Y') }}</span>
                                    </div>
                                </div>
                            @endif

                            @if(!empty($event->formatted_time))
                                <div class="ed-info-item">
                                    <div class="ed-info-item__icon"><i data-lucide="clock"></i></div>
                                    <div>
                                        <span class="ed-info-item__label">Time</span>
                                        <span class="ed-info-item__val">{{ $event->formatted_time }}</span>
                                    </div>
                                </div>
                            @endif

                            @if(!empty($event->location))
                                <div class="ed-info-item">
                                    <div class="ed-info-item__icon"><i data-lucide="map-pin"></i></div>
                                    <div>
                                        <span class="ed-info-item__label">Location</span>
                                        <span class="ed-info-item__val">{{ $event->location }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if(!$event->is_past && !empty($event->link_url))
                            <a href="{{ $event->link_url }}" target="_blank" rel="noopener noreferrer" class="ed-neo-btn ed-neo-btn--primary">
                                Register Now <i data-lucide="external-link"></i>
                            </a>
                        @else
                            <a href="{{ route('contact') }}" class="ed-neo-btn ed-neo-btn--secondary">
                                Contact Organizer <i data-lucide="arrow-right"></i>
                            </a>
                        @endif
                    </div>

                    {{-- Back Link --}}
                    <a href="{{ route('events') }}" class="ed-neo-btn ed-neo-btn--back">
                        <i data-lucide="arrow-left"></i> Back to Events
                    </a>
                </aside>
            </div>
        </div>
    </section>

    {{-- Related Events Section --}}
    @if(!empty($relatedEvents) && count($relatedEvents) > 0)
        <section class="ed-related">
            <div class="container">
                <div class="sec-head">
                    <div class="sec-label">More Events</div>
                    <h2 class="sec-title">Explore <em>Other Events</em></h2>
                </div>

                <div class="ep-grid">
                    @include('pages._event-cards', ['events' => $relatedEvents])
                </div>
            </div>
        </section>
    @endif
</div>

{{-- Lightbox Modal --}}
<div class="ed-lightbox" id="ed-lightbox" hidden aria-hidden="true">
    <div class="ed-lightbox__backdrop"></div>
    <div class="ed-lightbox__content">
        <button type="button" class="ed-lightbox__close" id="ed-lightbox-close" aria-label="Close Lightbox">
            <i data-lucide="x"></i>
        </button>

        <button type="button" class="ed-lightbox__nav ed-lightbox__nav--prev" id="ed-lightbox-prev" aria-label="Previous Image">
            <i data-lucide="chevron-left"></i>
        </button>

        <button type="button" class="ed-lightbox__nav ed-lightbox__nav--next" id="ed-lightbox-next" aria-label="Next Image">
            <i data-lucide="chevron-right"></i>
        </button>

        <img src="" alt="" id="ed-lightbox-img">
        <div class="ed-lightbox__counter" id="ed-lightbox-counter">1 / 1</div>
    </div>
</div>

@include('sections.final-cta')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const galleryItems = Array.from(document.querySelectorAll('.ed-gallery-item'));
        const lightbox = document.getElementById('ed-lightbox');
        const lightboxImg = document.getElementById('ed-lightbox-img');
        const lightboxClose = document.getElementById('ed-lightbox-close');
        const lightboxPrev = document.getElementById('ed-lightbox-prev');
        const lightboxNext = document.getElementById('ed-lightbox-next');
        const lightboxCounter = document.getElementById('ed-lightbox-counter');

        if (!lightbox || !lightboxImg || galleryItems.length === 0) return;

        let currentIndex = 0;

        const images = galleryItems.map(item => item.getAttribute('data-full-image'));

        const showImageAtIndex = (index) => {
            if (index < 0) index = images.length - 1;
            if (index >= images.length) index = 0;
            currentIndex = index;

            lightboxImg.src = images[currentIndex];
            if (lightboxCounter) {
                lightboxCounter.textContent = `${currentIndex + 1} / ${images.length}`;
            }

            if (images.length <= 1) {
                if (lightboxPrev) lightboxPrev.style.display = 'none';
                if (lightboxNext) lightboxNext.style.display = 'none';
            } else {
                if (lightboxPrev) lightboxPrev.style.display = 'flex';
                if (lightboxNext) lightboxNext.style.display = 'flex';
            }
        };

        galleryItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                showImageAtIndex(index);
                lightbox.removeAttribute('hidden');
                lightbox.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            });
        });

        const closeLightbox = () => {
            lightbox.setAttribute('hidden', 'true');
            lightbox.setAttribute('aria-hidden', 'true');
            lightboxImg.src = '';
            document.body.style.overflow = '';
        };

        if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
        if (lightboxPrev) lightboxPrev.addEventListener('click', () => showImageAtIndex(currentIndex - 1));
        if (lightboxNext) lightboxNext.addEventListener('click', () => showImageAtIndex(currentIndex + 1));

        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox || e.target.classList.contains('ed-lightbox__backdrop')) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (lightbox.hasAttribute('hidden')) return;

            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') showImageAtIndex(currentIndex - 1);
            if (e.key === 'ArrowRight') showImageAtIndex(currentIndex + 1);
        });
    });
</script>
@endpush
@endsection
