@extends('layouts.app')

@section('title', 'Media Gallery - Maverick Business Academy')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/media-gallery.css') }}">
@endpush

@section('content')
<div class="page-gallery gallery-page">

@php
    // ═══════════════════════════════════════════
    // HERO (decorative only — all gallery content
    // is loaded dynamically from the database)
    // ═══════════════════════════════════════════
    $hero = (object)[
        'tag' => 'MEDIA GALLERY',
        'heading' => 'Life at Maverick,',
        'heading_italic' => 'In Pictures',
        'description' => 'Explore the moments that define our community — from graduation celebrations and campus life to global events and media spotlight.',
        'background_image' => 'https://images.pexels.com/photos/1181406/pexels-photo-1181406.jpeg?auto=compress&cs=tinysrgb&w=1920',
    ];

    $photoCount = $photos->count();
    $videoCount = $videos->count();
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
     2. GALLERY + LIGHTBOX
═══════════════════════════════════════════ --}}
<section class="gallery section-wrapper" id="gallery">
    <div class="container">

        <div class="section-heading-block">
            <span class="section-label">GALLERY</span>
            <h2 class="section-heading">
                Moments <em>Captured</em>
            </h2>
            <p class="section-subheading">
                A living collection of our community — step into the full-screen lightbox.
            </p>
        </div>

        @if($photoCount > 0)
            <div class="gallery__masonry" id="galleryMasonry" data-masonry>
                @foreach($photos as $index => $photo)
                    <figure class="gallery-item gallery-item--{{ $photo->size ?: 'medium' }}"
                            data-category="{{ $photo->category ?: 'all' }}"
                            data-src="{{ $photo->image_url }}"
                            data-caption="{{ $photo->caption }}"
                            data-index="{{ $index }}"
                            tabindex="0"
                            role="button"
                            aria-label="{{ $photo->caption ?: 'Open photo' }}">
                        <img src="{{ $photo->image_url }}"
                             alt="{{ $photo->caption ?: 'Maverick gallery photo' }}"
                             loading="lazy"
                             class="gallery-item__image">
                        <figcaption class="gallery-item__overlay">
                            <span class="gallery-item__title">{{ $photo->caption ?: 'Untitled' }}</span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>

            <div class="gallery__load-more" data-load-more-wrap="photos">
                <button type="button" class="btn-outline" data-load-more="photos">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Load More Photos
                </button>
            </div>
        @else
            <div class="gallery__empty">
                <p>Our photo gallery is being curated. Check back soon.</p>
            </div>
        @endif

    </div>
</section>


{{-- ═══════════════════════════════════════════
     3. FEATURED VIDEOS (modal player)
═══════════════════════════════════════════ --}}
<section class="featured-videos section-wrapper section--light" id="videos">
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

        @if($videoCount > 0)
            <div class="featured-videos__grid" data-videos-grid>
                @foreach($videos as $video)
                    <article class="video-card" data-video-url="{{ $video->video_url }}" data-video-item>
                        <button type="button" class="video-card__trigger" data-video-open aria-haspopup="dialog">
                            <div class="video-card__thumb">
                                <img src="{{ $video->auto_thumbnail }}"
                                     alt="{{ $video->title }}"
                                     loading="lazy"
                                     class="video-card__image">
                                <div class="video-card__play">
                                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                                @if($video->duration)
                                    <span class="video-card__duration">{{ $video->duration }}</span>
                                @endif
                            </div>
                            <h3 class="video-card__title">{{ $video->title }}</h3>
                        </button>
                    </article>
                @endforeach
            </div>

            <div class="gallery__load-more" data-load-more-wrap="videos">
                <button type="button" class="btn-outline" data-load-more="videos">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Load More Videos
                </button>
            </div>
        @else
            <div class="gallery__empty">
                <p>Featured videos are coming soon.</p>
            </div>
        @endif

    </div>
</section>


{{-- ═══════════════════════════════════════════
     4. UPCOMING EVENTS (shared section)
═══════════════════════════════════════════ --}}
@include('sections.upcoming-events')

</div>

@include('sections.final-cta')


{{-- ═══════════════════════════════════════════
     LIGHTBOX
═══════════════════════════════════════════ --}}
<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Photo lightbox" hidden>
    <button type="button" class="lightbox__close" data-lightbox-close aria-label="Close lightbox">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M18 6L6 18M6 6l12 12"/>
        </svg>
    </button>
    <button type="button" class="lightbox__nav lightbox__nav--prev" data-lightbox-prev aria-label="Previous photo">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M15 18l-6-6 6-6"/>
        </svg>
    </button>
    <div class="lightbox__stage">
        <img src="" alt="" class="lightbox__image" data-lightbox-image>
        <div class="lightbox__caption" data-lightbox-caption></div>
        <div class="lightbox__counter" data-lightbox-counter></div>
    </div>
    <button type="button" class="lightbox__nav lightbox__nav--next" data-lightbox-next aria-label="Next photo">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M9 18l6-6-6-6"/>
        </svg>
    </button>
</div>


{{-- ═══════════════════════════════════════════
     VIDEO MODAL
═══════════════════════════════════════════ --}}
<div class="video-modal" id="videoModal" role="dialog" aria-modal="true" aria-label="Video player" hidden>
    <button type="button" class="video-modal__close" data-video-close aria-label="Close video">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M18 6L6 18M6 6l12 12"/>
        </svg>
    </button>
    <div class="video-modal__frame" data-video-frame></div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/pages/media-gallery.js') }}" defer></script>
@endpush
