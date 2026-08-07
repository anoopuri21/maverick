@extends('layouts.app')

@section('title', 'Global University Partners - Maverick Business Academy')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/global-university-partners.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
<div class="page-gup gup">


@php
    // ═══════════════════════════════════════════
    // STATIC DATA (Future: Move to admin/database)
    // ═══════════════════════════════════════════

    $hero = (object)[
        'tag' => 'GLOBAL PARTNERSHIPS',
        'heading_line1' => 'Global',
        'heading_italic' => 'University Partners',
        'description' => 'Maverick Business Academy collaborates with internationally recognized universities and educational institutions across five continents, creating academic bridges that connect ambitious learners with globally respected qualifications, cutting-edge research opportunities, and transformative career pathways that transcend geographical boundaries.',
        'background_image' => 'https://images.pexels.com/photos/5725589/pexels-photo-5725589.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600',
    ];

    $overview = (object)[
        'tag' => 'GLOBAL PARTNERSHIPS',
        'heading' => 'Building Global Pathways Through ',
        'heading_italic' => 'Strategic Academic Partnerships',
        'paragraphs' => [
            'Maverick Business Academy collaborates with internationally recognized universities and educational institutions to provide learners with access to globally respected qualifications, flexible learning opportunities, and career-focused academic pathways',
        ],
        'image' => asset('https://res.cloudinary.com/i08gwudw/image/upload/v1785340343/maverick-academy/our-story/timeline/qpg8khpl9f0tg6xzyhz5.jpg'),
    ];

    $whyPartnerships = (object)[
        'tag' => 'OUR VALUE',
        'heading' => 'Why Our Partnerships',
        'heading_italic' => 'Matter',
        'quote' => 'Every partnership we sign is measured against one question — does it open a new door for our students?',
        'items' => collect([
            (object)[
                'icon' => 'graduation-cap',
                'title' => 'Internationally Recognized Qualifications',
                'description' => 'Access degrees and certifications respected by employers and institutions worldwide.',
            ],
            (object)[
                'icon' => 'globe',
                'title' => 'Global Learning Opportunities',
                'description' => 'Study across borders through partner campuses and international exchange programs.',
            ],
            (object)[
                'icon' => 'book-open',
                'title' => 'Flexible Study Pathways',
                'description' => 'Choose from full-time, part-time, online, and hybrid learning formats.',
            ],
            (object)[
                'icon' => 'award',
                'title' => 'Academic Excellence',
                'description' => 'Learn from world-class faculty and follow curricula aligned with global standards.',
            ],
            (object)[
                'icon' => 'rocket',
                'title' => 'Career Advancement',
                'description' => 'Unlock opportunities with qualifications that accelerate professional growth.',
            ],
        ]),
    ];

    $benefits = (object)[
        'tag' => 'Student Benefits',
        'heading' => 'Benefits of Studying Through ',
        'heading_italic' => 'Maverick Partnerships',
        'main_image' => asset('https://res.cloudinary.com/i08gwudw/image/upload/v1784534077/maverick-academy/programs/igxpmziapl3v5xaqozki.jpg'),
        'secondary_image' => asset('https://res.cloudinary.com/i08gwudw/image/upload/v1784441348/mba_sa4pmo.jpg'),
        'floating_stat' => (object)['number' => '30k+', 'label' => 'GLOBAL ALUMNI'],
        'items' => collect([
            (object)[
                'title' => 'Access to internationally recognized qualifications ',
                'description' => 'Degrees and certifications valued by employers across the globe.',
            ],
            (object)[
                'title' => 'Flexible Learning Formats',
                'description' => 'Full-time, part-time, online and hybrid — learning that fits your life.',
            ],
            (object)[
                'title' => 'Global Alumni Networks',
                'description' => 'Join 30,000+ graduates building careers in over 50 countries.',
                'highlighted' => true,
            ],
            (object)[
                'title' => 'Industry-Relevant Curriculum',
                'description' => 'Programmes shaped with employers and updated for the real world of work.',
            ],
            (object)[
                'title' => 'Career Progression Opportunities',
                'description' => 'Qualifications designed to accelerate your professional growth.',
            ],
            (object)[
                'title' => 'Diverse International Learning Environment ',
                'description' => 'Learn alongside peers from every corner of the world.',
            ],
        ]),
    ];

    $partnerUniversities = collect([
        (object)[
            'slug' => 'gloucestershire',
            'name' => 'University of Gloucestershire',
            'abbreviation' => 'UoG',
            'country' => 'United Kingdom',
            'flag' => '🇬🇧',
            'recognition' => 'A renowned UK university with a strong reputation for business, leadership, and professional education.',
            'logo' => asset('assets/images/universities/uog-logo.png'),
            'cta_url' => url('/#featured-programs'),
        ],
        (object)[
            'slug' => 'uca',
            'name' => 'University of the Creative Arts',
            'abbreviation' => 'UCA',
            'country' => 'United Kingdom',
            'flag' => '🇬🇧',
            'recognition' => 'One of the UK\'s leading specialist universities for business, creativity, and innovation.',
            'logo' => asset('assets/images/universities/uca-logo.png'),
            'cta_url' => url('/#featured-programs'),
        ],
        (object)[
            'slug' => 'gau',
            'name' => 'Girne American University',
            'abbreviation' => 'GAU',
            'country' => 'North Cyprus',
            'flag' => '🇨🇾',
            'recognition' => 'Internationally recognized university offering undergraduate, postgraduate, and doctoral programs.',
            'logo' => asset('assets/images/universities/gau-logo.png'),
            'cta_url' => url('/#featured-programs'),
        ],
        (object)[
            'slug' => 'rbs',
            'name' => 'Rushford Business School',
            'abbreviation' => 'RBS',
            'country' => 'Switzerland',
            'flag' => '🇨🇭',
            'recognition' => 'International business school focused on management, leadership, and executive education.',
            'logo' => asset('assets/images/universities/rbs-logo.png'),
            'cta_url' => url('/#featured-programs'),
        ],
        (object)[
            'slug' => 'charisma',
            'name' => 'Charisma University',
            'abbreviation' => 'CU',
            'country' => 'Turks & Caicos Islands',
            'flag' => '🇹🇨',
            'recognition' => 'International institution offering business, education, health sciences, and doctoral qualifications.',
            'logo' => asset('assets/images/universities/charisma-logo.png'),
            'cta_url' => url('/#featured-programs'),
        ],
    ]);

    $galleryCategories = collect([
        (object)['slug' => 'all', 'name' => 'All', 'count' => 11],
        (object)['slug' => 'mou-signings', 'name' => 'MOU Signings', 'count' => 3],
        (object)['slug' => 'graduations', 'name' => 'Graduations', 'count' => 2],
        (object)['slug' => 'university-visits', 'name' => 'University Visits', 'count' => 2],
        (object)['slug' => 'conferences', 'name' => 'Conferences', 'count' => 2],
        (object)['slug' => 'forums', 'name' => 'Forums', 'count' => 2],
    ]);

    $galleryItems = collect([
        (object)[
            'category' => 'mou-signings',
            'badge' => 'Forum',
            'date' => '14 Feb 2025',
            'title' => null,
            'caption' => 'Cross-Border Learning Roundtable',
            'image' => asset('https://images.pexels.com/photos/11299318/pexels-photo-11299318.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=940'),
            'size' => 'medium',
        ],
        (object)[
            'category' => 'mou-signings',
            'badge' => 'MOU SIGNING',
            'date' => '28 Oct 2024',
            'title' => null,
            'caption' => null,
            'image' => asset('https://images.pexels.com/photos/7433919/pexels-photo-7433919.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=940'),
            'size' => 'tall',
        ],
        (object)[
            'category' => 'university-visits',
            'badge' => 'UNIVERSITY VISIT',
            'date' => '03 Jun 2024',
            'title' => 'Partner Campus Tour — Toronto',
            'caption' => 'Scoping new articulation routes across North America.',
            'image' => asset('https://images.pexels.com/photos/27238168/pexels-photo-27238168.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=700'),
            'size' => 'tall',
        ],
        (object)[
            'category' => 'graduations',
            'badge' => 'Conference',
            'date' => '18 Dec 2024',
            'title' => 'Global Education Summit London',
            'caption' => null,
            'image' => asset('https://images.pexels.com/photos/2774556/pexels-photo-2774556.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=940'),
            'size' => 'tall',
        ],
        (object)[
            'category' => 'mou-signings',
            'badge' => 'MOU SIGNING',
            'date' => '17 May 2024',
            'title' => 'Kuala Lumpur Academic Alliance',
            'caption' => null,
            'image' => asset('https://images.pexels.com/photos/17682883/pexels-photo-17682883.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=800&w=800'),
            'size' => 'medium',
        ],
        (object)[
            'category' => 'graduations',
            'badge' => 'GRADUATION',
            'date' => '18 Dec 2024',
            'title' => 'Class of 2024 — Caps in the Air',
            'caption' => null,
            'image' => asset('https://images.pexels.com/photos/31290544/pexels-photo-31290544.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=940'),
            'size' => 'medium',
        ],
        (object)[
            'category' => 'conferences',
            'badge' => 'CONFERENCE',
            'date' => '05 Nov 2024',
            'title' => 'UK–Oman Digital Connectivity Forum',
            'caption' => null,
            'image' => asset('https://images.pexels.com/photos/29335353/pexels-photo-29335353.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=940'),
            'size' => 'medium',
        ],
        (object)[
            'category' => 'forums',
            'badge' => 'FORUM',
            'date' => '21 Mar 2024',
            'title' => 'International Education Forum',
            'caption' => null,
            'image' => asset('https://images.pexels.com/photos/7640781/pexels-photo-7640781.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=800&w=800'),
            'size' => 'wide',
        ],
    ]);
