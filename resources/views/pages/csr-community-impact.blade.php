@extends('layouts.app')

@section('title', 'CSR & Community Impact | Maverick Business Academy London')
@section('meta_description', 'Completely redesigned CSR & Community Impact page of Maverick Business Academy London — Creating Positive Impact Through Education, Community Engagement, and Social Responsibility.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/csr-community-impact.css') }}" />
@endpush

@section('content')
<div class="csr-page">

    {{-- Abstract ambient/decorative ambient SVGs & washes in navy/red tints --}}
    <div class="csr-decorative-blob csr-decorative-blob--1"></div>
    <div class="csr-decorative-blob csr-decorative-blob--2"></div>
    <div class="csr-decorative-blob csr-decorative-blob--3"></div>

    {{-- ==========================================
         PAGE BANNER (HERO)
         ========================================== --}}
    <section class="accred-hero" style="background-image: url('https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&q=80&w=1920')">
        <div class="accred-hero__overlay"></div>
        <div class="container accred-hero__content">
            <span class="accred-hero__tag">CSR & Community Impact</span>
            <h1 class="accred-hero__heading">
                CSR & Community Impact
            </h1>
            <p class="accred-hero__description">Creating Positive Impact Through Education, Community Engagement, and Social Responsibility.</p>
        </div>
    </section>
    {{-- ==========================================
         SECTION 1: OUR COMMITMENT
         ========================================== --}}
    <section class="csr-commitment section--light">
        <div class="container">
            <div class="csr-commitment__grid">
                <div class="csr-commitment__content">
                    <div class="section-label"><span>Our Values</span></div>
                    <h2 class="csr-section-heading">Our <span class="csr-text-accent">Commitment</span></h2>
                    <p class="csr-body-text">
                         At Maverick Business Academy, we believe education extends beyond classrooms. Through our CSR initiatives, we actively contribute to community development, educational accessibility, professional growth, and social wellbeing.
                    </p>    
                </div>
                <div class="csr-commitment__visual">
                    <div class="csr-commitment__image-container">
                        <img src="https://images.pexels.com/photos/6565755/pexels-photo-6565755.jpeg" alt="Students and educators community engagement" class="csr-commitment__img" loading="lazy">
                        <div class="csr-decorative-pattern"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 2: CSR FOCUS AREAS (Icon Cards)
         ========================================== --}}
    <section class="csr-focus section--light">
        <div class="container">
            <div class="csr-section-header">
            <div class="section-label"><span>Pillars</span></div>
                <h2 class="csr-section-heading">CSR Focus <span class="csr-text-accent">Areas</span></h2>
            </div>

            <div class="csr-focus__grid">
                @foreach($focusAreas as $card)
                <div class="csr-focus-card">
                    <div class="csr-focus-card__icon-wrapper">
                        <span class="csr-focus-card__icon" data-lucide="{{ $card['icon'] }}"></span>
                    </div>
                    <h3 class="csr-focus-card__title">{{ $card['title'] }}</h3>
                    <ul class="csr-focus-card__list">
                        @foreach($card['activities'] as $activity)
                        <li class="csr-focus-card__item">
                            <span class="csr-focus-card__item-dot"></span>
                            {{ $activity }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 3: CSR ACTIVITIES GALLERY (⭐ MAIN SECTION)
         ========================================== --}}
    <section class="csr-gallery section--light">
        <div class="container">
            <div class="csr-section-header">
            <div class="section-label"><span>Our Impact In Action</span></div>
                <h2 class="csr-section-heading">CSR <span class="csr-text-accent">Activities</span></h2>
            </div>

            <div class="csr-gallery__grid">
                @foreach($galleryActivities as $index => $item)
                @php
                    // Create an asymmetrical layout sequence
                    // Card 0, 3: large (span 7) | Card 1, 2: medium (span 5)
                    $cardClass = ($index % 4 === 0 || $index % 4 === 3) ? 'csr-gallery-card--large' : 'csr-gallery-card--medium';
                @endphp
                <div class="csr-gallery-card {{ $cardClass }}">
                    <div class="csr-gallery-card__image-wrapper">
                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="csr-gallery-card__image" loading="lazy">
                    </div>
                    <div class="csr-gallery-card__content">
                        <h3 class="csr-gallery-card__title">{{ $item['title'] }}</h3>
                        <p class="csr-gallery-card__desc">{{ $item['description'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 4: IMPACT NUMBERS (Counters)
         ========================================== --}}
    @if($impactNumbers && count($impactNumbers) > 0)
    <section class="csr-impact section--dark">
        <div class="csr-impact__pattern"></div>
        <div class="container">
            <div class="csr-impact__grid">
                @foreach($impactNumbers as $counter)
                <div class="csr-impact-card">
                    <div class="csr-impact-card__number-wrapper">
                        <span class="csr-impact-card__number" data-target="{{ $counter['value'] }}">0</span><span class="csr-impact-card__suffix">{{ $counter['suffix'] }}</span>
                    </div>
                    <div class="csr-impact-card__divider"></div>
                    <div class="csr-impact-card__label">{{ $counter['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    {{-- ==========================================
         SECTION 5: SCHOLARSHIP & EDUCATIONAL SUPPORT
         ========================================== --}}
    <section class="csr-scholarship section--light">
        <div class="container">
            <div class="csr-scholarship__split">
                <div class="csr-scholarship__intro">
                <div class="section-label"><span>Scholarship & Educational Support</span></div>
                    <h2 class="csr-section-heading">Educational Access  <span class="csr-text-accent">& Scholarships</span></h2>
                    <p class="csr-body-text">
                    Maverick supports deserving learners through scholarship opportunities, flexible learning pathways, and professional development initiatives that help individuals achieve their educational goals.
                    </p>
                </div>

                <div class="csr-scholarship__checklist">
                    <div class="csr-checklist-grid">
                        @foreach($scholarshipActivities as $item)
                        <div class="csr-checklist-card">
                            <div class="csr-checklist-card__icon-wrapper">
                                <span class="csr-checklist-card__icon" data-lucide="check"></span>
                            </div>
                            <span class="csr-checklist-card__text">{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         FINAL CTA
         ========================================== --}}
    @include('sections.final-cta')

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/pages/csr-community-impact.js') }}" defer></script>
@endpush
