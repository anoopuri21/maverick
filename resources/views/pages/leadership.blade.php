@extends('layouts.app')

@section('title', 'Leadership & Board - Maverick Business Academy')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/leadership.css') }}">
@endpush

@section('content')

@php
    // ═══════════════════════════════════════════
    // STATIC DATA (Future: Move to admin/database)
    // ═══════════════════════════════════════════

    $hero = (object)[
        'tag' => 'LEADERSHIP & GOVERNANCE',
        'heading' => 'The Visionaries Behind',
        'heading_italic' => 'Maverick Academy',
        'description' => 'Meet the distinguished leaders and board members who guide our mission to transform lives through accessible, world-class business education.',
        'background_image' => asset('assets/images/leadership/hero-bg.jpg'),
    ];

    $executiveTeam = collect([
        (object)[
            'name' => 'Dr. Elizabeth Chen',
            'designation' => 'Chief Academic Officer',
            'bio' => 'Former Dean at London School of Economics with 25+ years in higher education. Dr. Chen oversees all academic programmes and faculty development, ensuring world-class educational standards.',
            'image_url' => asset('assets/images/leadership/elizabeth-chen.jpg'),
            'linkedin_url' => '#',
        ],
        (object)[
            'name' => "Michael O'Brien",
            'designation' => 'Chief Operating Officer',
            'bio' => 'Previously led operations at Pearson Education for 15 years. Michael ensures seamless delivery of our programmes across all global touchpoints and drives operational excellence.',
            'image_url' => asset('assets/images/leadership/michael-obrien.jpg'),
            'linkedin_url' => '#',
        ],
        (object)[
            'name' => 'Amara Okonkwo',
            'designation' => 'Chief Strategy Officer',
            'bio' => "Former McKinsey partner and Harvard MBA. Amara leads our strategic initiatives, global expansion efforts, and corporate partnerships that shape Maverick's future.",
            'image_url' => asset('assets/images/leadership/amara-okonkwo.jpg'),
            'linkedin_url' => '#',
        ],
        (object)[
            'name' => 'Robert Williams',
            'designation' => 'Chief Financial Officer',
            'bio' => 'Chartered accountant with experience at KPMG and Deloitte. Robert oversees financial strategy, investor relations, and ensures sustainable growth for the academy.',
            'image_url' => asset('assets/images/leadership/robert-williams.jpg'),
            'linkedin_url' => '#',
        ],
        (object)[
            'name' => 'Dr. Sarah Mitchell',
            'designation' => 'Chief Digital Officer',
            'bio' => 'Tech visionary and former Google executive. Sarah drives our digital transformation, online learning platform development, and AI-powered educational innovations.',
            'image_url' => asset('assets/images/leadership/sarah-mitchell.jpg'),
            'linkedin_url' => '#',
        ],
        (object)[
            'name' => 'David Oyelaran',
            'designation' => 'Chief Marketing Officer',
            'bio' => "Brand strategist with 20+ years building global education brands. David leads our marketing, communications, and student recruitment efforts worldwide.",
            'image_url' => asset('assets/images/leadership/david-oyelaran.jpg'),
            'linkedin_url' => '#',
        ],
    ]);

    $boardMembers = collect([
        (object)['name' => 'Dame Victoria Ashford', 'role' => 'CHAIRPERSON', 'image_url' => asset('assets/images/leadership/board/victoria.jpg')],
        (object)['name' => 'Lord Richard Pemberton', 'role' => 'VICE CHAIR', 'image_url' => asset('assets/images/leadership/board/richard.jpg')],
        (object)['name' => 'Prof. Mei-Lin Zhang', 'role' => 'NON-EXECUTIVE DIRECTOR', 'image_url' => asset('assets/images/leadership/board/meilin.jpg')],
        (object)['name' => 'Sir Marcus Thompson', 'role' => 'NON-EXECUTIVE DIRECTOR', 'image_url' => asset('assets/images/leadership/board/marcus.jpg')],
        (object)['name' => 'Fatima Al-Hassan', 'role' => 'NON-EXECUTIVE DIRECTOR', 'image_url' => asset('assets/images/leadership/board/fatima.jpg')],
        (object)['name' => 'Jonathan Clarke', 'role' => 'NON-EXECUTIVE DIRECTOR', 'image_url' => asset('assets/images/leadership/board/jonathan.jpg')],
        (object)['name' => 'Dr. Patricia Mendez', 'role' => 'NON-EXECUTIVE DIRECTOR', 'image_url' => asset('assets/images/leadership/board/patricia.jpg')],
        (object)['name' => 'Andrew Blackwell', 'role' => 'COMPANY SECRETARY', 'image_url' => asset('assets/images/leadership/board/andrew.jpg')],
    ]);

    $advisors = collect([
        (object)['name' => 'Prof. Rajesh Kapoor', 'expertise' => 'Digital Transformation', 'image_url' => asset('assets/images/leadership/advisors/rajesh.jpg')],
        (object)['name' => 'Dr. Angela Morrison', 'expertise' => 'Executive Education', 'image_url' => asset('assets/images/leadership/advisors/angela.jpg')],
        (object)['name' => 'William Frost', 'expertise' => 'Corporate Strategy', 'image_url' => asset('assets/images/leadership/advisors/william.jpg')],
        (object)['name' => 'Dr. Chioma Eze', 'expertise' => 'International Accreditation', 'image_url' => asset('assets/images/leadership/advisors/chioma.jpg')],
        (object)['name' => 'Thomas Bergström', 'expertise' => 'Sustainability & ESG', 'image_url' => asset('assets/images/leadership/advisors/thomas.jpg')],
        (object)['name' => 'Dr. Yasmin Patel', 'expertise' => 'Lifelong Learning', 'image_url' => asset('assets/images/leadership/advisors/yasmin.jpg')],
    ]);