@endphp


{{-- ═══════════════════════════════════════════
     1. HERO SECTION (Cinematic Design)
═══════════════════════════════════════════ --}}
<section class="cinematic-hero" aria-label="Global University Partners Hero">
    <div class="cinematic-hero__bg" aria-hidden="true">
        <div class="cinematic-hero__bg-image" style="background-image: url('{{ $hero->background_image }}')"></div>
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
            {{ $hero->tag }}
        </span>
        <h1 class="cinematic-hero__title">
            {{ $hero->heading_line1 }}<br>
            <em>{{ $hero->heading_italic }}</em>
        </h1>
        <p class="cinematic-hero__description">{{ $hero->description }}</p>
        <div class="cinematic-hero__scroll-hint" aria-hidden="true">
            <span class="cinematic-hero__scroll-text">Scroll to explore</span>
            <span class="cinematic-hero__scroll-arrow" data-lucide="chevron-down"></span>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     2. PARTNERSHIP OVERVIEW (Split with Stats)
═══════════════════════════════════════════ --}}
<section class="gup-overview section-wrapper">
    <div class="container">
        <div class="gup-overview__grid">

            {{-- Right: Content + Image --}}
            <div class="gup-overview__main">
                <span class="section-label gup-overview__label">{{ $overview->tag }}</span>
                <h2 class="gup-overview__heading">
                    {{ $overview->heading }}
                    <em>{{ $overview->heading_italic }}</em>
                </h2>
                @foreach($overview->paragraphs as $paragraph)
                <p class="gup-overview__paragraph">{{ $paragraph }}</p>
                @endforeach

                <div class="gup-overview__image-wrapper">
                    <img src="{{ $overview->image }}" 
                         alt="Partnership" 
                         class="gup-overview__image"
                         loading="lazy">
                </div>
            </div>

        </div>
    </div>
