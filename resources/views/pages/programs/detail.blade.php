@extends('layouts.app')

@section('title', ($program->title ?? 'Programme') . ' | Maverick Business Academy')
@section('meta_description', $program->short_description ?? 'Explore this Maverick Business Academy programme.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/program-detail.css') }}">
@endpush

@section('content')
@php
    // ------------------------------------------------------------------
    // STRUCTURED PROGRAMME DATA
    // Fields the Program model does not already provide are defined here,
    // kept separate from presentation for easy CMS migration later.
    // Marked values that need verification are flagged with "VERIFY:".
    // ------------------------------------------------------------------
    $facts = [
        ['label' => 'Degree Award', 'value' => $program->level ?: 'BSc (Hons)'],
        ['label' => 'Awarding University', 'value' => $program->partner_university ?: 'Girne American University'],
        ['label' => 'Duration', 'value' => $program->duration ?: 'VERIFY: 20–24 Months'],
        ['label' => 'Credits', 'value' => 'VERIFY: ECTS'],
        ['label' => 'Study Mode', 'value' => 'VERIFY: Online / Hybrid / Part-time'],
        ['label' => 'Intakes', 'value' => 'VERIFY: Intake'],
        ['label' => 'Assessment', 'value' => 'Assignments & examinations'],
        ['label' => 'Eligibility', 'value' => 'VERIFY: Entry requirements'],
    ];

    $highlights = [
        ['icon' => 'award', 'label' => 'Awarded By', 'value' => $program->partner_university ?: 'Girne American University'],
        ['icon' => 'clock', 'label' => 'Duration', 'value' => $program->duration ?: 'VERIFY'],
        ['icon' => 'layers', 'label' => 'Credits', 'value' => 'VERIFY: ECTS'],
        ['icon' => 'laptop', 'label' => 'Learning', 'value' => 'Flexible Learning'],
        ['icon' => 'briefcase', 'label' => 'Curriculum', 'value' => 'Industry-Focused'],
        ['icon' => 'badge', 'label' => 'Scholarships', 'value' => 'Available (verify)'],
    ];

    $recognition = [
        ['name' => 'IACBE', 'note' => 'International Accreditation Council for Business Education'],
        ['name' => 'YÖK', 'note' => 'Higher Education Council of Turkey'],
        ['name' => 'YÖDAK', 'note' => 'Higher Education Planning, Supervision, Accreditation and Coordination Committee (North Cyprus)'],
    ];

    $overview = $program->description ?: 'The Bachelor of Business Management develops the knowledge, practical skills, and leadership abilities required to manage organisations in today\'s competitive global business environment.';

    $benefits = [
        ['num' => '01', 'title' => 'Develop Leadership Skills', 'desc' => 'Learn how to lead teams and organisations.'],
        ['num' => '02', 'title' => 'Industry-Relevant Curriculum', 'desc' => 'Practical learning aligned with current business practices.'],
        ['num' => '03', 'title' => 'International Recognition', 'desc' => 'Graduate with an internationally recognised university qualification.'],
        ['num' => '04', 'title' => 'Career Progression', 'desc' => 'Prepare for leadership roles across multiple industries.'],
        ['num' => '05', 'title' => 'Flexible Learning', 'desc' => 'Designed to support both students and working professionals.'],
    ];

    $capabilities = [
        'Develop strategic thinking', 'Apply business management principles', 'Analyse financial information',
        'Understand marketing strategies', 'Improve organisational performance', 'Lead diverse teams',
        'Make ethical business decisions', 'Manage business operations effectively',
    ];

    $careers = [
        'Business Manager', 'Operations Manager', 'Marketing Executive', 'Human Resource Executive',
        'Business Analyst', 'Project Coordinator', 'Entrepreneur', 'Sales Manager',
        'Business Development Executive', 'Management Consultant',
    ];

    $years = [
        ['year' => 'Year 1', 'title' => 'Business Foundations', 'modules' => ['Principles of Management', 'Business Economics', 'Accounting Fundamentals', 'Marketing Essentials']],
        ['year' => 'Year 2', 'title' => 'Core Business Functions', 'modules' => ['Financial Management', 'Organisational Behaviour', 'Operations Management', 'Business Law']],
        ['year' => 'Year 3', 'title' => 'Advanced Business Management', 'modules' => ['Strategic Management', 'International Business', 'Entrepreneurship', 'Human Resource Management']],
        ['year' => 'Year 4', 'title' => 'Leadership, Strategy & Internship / Capstone', 'modules' => ['Leadership & Change', 'Business Strategy', 'Internship / Capstone Project', 'Global Business Perspectives']],
    ];

    $university = 'Girne American University (GAU), established in 1985, is one of Northern Cyprus\' leading universities. It offers internationally focused education with programmes designed to prepare graduates for the global workplace.';

    $accreditationGroups = [
        ['group' => 'Institutional Recognition', 'items' => ['GAU', 'YÖDAK']],
        ['group' => 'International Accreditation', 'items' => ['IACBE']],
        ['group' => 'Professional Recognition', 'items' => ['YÖK']],
    ];

    $support = [
        'Dedicated Academic Support', 'Experienced Faculty', 'Flexible Learning', 'Student Success Team',
        'Assignment Support', 'Affordable Instalments', 'Career Guidance', 'Graduation Support', 'Documentation Assistance',
    ];

    $testimonials = [
        ['name' => 'Student Name', 'country' => 'UAE', 'role' => 'Business Manager', 'quote' => 'Programme testimonial to be sourced from verified students.'],
    ];

    $faqs = $program->faqs->count()
        ? $program->faqs
        : collect([
            (object)['question' => 'Is the degree internationally recognised?', 'answer' => 'The degree is awarded by the partner university. Recognition details are available from our admissions team.'],
            (object)['question' => 'Can I study while working?', 'answer' => 'The programme supports flexible study for working professionals.'],
            (object)['question' => 'How are students assessed?', 'answer' => 'Assessment is through assignments and examinations.'],
            (object)['question' => 'What are the entry requirements?', 'answer' => 'Entry requirements are shared during your eligibility review.'],
            (object)['question' => 'Are scholarships available?', 'answer' => 'Scholarship availability is confirmed by the admissions team.'],
            (object)['question' => 'Can I continue to a master\'s degree?', 'answer' => 'Progression to postgraduate study is possible, subject to admission criteria.'],
        ]);

    $journey = ['OVERVIEW', 'LEARNING', 'CAREERS', 'STRUCTURE', 'UNIVERSITY', 'SUPPORT', 'FEES', 'APPLY'];
