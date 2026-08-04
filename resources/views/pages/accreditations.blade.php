@extends('layouts.app')

@section('title', 'Accreditations & Recognitions - Maverick Business Academy')
@section('meta_description', 'Explore Maverick Business Academy\'s accreditations, partnerships with leading universities, and industry recognition awards.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/accreditations.css') }}">
@endpush

@section('content')
<div class="page-accreditations accred">

{{-- ═══════════════════════════════════════════
     HERO SECTION (Matches Our Story Design)
═══════════════════════════════════════════ --}}
<section class="accred-hero" aria-label="Accreditations Hero">
    <div class="accred-hero__bg" aria-hidden="true">
        <div class="accred-hero__bg-image" style="background-image: url('https://images.pexels.com/photos/267885/pexels-photo-267885.jpeg?auto=compress&cs=tinysrgb&w=1920')"></div>
        <div class="accred-hero__gradient"></div>
        <div class="accred-hero__noise"></div>
        
        {{-- Floating Geometric Shapes --}}
        <div class="accred-hero__shapes">
            <svg class="accred-hero__shape accred-hero__shape--1" viewBox="0 0 200 200" fill="none">
                <circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.15)" stroke-width="1"/>
            </svg>
            <svg class="accred-hero__shape accred-hero__shape--2" viewBox="0 0 300 300" fill="none">
                <circle cx="150" cy="150" r="120" stroke="rgba(220,38,38,0.2)" stroke-width="1"/>
            </svg>
            <svg class="accred-hero__shape accred-hero__shape--3" viewBox="0 0 100 100" fill="none">
                <rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.15)" stroke-width="1" transform="rotate(20 50 50)"/>
            </svg>
        </div>
        
        {{-- Particles --}}
        <div class="accred-hero__particles">
            @for($i = 0; $i < 6; $i++)
                <div class="accred-hero__particle"></div>
            @endfor
        </div>
        
        {{-- Scanline --}}
        <div class="accred-hero__scanline"></div>
        
        {{-- Corner Brackets --}}
        <div class="accred-hero__corners">
            <div class="accred-hero__corner accred-hero__corner--tl"></div>
            <div class="accred-hero__corner accred-hero__corner--tr"></div>
            <div class="accred-hero__corner accred-hero__corner--bl"></div>
            <div class="accred-hero__corner accred-hero__corner--br"></div>
        </div>
    </div>
    
    <div class="container accred-hero__content">
        <span class="accred-hero__eyebrow fade-up">
            <span class="accred-hero__eyebrow-line"></span>
            ACCREDITATIONS & RECOGNITIONS
        </span>
        <h1 class="accred-hero__title fade-up">
            Globally Recognised,<br>
            <em>Locally Trusted</em>
        </h1>
        <p class="accred-hero__description fade-up">
            Our commitment to excellence is validated by the world's most respected accreditation bodies, 
            regulatory authorities, and industry partners.
        </p>
        <div class="accred-hero__scroll-hint fade-up" aria-hidden="true">
            <span class="accred-hero__scroll-text">Scroll to explore</span>
            <span class="accred-hero__scroll-arrow" data-lucide="chevron-down"></span>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 1: ACCREDITATIONS & PARTNERSHIPS
═══════════════════════════════════════════ --}}
<section class="accreditations section-wrapper section--light" aria-label="Accreditations">
    ...
</section>

{{-- ═══════════════════════════════════════════
     SECTION 2: CINEMATIC PINNED IMAGE
═══════════════════════════════════════════ --}}
<section class="accred-cinematic" data-cinematic-pin aria-hidden="true">
    <div class="accred-cinematic__inner">
        <div class="accred-cinematic__bg">
            <img src="https://images.pexels.com/photos/267885/pexels-photo-267885.jpeg?auto=compress&cs=tinysrgb&w=1920" 
                 alt="Cinematic Background" 
                 class="accred-cinematic__image"
                 data-cinematic-image>
            <div class="accred-cinematic__overlay"></div>
        </div>
        <div class="accred-cinematic__content" data-cinematic-content>
            <h2 class="accred-cinematic__heading">
                Uncompromising <em>Quality</em>,<br>
                Global <em>Excellence</em>
            </h2>
            <p class="accred-cinematic__text">
                Every partnership we forge and every accreditation we hold is a testament to our 
                unwavering commitment to providing world-class business education.
            </p>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     SECTION 3: AWARDS & RECOGNITION