</section>

@include('sections.university-partners')


{{-- ═══════════════════════════════════════════
     PARTNER UNIVERSITIES — 3D Cards with Aura
═══════════════════════════════════════════ --}}
<section class="gup-partner-cards section-wrapper" data-testid="gup-partner-cards" aria-label="Partner Universities">
    <div class="container">

        <div class="section-heading-block">
            <span class="section-label"><span>PARTNER UNIVERSITIES</span></span>
            <h2 class="section-heading">
                Our Global <em>Academic Network</em>
            </h2>
            <p class="section-subheading">
                Maverick Business Academy partners with world-class universities across Europe and beyond, offering students internationally recognised pathways to academic and career success.
            </p>
        </div>

        <div class="gup-partner-cards__grid">
            @foreach($partnerUniversities as $uni)
            <article class="gup-uni-card" data-testid="uni-card-{{ $uni->slug }}">
                <div class="gup-uni-card__aura" aria-hidden="true"></div>
                <div class="gup-uni-card__border" aria-hidden="true"></div>
                <div class="gup-uni-card__inner">
                    <div class="gup-uni-card__logo-wrap">
                        <img
                            src="{{ $uni->logo }}"
                            alt="{{ $uni->name }} logo"
                            class="gup-uni-card__logo"
                            loading="lazy"
                            width="120"
                            height="60"
                            onerror="this.style.display='none'; this.nextElementSibling.hidden=false;"
                        >
                        <span class="gup-uni-card__logo-fallback" hidden aria-hidden="true">{{ $uni->abbreviation }}</span>
                    </div>

                    <h3 class="gup-uni-card__name">{{ $uni->name }}</h3>

                    <p class="gup-uni-card__country">
                        <span class="gup-uni-card__flag" aria-hidden="true">{{ $uni->flag }}</span>
                        {{ $uni->country }}
                    </p>

                    <div class="gup-uni-card__recognition">
                        <span class="gup-uni-card__recognition-label">Recognition</span>
                        <p class="gup-uni-card__recognition-text">{{ $uni->recognition }}</p>
                    </div>

                    <a href="{{ $uni->cta_url }}" class="gup-uni-card__cta btn btn--primary" data-testid="uni-cta-{{ $uni->slug }}">
                        Explore Programs
                    </a>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════
     3. WHY OUR PARTNERSHIPS MATTER (Sticky Left)
