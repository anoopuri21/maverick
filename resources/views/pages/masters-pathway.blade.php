@extends('layouts.app')

@section('title', 'International Master\'s Pathway Program | Maverick Business Academy London')
@section('meta_description', 'Start your international Master\'s journey with Maverick Business Academy London. Complete a Level 7 Diploma and progress to partner universities in Hungary, Moldova or Romania for final-stage Master\'s completion.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/masters-pathway.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
<div class="page-mp">

@php
    $hero = (object)[
        'tag' => 'MASTER\'S PATHWAY',
        'heading' => 'International Master\'s',
        'heading_italic' => 'Pathway Program',
        'sub_heading' => 'A smarter route to a globally recognised Master\'s degree in Europe',
        'description' => 'The Maverick International Master\'s Pathway Program is designed for graduates and working professionals who want a structured, flexible and cost-effective route towards an international Master\'s degree.',
        'description2' => 'Through this pathway, students complete the first academic phase with Maverick Business Academy London through a Level 7 Diploma carrying 120 UK credits, followed by progression to a partner university for the final stage of study in Hungary, Moldova or Romania, subject to university approval and academic mapping.',
        'background_image' => 'https://images.pexels.com/photos/256541/pexels-photo-256541.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600',
    ];

    $destinations = collect([
        (object)[
            'slug' => 'hungary',
            'name' => 'Hungary',
            'label' => 'CENTRAL EUROPE',
            'university' => 'IBS International Business School',
            'image' => 'https://images.pexels.com/photos/16356273/pexels-photo-16356273.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=950&w=760',
            'position' => 'left',
            'description' => 'Students may choose Hungary as a premium Central European study destination with international classroom exposure and strong academic progression options. Maverick\'s Hungary route is connected with IBS International Business School, with final-stage study expected to take approximately one academic year, subject to IBS academic mapping and admission approval. The available specialisation areas may include management, finance, marketing, AI, cybersecurity and business analytics.',
            'points' => ['Management', 'Finance', 'Marketing', 'AI', 'Cybersecurity', 'Business Analytics'],
            'best_for' => 'Students looking for a premium European Master\'s experience with specialised business and technology-related study options.',
            'qualification' => 'Subject to IBS academic mapping and admission approval.',
        ],
        (object)[
            'slug' => 'moldova',
            'name' => 'Moldova',
            'label' => 'EASTERN EUROPE',
            'university' => 'USPEE "Constantin Stere University"',
            'image' => 'https://images.pexels.com/photos/346823/pexels-photo-346823.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=950&w=760',
            'position' => 'right',
            'description' => 'Moldova is positioned as a flexible and cost-efficient European degree completion destination. Students complete a relevant Level 7 Diploma carrying 120 UK credits, aligned with their intended Master\'s specialisation. The Moldova route is connected with USPEE "Constantin Stere University", where students may progress to the university-defined final stage, subject to admission, residency requirements, academic compatibility and credit mapping.',
            'points' => ['Level 7 Diploma carrying 120 UK credits', 'Subject-aligned pathway', 'Flexible and cost-efficient completion'],
            'best_for' => 'Students looking for a cost-effective European Master\'s completion route with flexible academic options.',
            'qualification' => 'Subject to admission, residency requirements, academic compatibility and credit mapping.',
        ],
        (object)[
            'slug' => 'romania',
            'name' => 'Romania',
            'label' => 'SOUTHEAST EUROPE',
            'university' => 'Aurel Vlaicu University of Arad',
            'image' => 'https://images.pexels.com/photos/207684/pexels-photo-207684.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=950&w=760',
            'position' => 'left',
            'description' => 'Romania provides a practical European study option for students who want to complete the final university stage on campus. The Romania route is connected with Aurel Vlaicu University of Arad, where eligible students may progress after completing the 120 UK-credit Level 7 stage. Final progression is subject to admission, credit recognition, curriculum alignment and selected programme requirements.',
            'points' => ['On-campus final-stage Master\'s experience', 'Direct European progression', '120 UK-credit Level 7 stage'],
            'best_for' => 'Students looking for direct European progression and an on-campus final-stage Master\'s experience.',
            'qualification' => 'Final progression is subject to admission, credit recognition, curriculum alignment and selected programme requirements.',
        ],
    ]);

    $benefits = collect([
        (object)['num' => '01', 'title' => 'Flexible First Phase', 'desc' => 'Start your postgraduate journey online or through Maverick while continuing your professional and personal commitments.'],
        (object)['num' => '02', 'title' => 'Cost-Efficient Route', 'desc' => 'Reduce the period of full-time overseas study and lower your overall exposure to international tuition and living costs.'],
        (object)['num' => '03', 'title' => 'European University Progression', 'desc' => 'Progress towards final-stage Master\'s completion in Hungary, Moldova or Romania, subject to university approval.'],
        (object)['num' => '04', 'title' => 'Academic Mapping Support', 'desc' => 'Maverick supports students with documentation, credit mapping coordination, university communication and progression guidance.'],
        (object)['num' => '05', 'title' => 'Career-Focused Learning', 'desc' => 'Develop advanced academic, analytical, leadership, research and management capabilities for professional growth.'],
    ]);

    $audience = collect([
        'Graduates who want to progress towards an international Master\'s degree',
        'Working professionals seeking postgraduate advancement',
        'Students looking for a cost-effective European study route',
        'Learners who want to begin online before travelling abroad',
        'Applicants interested in management, IT, finance, marketing, AI, cybersecurity, data analytics and related fields',
        'Students who need structured support for academic progression and documentation',
    ]);

    $requirements = collect([
        'Recognised Bachelor\'s degree or equivalent qualification',
        'Academic transcripts and certificates',
        'Valid passport or national ID',
        'Updated CV',
        'Statement of purpose or motivation letter',
        'English-language evidence, where required',
        'Successful academic and admissions review',
        'Additional documents, interviews or legalisation, where required by the destination university',
    ]);

    $steps = collect([
        (object)['num' => '01', 'title' => 'Academic Consultation', 'desc' => 'Choose your preferred destination and intended Master\'s specialisation.'],
        (object)['num' => '02', 'title' => 'Eligibility Review', 'desc' => 'Submit your academic documents, CV, passport and required records.'],
        (object)['num' => '03', 'title' => 'Phase 1 Enrolment', 'desc' => 'Begin the Level 7 Diploma / subject-aligned Level 7 stage with Maverick.'],
        (object)['num' => '04', 'title' => 'University Mapping', 'desc' => 'Maverick coordinates academic records for credit evaluation and progression review.'],
        (object)['num' => '05', 'title' => 'Conditional or Final Offer', 'desc' => 'The destination university confirms eligibility, remaining modules, fees and conditions.'],
        (object)['num' => '06', 'title' => 'Visa & Travel Preparation', 'desc' => 'Complete tuition payment, documentation, accommodation and visa formalities.'],
        (object)['num' => '07', 'title' => 'University Completion', 'desc' => 'Complete the final semester/year, dissertation, project or final assessment and graduate.'],
    ]);

    $facultyInsights = collect([
        (object)[
            'title' => 'Navigating International Master\'s Progression',
            'badge' => 'Master\'s Pathway',
            'image_url' => 'https://images.pexels.com/photos/256541/pexels-photo-256541.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=560&w=640',
            'link_url' => route('contact'),
        ],
        (object)[
            'title' => 'Why Academic Mapping Matters for Your Study Route',
            'badge' => 'Academic Mapping',
            'image_url' => 'https://images.pexels.com/photos/207691/pexels-photo-207691.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=560&w=640',
            'link_url' => route('contact'),
        ],
        (object)[
            'title' => 'Building a Career-Focused Learning Journey',
            'badge' => 'Career Focus',
            'image_url' => 'https://images.pexels.com/photos/346823/pexels-photo-346823.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=560&w=640',
            'link_url' => route('contact'),
        ],
        (object)[
            'title' => 'From Level 7 Diploma to European Completion',
            'badge' => 'Progression',
            'image_url' => 'https://images.pexels.com/photos/16356273/pexels-photo-16356273.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=560&w=640',
            'link_url' => route('contact'),
        ],
    ]);
@endphp

{{-- ═══════════════════════════════════════════
     HERO
═══════════════════════════════════════════ --}}
<section class="cinematic-hero mp-hero" aria-label="International Master's Pathway Program" data-testid="mp-hero">
    <div class="cinematic-hero__bg" aria-hidden="true">
        <div class="cinematic-hero__bg-image" style="background-image: url('{{ $hero->background_image }}')"></div>
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
        <span class="cinematic-hero__eyebrow">
            <span class="cinematic-hero__eyebrow-line"></span>
            {{ $hero->tag }}
        </span>
        <h1 class="cinematic-hero__title">
            {{ $hero->heading }}<br>
            <em>{{ $hero->heading_italic }}</em>
        </h1>
        <p class="cinematic-hero__description">{{ $hero->sub_heading }}</p>
        <p class="mp-hero__desc">{{ $hero->description }}</p>
        <p class="mp-hero__desc">{{ $hero->description2 }}</p>
        <div class="mp-hero__ctas">
            <a href="#enquire" class="btn btn--primary">Check Eligibility</a>
            <a href="{{ route('contact') }}" class="btn btn--secondary">Speak to an Advisor</a>
        </div>
        <div class="mp-hero__route" aria-hidden="true">
            <span>Level 7 Diploma</span>
            <span class="mp-hero__route-arrow">→</span>
            <span>Partner University</span>
            <span class="mp-hero__route-arrow">→</span>
            <span>Master's Completion</span>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     1. PATHWAY OVERVIEW
═══════════════════════════════════════════ --}}
<section class="mp-overview section-wrapper section--light" aria-label="What Is the Master's Pathway Program" data-testid="mp-overview">
    <div class="container">
        <div class="mp-overview__grid">
            <div class="mp-overview__left">
                <span class="section-label"><span>YOUR PATHWAY</span></span>
                <h2 class="mp-overview__heading section-title">
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">What Is the Maverick Master's</span></span>
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Pathway Program?</em></span></span>
                </h2>
            </div>
            <div class="mp-overview__right">
                <p class="body-text fade-up">The Maverick Master's Pathway Program is a two-phase international study route created for learners who want to begin their postgraduate journey with flexibility and progress towards a European university qualification.</p>
                <p class="body-text fade-up">In Phase 1, students complete a Level 7 Diploma with 120 UK credits through Maverick. In Phase 2, eligible students may apply for university progression, where the completed Level 7 qualification is reviewed for credit mapping and advanced-entry consideration by the destination university.</p>
                <p class="body-text fade-up">This pathway is ideal for students who want to reduce the time and cost of full-time overseas study while still gaining international academic exposure during the university completion stage.</p>
            </div>
        </div>

        <div class="mp-pathway" data-testid="mp-pathway">
            <div class="mp-pathway__phase mp-pathway__phase--1">
                <span class="mp-pathway__phase-label">PHASE 1</span>
                <h3 class="mp-pathway__phase-title">Level 7 Diploma</h3>
                <p class="mp-pathway__phase-meta">120 UK credits</p>
                <p class="mp-pathway__phase-desc">Complete your first phase online with Maverick.</p>
            </div>
            <div class="mp-pathway__connector" aria-hidden="true">
                <span class="mp-pathway__connector-line"></span>
                <span class="mp-pathway__connector-dot"></span>
            </div>
            <div class="mp-pathway__phase mp-pathway__phase--2">
                <span class="mp-pathway__phase-label">PHASE 2</span>
                <h3 class="mp-pathway__phase-title">Partner University</h3>
                <p class="mp-pathway__phase-meta">Final-stage study</p>
                <p class="mp-pathway__phase-desc">Hungary / Moldova / Romania</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     2. HOW THE PATHWAY WORKS
