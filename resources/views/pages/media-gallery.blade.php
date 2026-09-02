@extends('layouts.app')

@section('title', ($mediaGallerySeo->meta_title ?? 'Media Gallery - Maverick Business Academy'))
@section('meta_description', ($mediaGallerySeo->meta_description ?? 'Photos, videos and campus moments from Maverick Business Academy.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $mediaGallerySeo])
@endpush

@if(!empty($mediaGallerySeo->custom_body_scripts))
@push('scripts')
    {!! $mediaGallerySeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('css/pages/media-gallery.css') }}">
    <link rel="stylesheet" href="{{ cached_asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
@php
    $mediaGalleryPage = $mediaGalleryPage ?? safe_settings(\App\Settings\MediaGalleryPageSettings::class);
@endphp
<div class="page-gallery gallery-page">

@php
    $photos = collect($photos ?? []);
    $videos = collect($videos ?? []);
    $photoCount = $photos->count();
    $videoCount = $videos->count();
    $galleryHeroBg = media_url(
        $mediaGalleryPage->hero_background_image ?? null,
        'https://images.pexels.com/photos/1181406/pexels-photo-1181406.jpeg?auto=compress&cs=tinysrgb&w=1920'
    );
@endphp


{{-- ═══════════════════════════════════════════
     1. HERO SECTION (Cinematic Design)
═══════════════════════════════════════════ --}}
<section class="cinematic-hero cinematic-hero--short" aria-label="Media Gallery Hero">
    <div class="cinematic-hero__bg" aria-hidden="true">
        <div class="cinematic-hero__bg-image" @if($galleryHeroBg) style="background-image: url('{{ $galleryHeroBg }}')" @endif></div>
        <div class="cinematic-hero__gradient"></div>
        <div class="cinematic-hero__noise"></div>
        <div class="cinematic-hero__shapes">
            <svg class="cinematic-hero__shape cinematic-hero__shape--1" viewBox="0 0 200 200" fill="none">
                <circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.15)" stroke-width="1"/>
            </svg>
            <svg class="cinematic-hero__shape cinematic-hero__shape--2" viewBox="0 0 300 300" fill="none">
                <circle cx="150" cy="150" r="120" stroke="rgba(220,38,38,0.2)" stroke-width="1"/>
            </svg>
            <svg class="cinematic-hero__shape cinematic-hero__shape--3" viewBox="0 0 100 100" fill="none">
                <rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.15)" stroke-width="1" transform="rotate(20 50 50)"/>
            </svg>
        </div>
        <div class="cinematic-hero__particles">
            @for($i = 0; $i < 6; $i++)
                <div class="cinematic-hero__particle"></div>
            @endfor
        </div>
        <div class="cinematic-hero__scanline"></div>
        <div class="cinematic-hero__corners">
            <div class="cinematic-hero__corner cinematic-hero__corner--tl"></div>
            <div class="cinematic-hero__corner cinematic-hero__corner--tr"></div>
            <div class="cinematic-hero__corner cinematic-hero__corner--bl"></div>
            <div class="cinematic-hero__corner cinematic-hero__corner--br"></div>
        </div>
    </div>
    <div class="container cinematic-hero__content">
        <span class="cinematic-hero__eyebrow">
            <span class="cinematic-hero__eyebrow-line"></span>
            {{ $mediaGalleryPage->hero_tag ?? 'MEDIA GALLERY' }}
        </span>
        <h1 class="cinematic-hero__title">
            {{ $mediaGalleryPage->hero_heading_line1 ?? 'Life at Maverick,' }}<br>
            <em>{{ $mediaGalleryPage->hero_heading_italic ?? 'In Pictures' }}</em>
        </h1>
        <div class="cinematic-hero__description">{!! html_filled($mediaGalleryPage->hero_description ?? null) ? rich_html($mediaGalleryPage->hero_description ?? null) : 'Explore the moments that define our community — from graduation celebrations and campus life to global events and media spotlight. Every image tells a story of ambition, achievement, and the transformative power of education.' !!}</div>
        <div class="cinematic-hero__scroll-hint" aria-hidden="true">
            <span class="cinematic-hero__scroll-text">Scroll to explore</span>
            <span class="cinematic-hero__scroll-arrow" data-lucide="chevron-down"></span>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     2. GALLERY + LIGHTBOX
═══════════════════════════════════════════ --}}
<section class="gallery section-wrapper" id="gallery">
    <div class="container">

        <div class="section-heading-block">
            <span class="section-label">{{ $mediaGalleryPage->photos_label ?? 'GALLERY' }}</span>
            <h2 class="section-heading">
                {{ $mediaGalleryPage->photos_heading ?? 'Moments' }} <em>Captured</em>
            </h2>
            <p class="section-subheading">
                {{ $mediaGalleryPage->photos_subheading ?? 'A living collection of our community — step into the full-screen lightbox.' }}
            </p>
        </div>

        @if($photoCount > 0)
            <div class="gallery__masonry" id="galleryMasonry" data-masonry>
                @foreach($photos as $index => $photo)
                    @php $photoUrl = media_url($photo->image_url ?? null); @endphp
                    @if(! $photoUrl) @continue @endif
                    <figure class="gallery-item gallery-item--{{ $photo->size ?: 'medium' }}"
                            data-category="{{ $photo->category ?: 'all' }}"
                            data-src="{{ $photoUrl }}"
                            data-caption="{{ $photo->caption }}"
                            data-index="{{ $index }}"
                            tabindex="0"
                            role="button"
                            aria-label="{{ $photo->caption ?: 'Open photo' }}">
                        <img src="{{ $photoUrl }}"
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
            <span class="section-label">{{ $mediaGalleryPage->videos_label ?? 'WATCH' }}</span>
            <h2 class="section-heading">
                {{ $mediaGalleryPage->videos_heading ?? 'Featured' }} <em>Videos</em>
            </h2>
            <p class="section-subheading">
                {{ $mediaGalleryPage->videos_subheading ?? 'Relive our proudest moments through the lens — from graduation days to executive forums.' }}
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
    <script src="{{ cached_asset('assets/js/pages/media-gallery.js') }}" defer></script>
@endpush