═══════════════════════════════════════════ --}}
<section class="gup-why section-wrapper">
    <div class="container">
        <div class="gup-why__grid">

            {{-- Left: Sticky Content --}}
            <div class="gup-why__sticky">
                <div class="gup-why__sticky-inner">
                    <span class="section-label">{{ $whyPartnerships->tag }}</span>
                    <h2 class="gup-why__heading">
                        {{ $whyPartnerships->heading }}
                        <em>{{ $whyPartnerships->heading_italic }}</em>
                    </h2>
                    <blockquote class="gup-why__quote">
                        {{ $whyPartnerships->quote }}
                    </blockquote>
                </div>
            </div>

            {{-- Right: Scrollable Cards --}}
            <div class="gup-why__cards">
                @foreach($whyPartnerships->items as $index => $item)
                <article class="gup-why-card">
                    <div class="gup-why-card__icon">
                        <span data-lucide="{{ $item->icon }}"></span>
                    </div>
                    <div class="gup-why-card__content">
                        <h3 class="gup-why-card__title">{{ $item->title }}</h3>
                        <p class="gup-why-card__description">{{ $item->description }}</p>
                    </div>
                    <span class="gup-why-card__number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                </article>
                @endforeach
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     4. STUDENT BENEFITS (Checklist + Image Collage)
═══════════════════════════════════════════ --}}
<section class="gup-benefits section-wrapper section--light">
    <div class="container">
        <div class="gup-benefits__grid">

            {{-- Left: Content --}}
            <div class="gup-benefits__content">
                <span class="section-label gup-benefits__label">{{ $benefits->tag }}</span>
                <h2 class="gup-benefits__heading">
                    {{ $benefits->heading }}
                    <em>{{ $benefits->heading_italic }}</em>
                </h2>

                <ul class="gup-benefits__list">
                    @foreach($benefits->items as $item)
                    <li class="gup-benefit {{ ($item->highlighted ?? false) ? 'gup-benefit--highlighted' : '' }}">
                        <span class="gup-benefit__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                        <div class="gup-benefit__content">
                            <h4 class="gup-benefit__title">{{ $item->title }}</h4>
                            <p class="gup-benefit__description">{{ $item->description }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Right: Image Collage --}}
            <div class="gup-benefits__visual">
                <div class="gup-benefits__dots"></div>
                
                <div class="gup-benefits__main-image">
                    <img src="{{ $benefits->main_image }}" 
                         alt="Students" 
                         loading="lazy">
                </div>

                <div class="gup-benefits__secondary-image">
                    <img src="{{ $benefits->secondary_image }}" 
                         alt="Students walking" 
                         loading="lazy">
                </div>

                <div class="gup-benefits__floating-stat">
                    <div class="gup-benefits__floating-number">{{ $benefits->floating_stat->number }}</div>
                    <div class="gup-benefits__floating-label">{{ $benefits->floating_stat->label }}</div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     5. PARTNERSHIP JOURNEY (Gallery)
═══════════════════════════════════════════ --}}
<section class="gup-journey section-wrapper">
    <div class="container">

        <div class="section-heading-block">
            <span class="section-label">MOMENTS</span>
            <h2 class="section-heading">
                Our Partnership <em>Journey</em>
            </h2>
            <p class="section-subheading">
                A visual journey through our global collaborations, milestones, and academic partnerships.
            </p>
        </div>

        {{-- Category Filters --}}
        <div class="gup-journey__filters">
            @foreach($galleryCategories as $category)
            <button class="gup-filter {{ $category->slug === 'all' ? 'is-active' : '' }}" 
                    data-filter="{{ $category->slug }}">
                {{ $category->name }}
                <span class="gup-filter__count">{{ $category->count }}</span>
            </button>
            @endforeach
        </div>

        {{-- Masonry Gallery --}}
        <div class="gup-journey__gallery">
            @foreach($galleryItems as $item)
            <div class="gup-gallery-item gup-gallery-item--{{ $item->size }}" 
                 data-category="{{ $item->category }}">
                <img src="{{ $item->image }}" 
                     alt="{{ $item->title ?? $item->badge }}" 
                     loading="lazy"
                     class="gup-gallery-item__image">
                
                <div class="gup-gallery-item__top">
                    <span class="gup-gallery-item__badge">{{ $item->badge }}</span>
                    <span class="gup-gallery-item__date">{{ $item->date }}</span>
                </div>

                @if($item->title || $item->caption)
                <div class="gup-gallery-item__bottom">
                    @if($item->title)
                    <h3 class="gup-gallery-item__title">{{ $item->title }}</h3>
                    @endif
                    @if($item->caption)
                    <p class="gup-gallery-item__caption">{{ $item->caption }}</p>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>

    </div>
</section>

</div>

@include('sections.final-cta')
@endsection

@push('scripts')
    <script src="{{ asset('js/pages/global-university-partners.js') }}" defer></script>
@endpush