═══════════════════════════════════════════ --}}
<section class="mp-how section-wrapper section--light" aria-label="How the Master's Pathway Works" data-testid="mp-how">
    <div class="container">
        <div class="mp-how__header">
            <span class="section-label"><span>THE JOURNEY</span></span>
            <h2 class="mp-how__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">How the Master's Pathway</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Works</em></span></span>
            </h2>
        </div>

        <div class="mp-how__phases">
            <div class="mp-how__phase">
                <span class="mp-how__phase-num">PHASE 01</span>
                <h3 class="mp-how__phase-title">Level 7 Diploma</h3>
                <p class="mp-how__phase-sub">Subject-Aligned Level 7 Diploma</p>
                <dl class="mp-how__phase-facts">
                    <div><dt>Duration</dt><dd>Approx. 6 months</dd></div>
                    <div><dt>Mode</dt><dd>Online / With Maverick</dd></div>
                    <div><dt>Academic Evaluation</dt><dd>Credit mapping and university review</dd></div>
                </dl>
            </div>

            <div class="mp-how__phase">
                <span class="mp-how__phase-num">PHASE 02</span>
                <h3 class="mp-how__phase-title">Final Stage at Partner University</h3>
                <p class="mp-how__phase-sub">Final semester / final year</p>
                <dl class="mp-how__phase-facts">
                    <div><dt>Duration</dt><dd>Approx. 1 semester to 1 year</dd></div>
                    <div><dt>Mode</dt><dd>On Campus / Abroad</dd></div>
                    <div><dt>Completion</dt><dd>Dissertation, project or final university assessment</dd></div>
                    <div><dt>Partner University</dt><dd>University-defined Partner University</dd></div>
                </dl>
            </div>
        </div>

        <div class="mp-how__notice">
            <p>The final award is issued by the destination university after the student completes all required academic, assessment, residency, attendance and graduation requirements.</p>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     3. STUDY DESTINATIONS
