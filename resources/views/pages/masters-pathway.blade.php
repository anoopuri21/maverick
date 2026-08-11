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
        'background_image' => 'https://images.pexels.com/photos/1462630/pexels-photo-1462630.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600',
    ];

    $destinations = collect([
        (object)[
            'slug' => 'hungary',
            'name' => 'Hungary',
            'label' => 'CENTRAL EUROPE',
            'university' => 'IBS International Business School',
            'image' => 'https://images.pexels.com/photos/16356273/pexels-photo-16356273.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=950&w=760',
            'position' => 'left',
            'description' => 'A premium Central European study experience in an academic and business-driven environment, combining a rich educational heritage with a vibrant international atmosphere.',
            'points' => ['Management', 'Finance', 'Marketing', 'AI', 'Cybersecurity', 'Business Analytics'],
            'best_for' => 'Graduates and professionals seeking a business-focused European Master\'s pathway.',
            'qualification' => 'Subject to academic mapping and admission approval.',
        ],
        (object)[
            'slug' => 'moldova',
            'name' => 'Moldova',
            'label' => 'EASTERN EUROPE',
            'university' => 'USPEE "Constantin Stere University"',
            'image' => 'https://images.pexels.com/photos/346823/pexels-photo-346823.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=950&w=760',
            'position' => 'right',
            'description' => 'A broad and subject-aligned European study route designed to be accessible, flexible and well matched to a learner\'s academic background and career direction.',
            'points' => ['Flexible study route', 'Subject-aligned pathway', 'Structured academic progression', 'Cost-effective option'],
            'best_for' => 'Learners seeking a broad, subject-aligned pathway toward an international Master\'s.',
            'qualification' => 'Subject to admission, residency requirements, academic compatibility and credit mapping.',
        ],
        (object)[
            'slug' => 'romania',
            'name' => 'Romania',
            'label' => 'SOUTHEAST EUROPE',
            'university' => 'Aurel Vlaicu University of Arad',
            'image' => 'https://images.pexels.com/photos/207684/pexels-photo-207684.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=950&w=760',
            'position' => 'left',
            'description' => 'A practical European study option offering a strong on-campus final-stage experience within a welcoming and internationally minded academic environment.',
            'points' => ['On-campus final-stage experience', 'Practical European study option', 'International academic environment', 'Structured progression'],
            'best_for' => 'Students who want a hands-on, on-campus European Master\'s completion experience.',
            'qualification' => 'Final progression is subject to admission, credit recognition, curriculum alignment and selected programme requirements.',
        ],
    ]);

    $benefits = collect([
        (object)['num' => '01', 'title' => 'Flexible First Phase', 'desc' => 'Begin your Level 7 Diploma online with Maverick, fitting your studies around work and life.'],
        (object)['num' => '02', 'title' => 'Cost-Efficient Route', 'desc' => 'A structured and affordable route towards an international Master\'s degree, without the full cost of overseas study from day one.'],
        (object)['num' => '03', 'title' => 'European University Progression', 'desc' => 'Progress towards final-stage Master\'s completion at a partner university in Hungary, Moldova or Romania.'],
        (object)['num' => '04', 'title' => 'Academic Mapping Support', 'desc' => 'Our team supports academic mapping and documentation, so your Level 7 achievement is positioned clearly for university review.'],
        (object)['num' => '05', 'title' => 'Career-Focused Learning', 'desc' => 'Learn through a curriculum aligned to today\'s business and professional landscape.'],
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
        (object)['num' => '01', 'title' => 'Academic Consultation', 'desc' => 'Discuss your background, goals and the pathway options available to you with our academic team.'],
        (object)['num' => '02', 'title' => 'Eligibility Review', 'desc' => 'Our team reviews your qualifications and profile to confirm your suitability for the pathway.'],
        (object)['num' => '03', 'title' => 'Phase 1 Enrolment', 'desc' => 'Enrol on your Level 7 Diploma with Maverick and begin your online first phase.'],
        (object)['num' => '04', 'title' => 'University Mapping', 'desc' => 'We support academic mapping and documentation with your chosen partner university.'],
        (object)['num' => '05', 'title' => 'Conditional or Final Offer', 'desc' => 'Receive a conditional or final offer based on the university\'s review of your academic profile.'],
        (object)['num' => '06', 'title' => 'Visa & Travel Preparation', 'desc' => 'Prepare your visa and travel arrangements for your on-campus final stage abroad.'],
        (object)['num' => '07', 'title' => 'University Completion', 'desc' => 'Complete your final semester or year on campus and earn your Master\'s degree.'],
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
        <div class="mp-hero__ctas">
            <a href="#enquire" class="btn btn--primary">Check Eligibility</a>
            <a href="{{ route('contact') }}" class="btn btn--secondary">Speak to an Advisor</a>
        </div>
        <div class="mp-hero__route" aria-hidden="true">
            <span>Level 7 Diploma</span>
            <span class="mp-hero__route-arrow">→</span>
            <span>Partner University</span>
            <span class="mp-hero__route-arrow">→</span>
            <span>Master\'s Completion</span>
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
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">What Is the Maverick Master\'s</span></span>
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Pathway Program?</em></span></span>
                </h2>
            </div>
            <div class="mp-overview__right">
                <p class="body-text fade-up">The Maverick International Master\'s Pathway Program offers a structured two-phase route towards an internationally recognised Master\'s degree. You begin with a Level 7 Diploma with Maverick, then progress to final-stage study at a partner university in Hungary, Moldova or Romania.</p>
                <p class="body-text fade-up">The pathway is designed for graduates and working professionals who want a flexible, cost-effective and globally focused route towards postgraduate completion, with academic mapping and documentation support throughout.</p>
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
<section class="mp-how section-wrapper" aria-label="How the Master's Pathway Works" data-testid="mp-how">
    <div class="container">
        <div class="mp-how__header">
            <span class="section-label"><span>THE JOURNEY</span></span>
            <h2 class="mp-how__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">How the Master\'s Pathway</span></span>
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
            <p class="body-text fade-up">Explore the three European destinations where you can complete the final stage of your Master\'s pathway.</p>
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
<section class="mp-why section-wrapper section--warm" aria-label="Why Choose Maverick's Master's Pathway" data-testid="mp-why">
    <div class="container">
        <div class="mp-why__grid">
            <div class="mp-why__left">
                <span class="section-label"><span>WHY MAVERICK</span></span>
                <h2 class="mp-why__heading section-title">
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">Why Choose Maverick\'s</span></span>
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Master\'s Pathway?</em></span></span>
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
            <p class="mp-audience__statement">Designed for ambitious learners at different stages of their professional journey.</p>
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
<section class="mp-requirements section-wrapper" aria-label="Entry Requirements" data-testid="mp-requirements">
    <div class="container">
        <div class="mp-requirements__grid">
            <div class="mp-requirements__intro">
                <span class="section-label"><span>ENTRY REQUIREMENTS</span></span>
                <h2 class="mp-requirements__heading section-title">
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">Entry</span></span>
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Requirements</em></span></span>
                </h2>
                <p class="body-text fade-up">To begin your Master\'s Pathway with Maverick, you will need to meet the following entry requirements.</p>
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

        <div class="mp-timeline">
            @foreach($steps as $i => $step)
            <div class="mp-timeline__item {{ $i % 2 === 0 ? 'is-left' : 'is-right' }}" data-testid="mp-step-{{ $step->num }}">
                <div class="mp-timeline__node"><span>{{ $step->num }}</span></div>
                <div class="mp-timeline__content fade-up">
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
<section class="mp-notice section-wrapper" aria-label="Important Academic Notice" data-testid="mp-notice">
    <div class="container">
        <div class="mp-notice__panel">
            <div class="mp-notice__label">ACADEMIC NOTICE</div>
            <div class="mp-notice__content">
                <p>Progression to a partner university and the award of a Master\'s degree are <strong>not automatic</strong>. The final award is issued by the destination university after the student completes all required academic, assessment, residency, attendance and graduation requirements, and is subject to admission approval, credit recognition, curriculum alignment and programme requirements. All progression remains subject to the relevant university\'s approval.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     9. FINAL CTA
═══════════════════════════════════════════ --}}
<section class="mp-final section-wrapper" id="enquire" aria-label="Start Your Master's Journey" data-testid="mp-final">
    <div class="container">
        <div class="mp-final__panel">
            <span class="section-label"><span>BEGIN TODAY</span></span>
            <h2 class="mp-final__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">Start Your Master\'s Journey</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">with <em>Maverick</em></span></span>
            </h2>
            <p class="mp-final__text body-text">Begin your international Master\'s journey with a structured, flexible and cost-effective route towards a globally recognised degree. Request an eligibility assessment today to find out if the Master\'s Pathway is right for you.</p>
            <div class="mp-final__cta">
                <a href="{{ route('contact') }}" class="btn btn--primary">Request Eligibility Assessment</a>
            </div>
            <div class="mp-final__contact">
                <span>Email: <a href="mailto:admissions@mbalondon.org.uk">admissions@mbalondon.org.uk</a></span>
                <span>Web: <a href="https://www.mbalondon.org.uk">www.mbalondon.org.uk</a></span>
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

    if (AnimationUtils.prefersReducedMotion) {
        gsap.set('.mp-overview .fade-up, .mp-benefit, .mp-audience__item, .mp-requirements__item, .mp-timeline__content, .mp-pathway__phase, .mp-how__phase, .mp-dest__content', {
            clearProps: 'all', opacity: 1, y: 0,
        });
        return;
    }

    // Pathway phases + connector
    gsap.from('.mp-pathway__phase--1', { scrollTrigger: { trigger: '.mp-pathway', start: 'top 80%', once: true }, opacity: 0, x: -40, duration: 0.7, ease: 'power3.out' });
    gsap.from('.mp-pathway__connector-line', { scrollTrigger: { trigger: '.mp-pathway', start: 'top 80%', once: true }, scaleX: 0, transformOrigin: 'left center', duration: 0.6, ease: 'power2.out' });
    gsap.from('.mp-pathway__phase--2', { scrollTrigger: { trigger: '.mp-pathway', start: 'top 80%', once: true }, opacity: 0, x: 40, duration: 0.7, ease: 'power3.out' });

    // How phases
    gsap.from('.mp-how__phase', { scrollTrigger: { trigger: '.mp-how__phases', start: 'top 80%', once: true }, opacity: 0, y: 40, stagger: 0.2, duration: 0.7, ease: 'power3.out' });

    // Benefits
    AnimationUtils.fadeUp('.mp-benefit', { stagger: 0.1 });
    AnimationUtils.fadeUp('.mp-audience__item', { stagger: 0.06 });
    AnimationUtils.fadeUp('.mp-requirements__item', { stagger: 0.06 });

    // Destinations
    document.querySelectorAll('.mp-dest__content').forEach(el => {
        gsap.from(el, { scrollTrigger: { trigger: el, start: 'top 85%', once: true }, opacity: 0, y: 30, duration: 0.7, ease: 'power3.out' });
    });

    // Timeline progressive draw + reveal
    const items = document.querySelectorAll('.mp-timeline__item');
    gsap.fromTo('.mp-timeline__content', { opacity: 0, y: 30 }, {
        scrollTrigger: { trigger: '.mp-timeline', start: 'top 75%', once: true },
        opacity: 1, y: 0, stagger: 0.15, duration: 0.6, ease: 'power3.out',
    });
});
</script>
@endpush