═══════════════════════════════════════════ --}}
<section class="awards section-wrapper section--light" aria-label="Awards & Recognition">
    <div class="container">
        <div class="awards__header">
            <span class="section-label"><span>Achievements</span></span>
            <h2 class="section-title">Awards <span>& Recognition</span></h2>
            <p class="awards__subtitle">
                Our commitment to excellence has been recognised by leading education bodies worldwide.
            </p>
        </div>
    </div>

    {{-- Awards Slider --}}
    <div class="awards__carousel" data-awards-carousel>
        <div class="awards__carousel-track" data-awards-track>
            {{-- Duplicate for infinite loop --}}
            @for($r = 0; $r < 3; $r++)
                @foreach($awardLogos as $logo)
                <div class="awards__card" data-award-card>
                    <span class="awards__card-badge">{{ ucfirst($logo->type) }}</span>
                    <div class="awards__card-image">
                        @if($logo->logo_url)
                            <img src="{{ $logo->logo_url }}" alt="{{ $logo->name }}" loading="lazy" draggable="false">
                        @else
                            <div class="awards__card-placeholder">
                                <span>{{ strtoupper(substr($logo->name, 0, 3)) }}</span>
                            </div>
                        @endif
                    </div>
                    <h4 class="awards__card-title">{{ $logo->name }}</h4>
                </div>
                @endforeach
            @endfor
        </div>
    </div>
</section>

</div>

@include('sections.final-cta')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Draggable carousel functionality
        function initCarousel(carousel) {
            const track = carousel.querySelector('[data-carousel-track], [data-awards-track]');
            if (!track) return;

            let isDragging = false;
            let startX = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;
            let isAutoSliding = true;
            let animationId = null;

            const getTrackWidth = () => track.scrollWidth / 3;

            function autoSlide() {
                if (!isAutoSliding || isDragging) return;
                currentTranslate -= 0.5;
                if (Math.abs(currentTranslate) >= getTrackWidth()) {
                    currentTranslate = 0;
                }
                track.style.transform = `translateX(${currentTranslate}px)`;
                animationId = requestAnimationFrame(autoSlide);
            }

            function startAutoSlide() {
                isAutoSliding = true;
                autoSlide();
            }

            function stopAutoSlide() {
                isAutoSliding = false;
                if (animationId) cancelAnimationFrame(animationId);
            }

            // Mouse events
            carousel.addEventListener('mousedown', (e) => {
                isDragging = true;
                startX = e.pageX;
                prevTranslate = currentTranslate;
                stopAutoSlide();
            });

            carousel.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                e.preventDefault();
                currentTranslate = prevTranslate + (e.pageX - startX) * 1.5;
                track.style.transform = `translateX(${currentTranslate}px)`;
            });

            carousel.addEventListener('mouseup', () => {
                isDragging = false;
                startAutoSlide();
            });

            carousel.addEventListener('mouseleave', () => {
                if (isDragging) {
                    isDragging = false;
                    startAutoSlide();
                }
            });

            // Touch events
            carousel.addEventListener('touchstart', (e) => {
                isDragging = true;
                startX = e.touches[0].pageX;
                prevTranslate = currentTranslate;
                stopAutoSlide();
            }, { passive: true });

            carousel.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                currentTranslate = prevTranslate + (e.touches[0].pageX - startX) * 1.5;
                track.style.transform = `translateX(${currentTranslate}px)`;
            }, { passive: true });

            carousel.addEventListener('touchend', () => {
                isDragging = false;
                startAutoSlide();
            });

            // Hover pause
            carousel.addEventListener('mouseenter', stopAutoSlide);
            carousel.addEventListener('mouseleave', () => {
                if (!isDragging) startAutoSlide();
            });

            // Start auto-slide
            autoSlide();
        }

        // Initialize all carousels
        document.querySelectorAll('[data-carousel], [data-awards-carousel]').forEach(initCarousel);
    });
</script>
@endpush
