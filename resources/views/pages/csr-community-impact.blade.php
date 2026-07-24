@extends('layouts.app')

@section('title', 'CSR & Community Impact - Maverick Business Academy')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/csr-community-impact.css') }}">
@endpush

@section('content')

@php
    // ═══════════════════════════════════════════
    // STATIC DATA (Future: Move to admin/database)
    // ═══════════════════════════════════════════

    $hero = (object)[
        'tag' => 'CSR & COMMUNITY',
        'heading' => 'Building Futures,',
        'heading_italic' => 'Changing Lives',
        'description' => 'Beyond the classroom, we believe that education is the most powerful tool for social transformation. Our commitment to community impact reflects the values at the core of Maverick Academy.',
        'background_image' => asset('assets/images/csr/hero-bg.jpg'),
    ];

    $commitment = (object)[
        'tag' => 'OUR COMMITMENT',
        'heading' => 'Education as a Force for',
        'heading_italic' => 'Good',
        'description' => 'We believe that every individual, regardless of their circumstances, deserves access to transformative education. At Maverick Academy, our commitment to social responsibility is woven into everything we do — from scholarship programmes that open doors for underrepresented students, to community workshops that bring learning to those who need it most. We measure our success not just by the qualifications we award, but by the lives we touch and the communities we strengthen.',
        'image' => asset('assets/images/csr/volunteer.jpg'),
    ];

    $impactStats = collect([
        (object)['icon' => 'users', 'number' => '3200', 'label' => 'STUDENTS SPONSORED'],
        (object)['icon' => 'map-pin', 'number' => '42', 'label' => 'COMMUNITIES REACHED'],
        (object)['icon' => 'award', 'number' => '850+', 'label' => 'SCHOLARSHIPS GIVEN'],
        (object)['icon' => 'clock', 'number' => '12500+', 'label' => 'VOLUNTEER HOURS'],
    ]);

    $stories = collect([
        (object)[
            'name' => 'Aisha Mohammed',
            'story' => 'Growing up in a low-income community in East London, Aisha never imagined she could pursue a business qualification. Through our Future Leaders Scholarship, she completed her Executive MBA and now leads a social enterprise that provides digital skills training to young people in her neighbourhood.',
            'impact' => 'Now mentoring 15 other scholarship recipients and has created employment opportunities for 30 young people in her community.',
            'image' => asset('assets/images/csr/stories/aisha.jpg'),
            'image_position' => 'right',
        ],
        (object)[
            'name' => 'Thomas Reed',
            'story' => 'After losing his job during the pandemic, Thomas used our free professional development workshops to learn digital marketing skills. Within six months, he launched his own consultancy that helps small businesses grow online. Today, he volunteers as a workshop facilitator, sharing his journey with others facing similar challenges.',
            'impact' => 'Has facilitated 12 free workshops, reaching over 200 participants, and his consultancy employs 5 people from his former community.',
            'image' => asset('assets/images/csr/stories/thomas.jpg'),
            'image_position' => 'left',
        ],
    ]);

    $partners = collect([
        (object)['name' => 'EdArabia', 'logo' => asset('assets/images/csr/partners/edarabia.png')],
        (object)['name' => 'PRME', 'logo' => asset('assets/images/csr/partners/prme.png')],
        (object)['name' => 'EdArabia', 'logo' => asset('assets/images/csr/partners/edarabia.png')],
        (object)['name' => 'PRME', 'logo' => asset('assets/images/csr/partners/prme.png')],
        (object)['name' => 'EdArabia', 'logo' => asset('assets/images/csr/partners/edarabia.png')],
        (object)['name' => 'PRME', 'logo' => asset('assets/images/csr/partners/prme.png')],
    ]);

    $pillars = (object)[
        'heading' => 'Three Pillars of',
        'heading_italic' => 'Global Education',
        'description' => 'We deliver education through three connected pathways academic qualifications, professional development, and international opportunities designed to support every stage of your journey.',
        'items' => collect([
            (object)[
                'number' => '01',
                'title' => 'Academic Qualifications',
                'description' => 'Internationally recognized degrees at every level.',
                'features' => ["Bachelor's Degrees", "Master's Degrees", 'Doctorate Degrees'],
                'cta_text' => 'Explore Programs',
                'cta_url' => '#',
            ],
            (object)[
                'number' => '02',
                'title' => 'Professional Development',
                'description' => 'Career-focused training designed for working leaders.',
                'features' => ['Executive Education', 'Corporate Training', 'Leadership Programs'],
                'cta_text' => 'View Programs',
                'cta_url' => '#',
            ],
            (object)[
                'number' => '03',
                'title' => 'International Opportunities',
                'description' => 'Global experiences that broaden horizons.',
                'features' => ['Study Abroad', 'Student Exchange', 'Global Bachelor Pathways', 'Internships'],
                'cta_text' => 'Discover Opportunities',
                'cta_url' => '#',
            ],
        ]),
    ];