@endphp

<div class="page-pd">

    {{-- ============ STICKY JOURNEY NAV ============ --}}
    <nav class="pd-journey" data-testid="pd-journey" aria-label="Programme journey">
        <div class="container pd-journey__inner">
            @foreach($journey as $i => $step)
                <span class="pd-journey__step" data-journey-step="{{ $i + 1 }}">{{ $step }}</span>
            @endforeach
        </div>
    </nav>

    {{-- ============ 01 HERO ============ --}}
    <section class="pd-hero" aria-label="{{ $program->title }}" data-testid="pd-hero" data-journey-anchor="1">
        <div class="container pd-hero__inner">
            <div class="pd-hero__content">
                @if(isset($scholarship_verified) && $scholarship_verified)
                    <span class="pd-hero__ribbon">SCHOLARSHIPS AVAILABLE</span>
                @endif
                <span class="pd-hero__eyebrow">BACHELOR'S PROGRAMME</span>
                <h1 class="pd-hero__title">{{ $program->title }}</h1>
                <p class="pd-hero__desc">{{ $program->short_description ?? $overview }}</p>
                <div class="pd-hero__ctas">
                    <a href="#enquire" class="btn btn--primary">Apply Now</a>
                    <a href="{{ route('contact') }}" class="btn btn--secondary">Enquire Now</a>
                </div>
                <a href="#structure" class="pd-hero__brochure">Download Brochure</a>
            </div>
            <div class="pd-hero__media">
                <img src="{{ $program->image_url ?? asset('assets/images/homepage/mba.jpg') }}" alt="{{ $program->title }}" loading="eager" width="900" height="700">
            </div>
        </div>

        {{-- Quick fact panel --}}
        <div class="pd-facts">
            <div class="container">
                <div class="pd-facts__bar">
                    @foreach($highlights as $h)
                        <div class="pd-facts__item">
                            <span class="pd-facts__icon inline-icon" data-lucide="{{ $h['icon'] }}"></span>
                            <span class="pd-facts__label">{{ $h['label'] }}</span>
                            <span class="pd-facts__value">{{ $h['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============ 02 QUICK HIGHLIGHTS ============ --}}
    <section class="pd-highlights section-wrapper section--light" aria-label="Programme highlights" data-testid="pd-highlights">
        <div class="container pd-highlights__strip">
            @foreach($highlights as $h)
                <div class="pd-highlights__item">
                    <span class="inline-icon pd-highlights__icon" data-lucide="{{ $h['icon'] }}"></span>
                    <span class="pd-highlights__label">{{ $h['label'] }}</span>
                    <span class="pd-highlights__value">{{ $h['value'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ 03 RECOGNITION STRIP ============ --}}
    <section class="pd-recognition section-wrapper" aria-label="Recognition and accreditation" data-testid="pd-recognition">
        <div class="container pd-recognition__inner">
            <div class="pd-recognition__awarded">
                <span class="pd-recognition__label">Awarded By</span>
                <span class="pd-recognition__uni">{{ $program->partner_university ?: 'Girne American University' }}</span>
            </div>
            <div class="pd-recognition__list">
                @foreach($recognition as $r)
                    <details class="pd-recognition__item">
                        <summary>{{ $r['name'] }} <span class="inline-icon" data-lucide="chevron-down"></span></summary>
                        <p>{{ $r['note'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 04 SNAPSHOT ============ --}}
    <section class="pd-snapshot section-wrapper section--light" aria-label="Programme at a glance" data-testid="pd-snapshot">
        <div class="container">
            <div class="section-label"><span>AT A GLANCE</span></div>
            <h2 class="section-title">Programme at a <em>Glance</em></h2>
            <div class="pd-snapshot__grid">
                @foreach($facts as $f)
                    <div class="pd-snapshot__item">
                        <span class="pd-snapshot__label">{{ $f['label'] }}</span>
                        <span class="pd-snapshot__value">{{ $f['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 05 OVERVIEW ============ --}}
    <section class="pd-overview section-wrapper" aria-label="Programme overview" data-testid="pd-overview">
        <div class="container pd-overview__grid">
            <div class="pd-overview__statement">
                <span class="section-label"><span>OVERVIEW</span></span>
                <h2 class="pd-overview__title">Build the Business Skills That Move Organisations Forward</h2>
                <span class="pd-overview__watermark" aria-hidden="true">{{ $program->level ? strtoupper($program->level) : 'BSC' }}</span>
            </div>
            <div class="pd-overview__body">
                <p class="body-text">{{ $overview }}</p>
            </div>
        </div>
    </section>

    {{-- ============ 06 WHY CHOOSE ============ --}}
    <section class="pd-why section-wrapper section--light" aria-label="Why choose this programme" data-testid="pd-why">
        <div class="container pd-why__grid">
            <div class="pd-why__intro">
                <span class="section-label"><span>WHY CHOOSE</span></span>
                <h2 class="section-title">Why Choose This <em>Programme?</em></h2>
            </div>
            <div class="pd-why__list">
                <div class="pd-why__feature">
                    <span class="pd-why__num">{{ $benefits[0]['num'] }}</span>
                    <h3>{{ $benefits[0]['title'] }}</h3>
                    <p>{{ $benefits[0]['desc'] }}</p>
                </div>
                @foreach(array_slice($benefits, 1) as $b)
                    <div class="pd-why__item">
                        <span class="pd-why__num">{{ $b['num'] }}</span>
                        <div>
                            <h4>{{ $b['title'] }}</h4>
                            <p>{{ $b['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 07 WHAT YOU'LL LEARN ============ --}}
    <section class="pd-learn section-wrapper" aria-label="What you'll learn" data-testid="pd-learn" data-journey-anchor="2">
        <div class="container pd-learn__grid">
            <div class="pd-learn__intro">
                <span class="section-label"><span>LEARNING OUTCOMES</span></span>
                <h2 class="section-title">What You'll <em>Learn</em></h2>
                <p class="body-text">Students will learn to:</p>
                <span class="pd-learn__count">{{ count($capabilities) }} CORE CAPABILITIES</span>
            </div>
            <div class="pd-learn__list">
                @foreach($capabilities as $i => $cap)
                    <div class="pd-learn__item">
                        <span class="pd-learn__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="pd-learn__text">{{ $cap }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 08 CAREERS ============ --}}
    <section class="pd-careers section-wrapper section--light" aria-label="Career opportunities" data-testid="pd-careers" data-journey-anchor="3">
        <div class="container">
            <div class="section-label"><span>CAREERS</span></div>
            <h2 class="section-title">Where This Degree Can <em>Take You</em></h2>
            <p class="body-text">Potential careers include:</p>
            <div class="pd-careers__grid">
                @foreach($careers as $i => $career)
                    <div class="pd-careers__item">
                        <span class="pd-careers__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span>{{ $career }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 09 PROGRAMME STRUCTURE ============ --}}
    <section class="pd-structure section-wrapper" id="structure" aria-label="Programme structure" data-testid="pd-structure" data-journey-anchor="4">
        <div class="container">
            <div class="section-label"><span>STRUCTURE</span></div>
            <h2 class="section-title">Programme <em>Structure</em></h2>
            <div class="pd-structure__accordion">
                @foreach($years as $i => $year)
                    <div class="pd-structure__item {{ $i === 0 ? 'is-open' : '' }}">
                        <button class="pd-structure__head">
                            <span class="pd-structure__year">{{ $year['year'] }}</span>
                            <span class="pd-structure__title">{{ $year['title'] }}</span>
                            <span class="inline-icon pd-structure__chevron" data-lucide="chevron-down"></span>
                        </button>
                        <div class="pd-structure__body">
                            <ul class="pd-structure__modules">
                                @foreach($year['modules'] as $m)
                                    <li>{{ $m }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 10 ABOUT UNIVERSITY ============ --}}
    <section class="pd-uni section-wrapper section--light" aria-label="About {{ $program->partner_university ?? 'the university' }}" data-testid="pd-uni" data-journey-anchor="5">
        <div class="container pd-uni__grid">
            <div class="pd-uni__media">
                <img src="{{ asset('assets/images/homepage/mba.jpg') }}" alt="{{ $program->partner_university ?? 'University campus' }}" loading="lazy">
            </div>
            <div class="pd-uni__content">
                <span class="section-label"><span>THE UNIVERSITY</span></span>
                <h2 class="section-title">A Globally Connected <em>University</em></h2>
                <p class="body-text">{{ $university }}</p>
            </div>
        </div>
    </section>

    {{-- ============ 11 ACCREDITATION ============ --}}
    <section class="pd-accred section-wrapper" aria-label="Accreditation and recognition" data-testid="pd-accred">
        <div class="container">
            <div class="section-label"><span>ACCREDITATION</span></div>
            <h2 class="section-title">Accreditation & <em>Recognition</em></h2>
            <div class="pd-accred__grid">
                @foreach($accreditationGroups as $g)
                    <div class="pd-accred__group">
                        <h3>{{ $g['group'] }}</h3>
                        <div class="pd-accred__logos">
                            @foreach($g['items'] as $item)
                                <span class="pd-accred__logo">{{ $item }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 12 WHY MAVERICK SUPPORT ============ --}}
    <section class="pd-support section-wrapper section--light" aria-label="Why study through Maverick" data-testid="pd-support" data-journey-anchor="6">
        <div class="container">
            <div class="section-label"><span>YOUR LEARNING PARTNER</span></div>
            <h2 class="section-title">Why Study Through <em>Maverick?</em></h2>
            <p class="body-text">Students receive:</p>
            <div class="pd-support__grid">
                @foreach($support as $i => $s)
                    <div class="pd-support__item">
                        <span class="pd-support__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span>{{ $s }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 13 TESTIMONIALS ============ --}}
    <section class="pd-testimonials section-wrapper" aria-label="Student success stories" data-testid="pd-testimonials">
        <div class="container">
            <div class="section-label"><span>SUCCESS STORIES</span></div>
            <h2 class="section-title">Student <em>Success Stories</em></h2>
            <div class="pd-testimonials__grid">
                @foreach($testimonials as $t)
                    <div class="pd-testimonials__card">
                        <p class="pd-testimonials__quote">&ldquo;{{ $t['quote'] }}&rdquo;</p>
                        <div class="pd-testimonials__author">
                            <span class="pd-testimonials__name">{{ $t['name'] }}</span>
                            <span class="pd-testimonials__meta">{{ $t['role'] }} · {{ $t['country'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 14 FEES & SCHOLARSHIPS ============ --}}
    <section class="pd-fees section-wrapper section--light" aria-label="Fees and scholarships" data-testid="pd-fees" data-journey-anchor="7">
        <div class="container pd-fees__inner">
            <span class="section-label"><span>FEES & SCHOLARSHIPS</span></span>
            <h2 class="section-title">Fees & <em>Scholarships</em></h2>
            <div class="pd-fees__list">
                <span>Registration Fee</span>
                <span>Initial Payment</span>
                <span>Monthly Instalments</span>
                <span>Scholarship Availability</span>
                <span>Offer Validity</span>
            </div>
            <a href="{{ route('contact') }}" class="btn btn--primary">Request Fee Structure</a>
        </div>
    </section>

    {{-- ============ 15 FAQS ============ --}}
    <section class="pd-faq section-wrapper" aria-label="Frequently asked questions" data-testid="pd-faq">
        <div class="container">
            <div class="section-label"><span>FAQs</span></div>
            <h2 class="section-title">Frequently Asked <em>Questions</em></h2>
            <div class="pd-faq__list">
                @foreach($faqs as $i => $faq)
                    <div class="pd-faq__item {{ $i === 0 ? 'is-open' : '' }}">
                        <button class="pd-faq__question">
                            {{ $faq->question }}
                            <span class="inline-icon pd-faq__chevron" data-lucide="chevron-down"></span>
                        </button>
                        <div class="pd-faq__answer"><p>{{ $faq->answer }}</p></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 16 ENQUIRY FORM ============ --}}
    <section class="pd-enquire section-wrapper section--light" id="enquire" aria-label="Enquire about this programme" data-testid="pd-enquire" data-journey-anchor="8">
        <div class="container pd-enquire__grid">
            <div class="pd-enquire__intro">
                <span class="section-label"><span>APPLY</span></span>
                <h2 class="section-title">Ready to <em>Apply?</em></h2>
                <p class="body-text">Speak to our admissions team to check your eligibility and get started.</p>
            </div>
            <form class="pd-enquire__form" action="{{ route('contact') }}" method="POST">
                @csrf
                <div class="pd-enquire__field">
                    <label for="pd-name">Full Name</label>
                    <input id="pd-name" name="name" type="text" required>
                </div>
                <div class="pd-enquire__field">
                    <label for="pd-email">Email</label>
                    <input id="pd-email" name="email" type="email" required>
                </div>
                <div class="pd-enquire__field">
                    <label for="pd-phone">Phone</label>
                    <input id="pd-phone" name="phone" type="tel">
                </div>
                <div class="pd-enquire__field">
                    <label for="pd-message">Message</label>
                    <textarea id="pd-message" name="message" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn--primary">Submit Enquiry</button>
            </form>
        </div>
    </section>

    {{-- ============ 17 REVIEWS ============ --}}
    <section class="pd-reviews section-wrapper" aria-label="Student reviews" data-testid="pd-reviews">
        <div class="container">
            <div class="section-label"><span>REVIEWS</span></div>
            <h2 class="section-title">What Students <em>Say</em></h2>
            <p class="body-text">Reviews are sourced from verified students. Google rating is displayed once integration is verified.</p>
        </div>
    </section>

    {{-- ============ 18 FINAL CTA ============ --}}
    <section class="pd-final" aria-label="Ready to take the next step" data-testid="pd-final">
        <div class="container pd-final__inner">
            <h2 class="pd-final__title">Ready to Take the <em>Next Step?</em></h2>
            <p class="pd-final__sub">Speak to our admissions team and begin your journey today.</p>
            <div class="pd-final__ctas">
                <a href="#enquire" class="btn pd-final__btn pd-final__btn--solid">Apply Now</a>
                <a href="{{ route('contact') }}" class="btn pd-final__btn pd-final__btn--outline">Speak to an Advisor</a>
            </div>
        </div>
    </section>

    {{-- ============ STICKY CTA ============ --}}
    <div class="pd-sticky" data-testid="pd-sticky">
        <a href="#enquire" class="pd-sticky__apply">Apply Now</a>
        <a href="{{ route('contact') }}" class="pd-sticky__enquire">Enquire</a>
    </div>

    {{-- ============ WHATSAPP ============ --}}
    <a class="pd-whatsapp" href="https://wa.me/{{ $site->whatsapp_number ?? '' }}" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp">
        <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 1.8a8.2 8.2 0 1 1-4.2 15.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 0 1 12 3.8zm-2.6 4.6c-.2 0-.5.1-.7.4-.2.3-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.7 2.7 4.2 3.7 2 .9 2.5.8 2.9.7.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2l-.5-.3-1.7-.8c-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1-.7-.3-1.5-.9-2-1.6-1-1.1-1.3-1.7-1.4-2 0-.2 0-.3.1-.4l.4-.5c.1-.2.1-.3.1-.4l-.7-1.6c-.2-.4-.4-.4-.5-.4z"/></svg>
    </a>

    @include('sections.final-cta')

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof AnimationUtils === 'undefined' || typeof gsap === 'undefined') return;
    const reduced = AnimationUtils.prefersReducedMotion;

    // Accordions (structure + FAQ)
    document.querySelectorAll('.pd-structure__head, .pd-faq__question').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.pd-structure__item, .pd-faq__item');
            const body = item.querySelector('.pd-structure__body, .pd-faq__answer');
            const isOpen = item.classList.contains('is-open');
            // close siblings in same group
            item.parentElement.querySelectorAll('.is-open').forEach(o => {
                if (o !== item) { o.classList.remove('is-open'); o.querySelector('.pd-structure__body, .pd-faq__answer').style.maxHeight = '0'; }
            });
            if (isOpen) { item.classList.remove('is-open'); body.style.maxHeight = '0'; }
            else { item.classList.add('is-open'); body.style.maxHeight = body.scrollHeight + 'px'; }
        });
    });

    // Recognition details toggle (desktop hover / click)
    document.querySelectorAll('.pd-recognition__item summary').forEach(s => {
        s.addEventListener('click', (e) => {
            const det = s.closest('details');
            document.querySelectorAll('.pd-recognition__item[open]').forEach(d => { if (d !== det) d.removeAttribute('open'); });
        });
    });

    // Journey nav active state on scroll
    const anchors = document.querySelectorAll('[data-journey-anchor]');
    const steps = document.querySelectorAll('[data-journey-step]');
    if (anchors.length && steps.length && typeof ScrollTrigger !== 'undefined') {
        const stepMap = {};
        anchors.forEach(a => { stepMap[a.getAttribute('data-journey-anchor')] = a; });
        steps.forEach((st, i) => {
            const anchor = stepMap[String(i + 1)];
            if (!anchor) return;
            ScrollTrigger.create({
                trigger: anchor, start: 'top 55%', end: 'bottom 45%',
                onEnter: () => setActive(steps, i),
                onEnterBack: () => setActive(steps, i),
            });
        });
        function setActive(all, i) { all.forEach((s, j) => s.classList.toggle('is-active', j === i)); }
    }

    if (reduced) {
        document.querySelectorAll('.text-reveal-inner, .fade-up').forEach(el => { el.style.opacity = 1; el.style.transform = 'none'; });
        return;
    }

    // Text reveals + fade ups
    document.querySelectorAll('.page-pd .text-reveal-inner').forEach(() => {});
    AnimationUtils.textReveal('.page-pd h2 .text-reveal-inner, .page-pd .section-title .text-reveal-inner', { stagger: 0.1 });
    AnimationUtils.fadeUp('.pd-highlights__item', { stagger: 0.06 });
    AnimationUtils.fadeUp('.pd-snapshot__item', { stagger: 0.05 });
    AnimationUtils.fadeUp('.pd-why__item, .pd-why__feature', { stagger: 0.08 });
    AnimationUtils.fadeUp('.pd-learn__item', { stagger: 0.05 });
    AnimationUtils.fadeUp('.pd-careers__item', { stagger: 0.04 });
    AnimationUtils.fadeUp('.pd-support__item', { stagger: 0.04 });
    AnimationUtils.fadeUp('.pd-structure__item', { stagger: 0.08 });
});
</script>
@endpush
