@extends('layouts.app')

@section('title', ($accreditationsSeo->meta_title ?? 'Accreditations & Recognitions - Maverick Business Academy'))
@section('meta_description', ($accreditationsSeo->meta_description ?? 'Explore Maverick Business Academy\'s accreditations, partnerships with leading universities, and industry recognition awards.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $accreditationsSeo])
@endpush

@if(!empty($accreditationsSeo->custom_body_scripts))
@push('scripts')
    {!! $accreditationsSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('css/pages/accreditations.css') }}">
    <link rel="stylesheet" href="{{ cached_asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
    @php
        $awardLogos = collect($awardLogos ?? []);
        $accreditationLogos = collect($accreditationLogos ?? []);
        $accreditationsPage = $accreditationsPage ?? safe_settings(\App\Settings\AccreditationsPageSettings::class);
        $accreditationsSeo = $accreditationsSeo ?? safe_settings(\App\Settings\AccreditationsSeoSettings::class);
    @endphp
    <div class="page-accreditations accred">

        {{-- ═══════════════════════════════════════════
            HERO SECTION (Cinematic Design)
        ═══════════════════════════════════════════ --}}
        <section class="cinematic-hero" aria-label="Accreditations Hero">
            <div class="cinematic-hero__bg" aria-hidden="true">
                @php $accredHeroBg = settings_media_url($accreditationsPage, 'hero_background_image') ?: 'https://images.pexels.com/photos/267885/pexels-photo-267885.jpeg?auto=compress&cs=tinysrgb&w=1920'; @endphp
                <div class="cinematic-hero__bg-image" @if($accredHeroBg) style="background-image: url('{{ $accredHeroBg }}')" @endif></div>
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
                    {{ $accreditationsPage->hero_tag ?? 'ACCREDITATIONS & RECOGNITIONS' }}
                </span>
                <h1 class="cinematic-hero__title">
                    {{ $accreditationsPage->hero_heading_line1 ?? 'Globally Recognised,' }}<br>
                    @if($accreditationsPage->hero_heading_italic)
                        <em>{{ $accreditationsPage->hero_heading_italic }}</em>
                    @else
                        <em>Locally Trusted</em>
                    @endif
                </h1>
                <div class="cinematic-hero__description">
                    {!! html_filled($accreditationsPage->hero_description ?? null) ? rich_html($accreditationsPage->hero_description ?? null) : 'Our commitment to excellence is validated by the world\'s most respected accreditation bodies, regulatory authorities, and industry partners. Every credential represents our dedication to quality.' !!}
                </div>
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
            <span class="section-label"><span>{{ $accreditationsPage->credentials_label ?? 'Our Credentials' }}</span></span>
            <h2 class="section-title">
                {{ $accreditationsPage->credentials_heading ?? 'Accreditations' }}
                @if($accreditationsPage->credentials_heading_span)
                    <span>{{ $accreditationsPage->credentials_heading_span }}</span>
                @else
                    <span>& Recognition</span>
                @endif
            </h2>
            <p class="accreditations__subtitle">
                {{ $accreditationsPage->credentials_subtitle ?? 'We partner with leading universities and hold accreditations from globally respected bodies.' }}
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
                        @foreach(($accreditationLogos ?? collect()) as $logo)
                        <div class="accreditations__card" data-card>
                            <div class="accreditations__card-logo">
                                @if($url = media_url($logo->logo_url ?? null))
                                    <img src="{{ $url }}" alt="{{ $logo->name }}" loading="lazy">
                                @else
                                    <span>{{ strtoupper(substr($logo->name ?? '', 0, 3)) }}</span>
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
        @if($url = settings_media_url($cinematicSettings, 'image_url'))
        <section class="accred-cinematic" data-cinematic-pin aria-hidden="true">
            <div class="accred-cinematic__inner">
                <div class="accred-cinematic__bg">
                    <img src="{{ $url }}" 
                        alt="Cinematic Background" 
                        class="accred-cinematic__image"
                        data-cinematic-image
                        loading="lazy" decoding="async">
                    <div class="accred-cinematic__overlay"></div>
                </div>
                <div class="accred-cinematic__content" data-cinematic-content>
                    @if($cinematicSettings->heading)
                    <h2 class="accred-cinematic__heading">
                        {!! $cinematicSettings->heading !!}
                    </h2>
                    @endif
                    @if(html_filled($cinematicSettings->text ?? null))
                    <div class="accred-cinematic__text">
                        {!! rich_html($cinematicSettings->text ?? null) !!}
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
            <span class="section-label"><span>{{ $accreditationsPage->awards_label ?? 'Achievements' }}</span></span>
            <h2 class="section-title">
                {{ $accreditationsPage->awards_heading ?? 'Awards' }}
                @if($accreditationsPage->awards_heading_span)
                    <span>{{ $accreditationsPage->awards_heading_span }}</span>
                @else
                    <span>& Achievements</span>
                @endif
            </h2>
            <p class="awards__subtitle">
                {{ $accreditationsPage->awards_subtitle ?? 'Our commitment to excellence has been recognised by leading education bodies worldwide.' }}
            </p>
        </div>

                {{-- Awards Grid — Flat 2.0 + Micro-animations --}}
                @if($awardLogos->count())
                <div class="award-grid">
                    @foreach($awardLogos as $logo)
                    <article class="award-card" data-reveal>
                        <div class="award-card__media">
                            @if($url = media_url($logo->logo_url ?? null))
                                <img src="{{ $url }}" alt="{{ $logo->name }}" loading="lazy" class="award-card__img">
                            @else
                                <span class="award-card__placeholder">{{ strtoupper(substr($logo->name ?? '', 0, 3)) }}</span>
                            @endif
                        </div>
                        <div class="award-card__body">
                            <span class="award-card__kicker">Award</span>
                            <h4 class="award-card__title">{{ $logo->name }}</h4>
                            @if($logo->description)
                                <div class="award-card__desc">{!! rich_html($logo->description ?? null) !!}</div>
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
            if (!track || carousel.dataset.carouselReady === 'true') return;
            carousel.dataset.carouselReady = 'true';

            const basePxPerSecond = 30;
            const extraScrollSeconds = 2;
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

            let isDragging = false;
            let isAutoSliding = !reducedMotion;
            let pointerId = null;
            let startX = 0;
            let originTranslate = 0;
            let currentTranslate = 0;
            let lastTime = 0;
            let animationId = 0;

            const loopWidth = () => {
                const width = track.scrollWidth / 3;
                return Number.isFinite(width) && width > 1 ? width : 0;
            };

            const wrap = (value) => {
                const width = loopWidth();
                if (!width) return 0;
                let next = value % width;
                if (next > 0) next -= width;
                return next;
            };

            const paint = () => {
                track.style.transform = `translate3d(${currentTranslate}px, 0, 0)`;
            };

            function frame(now) {
                if (!isAutoSliding || isDragging) return;
                if (!lastTime) {
                    lastTime = now;
                    animationId = requestAnimationFrame(frame);
                    return;
                }

                const delta = Math.min((now - lastTime) / 1000, 0.05);
                lastTime = now;
                const width = loopWidth();

                if (width) {
                    const duration = (width / basePxPerSecond) + extraScrollSeconds;
                    currentTranslate = wrap(currentTranslate - (width / duration) * delta);
                    paint();
                }

                animationId = requestAnimationFrame(frame);
            }

            function startAutoSlide() {
                if (reducedMotion || isDragging) return;
                isAutoSliding = true;
                lastTime = 0;
                cancelAnimationFrame(animationId);
                animationId = requestAnimationFrame(frame);
            }

            function stopAutoSlide() {
                isAutoSliding = false;
                lastTime = 0;
                cancelAnimationFrame(animationId);
            }

            function endDrag(event) {
                if (!isDragging || (event && event.pointerId !== pointerId)) return;
                isDragging = false;
                pointerId = null;
                carousel.classList.remove('is-dragging');
                if (carousel.hasPointerCapture(event.pointerId)) {
                    carousel.releasePointerCapture(event.pointerId);
                }
                startAutoSlide();
            }

            carousel.addEventListener('pointerdown', (event) => {
                if (event.pointerType === 'mouse' && event.button !== 0) return;
                isDragging = true;
                pointerId = event.pointerId;
                startX = event.clientX;
                originTranslate = currentTranslate;
                carousel.classList.add('is-dragging');
                carousel.setPointerCapture(event.pointerId);
                stopAutoSlide();
            });

            carousel.addEventListener('pointermove', (event) => {
                if (!isDragging || event.pointerId !== pointerId) return;
                const delta = event.clientX - startX;
                if (Math.abs(delta) > 4) event.preventDefault();
                currentTranslate = wrap(originTranslate + delta);
                paint();
            });

            carousel.addEventListener('pointerup', endDrag);
            carousel.addEventListener('pointercancel', endDrag);

            if (canHover) {
                carousel.addEventListener('pointerenter', (event) => {
                    if (event.pointerType === 'mouse') stopAutoSlide();
                });
                carousel.addEventListener('pointerleave', (event) => {
                    if (event.pointerType === 'mouse' && !isDragging) startAutoSlide();
                });
            }

            paint();
            startAutoSlide();
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
