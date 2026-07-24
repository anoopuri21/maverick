@extends('layouts.app')

@section('title', 'Global University Partners - Maverick Business Academy')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/global-university-partners.css') }}">
@endpush

@section('content')

@php
    // ═══════════════════════════════════════════
    // STATIC DATA (Future: Move to admin/database)
    // ═══════════════════════════════════════════

    $hero = (object)[
        'tag' => 'GLOBAL PARTNERSHIPS',
        'heading_line1' => 'Building Global Pathways Through',
        'heading_italic' => 'Strategic Academic Partnerships',
        'description' => 'Maverick Business Academy collaborates with internationally recognized universities and educational institutions to provide learners with access to globally respected qualifications, flexible learning opportunities, and career-focused academic pathways.',
        'background_image' => asset('https://images.pexels.com/photos/5725589/pexels-photo-5725589.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600'),
    ];

    $overview = (object)[
        'tag' => 'PARTNERSHIP OVERVIEW',
        'heading' => 'Borders Should Never',
        'heading_italic' => 'Limit Ambition',
        'paragraphs' => [
            'Our partnerships are more than logos on a page — they are carefully built academic bridges. Each collaboration is designed so that credits earned at Maverick transfer directly into degree programmes at our partner universities, saving our students time and money.',
            'From articulation agreements and validated degrees to joint research and faculty exchange, we work hand-in-hand with institutions that share our belief that world-class education should be within everyone\'s reach.',
        ],
        'image' => asset('https://images.pexels.com/photos/7972324/pexels-photo-7972324.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=900'),
        'stats' => collect([
            (object)['number' => '50+', 'label' => 'PARTNER UNIVERSITIES'],
            (object)['number' => '25+', 'label' => 'COUNTRIES WORLDWIDE'],
            (object)['number' => '120+', 'label' => 'ARTICULATION PATHWAYS'],
            (object)['number' => '30k+', 'label' => 'GLOBAL ALUMNI NETWORK'],
        ]),
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
        'tag' => 'STUDENT ADVANTAGES',
        'heading' => 'Benefits of Studying Through',
        'heading_italic' => 'Maverick Partnerships',
        'main_image' => asset('https://images.pexels.com/photos/5538583/pexels-photo-5538583.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760'),
        'secondary_image' => asset('https://images.pexels.com/photos/7972324/pexels-photo-7972324.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=420&w=560'),
        'floating_stat' => (object)['number' => '30k+', 'label' => 'GLOBAL ALUMNI'],
        'items' => collect([
            (object)[
                'title' => 'Internationally Recognized Qualifications',
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
                'title' => 'Diverse International Environment',
                'description' => 'Learn alongside peers from every corner of the world.',
            ],
        ]),
    ];

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
     1. HERO SECTION
═══════════════════════════════════════════ --}}
<section class="gup-hero" style="background-image: url('{{ $hero->background_image }}');">
    <div class="gup-hero__overlay"></div>
    <div class="container gup-hero__content">
        <span class="gup-hero__tag">{{ $hero->tag }}</span>
        <h1 class="gup-hero__heading">
            {{ $hero->heading_line1 }}
            <em class="gup-hero__heading-italic">{{ $hero->heading_italic }}</em>
        </h1>
        <p class="gup-hero__description">{{ $hero->description }}</p>
        <div class="gup-hero__scroll">
            <span>SCROLL</span>
            <div class="gup-hero__scroll-line"></div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     2. PARTNERSHIP OVERVIEW (Split with Stats)
═══════════════════════════════════════════ --}}
<section class="gup-overview section-wrapper">
    <div class="container">
        <div class="gup-overview__grid">

            {{-- Left: Stats --}}
            <div class="gup-overview__stats">
                @foreach($overview->stats as $stat)
                <div class="gup-stat">
                    <div class="gup-stat__number">{{ $stat->number }}</div>
                    <div class="gup-stat__label">{{ $stat->label }}</div>
                </div>
                @endforeach
            </div>

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

@include('sections.final-cta')

@endsection

@push('scripts')
    <script src="{{ asset('js/pages/global-university-partners.js') }}" defer></script>
@endpush