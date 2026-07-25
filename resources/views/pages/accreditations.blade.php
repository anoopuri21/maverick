@extends('layouts.app')

@section('title', 'Accreditations & Recognition - Maverick Business Academy')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/accreditations.css') }}">
@endpush

@section('content')
<div class="page-accreditations accred">


@php
    // ═══════════════════════════════════════════
    // STATIC DATA (Future: Move to admin/database)
    // ═══════════════════════════════════════════

    $hero = (object)[
        'tag' => 'ACCREDITATIONS & RECOGNITION',
        'heading' => 'Globally Recognised,',
        'heading_italic' => 'Locally Trusted',
        'description' => "Our commitment to excellence is validated by the world's most respected accreditation bodies, regulatory authorities, and industry partners.",
        'background_image' => asset('assets/images/accreditations/hero-bg.jpg'),
    ];

    // Grouped by category
    $accreditationCategories = collect([
        (object)[
            'icon' => 'graduation-cap',
            'title' => 'University Partners',
            'items' => collect([
                (object)[
                    'code' => 'UOL',
                    'name' => 'University of London',
                    'description' => "Official academic partnership for undergraduate and postgraduate business programmes with one of the UK's most prestigious institutions.",
                ],
                (object)[
                    'code' => 'UON',
                    'name' => 'University of Northampton',
                    'description' => 'Validated degree pathways and articulation agreements for seamless progression to full honours degrees.',
                ],
                (object)[
                    'code' => 'ARU',
                    'name' => 'Anglia Ruskin University',
                    'description' => 'Joint delivery of executive education programmes and professional development certificates.',
                ],
                (object)[
                    'code' => 'NCFE',
                    'name' => 'NCFE (Northern Council for Further Education)',
                    'description' => 'Approved for delivery of NCFE CACHE qualifications in business and professional development.',
                ],
            ]),
        ],
    ]);

    $qualityAssurance = (object)[
        'tag' => 'QUALITY ASSURANCE',
        'heading' => 'Our Commitment to',
        'heading_italic' => 'Excellence',
        'description' => 'Every programme at Maverick Academy undergoes rigorous quality assurance processes to ensure our learners receive education that meets the highest global standards.',
        'image' => asset('assets/images/accreditations/quality-image.jpg'),
        'features' => collect([
            (object)[
                'icon' => 'clipboard-list',
                'title' => 'Curriculum Design',
                'description' => 'Programmes designed in collaboration with industry experts and academic advisors to ensure relevance and rigour.',
            ],
            (object)[
                'icon' => 'users',
                'title' => 'Faculty Vetting',
                'description' => 'All tutors undergo thorough qualification verification and continuous professional development.',
            ],
            (object)[
                'icon' => 'bar-chart',
                'title' => 'Continuous Assessment',
                'description' => 'Regular internal audits, student feedback analysis, and external examiner reviews.',
            ],
            (object)[
                'icon' => 'award',
                'title' => 'External Validation',
                'description' => 'Annual reviews by accreditation bodies and regulatory authorities to maintain standards.',
            ],
            (object)[
                'icon' => 'refresh-cw',
                'title' => 'Improvement Cycle',
                'description' => 'Systematic feedback loops ensure continuous enhancement of teaching and learning.',
            ],
            (object)[
                'icon' => 'shield-check',
                'title' => 'Compliance Monitoring',
                'description' => 'Dedicated compliance team ensuring adherence to all regulatory requirements.',
            ],
        ]),
    ];

    $awards = collect([
        (object)[
            'title' => 'Best Emerging Business School 2024',
            'subtitle' => 'Education Today Awards',
            'image_url' => asset('assets/images/accreditations/awards/award-1.jpg'),
        ],
        (object)[
            'title' => 'Excellence in Online Learning 2023',
            'subtitle' => 'EdTech Breakthrough',
            'image_url' => asset('assets/images/accreditations/awards/award-2.jpg'),
        ],
        (object)[
            'title' => 'Innovation in Executive Education 2023',
            'subtitle' => 'British Education Awards',
            'image_url' => asset('assets/images/accreditations/awards/award-3.jpg'),
        ],
        (object)[
            'title' => 'Top 50 Global Online MBA',
            'subtitle' => 'QS World',
            'image_url' => asset('assets/images/accreditations/awards/award-4.jpg'),
        ],
    ]);

    $mediaRankings = collect([
        (object)['code' => 'FT', 'name' => 'Financial Times', 'rank' => 'Top 100 European Business Schools', 'year' => '2024'],
        (object)['code' => 'ECO', 'name' => 'The Economist', 'rank' => 'Best Online MBA Programmes', 'year' => '2024'],
        (object)['code' => 'FOR', 'name' => 'Forbes', 'rank' => 'Most Innovative Education Provider', 'year' => '2023'],
        (object)['code' => 'TG', 'name' => 'The Guardian', 'rank' => 'Top UK Business Qualifications', 'year' => '2024'],
        (object)['code' => 'THE', 'name' => 'Times Higher Education', 'rank' => 'Emerging Excellence in HE', 'year' => '2023'],
        (object)['code' => 'BB', 'name' => 'Bloomberg', 'rank' => 'Rising Stars in Business Education', 'year' => '2024'],
    ]);
@endphp