═══════════════════════════════════════════ --}}
<section class="mp-destinations section-wrapper section--light" aria-label="Study Destinations" data-testid="mp-destinations">
    <div class="container">
        <div class="mp-destinations__header">
            <span class="section-label"><span>STUDY DESTINATIONS</span></span>
            <h2 class="mp-destinations__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">Study</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Destinations</em></span></span>
            </h2>
            <p class="body-text fade-up">Explore the three European destinations where you can complete the final stage of your Master's pathway.</p>
        </div>

        <div class="mp-destinations__list">
            @foreach($destinations as $dest)
            <article class="mp-dest mp-dest--{{ $dest->position }}" data-testid="mp-dest-{{ $dest->slug }}">
                <div class="mp-dest__media">
                    <img class="mp-dest__image" src="{{ $dest->image }}" alt="Study in {{ $dest->name }}" loading="lazy" width="760" height="950">
                    <div class="mp-dest__overlay" aria-hidden="true"></div>
                    <span class="mp-dest__country">{{ $dest->name }}</span>
                </div>
                <div class="mp-dest__content fade-up">
                    <span class="mp-dest__label">{{ $dest->label }}</span>
                    <h3 class="mp-dest__title card-title">Study in <em>{{ $dest->name }}</em></h3>
                    <p class="mp-dest__partner">{{ $dest->university }}</p>
                    <p class="mp-dest__description body-text">{{ $dest->description }}</p>
                    <ul class="mp-dest__points">
                        @foreach($dest->points as $point)
                        <li><span class="mp-dest__point-dot" aria-hidden="true"></span>{{ $point }}</li>
                        @endforeach
                    </ul>
                    <p class="mp-dest__best"><strong>Best Suited For:</strong> {{ $dest->best_for }}</p>
                    <p class="mp-dest__qualification">{{ $dest->qualification }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     4. WHY CHOOSE MAVERICK
═══════════════════════════════════════════ --}}
<section class="mp-why section-wrapper section--light" aria-label="Why Choose Maverick's Master's Pathway" data-testid="mp-why">
    <div class="container">
        <div class="mp-why__grid">
            <div class="mp-why__left">
                <span class="section-label"><span>WHY MAVERICK</span></span>
                <h2 class="mp-why__heading section-title">
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">Why Choose Maverick's</span></span>
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Master's Pathway?</em></span></span>
                </h2>
                <p class="mp-why__statement">A pathway built around your life.</p>
            </div>
            <div class="mp-why__right">
                @foreach($benefits as $benefit)
                <div class="mp-benefit fade-up">
                    <span class="mp-benefit__num">{{ $benefit->num }}</span>
                    <div class="mp-benefit__body">
                        <h3 class="mp-benefit__title">{{ $benefit->title }}</h3>
                        <p class="mp-benefit__desc">{{ $benefit->desc }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     5. WHO IS THIS PROGRAM FOR
═══════════════════════════════════════════ --}}
<section class="mp-audience section-wrapper section--light" aria-label="Who Is This Program For" data-testid="mp-audience">
    <div class="container">
        <div class="mp-audience__header">
            <span class="section-label"><span>WHO IS IT FOR</span></span>
            <h2 class="mp-audience__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">Who Is This Program</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>For?</em></span></span>
            </h2>
            <p class="mp-audience__statement">This Master's Pathway is suitable for:</p>
        </div>

        <div class="mp-audience__grid">
            @foreach($audience as $i => $item)
            <div class="mp-audience__item fade-up">
                <span class="mp-audience__index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <p class="mp-audience__text">{{ $item }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     6. ENTRY REQUIREMENTS
