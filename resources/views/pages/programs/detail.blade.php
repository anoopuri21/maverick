@extends('layouts.app')

@section('title', ($program->title ?? 'Programme') . ' | Maverick Business Academy')
@section('meta_description', $program->short_description ?? 'Explore this Maverick Business Academy programme.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/program-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
@php
    // ------------------------------------------------------------------
    // PROGRAMME CONTENT MODEL — Udemy-style detail
    // Every section is optional; render only if content exists.
    // ------------------------------------------------------------------
    $cat = $program->programCategory;

    // Quick highlights (from structure doc)
    $highlights = collect([
        ['label' => 'Awarded By', 'value' => $program->partner_university],
        ['label' => 'Duration', 'value' => $program->duration],
        ['label' => 'ECTS Credits', 'value' => 'VERIFY'],
        ['label' => 'Learning', 'value' => 'Flexible'],
        ['label' => 'Curriculum', 'value' => 'Industry-Focused'],
        ['label' => 'Scholarships', 'value' => 'Available (verify)'],
    ])->filter(fn ($h) => !empty($h['value']) && !str_starts_with($h['value'], 'VERIFY'))->values();

    // Recognition slider
    $recognition = collect([
        ['name' => 'IACBE', 'note' => 'International Accreditation Council for Business Education'],
        ['name' => 'YÖK', 'note' => 'Higher Education Council of Turkey'],
        ['name' => 'YÖDAK', 'note' => 'Higher Education Planning, Supervision, Accreditation and Coordination Committee (North Cyprus)'],
    ]);

    // Snapshot
    $snapshot = collect([
        ['label' => 'Degree Award', 'value' => $program->level],
        ['label' => 'Awarding University', 'value' => $program->partner_university],
        ['label' => 'Duration', 'value' => $program->duration],
        ['label' => 'Study Mode', 'value' => 'Online / Hybrid'],
        ['label' => 'Intakes', 'value' => 'Multiple'],
        ['label' => 'Assessment', 'value' => 'Assignments & Examinations'],
        ['label' => 'Credits', 'value' => 'VERIFY'],
        ['label' => 'Eligibility', 'value' => 'VERIFY'],
    ])->filter(fn ($s) => !empty($s['value']) && !str_starts_with($s['value'], 'VERIFY'))->values();

    $benefits = collect([
        ['title' => 'Develop Leadership Skills', 'desc' => 'Learn how to lead teams and organisations.'],
        ['title' => 'Industry-Relevant Curriculum', 'desc' => 'Practical learning aligned with current business practices.'],
        ['title' => 'International Recognition', 'desc' => 'Graduate with an internationally recognised university qualification.'],
        ['title' => 'Career Progression', 'desc' => 'Prepare for leadership roles across multiple industries.'],
        ['title' => 'Flexible Learning', 'desc' => 'Designed to support both students and working professionals.'],
    ]);

    $learning = collect([
        'Develop strategic thinking', 'Apply business management principles', 'Analyse financial information',
        'Understand marketing strategies', 'Improve organisational performance', 'Lead diverse teams',
        'Make ethical business decisions', 'Manage business operations effectively',
    ]);

    $careers = collect([
        'Business Manager', 'Operations Manager', 'Marketing Executive', 'Human Resource Executive',
        'Business Analyst', 'Project Coordinator', 'Entrepreneur', 'Sales Manager',
        'Business Development Executive', 'Management Consultant',
    ]);

    $structure = collect([
        ['title' => 'Year 1', 'subtitle' => 'Business Foundations', 'modules' => [
            ['title' => 'Principles of Management', 'desc' => 'Core concepts of managing people and organisations.'],
            ['title' => 'Business Economics', 'desc' => 'Economic principles applied to business decisions.'],
            ['title' => 'Accounting Fundamentals', 'desc' => 'Basics of financial and management accounting.'],
            ['title' => 'Marketing Essentials', 'desc' => 'Foundations of marketing and market analysis.'],
        ]],
        ['title' => 'Year 2', 'subtitle' => 'Core Business Functions', 'modules' => [
            ['title' => 'Financial Management', 'desc' => 'Managing financial resources and planning.'],
            ['title' => 'Organisational Behaviour', 'desc' => 'Understanding people and behaviour in organisations.'],
            ['title' => 'Operations Management', 'desc' => 'Designing and managing business operations.'],
            ['title' => 'Business Law', 'desc' => 'Legal frameworks relevant to business.'],
        ]],
        ['title' => 'Year 3', 'subtitle' => 'Advanced Business Management', 'modules' => [
            ['title' => 'Strategic Management', 'desc' => 'Developing and executing organisational strategy.'],
            ['title' => 'International Business', 'desc' => 'Operating across global markets and cultures.'],
            ['title' => 'Entrepreneurship', 'desc' => 'Creating and scaling new ventures.'],
            ['title' => 'Human Resource Management', 'desc' => 'Managing talent, teams and organisational culture.'],
        ]],
        ['title' => 'Year 4', 'subtitle' => 'Leadership, Strategy & Internship / Capstone', 'modules' => [
            ['title' => 'Leadership & Change', 'desc' => 'Leading teams and managing organisational change.'],
            ['title' => 'Business Strategy', 'desc' => 'Advanced strategic thinking and decision-making.'],
            ['title' => 'Internship / Capstone', 'desc' => 'Practical application through an internship or final project.'],
            ['title' => 'Global Business Perspectives', 'desc' => 'Contemporary issues shaping the global business landscape.'],
        ]],
    ]);

    $support = collect([
        'Dedicated Academic Support', 'Experienced Faculty', 'Flexible Learning', 'Student Success Team',
        'Assignment Support', 'Affordable Instalments', 'Career Guidance', 'Graduation Support', 'Documentation Assistance',
    ]);

    $university = (object) [
        'name' => $program->partner_university,
        'description' => 'Girne American University (GAU), established in 1985, is one of Northern Cyprus\' leading universities. It offers internationally focused education with programmes designed to prepare graduates for the global workplace.',
        'establishment' => 'Established 1985',
    ];

    // Accreditation groups: each item may have a logo image URL or fall back to text.
    $accreditationGroups = collect([
        ['group' => 'Institutional Recognition', 'items' => collect([
            ['name' => 'GAU', 'logo' => null],
            ['name' => 'YÖDAK', 'logo' => null],
        ])],
        ['group' => 'International Accreditation', 'items' => collect([
            ['name' => 'IACBE', 'logo' => null],
        ])],
        ['group' => 'Professional Recognition', 'items' => collect([
            ['name' => 'YÖK', 'logo' => null],
        ])],
    ]);

    $testimonials = collect([
        ['name' => 'Verified Student', 'role' => 'Business Manager', 'country' => 'UAE', 'category' => 'STUDENT', 'thumb' => null, 'video' => null],
        ['name' => 'Verified Graduate', 'role' => 'Marketing Executive', 'country' => 'UAE', 'category' => 'GRADUATE', 'thumb' => null, 'video' => null],
        ['name' => 'Verified Learner', 'role' => 'Entrepreneur', 'country' => 'UAE', 'category' => 'STUDENT', 'thumb' => null, 'video' => null],
    ]);

    $fees = collect(['Registration Fee', 'Initial Payment', 'Monthly Instalments', 'Scholarship Availability', 'Offer Validity']);

    $faqs = $program->faqs;
    $reviews = collect([
        ['name' => 'Rahul S.', 'avatar' => null, 'rating' => 5, 'review' => 'Great programme structure and excellent support throughout my studies.'],
        ['name' => 'Fatima A.', 'avatar' => null, 'rating' => 5, 'review' => 'The flexible learning approach let me balance work and study. Highly recommended.'],
        ['name' => 'Mohammed K.', 'avatar' => null, 'rating' => 4, 'review' => 'A solid international pathway with very helpful advisors.'],
    ]);

    // Reuse the shared Our Story testimonials slider shape (same partial as
    // the our-story page) — only the heading differs per page.
    $ourStoryTestimonials = $reviews->map(fn ($r) => (object) [
        'name' => $r['name'] ?? '',
        'rating' => $r['rating'] ?? 5,
        'testimonial' => $r['review'] ?? '',
        'photo' => $r['avatar'] ?? null,
    ])->values();
@endphp

<div class="page-pd">

    {{-- ============ STICKY SIDEBAR / BOTTOM BAR ============ --}}
    <aside class="pd-apply" data-testid="pd-apply">
        <a href="#enquire" class="pd-apply__primary">Apply Now</a>
        <a href="{{ route('contact') }}" class="pd-apply__ghost">Enquire</a>
    </aside>

    {{-- ============ STEP 1 · HERO (cinematic — same as other pages) ============ --}}
    <section class="cinematic-hero pd-hero" id="top" aria-label="{{ $program->title }}" data-testid="pd-hero">
        <div class="cinematic-hero__bg" aria-hidden="true">
            <div class="cinematic-hero__bg-image" style="background-image: url('{{ $program->image_url ?? asset('assets/images/homepage/mba.jpg') }}')"></div>
            <div class="cinematic-hero__gradient"></div>
            <div class="cinematic-hero__noise"></div>
            <div class="cinematic-hero__shapes">
                <svg class="cinematic-hero__shape cinematic-hero__shape--1" viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.15)" stroke-width="1"/></svg>
                <svg class="cinematic-hero__shape cinematic-hero__shape--2" viewBox="0 0 300 300" fill="none"><circle cx="150" cy="150" r="120" stroke="rgba(236,31,36,0.22)" stroke-width="1"/></svg>
                <svg class="cinematic-hero__shape cinematic-hero__shape--3" viewBox="0 0 100 100" fill="none"><rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.15)" stroke-width="1" transform="rotate(20 50 50)"/></svg>
            </div>
            <div class="cinematic-hero__particles">
                @for($i = 0; $i < 6; $i++)
                    <div class="cinematic-hero__particle"></div>
                @endfor
            </div>
            <div class="cinematic-hero__scanline"></div>
        </div>

        <div class="container cinematic-hero__content">
            @if($cat)
                <span class="cinematic-hero__eyebrow">
                    <span class="cinematic-hero__eyebrow-line"></span>
                    {{ $cat->name }}
                </span>
            @endif
            <h1 class="cinematic-hero__title">{{ $program->title }}</h1>
            @if($program->short_description)
                <p class="cinematic-hero__description">{{ $program->short_description }}</p>
            @endif
            <div class="pd-hero__ctas">
                <a href="#enquire" class="btn btn--primary">Apply Now</a>
                <a href="#structure" class="btn btn--secondary">Download Brochure</a>
                <a href="{{ route('contact') }}" class="btn btn--outline">Enquire Now</a>
            </div>
        </div>
    </section>

    {{-- ================================================================
         EXPERIMENT · TWO-COLUMN LAYOUT (7:3)
         Quick Highlights → Reviews run in the left column; the
         "Programme at a Glance" (snapshot) is a sticky box on the right.
         ================================================================ --}}
    <div class="pd-layout">
        <div class="pd-layout__main">

    {{-- ============ STEP 2 · QUICK HIGHLIGHTS ============ --}}
    @if($highlights->count())
    <section class="pd-highlights section--light" aria-label="Quick highlights" data-testid="pd-highlights">
        <div class="container">
            <h2 class="pd-section-title">Quick <em>Highlights</em></h2>
            <div class="pd-highlights__grid">
                @foreach($highlights as $h)
                    <div class="pd-highlights__card">
                        <div class="pd-highlights__card-text">
                            <span class="pd-highlights__label">{{ $h['label'] }}</span>
                            <span class="pd-highlights__value">{{ $h['value'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ STEP 2 · RECOGNITION & ACCREDITATION ============ --}}
    @if($recognition->count())
    <section class="pd-recognition" aria-label="Recognition and accreditation" data-testid="pd-recognition">
        <div class="container">
            <h2 class="pd-section-title">Recognition & <em>Accreditation</em></h2>

            {{-- Awarded By --}}
            @if($program->partner_university)
            <div class="pd-recognition__group">
                <h3 class="pd-recognition__heading">Awarded By</h3>
                <div class="pd-recognition__awarded-grid">
                    <div class="pd-recognition__awarded-card">
                        @if(isset($program->award_logo) && $program->award_logo)
                            <img class="pd-recognition__award-logo" src="{{ $program->award_logo }}" alt="{{ $program->partner_university }}" loading="lazy">
                        @endif
                        <div class="pd-recognition__award-text">
                            <span class="pd-recognition__award-title">{{ $program->partner_university }}</span>
                            <p class="pd-recognition__award-desc">{{ $university->description ?? 'Awarding university.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Recognised / Accredited --}}
            <div class="pd-recognition__group">
                <h3 class="pd-recognition__heading">Recognised / Accredited</h3>
                <div class="pd-recognition__slider">
                    @foreach($recognition as $r)
                        <div class="pd-recognition__slide">
                            <span class="pd-recognition__logo">{{ $r['name'] }}</span>
                            <p class="pd-recognition__note">{{ $r['note'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ============ STEP 3 · OVERVIEW ============ --}}
    @if($program->description)
    <section class="pd-overview" aria-label="Programme overview" data-testid="pd-overview">
        <div class="container">
            <span class="pd-section-label">Overview</span>
            <h2 class="pd-section-title">Programme <em>Overview</em></h2>
            <p class="pd-overview__body">{{ $program->description }}</p>
        </div>
    </section>
    @endif

    {{-- ============ STEP 4 · WHY CHOOSE ============ --}}
    @if($benefits->count())
    <section class="pd-benefits section--light" aria-label="Why choose this programme" data-testid="pd-benefits">
        <div class="container">
            <span class="pd-section-label">Why Choose</span>
            <h2 class="pd-section-title">Why Choose This <em>Programme?</em></h2>
            <div class="pd-benefits__grid">
                @foreach($benefits as $i => $b)
                    <div class="pd-benefits__card">
                        <span class="pd-benefits__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3>{{ $b['title'] }}</h3>
                        <p>{{ $b['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ STEP 4 · WHAT YOU'LL LEARN ============ --}}
    @if($learning->count())
    <section class="pd-learn" aria-label="What you'll learn" data-testid="pd-learn">
        <div class="container">
            <div class="pd-learn__header">
                <span class="pd-section-label">Learning Outcomes</span>
                <h2 class="pd-section-title">What You'll <em>Learn</em></h2>
                <p class="pd-learn__sub">Students will learn to:</p>
            </div>
            <ol class="pd-learn__list">
                @foreach($learning as $i => $cap)
                    <li class="pd-learn__item">
                        <span class="pd-learn__tick" aria-hidden="true">✓</span>
                        <span>{{ $cap }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
    @endif

    {{-- ============ STEP 5 · CAREERS ============ --}}
    @if($careers->count())
    <section class="pd-careers section--light" aria-label="Career opportunities" data-testid="pd-careers">
        <div class="container">
            <span class="pd-section-label">Careers</span>
            <h2 class="pd-section-title">Where This Degree Can <em>Take You</em></h2>
            <p class="pd-careers__sub">Potential careers include:</p>
            <div class="pd-careers__grid">
                @foreach($careers as $i => $career)
                    <div class="pd-careers__card">
                        <span class="pd-careers__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                        </span>
                        <span class="pd-careers__label">{{ $career }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ STEP 5 · PROGRAMME STRUCTURE ============ --}}
    @if($structure->count())
    <section class="pd-structure" id="structure" aria-label="Programme structure" data-testid="pd-structure">
        <div class="container">
            <span class="pd-section-label">Programme Structure</span>
            <h2 class="pd-section-title">Programme <em>Structure</em></h2>
            <div class="pd-structure__list">
                @foreach($structure as $i => $stage)
                    <details class="pd-structure__year" {{ $i === 0 ? 'open' : '' }}>
                        <summary class="pd-structure__year-head">
                            <span class="pd-structure__year-title">Year {{ $i + 1 }}</span>
                            @if(!empty($stage['subtitle']))<span class="pd-structure__year-sub">{{ $stage['subtitle'] }}</span>@endif
                            <span class="pd-structure__year-arrow" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                            </span>
                        </summary>
                        @if(!empty($stage['modules']))
                            <div class="pd-structure__modules">
                                @foreach($stage['modules'] as $mi => $module)
                                    <details class="pd-structure__module" {{ $mi === 0 ? 'open' : '' }}>
                                        <summary class="pd-structure__module-head">
                                            <span class="pd-structure__module-arrow" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
                                            </span>
                                            <span class="pd-structure__module-title">Module {{ $mi + 1 }}</span>
                                        </summary>
                                        @if(is_array($module))
                                            <div class="pd-structure__module-content">
                                                <h5 class="pd-structure__module-name">{{ $module['title'] }}</h5>
                                                @if(!empty($module['desc']))<p class="pd-structure__module-desc">{{ $module['desc'] }}</p>@endif
                                            </div>
                                        @else
                                            <div class="pd-structure__module-content">
                                                <p class="pd-structure__module-desc">{{ $module }}</p>
                                            </div>
                                        @endif
                                    </details>
                                @endforeach
                            </div>
                        @endif
                    </details>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ STEP 6 · UNIVERSITY ============ --}}
    @if($university->name)
    <section class="pd-uni section--light" aria-label="About the awarding university" data-testid="pd-uni">
        <div class="container">
            <span class="pd-section-label">About the University</span>
            <h2 class="pd-section-title">A Globally Connected <em>University</em></h2>
            <div class="pd-uni__body">
                <p>{{ $university->description }}</p>
                @if($university->establishment)<span class="pd-uni__meta">{{ $university->establishment }}</span>@endif
            </div>
        </div>
    </section>
    @endif

    {{-- ============ STEP 6 · ACCREDITATION ============ --}}
    @if($accreditationGroups->count())
    <section class="pd-accred" aria-label="Accreditation and recognition" data-testid="pd-accred">
        <div class="container">
            <span class="pd-section-label">Accreditation & Recognition</span>
            <h2 class="pd-section-title">Accreditation & <em>Recognition</em></h2>

            @foreach($accreditationGroups as $g)
            <div class="pd-accred__division">
                <h3 class="pd-accred__division-heading">{{ $g['group'] }}</h3>
                <div class="pd-accred__slider">
                    @foreach($g['items'] as $item)
                        <div class="pd-accred__logo-box">
                            @if(!empty($item['logo']))
                                <img class="pd-accred__logo-img" src="{{ $item['logo'] }}" alt="{{ $item['name'] }}" loading="lazy">
                            @else
                                <span class="pd-accred__logo-text">{{ $item['name'] }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ============ STEP 7 · MAVERICK SUPPORT ============ --}}
    @if($support->count())
    <section class="pd-support section--light" aria-label="Why study through Maverick" data-testid="pd-support">
        <div class="container">
            <span class="pd-section-label">Your Learning Partner</span>
            <h2 class="pd-section-title">Why Study Through <em>Maverick?</em></h2>
            <p class="pd-support__sub">Students receive:</p>
            <div class="pd-support__grid">
                @foreach($support as $i => $s)
                    <div class="pd-support__item">
                        <span class="pd-support__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                        </span>
                        <span class="pd-support__text">{{ $s }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ STEP 7 · TESTIMONIALS (video, like home) ============ --}}
    @if($testimonials->count())
    <section class="pd-testimonials" aria-label="Student success stories" data-testid="pd-testimonials">
        <div class="container">
            <span class="pd-section-label">Testimonials</span>
            <h2 class="pd-section-title">Student Success <em>Stories</em></h2>
            <div class="scroll-row scroll-row--light" data-scroll-row>
                <button class="scroll-row__btn scroll-row__btn--prev" aria-label="Scroll left" data-scroll-prev>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6" /></svg>
                </button>
                <div class="pd-testimonials__scroll" data-scroll-container data-lenis-prevent>
                    <div class="pd-testimonials__track">
                        @foreach($testimonials as $t)
                            <article class="testimonials__card pd-testimonials__card" data-testid="pd-testimonial-card">
                                <span class="testimonials__card-badge">{{ $t['category'] ?? 'STUDENT' }}</span>
                                <div class="testimonials__card-thumb">
                                    @if(!empty($t['thumb']))
                                        <img src="{{ $t['thumb'] }}" alt="{{ $t['name'] }}" loading="lazy">
                                    @else
                                        <div class="img-placeholder pd-testimonials__thumb-ph" aria-hidden="true"></div>
                                    @endif
                                    @if(!empty($t['video']))
                                        <a class="testimonials__play" href="{{ $t['video'] }}" data-modal-video="{{ $t['video'] }}" aria-label="Play {{ $t['name'] }}'s video" data-testid="pd-play">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                                        </a>
                                    @else
                                        <span class="testimonials__play" aria-hidden="true">
                                            <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </span>
                                    @endif
                                </div>
                                <div class="testimonials__card-info">
                                    <h3 class="testimonials__card-name">{{ $t['name'] }}</h3>
                                    <p class="testimonials__card-role">{{ $t['role'] ?? '' }}{{ !empty($t['role']) && !empty($t['country']) ? ' · ' : '' }}{{ $t['country'] ?? '' }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
                <button class="scroll-row__btn scroll-row__btn--next" aria-label="Scroll right" data-scroll-next>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6" /></svg>
                </button>
            </div>
        </div>
    </section>
    @endif

    {{-- ============ STEP 8 · FEES ============ --}}
    <section class="pd-fees section--light" aria-label="Fees and scholarships" data-testid="pd-fees">
        <div class="container pd-fees__inner">
            <div class="pd-fees__text">
                <h2 class="pd-section-title">Fees & <em>Scholarships</em></h2>
                <p class="body-text">Fee structure varies by intake and study mode. Request the full fee structure for details.</p>
            </div>
            <div class="pd-fees__side">
                @if($fees->count())
                    <div class="pd-fees__list">
                        @foreach($fees as $fee)
                            <a href="{{ route('contact') }}" class="pd-fees__chip">{{ $fee }}</a>
                        @endforeach
                    </div>
                @endif
                <a href="{{ route('contact') }}" class="btn pd-btn pd-btn--primary">Request Fee Structure</a>
            </div>
        </div>
    </section>

    {{-- ============ STEP 8 · FAQ ============ --}}
    @if($faqs->count())
    <section class="pd-faq" aria-label="Frequently asked questions" data-testid="pd-faq">
        <div class="container">
            <h2 class="pd-section-title">Frequently Asked <em>Questions</em></h2>
            <div class="pd-faq__list">
                @foreach($faqs as $i => $faq)
                    <details class="pd-faq__item" {{ $i === 0 ? 'open' : '' }}>
                        <summary class="pd-faq__question">{{ $faq->question }}<span class="pd-faq__chevron" aria-hidden="true">+</span></summary>
                        <div class="pd-faq__answer">{{ $faq->answer }}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ STEP 8 · ENQUIRY ============ --}}
    <section class="pd-enquire section--light" id="enquire" aria-label="Enquire about this programme" data-testid="pd-enquire">
        <div class="container pd-enquire__inner">
            <div class="pd-enquire__intro">
                <h2 class="pd-section-title pd-section-title--single">Enquire About This Programme</h2>
                <p class="body-text">Speak to our admissions team to check your eligibility and get started.</p>
            </div>
            <form class="pd-form" action="{{ route('contact') }}" method="POST">
                @csrf
                <input type="hidden" name="programme" value="{{ $program->title }}">
                <div class="pd-form__row">
                    <div class="pd-form__field">
                        <label for="pd-name">Full name</label>
                        <input id="pd-name" name="name" type="text" required>
                    </div>
                    <div class="pd-form__field">
                        <label for="pd-email">Email</label>
                        <input id="pd-email" name="email" type="email" required>
                    </div>
                    <div class="pd-form__field">
                        <label for="pd-phone">Phone</label>
                        <input id="pd-phone" name="phone" type="tel" required>
                    </div>
                    <div class="pd-form__field">
                        <label for="pd-country">Country</label>
                        <input id="pd-country" name="country" type="text">
                    </div>
                    <div class="pd-form__field">
                        <label for="pd-study-mode">Study mode</label>
                        <select id="pd-study-mode" name="study_mode">
                            <option value="">Select study mode</option>
                            <option value="online">Online</option>
                            <option value="hybrid">Hybrid</option>
                            <option value="part-time">Part-time</option>
                        </select>
                    </div>
                    <div class="pd-form__field">
                        <label for="pd-message">Message</label>
                        <textarea id="pd-message" name="message" rows="2"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn pd-btn pd-btn--primary">Submit Enquiry</button>
            </form>
        </div>
    </section>

    {{-- ============ STEP 8 · REVIEWS (shared Our Story slider) ============ --}}
    @include('sections.our-story-testimonials', [
        'osTestimonialsId'      => 'pd-reviews',
        'osTestimonialsLabel'   => 'Reviews',
        'osTestimonialsHeading' => 'Student Reviews with Google Ratings',
    ])

        </div>{{-- /.pd-layout__main --}}

        {{-- ==========================================================
             RIGHT STICKY SIDEBAR · "Programme at a Glance"
             ========================================================== --}}
        @if($snapshot->count())
        <aside class="pd-layout__side" aria-label="Programme at a glance">
            <div class="pd-layout__sticky">
                <div class="pd-snapshot-box" data-testid="pd-snapshot">
                    <span class="pd-section-label">Programme at a Glance</span>
                    <div class="pd-snapshot-box__list">
                        @foreach($snapshot as $s)
                            <div class="pd-snapshot-box__item">
                                <span class="pd-snapshot-box__label">{{ $s['label'] }}</span>
                                <span class="pd-snapshot-box__value">{{ $s['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="pd-snapshot-box__actions">
                        <a href="#enquire" class="btn pd-btn pd-btn--primary pd-snapshot-box__cta">Apply Now</a>
                        <a href="{{ route('contact') }}" class="btn pd-btn pd-btn--ghost pd-snapshot-box__cta pd-snapshot-box__cta--ghost">Enquire</a>
                    </div>
                </div>
            </div>
        </aside>
        @endif

    </div>{{-- /.pd-layout --}}

    {{-- ============ FINAL CTA ============ --}}
    <section class="pd-cta" aria-label="Take the next step" data-testid="pd-cta">
        <div class="container pd-cta__inner">
            <h2 class="pd-cta__title">Ready to Take the <em>Next Step?</em></h2>
            <div class="pd-cta__actions">
                <a href="#enquire" class="btn pd-btn pd-btn--primary">Apply Now</a>
                <a href="{{ route('contact') }}" class="btn pd-btn pd-btn--ghost">Speak to an Advisor</a>
            </div>
        </div>
    </section>

    @if(!empty($site->whatsapp_number))
        <a class="pd-whatsapp" href="https://wa.me/{{ $site->whatsapp_number }}" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 1.8a8.2 8.2 0 1 1-4.2 15.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 0 1 12 3.8z"/></svg>
        </a>
    @endif

    @include('sections.final-cta')

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Accordions: close others in same group (structure years, structure modules, FAQ)
    document.querySelectorAll('.pd-structure__year, .pd-structure__module, .pd-faq__item').forEach(details => {
        details.addEventListener('toggle', () => {
            if (!details.open) return;
            const group = details.parentElement;
            group.querySelectorAll('details[open]').forEach(d => { if (d !== details) d.open = false; });
        });
    });

    if (typeof AnimationUtils !== 'undefined' && typeof gsap !== 'undefined' && !AnimationUtils.prefersReducedMotion) {
        AnimationUtils.fadeUp('.pd-hero__content', {});
        AnimationUtils.fadeUp('.pd-highlights__card', { stagger: 0.05 });
        AnimationUtils.fadeUp('.pd-snapshot__item', { stagger: 0.05 });
        AnimationUtils.fadeUp('.pd-benefits__card', { stagger: 0.06 });
        AnimationUtils.fadeUp('.pd-learn__item', { stagger: 0.04 });
        AnimationUtils.fadeUp('.pd-careers__card', { stagger: 0.04 });
        AnimationUtils.fadeUp('.pd-support__item', { stagger: 0.04 });
        AnimationUtils.fadeUp('.pd-testimonials__card', { stagger: 0.1 });
    }
});
</script>
@endpush
