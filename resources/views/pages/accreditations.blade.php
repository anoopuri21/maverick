@extends('layouts.app')

@section('title', 'Accreditations & Recognitions - Maverick Business Academy')
@section('meta_description', 'Explore Maverick Business Academy\'s accreditations, partnerships with leading universities, and industry recognition awards.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/accreditations.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
    <div class="page-accreditations accred">

        {{-- ═══════════════════════════════════════════
            HERO SECTION (Cinematic Design)
        ═══════════════════════════════════════════ --}}
        <section class="cinematic-hero" aria-label="Accreditations Hero">
            <div class="cinematic-hero__bg" aria-hidden="true">
                <div class="cinematic-hero__bg-image" style="background-image: url('https://images.pexels.com/photos/267885/pexels-photo-267885.jpeg?auto=compress&cs=tinysrgb&w=1920')"></div>
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
                    ACCREDITATIONS & RECOGNITIONS
                </span>
                <h1 class="cinematic-hero__title">
                    Globally Recognised,<br>
                    <em>Locally Trusted</em>
                </h1>
                <p class="cinematic-hero__description">
                    Our commitment to excellence is validated by the world's most respected accreditation bodies, 
                    regulatory authorities, and industry partners. Every credential represents our dedication to quality.
                </p>
                <div class="cinematic-hero__scroll-hint" aria-hidden="true">
                    <span class="cinematic-hero__scroll-text">Scroll to explore</span>
                    <span class="cinematic-hero__scroll-arrow" data-lucide="chevron-down"></span>
                </div>
            </div>
        </section>


        {{-- ═══════════════════════════════════════════
            SECTION 1: ACCREDITATIONS & PARTNERSHIPS
        ═══════════════════════════════════════════ --}}
        <section class="accreditations section-wrapper section--light" aria-label="Accreditations">
            <div class="container">
                <div class="accreditations__header">
                    <span class="section-label"><span>Our Credentials</span></span>
                    <h2 class="section-title">Accreditations <span>& Partnerships</span></h2>
                    <p class="accreditations__subtitle">
                        We partner with leading universities and hold accreditations from globally respected bodies.
                    </p>
                </div>
            </div>

            {{-- Background Geometric Shapes --}}
            <div class="accreditations__bg-shapes" aria-hidden="true">
                <div class="accreditations__bg-shape accrediations__bg-shape--circle accrediations__bg-shape--1"></div>
                <div class="accreditations__bg-shape accrediations__bg-shape--circle accrediations__bg-shape--2"></div>
                <div class="accreditations__bg-shape accrediations__bg-shape--triangle accrediations__bg-shape--3"></div>
                <div class="accreditations__bg-shape accrediations__bg-shape--square accrediations__bg-shape--4"></div>
                <div class="accreditations__bg-shape accrediations__bg-shape--dot accrediations__bg-shape--5"></div>
                <div class="accreditations__bg-shape accrediations__bg-shape--dot accrediations__bg-shape--6"></div>
                <div class="accreditations__bg-shape accrediations__bg-shape--dot accrediations__bg-shape--7"></div>
            </div>

            {{-- Draggable Slider --}}
            <div class="accreditations__carousel" data-carousel>
                <div class="accreditations__carousel-track" data-carousel-track>
                    {{-- Duplicate for infinite loop --}}
                    @for($r = 0; $r < 3; $r++)
                        @foreach($accreditationLogos as $logo)
                        <div class="accreditations__card" data-card>
                            <div class="accreditations__card-logo">
                                @if($logo->logo_url)
                                    <img src="{{ $logo->logo_url }}" alt="{{ $logo->name }}" loading="lazy">
                                @else
                                    <span>{{ strtoupper(substr($logo->name, 0, 3)) }}</span>
                                @endif
                            </div>
                            <h4 class="accreditations__card-name">{{ $logo->name }}</h4>
                            <!-- <span class="accreditations__card-type">{{ ucfirst($logo->type) }}</span> -->
                        </div>
                        @endforeach
                    @endfor
                </div>
            </div>
        </section>

        {{-- ═══════════════════════════════════════════
            SECTION 2: CINEMATIC PINNED IMAGE
        ═══════════════════════════════════════════ --}}
        @if($cinematicSettings->image_url)
        <section class="accred-cinematic" data-cinematic-pin aria-hidden="true">
            <div class="accred-cinematic__inner">
                <div class="accred-cinematic__bg">
                    <img src="{{ $cinematicSettings->image_url }}" 
                        alt="Cinematic Background" 
                        class="accred-cinematic__image"
                        data-cinematic-image>
                    <div class="accred-cinematic__overlay"></div>
                </div>
                <div class="accred-cinematic__content" data-cinematic-content>
                    @if($cinematicSettings->heading)
                    <h2 class="accred-cinematic__heading">
                        {!! $cinematicSettings->heading !!}
                    </h2>
                    @endif
                    @if($cinematicSettings->text)
                    <div class="accred-cinematic__text">
                        {!! $cinematicSettings->text !!}
                    </div>
                    @endif
                </div>
            </div>
        </section>
        @endif

        {{-- ═══════════════════════════════════════════
            SECTION 3: AWARDS
        ═══════════════════════════════════════════ --}}
        <section class="awards section-wrapper section--light" aria-label="Awards">
            <div class="container">
                <div class="awards__header">
                    <span class="section-label"><span>Achievements</span></span>
                    <h2 class="section-title">Awards <span>& Recognition</span></h2>
                    <p class="awards__subtitle">
                        Our commitment to excellence has been recognised by leading education bodies worldwide.
                    </p>
                </div>

                {{-- Awards Grid — Flat 2.0 + Micro-animations --}}
                @if($awardLogos->count())
                <div class="award-grid">
                    @foreach($awardLogos as $logo)
                    <article class="award-card" data-reveal>
                        <div class="award-card__media">
                            @if($logo->logo_url)
                                <img src="{{ media_url($logo->logo_url) }}" alt="{{ $logo->name }}" loading="lazy" class="award-card__img">
                            @else
                                <span class="award-card__placeholder">{{ strtoupper(substr($logo->name, 0, 3)) }}</span>
                            @endif
                        </div>
                        <div class="award-card__body">
                            <span class="award-card__kicker">Award</span>
                            <h4 class="award-card__title">{{ $logo->name }}</h4>
                            @if($logo->description)
                                <p class="award-card__desc">{{ $logo->description }}</p>
                            @endif
                        </div>
                        <span class="award-card__accent" aria-hidden="true"></span>
                    </article>
                    @endforeach
                </div>
                @endif
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

        // Initialize accreditations carousel
        document.querySelectorAll('[data-carousel]').forEach(initCarousel);

        // Award cards reveal-on-scroll (micro-animation, reduced-motion safe)
        const awardReveals = document.querySelectorAll('.award-card[data-reveal]');
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (awardReveals.length && !prefersReduced && 'IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) { entry.target.classList.add('is-revealed'); io.unobserve(entry.target); }
                });
            }, { rootMargin: '0px 0px -8% 0px', threshold: 0.1 });
            awardReveals.forEach((el, i) => setTimeout(() => io.observe(el), i * 70));
        } else {
            awardReveals.forEach((el) => el.classList.add('is-revealed'));
        }
    });
</script>
@endpush
