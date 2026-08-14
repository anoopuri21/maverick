@extends('layouts.app')

@section('title', ($program->title ?? 'Programme') . ' | Maverick Business Academy')
@section('meta_description', $program->short_description ?? 'Explore this Maverick Business Academy programme.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/program-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@push('head')
    @include('partials.seo-meta', ['seo' => $program->seo])
@endpush

@if(!empty($program->seo) && !empty($program->seo->custom_body_scripts))
@push('scripts')
    {!! $program->seo->custom_body_scripts !!}
@endpush
@endif

@section('content')
@php
    // ------------------------------------------------------------------
    // PROGRAMME CONTENT MODEL — admin-driven (from Program model JSON casts)
    // Normalized via model accessors so the blade stays clean.
    // Every section is optional; render only if content exists.
    // ------------------------------------------------------------------
    $cat = $program->programCategory;
    $highlights          = $program->highlights_list;
    $recognition         = $program->recognition_list;
    $snapshot            = $program->snapshot_list;
    $benefits            = $program->benefits_list;
    $learning            = $program->learning_list;
    $careers             = $program->careers_list;
    $structure           = $program->structure_list;
    $support             = $program->support_list;
    $university          = $program->university_object;
    $accreditationGroups = $program->accreditation_groups_list;
    $testimonials        = $program->testimonials_list;
    $fees                = $program->fees_list;
    $faqs                = $program->faqs;
    $reviews             = $program->reviews_list;
    $ourStoryTestimonials = $program->review_testimonial_objects;
    $sectionNav          = $program->section_nav;
@endphp

<div class="page-pd">
    <div class="pd-progress" aria-hidden="true"></div>

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

        {{-- ============ ACCREDITATION LOGO STRIP (trust opener, after hero) ============ --}}
    @if($recognition->count())
    <section class="pd-logo-strip" aria-label="Accredited and recognised by" data-testid="pd-logo-strip">
        <div class="container pd-logo-strip__inner">
            <h2 class="pd-logo-strip__label">Accredited &amp; Recognised By</h2>
            <div class="pd-logo-strip__marquee" data-pd-marquee data-lenis-prevent>
                <div class="pd-logo-strip__track">
                    @foreach($recognition->merge($recognition) as $r)
                        <div class="pd-logo-strip__logo">
                            @if(!empty($r['logo']))
                                <img src="{{ $r['logo'] }}" alt="{{ $r['name'] }}" loading="lazy">
                            @else
                                <span>{{ $r['name'] }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ============ LEFT-SIDE SCROLLSPY DOTS ============ --}}
    @if($sectionNav->count())
    <nav class="pd-dots" aria-label="Programme sections" data-pd-dots data-testid="pd-dots">
        @foreach($sectionNav as $s)
            <a class="pd-dots__item" href="#{{ $s['id'] }}" data-pd-dot="{{ $s['id'] }}" aria-label="{{ $s['label'] }}">
                <span class="pd-dots__dot" aria-hidden="true"></span>
                <span class="pd-dots__label">{{ $s['label'] }}</span>
            </a>
        @endforeach
    </nav>
    @endif

    
{{-- ================================================================
         EXPERIMENT · TWO-COLUMN LAYOUT (7:3)
         Quick Highlights → Reviews run in the left column; the
         "Programme at a Glance" (snapshot) is a sticky box on the right.
         ================================================================ --}}
    <div class="pd-layout">
        <div class="pd-layout__main">