@endphp


{{-- ═══════════════════════════════════════════
     1. HERO SECTION
═══════════════════════════════════════════ --}}
<section class="csr-hero" style="background-image: url('{{ $hero->background_image }}');">
    <div class="csr-hero__overlay"></div>
    <div class="container csr-hero__content">
        <span class="csr-hero__tag">{{ $hero->tag }}</span>
        <h1 class="csr-hero__heading">
            {{ $hero->heading }}
            <em class="csr-hero__heading-italic">{{ $hero->heading_italic }}</em>
        </h1>
        <p class="csr-hero__description">{{ $hero->description }}</p>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     2. COMMITMENT (Education as a Force for Good)
═══════════════════════════════════════════ --}}
<section class="commitment section-wrapper">
    <div class="container">
        <div class="commitment__grid">
            
            {{-- Left: Content --}}
            <div class="commitment__content">
                <span class="section-label commitment__label">{{ $commitment->tag }}</span>
                <h2 class="commitment__heading">
                    {{ $commitment->heading }}
                    <em>{{ $commitment->heading_italic }}</em>
                </h2>
                <p class="commitment__description">{{ $commitment->description }}</p>
            </div>

            {{-- Right: Image --}}
            <div class="commitment__image-wrapper">
                <img src="{{ $commitment->image }}" 
                     alt="Volunteer" 
                     class="commitment__image"
                     loading="lazy">
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     3. IMPACT STATS
═══════════════════════════════════════════ --}}
<section class="impact-stats">
    <div class="container">
        <div class="impact-stats__grid">
            @foreach($impactStats as $stat)
            <div class="impact-stat">
                <div class="impact-stat__icon">
                    <span data-lucide="{{ $stat->icon }}"></span>
                </div>
                <div class="impact-stat__number">{{ $stat->number }}</div>
                <div class="impact-stat__label">{{ $stat->label }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     4. MOST IN-DEMAND PROGRAMS
═══════════════════════════════════════════ --}}
    @include('sections.featured-programs')

{{-- ═══════════════════════════════════════════
     5. STORIES OF IMPACT
═══════════════════════════════════════════ --}}
<section class="stories section-wrapper section--light">
    <div class="container">
        
        <div class="section-heading-block">
            <span class="section-label">REAL STORIES</span>
            <h2 class="section-heading">
                Stories of <em>Impact</em>
            </h2>
        </div>

        <div class="stories__list">
            @foreach($stories as $story)
            <article class="story-card story-card--{{ $story->image_position }}">
                <div class="story-card__image-wrapper">
                    <img src="{{ $story->image }}" 
                         alt="{{ $story->name }}" 
                         class="story-card__image"
                         loading="lazy">
                </div>
                <div class="story-card__content">
                    <span class="story-card__tag">SUCCESS STORY</span>
                    <h3 class="story-card__name">{{ $story->name }}</h3>
                    <p class="story-card__story">{{ $story->story }}</p>
                    <div class="story-card__impact">
                        {{ $story->impact }}
                    </div>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════
     6. PARTNERSHIPS FOR GOOD
═══════════════════════════════════════════ --}}
<section class="partnerships section-wrapper">
    <div class="container">
        
        <div class="section-heading-block">
            <span class="section-label">PARTNERS</span>
            <h2 class="section-heading">
                Partnerships for <em>Good</em>
            </h2>
            <p class="section-subheading">
                Working alongside leading NGOs and community organisations to maximise our impact.
            </p>
        </div>

        <div class="partnerships__grid">
            @foreach($partners as $partner)
            <div class="partner-logo">
                <img src="{{ $partner->logo }}" 
                     alt="{{ $partner->name }}" 
                     loading="lazy">
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════
     7. THREE PILLARS OF GLOBAL EDUCATION
═══════════════════════════════════════════ --}}

@include('sections.what-we-do')
@include('sections.final-cta')
@endsection