{{-- ═══════════════════════════════════════════
     1. HERO SECTION
═══════════════════════════════════════════ --}}
<section class="accred-hero" style="background-image: url('{{ $hero->background_image }}');">
    <div class="accred-hero__overlay"></div>
    <div class="container accred-hero__content">
        <span class="accred-hero__tag">{{ $hero->tag }}</span>
        <h1 class="accred-hero__heading">
            {{ $hero->heading }}
            <em class="accred-hero__heading-italic">{{ $hero->heading_italic }}</em>
        </h1>
        <p class="accred-hero__description">{{ $hero->description }}</p>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     2. ACCREDITATIONS & PARTNERSHIPS
═══════════════════════════════════════════ --}}
<section class="accreditations section-wrapper">
    <div class="container">
        
        <div class="section-heading-block">
            <span class="section-label">OUR CREDENTIALS</span>
            <h2 class="section-heading">
                Accreditations <em>& Partnerships</em>
            </h2>
            <p class="section-subheading">
                We partner with leading universities and hold accreditations from globally respected awarding bodies.
            </p>
        </div>

        @foreach($accreditationCategories as $category)
        <div class="accreditations__category">
            
            <div class="category-header">
                <div class="category-header__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <h3 class="category-header__title">{{ $category->title }}</h3>
            </div>

            <div class="accreditations__grid">
                @foreach($category->items as $item)
                <article class="accred-card">
                    <div class="accred-card__logo">
                        <span>{{ $item->code }}</span>
                    </div>
                    <h4 class="accred-card__name">{{ $item->name }}</h4>
                    <p class="accred-card__description">{{ $item->description }}</p>
                    <div class="accred-card__badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>VERIFIED PARTNER</span>
                    </div>
                </article>
                @endforeach
            </div>

        </div>
        @endforeach

    </div>
</section>


{{-- ═══════════════════════════════════════════
     3. QUALITY ASSURANCE FRAMEWORK
═══════════════════════════════════════════ --}}
<section class="quality section-wrapper section--light">
    <div class="container">
        <div class="quality__grid">
            
            {{-- Left: Content --}}
            <div class="quality__content">
                <span class="section-label quality__label">{{ $qualityAssurance->tag }}</span>
                <h2 class="quality__heading">
                    {{ $qualityAssurance->heading }}
                    <em>{{ $qualityAssurance->heading_italic }}</em>
                </h2>
                <p class="quality__description">{{ $qualityAssurance->description }}</p>

                <div class="quality__features">
                    @foreach($qualityAssurance->features as $feature)
                    <div class="quality-feature">
                        <div class="quality-feature__icon">
                            <span data-lucide="{{ $feature->icon }}"></span>
                        </div>
                        <div class="quality-feature__content">
                            <h4 class="quality-feature__title">{{ $feature->title }}</h4>
                            <p class="quality-feature__description">{{ $feature->description }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Right: Image --}}
            <div class="quality__image-wrapper">
                <img src="{{ $qualityAssurance->image }}" 
                     alt="Quality Assurance" 
                     class="quality__image"
                     loading="lazy">
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     4. AWARDS & RECOGNITION (Slider)
═══════════════════════════════════════════ --}}
<section id="awards" class="awards testimonials section-wrapper section--light" aria-label="Awards & Recognition">
    <div class="container testimonials__inner">
        
        <div class="section-heading-block">
            <span class="section-label">ACHIEVEMENTS</span>
            <h2 class="section-heading">
                Awards <em>& Recognition</em>
            </h2>
            <p class="section-subheading">
                Our commitment to excellence has been recognised by leading education bodies worldwide.
            </p>
        </div>

        <div class="scroll-row scroll-row--light" data-scroll-row>
            <button class="scroll-row__btn scroll-row__btn--prev" aria-label="Scroll left" data-scroll-prev>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>
            <div class="testimonials__scroll" data-scroll-container data-lenis-prevent>
                <div class="testimonials__track">
                    @foreach($awards as $award)
                    <article class="testimonials__card">
                        <div class="testimonials__card-thumb">
                            <img src="{{ $award->image_url }}" 
                                 alt="{{ $award->title }}" 
                                 loading="lazy" 
                                 decoding="async" />
                        </div>
                        <h3 class="awards__card-title">{{ $award->title }}</h3>
                        <p class="awards__card-subtitle">{{ $award->subtitle }}</p>
                    </article>
                    @endforeach
                </div>
            </div>
            <button class="scroll-row__btn scroll-row__btn--next" aria-label="Scroll right" data-scroll-next>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     5. MEDIA & RANKINGS (Dark Section)
═══════════════════════════════════════════ --}}
<section class="media-rankings">
    <div class="container">
        
        <div class="section-heading-block media-rankings__header">
            <span class="section-label media-rankings__label">AS FEATURED IN</span>
            <h2 class="media-rankings__heading">
                Media <em>& Rankings</em>
            </h2>
            <p class="media-rankings__subheading">
                Recognised by the world's leading business and education publications.
            </p>
        </div>

        <div class="media-rankings__grid">
            @foreach($mediaRankings as $media)
            <article class="media-card">
                <div class="media-card__logo">
                    <span>{{ $media->code }}</span>
                </div>
                <h4 class="media-card__name">{{ $media->name }}</h4>
                <p class="media-card__rank">{{ $media->rank }}</p>
                <span class="media-card__year">{{ $media->year }}</span>
            </article>
            @endforeach
        </div>

    </div>
</section>
</div>

    @include('sections.final-cta')

@endsection