═══════════════════════════════════════════ --}}
<section class="mp-requirements section-wrapper section--light" aria-label="Entry Requirements" data-testid="mp-requirements">
    <div class="container">
        <div class="mp-requirements__grid">
            <div class="mp-requirements__intro">
                <span class="section-label"><span>ENTRY REQUIREMENTS</span></span>
                <h2 class="mp-requirements__heading section-title">
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">Entry</span></span>
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Requirements</em></span></span>
                </h2>
                <p class="body-text fade-up">Applicants are generally expected to provide:</p>
            </div>
            <ul class="mp-requirements__list">
                @foreach($requirements as $i => $req)
                <li class="mp-requirements__item fade-up">
                    <span class="mp-requirements__index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="mp-requirements__text">{{ $req }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     7. APPLICATION PROCESS
═══════════════════════════════════════════ --}}
<section class="mp-process section-wrapper section--light" aria-label="Application Process" data-testid="mp-process">
    <div class="container">
        <div class="mp-process__header">
            <span class="section-label"><span>APPLICATION PROCESS</span></span>
            <h2 class="mp-process__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">Application</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Process</em></span></span>
            </h2>
        </div>

        <div class="mp-timeline" data-testid="mp-timeline">
            <span class="mp-timeline__progress" aria-hidden="true"></span>
            @php
                $stepIcons = [
                    '01' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
                    '02' => '<path d="M9 12l2 2 4-4"/><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
                    '03' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>',
                    '04' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
                    '05' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 15l2 2 4-4"/>',
                    '06' => '<path d="M10.5 22l-3-5M3 14h18l-2.5 8H5.5z"/><path d="M10.5 22L9 14l3-5 3 5-1.5 8"/>',
                    '07' => '<circle cx="12" cy="8" r="6"/><path d="M15.5 13l2.5 8-6-3.5L6 21l2.5-8"/>',
                ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="mp-timeline__item {{ $i % 2 === 0 ? 'is-left' : 'is-right' }}" data-testid="mp-step-{{ $step->num }}">
                <div class="mp-timeline__marker">
                    <span class="mp-timeline__marker-num">{{ $step->num }}</span>
                    <span class="mp-timeline__marker-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $stepIcons[$step->num] ?? '<circle cx="12" cy="12" r="10"/>' !!}</svg>
                    </span>
                </div>
                <div class="mp-timeline__card fade-up">
                    <span class="mp-timeline__step">STEP {{ $step->num }}</span>
                    <h3 class="mp-timeline__title">{{ $step->title }}</h3>
                    <p class="mp-timeline__desc">{{ $step->desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     8. IMPORTANT ACADEMIC NOTICE
═══════════════════════════════════════════ --}}
<section class="mp-notice section-wrapper section--light" aria-label="Important Academic Notice" data-testid="mp-notice">
    <div class="container">
        <div class="mp-notice__panel">
            <div class="mp-notice__label">ACADEMIC NOTICE</div>
            <div class="mp-notice__content">
                <p>Progression is <strong>not automatic</strong>. Admission, credit recognition, advanced standing, specialisation availability, final duration and award requirements are determined by the destination university after formal academic evaluation.</p>
                <p>Students must meet all university admission, language, attendance, residency, assessment, dissertation, financial and immigration requirements. Programme structures, fees, visa rules and timelines may change as per university and government regulations.</p>
            </div>
        </div>
    </div>
</section>

@include('sections.faculty-insights')

{{-- ═══════════════════════════════════════════
     9. FINAL CTA
═══════════════════════════════════════════ --}}
<section class="mp-final" id="enquire" aria-label="Start Your Master's Journey" data-testid="mp-final">
    <div class="container">
        <div class="mp-final__inner">
            <span class="mp-final__eyebrow">Take the Next Step</span>
            <h2 class="mp-final__heading section-title">
                Start Your Master's Journey with <em>Maverick</em>
            </h2>
            <p class="mp-final__sub">A structured, flexible and cost-effective route towards a globally recognised degree.</p>
            <p class="mp-final__description body-text">
                Take the first step towards an international Master's degree through a structured, flexible and cost-effective European progression route. Speak to our admissions team today to check your eligibility, destination options and academic pathway.
            </p>
            <div class="mp-final__ctas">
                <a href="{{ route('contact') }}" class="btn mp-final__btn mp-final__btn--solid">Request Eligibility Assessment</a>
                <a href="mailto:admissions@mbalondon.org.uk" class="btn mp-final__btn mp-final__btn--outline">Speak to an Advisor</a>
            </div>
            <div class="mp-final__contact">
                <span>admissions@mbalondon.org.uk</span>
                <span>www.mbalondon.org.uk</span>
            </div>
        </div>
    </div>
</section>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof AnimationUtils === 'undefined' || typeof gsap === 'undefined') return;

    const reducedMotion = AnimationUtils.prefersReducedMotion;

    // ---------------------------------------------------------
    // GENERIC TEXT-REVEAL — animates every .text-reveal-inner on
    // this page (headings wrapped in .text-reveal-wrapper).
    // Each heading group is triggered by its own nearest section.
    // ---------------------------------------------------------
    function initTextReveals() {
        const inners = document.querySelectorAll('.page-mp .text-reveal-inner');
        if (!inners.length) return;

        // Group by their parent wrapper's nearest section so each
        // heading reveals when its own section enters the viewport.
        const sections = new Map();
        inners.forEach((el) => {
            const section = el.closest('section');
            if (!section) return;
            if (!sections.has(section)) sections.set(section, []);
            sections.get(section).push(el);
        });

        sections.forEach((els, section) => {
            gsap.fromTo(
                els,
                { y: '110%' },
                {
                    y: '0%',
                    duration: 0.9,
                    stagger: 0.12,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 78%',
                        once: true,
                    },
                },
            );
        });
    }

    // ---------------------------------------------------------
    // REDUCED MOTION — reveal everything, no animation
    // ---------------------------------------------------------
    if (reducedMotion) {
        gsap.set('.page-mp .text-reveal-inner', { y: '0%' });
        gsap.set('.page-mp .fade-up, .page-mp .mp-pathway__phase, .page-mp .mp-how__phase, .page-mp .mp-dest__content', {
            clearProps: 'all', opacity: 1, y: 0,
        });
        return;
    }

    // Text reveals (headings)
    initTextReveals();

    // Pathway phases + connector
    gsap.from('.mp-pathway__phase--1', { scrollTrigger: { trigger: '.mp-pathway', start: 'top 80%', once: true }, opacity: 0, x: -40, duration: 0.7, ease: 'power3.out' });
    gsap.from('.mp-pathway__connector-line', { scrollTrigger: { trigger: '.mp-pathway', start: 'top 80%', once: true }, scaleX: 0, transformOrigin: 'left center', duration: 0.6, ease: 'power2.out' });
    gsap.from('.mp-pathway__phase--2', { scrollTrigger: { trigger: '.mp-pathway', start: 'top 80%', once: true }, opacity: 0, x: 40, duration: 0.7, ease: 'power3.out' });

    // How phases
    gsap.from('.mp-how__phase', { scrollTrigger: { trigger: '.mp-how__phases', start: 'top 80%', once: true }, opacity: 0, y: 40, stagger: 0.2, duration: 0.7, ease: 'power3.out' });

    // Benefits / audience / requirements / destination content
    AnimationUtils.fadeUp('.mp-benefit', { stagger: 0.1 });
    AnimationUtils.fadeUp('.mp-audience__item', { stagger: 0.06 });
    AnimationUtils.fadeUp('.mp-requirements__item', { stagger: 0.06 });
    AnimationUtils.fadeUp('.mp-dest__content', { stagger: 0.1 });

    // Generic fallback: reveal ANY remaining .fade-up element that has
    // no ScrollTrigger of its own yet (e.g. overview/destinations/
    // requirements intro paragraphs), and make sure it is never stuck
    // hidden even if the trigger point was already passed on load.
    document.querySelectorAll('.page-mp .fade-up').forEach((el) => {
        // Skip elements already handled by an explicit animation above.
        if (el.classList.contains('mp-benefit')) return;
        if (el.classList.contains('mp-audience__item')) return;
        if (el.classList.contains('mp-requirements__item')) return;
        if (el.classList.contains('mp-timeline__card')) return;
        if (el.closest('.mp-dest__content')) return;

        el.setAttribute('data-mp-fade', 'done');
        gsap.fromTo(el,
            { opacity: 0, y: 30 },
            {
                opacity: 1, y: 0,
                duration: 0.6,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 92%',
                    once: true,
                },
            },
        );
    });

    // Guard: ensure any .fade-up already within the viewport on load is
    // revealed (in case ScrollTrigger's initial refresh misses it).
    requestAnimationFrame(() => {
        document.querySelectorAll('.page-mp .fade-up').forEach((el) => {
            if (parseFloat(getComputedStyle(el).opacity) < 0.5) {
                const r = el.getBoundingClientRect();
                if (r.top < window.innerHeight && r.bottom > 0) {
                    gsap.to(el, { opacity: 1, y: 0, duration: 0.4, overwrite: true });
                }
            }
        });
    });

    // Timeline — progressive line draw + card reveal
    const timeline = document.querySelector('.mp-timeline');
    const progress = document.querySelector('.mp-timeline__progress');
    if (timeline) {
        if (progress) {
            gsap.fromTo(progress, { height: '0%' }, {
                height: '100%',
                ease: 'none',
                scrollTrigger: {
                    trigger: timeline,
                    start: 'top 70%',
                    end: 'bottom 60%',
                    scrub: 0.6,
                },
            });
        }
        const cards = timeline.querySelectorAll('.mp-timeline__card');
        gsap.fromTo(cards, { opacity: 0, x: (i) => (i % 2 === 0 ? -40 : 40), y: 20 }, {
            scrollTrigger: { trigger: timeline, start: 'top 70%', once: true },
            opacity: 1, x: 0, y: 0, stagger: 0.15, duration: 0.6, ease: 'power3.out',
        });
    }
});
</script>
@endpush