{{-- ============ M2 · INTRO (Overview + Highlights, editorial pair) ============ --}}
    @if($highlights->count() || $program->description)
    <section id="overview" class="pd-intro pd-band--paper" aria-label="Programme introduction" data-testid="pd-intro" data-reveal>
        <div class="container pd-intro__grid">
            <div class="pd-intro__editorial">
                <span class="pd-section-label">Overview</span>
                <h2 class="pd-section-title">Programme <em>Overview</em></h2>
                @if($program->description)
                    <div class="pd-intro__body">{!! $program->description !!}</div>
                @endif
            </div>
            @if($highlights->count())
            <div class="pd-intro__stats">
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
            @endif
        </div>
    </section>
    @endif

    

    {{-- ============ STEP 4 · WHY CHOOSE ============ --}}
    @if($benefits->count())
    <section id="why-choose" class="pd-benefits pd-band--warm" aria-label="Why choose this programme" data-testid="pd-benefits" data-reveal>
        <div class="container">
            <span class="pd-section-label">Why Choose</span>
            <h2 class="pd-section-title">Why Choose This <em>Programme?</em></h2>
            <div class="pd-benefits__grid">
                @foreach($benefits as $i => $b)
                    <div class="pd-benefits__card">
                        <span class="pd-benefits__icon" aria-hidden="true">
                            <i data-lucide="{{ $b['icon'] ?? 'sparkles' }}"></i>
                        </span>
                        <h3>{{ $b['title'] }}</h3>
                        <div class="pd-benefits__desc">{!! $b['desc'] ?? '' !!}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    {{-- ============ M3 · OUTCOMES & CAREERS (Learn list + Careers, 2-col editorial) ============ --}}
    @if($learning->count() || $careers->count())
    <section id="careers" class="pd-outcomes pd-band--paper" aria-label="Learning outcomes and careers" data-testid="pd-outcomes" data-reveal>
        <div class="container pd-outcomes__grid">
            @if($learning->count())
            <div class="pd-outcomes__col pd-outcomes__col--learn">
                <span class="pd-section-label">What You'll Learn</span>
                <h2 class="pd-section-title">Learning <em>Outcomes</em></h2>
                <p class="pd-outcomes__sub">Students will learn to:</p>
                <ol class="pd-learn__list pd-learn__list--single">
                    @foreach($learning as $i => $cap)
                        <li class="pd-learn__item">
                            <span class="pd-learn__tick" aria-hidden="true">✓</span>
                            <span>{{ $cap }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
            @endif
            @if($careers->count())
            <div class="pd-outcomes__col pd-outcomes__col--careers">
                <span class="pd-section-label">Careers</span>
                <h2 class="pd-section-title">Where This Degree Can <em>Take You</em></h2>
                <p class="pd-outcomes__sub">Potential careers include:</p>
                <div class="pd-careers__grid pd-careers__grid--vertical">
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
            @endif
        </div>
    </section>
    @endif



    {{-- ============ STEP 5 · PROGRAMME STRUCTURE ============ --}}
    @if($structure->count())
    <section class="pd-structure pd-band--warm" id="structure" aria-label="Programme structure" data-testid="pd-structure">
        <div class="container">
            <span class="pd-section-label">Curriculum</span>
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
                                            <span class="pd-structure__module-title">{{ is_array($module) ? ($module['title'] ?? ('Module '.($mi + 1))) : $module }}</span>
                                        </summary>
                                        @if(is_array($module))
                                            <div class="pd-structure__module-content">
                                                @if(!empty($module['overview']))
                                                    <div class="pd-structure__module-desc">{!! $module['overview'] ?? '' !!}</div>
                                                    @elseif(!empty($module['desc']))
                                                    <div class="pd-structure__module-desc">{!! $module['desc'] ?? '' !!}</div>
                                                @endif
                                                @if(!empty($module['list']) && is_array($module['list']))
                                                    <ul class="pd-structure__module-list">
                                                        @foreach($module['list'] as $li)
                                                            <li class="pd-structure__module-li">
                                                                <span class="pd-structure__module-tick" aria-hidden="true">✓</span>
                                                                <span>{{ $li }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
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
    <section id="university" class="pd-uni pd-band--paper" aria-label="About the awarding university" data-testid="pd-uni" data-reveal>
        <div class="container">
            <span class="pd-section-label">University</span>
            <h2 class="pd-section-title">A Globally Connected <em>University</em></h2>
            <div class="pd-uni__inner {{ !empty($university->image) ? 'pd-uni__inner--with-image' : '' }}">
                @if(!empty($university->image))
                <div class="pd-uni__media">
                    <img src="{{ media_url($university->image) }}" alt="{{ $university->name }}" loading="lazy">
                </div>
                @endif
                <div class="pd-uni__body">
                    <div class="pd-uni__body-content">{!! $university->description ?? '' !!}</div>
                    @if($university->establishment)<span class="pd-uni__meta">{{ $university->establishment }}</span>@endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ============ STEP 6 · ACCREDITATION ============ --}}
    @if($accreditationGroups->count())
    <section id="accreditation" class="pd-accred pd-band--tint" aria-label="Accreditation and recognition" data-testid="pd-accred" data-reveal>
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
    <section id="support" class="pd-support pd-band--paper" aria-label="Why study through Maverick" data-testid="pd-support" data-reveal>
        <div class="container">
            <span class="pd-section-label">Support</span>
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
    <section id="testimonials" class="pd-testimonials pd-band--warm" aria-label="Student success stories" data-testid="pd-testimonials" data-reveal>
        <div class="container">
            <span class="pd-section-label">Stories</span>
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
    <section id="fees" class="pd-fees pd-band--navy" aria-label="Fees and scholarships" data-testid="pd-fees" data-reveal>
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

    {{-- ============ STEP 8 · FAQ ============ --}}
    @if($faqs->count())
    <section id="faq" class="pd-faq" aria-label="Frequently asked questions" data-testid="pd-faq" data-reveal>
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
    <section class="pd-enquire pd-band--warm" id="enquire" aria-label="Enquire about this programme" data-testid="pd-enquire">
        <div class="pd-enquire__particles" aria-hidden="true">
            @for($i = 1; $i <= 12; $i++)
                <span class="pd-enquire__orb"></span>
            @endfor
        </div>
        <div class="container pd-enquire__inner">
            <div class="pd-enquire__intro">
                <h2 class="pd-section-title pd-section-title--single">Enquire About This Programme</h2>
                <p class="body-text">Speak to our admissions team to check your eligibility and get started.</p>
                <div class="pd-enquire__visual">
                    <img
                        src="{{ asset('assets/images/programs/enquire-seminar.jpg') }}"
                        alt="Students collaborating in a lecture hall"
                        width="1600"
                        height="900"
                    >
                </div>
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
                        <label for="pd-qualification">Highest qualification</label>
                        <select id="pd-qualification" name="qualification">
                            <option value="">Select qualification</option>
                            <option value="high-school">High School / Secondary</option>
                            <option value="diploma">Diploma</option>
                            <option value="bachelor">Bachelor's Degree</option>
                            <option value="master">Master's Degree</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="pd-form__field pd-form__field--full">
                        <label for="pd-message">Message</label>
                        <textarea id="pd-message" name="message" rows="3"></textarea>
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


    {{-- ============ FINAL CTA ============ --}}
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

    // Scroll progress bar
    const progress = document.querySelector('.pd-progress');
    if (progress) {
        const updateProgress = () => {
            const scrollable = document.documentElement.scrollHeight - window.innerHeight;
            const pct = scrollable > 0 ? (window.scrollY / scrollable) * 100 : 0;
            progress.style.transform = `scaleX(${pct / 100})`;
        };
        updateProgress();
        window.addEventListener('scroll', updateProgress, { passive: true });
    }

    // Reveal-on-scroll for sections/cards (vanilla, respects reduced-motion).
    // Elements with [data-reveal] start hidden via CSS and animate in when
    // they enter the viewport. Headings reveal just before body content.
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const revealTargets = document.querySelectorAll('[data-reveal]');
    if (revealTargets.length && !prefersReduced && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    io.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -12% 0px', threshold: 0.08 });
        revealTargets.forEach((el) => io.observe(el));
    } else if (prefersReduced) {
        // Reduced motion: show everything immediately (never hide content).
        revealTargets.forEach((el) => el.classList.add('is-revealed'));
    }

    // Fallback: if JS animation fails for any reason, reveal everything so
    // content is never left hidden.
    window.setTimeout(() => {
        document.querySelectorAll('[data-reveal]:not(.is-revealed)').forEach((el) => el.classList.add('is-revealed'));
    }, 3000);

    if (typeof AnimationUtils !== 'undefined' && typeof gsap !== 'undefined' && !AnimationUtils.prefersReducedMotion) {
        AnimationUtils.fadeUp('.pd-hero__content', {});
    }
});
</script>
@endpush