@endphp


{{-- ═══════════════════════════════════════════
     1. HERO SECTION
═══════════════════════════════════════════ --}}
<section class="leadership-hero" style="background-image: url('{{ $hero->background_image }}');">
    <div class="leadership-hero__overlay"></div>
    <div class="container leadership-hero__content">
        <span class="leadership-hero__tag">{{ $hero->tag }}</span>
        <h1 class="leadership-hero__heading">
            {{ $hero->heading }}
            <em class="leadership-hero__heading-italic">{{ $hero->heading_italic }}</em>
        </h1>
        <p class="leadership-hero__description">{{ $hero->description }}</p>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     2. EXECUTIVE LEADERSHIP TEAM
═══════════════════════════════════════════ --}}
<section class="executive-team section-wrapper">
    <div class="container">
        
        <div class="section-heading-block">
            <span class="section-label">LEADERSHIP</span>
            <h2 class="section-heading">
                Executive <em>Leadership Team</em>
            </h2>
            <p class="section-subheading">
                The visionary leaders driving our mission to transform lives through education.
            </p>
        </div>

        <div class="executive-team__grid">
            @foreach($executiveTeam as $member)
            <article class="team-card">
                <div class="team-card__image-wrapper">
                    <img src="{{ $member->image_url }}" 
                         alt="{{ $member->name }}" 
                         class="team-card__image"
                         loading="lazy">
                </div>
                <div class="team-card__content">
                    <h3 class="team-card__name">{{ $member->name }}</h3>
                    <p class="team-card__designation">{{ strtoupper($member->designation) }}</p>
                    <p class="team-card__bio">{{ $member->bio }}</p>
                    <a href="{{ $member->linkedin_url }}" class="team-card__linkedin" target="_blank" rel="noopener">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20.5 2h-17A1.5 1.5 0 002 3.5v17A1.5 1.5 0 003.5 22h17a1.5 1.5 0 001.5-1.5v-17A1.5 1.5 0 0020.5 2zM8 19H5v-9h3zM6.5 8.25A1.75 1.75 0 118.3 6.5a1.78 1.78 0 01-1.8 1.75zM19 19h-3v-4.74c0-1.42-.6-1.93-1.38-1.93A1.74 1.74 0 0013 14.19a.66.66 0 000 .14V19h-3v-9h2.9v1.3a3.11 3.11 0 012.7-1.4c1.55 0 3.36.86 3.36 3.66z"/>
                        </svg>
                        Connect on LinkedIn
                    </a>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════
     3. BOARD OF DIRECTORS
═══════════════════════════════════════════ --}}
<section class="board-directors section-wrapper section--light">
    <div class="container">
        
        <div class="section-heading-block">
            <span class="section-label">GOVERNANCE</span>
            <h2 class="section-heading">
                Board of <em>Directors</em>
            </h2>
            <p class="section-subheading">
                Distinguished leaders providing strategic oversight and governance excellence.
            </p>
        </div>

        <div class="board-directors__grid">
            @foreach($boardMembers as $member)
            <article class="board-card">
                <div class="board-card__image-wrapper">
                    <img src="{{ $member->image_url }}" 
                         alt="{{ $member->name }}" 
                         class="board-card__image"
                         loading="lazy">
                </div>
                <h3 class="board-card__name">{{ $member->name }}</h3>
                <p class="board-card__role">{{ $member->role }}</p>
            </article>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════
     4. ADVISORY BOARD
═══════════════════════════════════════════ --}}
<section class="advisory-board section-wrapper">
    <div class="container">
        
        <div class="section-heading-block">
            <span class="section-label">EXTERNAL EXPERTISE</span>
            <h2 class="section-heading">
                Advisory <em>Board</em>
            </h2>
            <p class="section-subheading">
                Industry experts and thought leaders who guide our strategic direction.
            </p>
        </div>

        <div class="advisory-board__grid">
            @foreach($advisors as $advisor)
            <article class="advisor-card">
                <div class="advisor-card__image-wrapper">
                    <img src="{{ $advisor->image_url }}" 
                         alt="{{ $advisor->name }}" 
                         class="advisor-card__image"
                         loading="lazy">
                    <span class="advisor-card__badge">ADVISOR</span>
                </div>
                <h3 class="advisor-card__name">{{ $advisor->name }}</h3>
                <p class="advisor-card__expertise">{{ $advisor->expertise }}</p>
            </article>
            @endforeach
        </div>

    </div>
</section>

    @include('sections.final-cta')
@endsection