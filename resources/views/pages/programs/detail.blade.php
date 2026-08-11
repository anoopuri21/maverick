@extends('layouts.app')

@section('title', ($program->title ?? 'Programme') . ' | Maverick Business Academy')
@section('meta_description', $program->short_description ?? 'Explore this Maverick Business Academy programme.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/program-detail.css') }}">
@endpush

@section('content')
@php
    // ------------------------------------------------------------------
    // UNIVERSAL PROGRAMME CONTENT MODEL
    // Every field/section is OPTIONAL. A section renders only if its
    // content exists. Item counts are variable (0 / 1 / 3 / 10 / 20).
    // Core fields come from the Program model; rich section content is
    // provided per-program here (easy to migrate to CMS later).
    // ------------------------------------------------------------------
    $cat = $program->programCategory;

    $highlights = collect([
        ['label' => 'Awarded By', 'value' => $program->partner_university],
        ['label' => 'Duration', 'value' => $program->duration],
        ['label' => 'Level', 'value' => $program->level],
    ])->filter(fn ($h) => !empty($h['value']))->values();

    $snapshot = collect([
        ['label' => 'Degree Award', 'value' => $program->level],
        ['label' => 'Awarding University', 'value' => $program->partner_university],
        ['label' => 'Duration', 'value' => $program->duration],
        ['label' => 'Study Mode', 'value' => 'VERIFY: Mode'],
    ])->filter(fn ($h) => !empty($h['value']) && !str_starts_with($h['value'], 'VERIFY'))->values();

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
        ['title' => 'Year 1', 'subtitle' => 'Business Foundations', 'children' => ['Principles of Management', 'Business Economics', 'Accounting Fundamentals', 'Marketing Essentials']],
        ['title' => 'Year 2', 'subtitle' => 'Core Business Functions', 'children' => ['Financial Management', 'Organisational Behaviour', 'Operations Management', 'Business Law']],
        ['title' => 'Year 3', 'subtitle' => 'Advanced Business Management', 'children' => ['Strategic Management', 'International Business', 'Entrepreneurship', 'Human Resource Management']],
        ['title' => 'Year 4', 'subtitle' => 'Leadership, Strategy & Internship', 'children' => ['Leadership & Change', 'Business Strategy', 'Internship / Capstone', 'Global Business Perspectives']],
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

    $accreditations = collect(['IACBE', 'YÖK', 'YÖDAK']);

    $testimonials = collect([
        ['name' => 'Verified Student', 'role' => 'Business Manager', 'country' => 'UAE', 'quote' => 'Verified student story to be sourced.'],
    ]);

    $fees = collect(); // empty -> render only "Request Fee Structure"

    $faqs = $program->faqs;
    $reviews = collect(); // empty -> no review section

    $journey = ['DISCOVER', 'UNDERSTAND', 'IMAGINE', 'TRUST', 'DECIDE'];
@endphp

<div class="page-pd">

    {{-- ============ GLOBAL CHROME: STICKY PROGRESS NAV ============ --}}
    <nav class="pd-nav" data-testid="pd-nav" aria-label="Programme sections">
        <div class="container pd-nav__inner">
            @foreach($journey as $step)
                <a class="pd-nav__link" href="#{{ strtolower($step) }}" data-pd-nav-link="{{ strtolower($step) }}">{{ $step }}</a>
            @endforeach
        </div>
    </nav>

    {{-- ============ EXPERIENCE 01 — DISCOVER ============ --}}

    {{-- HERO --}}
    <section class="pd-hero" id="discover" aria-label="{{ $program->title }}" data-testid="pd-hero">
        <div class="container pd-hero__inner">
            <div class="pd-hero__content">
                @if($cat)
                    <span class="pd-hero__category">{{ $cat->name }}</span>
                @endif
                <h1 class="pd-hero__title">{{ $program->title }}</h1>
                @if($program->short_description)
                    <p class="pd-hero__lede">{{ $program->short_description }}</p>
                @endif
                <div class="pd-hero__ctas">
                    <a href="#enquire" class="btn pd-btn pd-btn--primary">Apply Now</a>
                    <a href="{{ route('contact') }}" class="btn pd-btn pd-btn--ghost">Enquire Now</a>
                    @if(isset($program->brochure_url) && $program->brochure_url)
                        <a href="{{ $program->brochure_url }}" class="pd-hero__brochure">Download Brochure</a>
                    @endif
                </div>
            </div>
            @if($program->image_url)
                <div class="pd-hero__media">
                    <img src="{{ $program->image_url }}" alt="{{ $program->title }}" loading="eager" width="900" height="720">
                </div>
            @endif
        </div>

        @if($highlights->count())
        <div class="container">
            <div class="pd-highlights">
                @foreach($highlights as $h)
                    <div class="pd-highlights__item">
                        <span class="pd-highlights__label">{{ $h['label'] }}</span>
                        <span class="pd-highlights__value">{{ $h['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </section>

    {{-- RECOGNITION --}}
    @if($accreditations->count())
    <section class="pd-recognition section--light" aria-label="Recognition" data-testid="pd-recognition">
        <div class="container pd-recognition__inner">
            <span class="pd-recognition__awarded">Awarded by <strong>{{ $program->partner_university }}</strong></span>
            <div class="pd-recognition__list">
                @foreach($accreditations as $acc)
                    <span class="pd-recognition__badge">{{ $acc }}</span>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- SNAPSHOT --}}
    @if($snapshot->count())
    <section class="pd-snapshot" aria-label="Programme snapshot" data-testid="pd-snapshot">
        <div class="container">
            <h2 class="pd-section-title">Programme at a <em>Glance</em></h2>
            <div class="pd-snapshot__grid">
                @foreach($snapshot as $s)
                    <div class="pd-snapshot__item">
                        <span class="pd-snapshot__label">{{ $s['label'] }}</span>
                        <span class="pd-snapshot__value">{{ $s['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ EXPERIENCE 02 — UNDERSTAND ============ --}}

    {{-- OVERVIEW --}}
    @if($program->description)
    <section class="pd-overview section--light" id="understand" aria-label="Programme overview" data-testid="pd-overview">
        <div class="container pd-overview__grid">
            <h2 class="pd-overview__title">Build the business skills that <em>move organisations forward</em>.</h2>
            <p class="pd-overview__body">{{ $program->description }}</p>
        </div>
    </section>
    @endif

    {{-- WHY CHOOSE --}}
    @if($benefits->count())
    <section class="pd-benefits" aria-label="Why choose this programme" data-testid="pd-benefits">
        <div class="container">
            <h2 class="pd-section-title">Why Choose This <em>Programme?</em></h2>
            <div class="pd-benefits__list">
                @foreach($benefits as $i => $b)
                    <div class="pd-benefits__item">
                        <span class="pd-benefits__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h3>{{ $b['title'] }}</h3>
                            <p>{{ $b['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- WHAT YOU'LL LEARN --}}
    @if($learning->count())
    <section class="pd-learn section--light" aria-label="What you'll learn" data-testid="pd-learn">
        <div class="container pd-learn__grid">
            <div class="pd-learn__intro">
                <span class="pd-section-label">Learning Outcomes</span>
                <h2 class="pd-section-title">What You'll <em>Learn</em></h2>
            </div>
            <ol class="pd-learn__list">
                @foreach($learning as $i => $cap)
                    <li class="pd-learn__item">
                        <span class="pd-learn__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span>{{ $cap }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
    @endif

    {{-- ============ EXPERIENCE 03 — IMAGINE ============ --}}

    {{-- CAREERS --}}
    @if($careers->count())
    <section class="pd-careers" id="imagine" aria-label="Career opportunities" data-testid="pd-careers">
        <div class="container">
            <h2 class="pd-section-title">Where This Degree Can <em>Take You</em></h2>
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
    @endif

    {{-- PROGRAMME STRUCTURE --}}
    @if($structure->count())
    <section class="pd-structure section--light" aria-label="Programme structure" data-testid="pd-structure">
        <div class="container">
            <h2 class="pd-section-title">Programme <em>Structure</em></h2>
            <div class="pd-structure__list">
                @foreach($structure as $i => $stage)
                    <details class="pd-structure__item" {{ $i === 0 ? 'open' : '' }}>
                        <summary class="pd-structure__head">
                            <span class="pd-structure__title">{{ $stage['title'] }}</span>
                            @if(!empty($stage['subtitle']))<span class="pd-structure__sub">{{ $stage['subtitle'] }}</span>@endif
                            <span class="pd-structure__chevron" aria-hidden="true">+</span>
                        </summary>
                        @if(!empty($stage['children']))
                            <ul class="pd-structure__modules">
                                @foreach($stage['children'] as $module)
                                    <li>{{ $module }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </details>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ EXPERIENCE 04 — TRUST ============ --}}

    {{-- UNIVERSITY --}}
    @if($university->name)
    <section class="pd-uni" aria-label="About the awarding university" data-testid="pd-uni">
        <div class="container pd-uni__grid">
            <h2 class="pd-section-title">About {{ $university->name }}</h2>
            <div class="pd-uni__body">
                <p>{{ $university->description }}</p>
                @if($university->establishment)<span class="pd-uni__meta">{{ $university->establishment }}</span>@endif
            </div>
        </div>
    </section>
    @endif

    {{-- ACCREDITATION --}}
    @if($accreditations->count())
    <section class="pd-accred section--light" aria-label="Accreditation" data-testid="pd-accred">
        <div class="container">
            <h2 class="pd-section-title">Accreditation & <em>Recognition</em></h2>
            <div class="pd-accred__list">
                @foreach($accreditations as $a)
                    <span class="pd-accred__badge">{{ $a }}</span>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- MAVERICK SUPPORT --}}
    @if($support->count())
    <section class="pd-support" aria-label="Why study through Maverick" data-testid="pd-support">
        <div class="container">
            <h2 class="pd-section-title">Why Study Through <em>Maverick?</em></h2>
            <div class="pd-support__list">
                @foreach($support as $i => $s)
                    <div class="pd-support__item">
                        <span class="pd-support__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span>{{ $s }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- TESTIMONIALS --}}
    @if($testimonials->count())
    <section class="pd-testimonials section--light" aria-label="Student success stories" data-testid="pd-testimonials">
        <div class="container">
            <h2 class="pd-section-title">Student <em>Success Stories</em></h2>
            <div class="pd-testimonials__list">
                @foreach($testimonials as $t)
                    <figure class="pd-testimonials__card">
                        <blockquote>&ldquo;{{ $t['quote'] }}&rdquo;</blockquote>
                        <figcaption>
                            <span class="pd-testimonials__name">{{ $t['name'] }}</span>
                            <span class="pd-testimonials__meta">{{ $t['role'] }} · {{ $t['country'] }}</span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ EXPERIENCE 05 — DECIDE ============ --}}

    {{-- FEES --}}
    <section class="pd-fees section--light" id="decide" aria-label="Fees and scholarships" data-testid="pd-fees">
        <div class="container pd-fees__inner">
            <h2 class="pd-section-title">Fees & <em>Scholarships</em></h2>
            @if($fees->count())
                <div class="pd-fees__list">
                    @foreach($fees as $fee)
                        <span>{{ $fee }}</span>
                    @endforeach
                </div>
            @else
                <p class="pd-fees__note">Fee structure varies by intake and study mode.</p>
            @endif
            <a href="{{ route('contact') }}" class="btn pd-btn pd-btn--primary">Request Fee Structure</a>
        </div>
    </section>

    {{-- FAQ --}}
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

    {{-- ENQUIRY --}}
    <section class="pd-enquire section--light" id="enquire" aria-label="Enquire about this programme" data-testid="pd-enquire">
        <div class="container pd-enquire__inner">
            <h2 class="pd-section-title">Enquire About This <em>Programme</em></h2>
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
                </div>
                <button type="submit" class="btn pd-btn pd-btn--primary">Submit Enquiry</button>
            </form>
        </div>
    </section>

    {{-- REVIEWS --}}
    @if($reviews->count())
    <section class="pd-reviews" aria-label="Student reviews" data-testid="pd-reviews">
        <div class="container">
            <h2 class="pd-section-title">What Students <em>Say</em></h2>
            <div class="pd-reviews__list">
                @foreach($reviews as $r)
                    <div class="pd-reviews__card">{{ $r }}</div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- FINAL CTA --}}
    <section class="pd-cta" aria-label="Take the next step" data-testid="pd-cta">
        <div class="container pd-cta__inner">
            <h2 class="pd-cta__title">Ready to Take the <em>Next Step?</em></h2>
            <div class="pd-cta__actions">
                <a href="#enquire" class="btn pd-btn pd-btn--primary">Apply Now</a>
                <a href="{{ route('contact') }}" class="btn pd-btn pd-btn--ghost">Speak to an Advisor</a>
            </div>
        </div>
    </section>

    {{-- ============ GLOBAL CHROME: STICKY CTA + WHATSAPP ============ --}}
    <div class="pd-sticky" data-testid="pd-sticky">
        <a href="#enquire" class="pd-sticky__apply">Apply Now</a>
        <a href="{{ route('contact') }}" class="pd-sticky__enquire">Enquire</a>
    </div>

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
    // Sticky progress nav active state
    const links = document.querySelectorAll('[data-pd-nav-link]');
    const sections = document.querySelectorAll('.page-pd section[id]');
    if (links.length && sections.length && typeof ScrollTrigger !== 'undefined') {
        const ids = ['discover', 'understand', 'imagine', 'trust', 'decide'];
        ids.forEach(id => {
            const el = document.getElementById(id);
            const link = document.querySelector(`[data-pd-nav-link="${id}"]`);
            if (!el || !link) return;
            ScrollTrigger.create({
                trigger: el, start: 'top 55%', end: 'bottom 45%',
                onEnter: () => setActive(link),
                onEnterBack: () => setActive(link),
            });
        });
        function setActive(active) { links.forEach(l => l.classList.toggle('is-active', l === active)); }
    }

    // Native <details> accordions: close others in same group
    document.querySelectorAll('.pd-structure__item, .pd-faq__item').forEach(details => {
        details.addEventListener('toggle', () => {
            if (!details.open) return;
            const group = details.parentElement;
            group.querySelectorAll('details[open]').forEach(d => { if (d !== details) d.open = false; });
        });
    });

    if (typeof AnimationUtils !== 'undefined' && typeof gsap !== 'undefined' && !AnimationUtils.prefersReducedMotion) {
        AnimationUtils.fadeUp('.pd-hero__content', {});
        AnimationUtils.fadeUp('.pd-highlights__item', { stagger: 0.05 });
        AnimationUtils.fadeUp('.pd-snapshot__item', { stagger: 0.05 });
        AnimationUtils.fadeUp('.pd-benefits__item', { stagger: 0.06 });
        AnimationUtils.fadeUp('.pd-learn__item', { stagger: 0.04 });
        AnimationUtils.fadeUp('.pd-careers__item', { stagger: 0.04 });
        AnimationUtils.fadeUp('.pd-support__item', { stagger: 0.04 });
        AnimationUtils.fadeUp('.pd-structure__item', { stagger: 0.06 });
        AnimationUtils.fadeUp('.pd-testimonials__card', { stagger: 0.1 });
    }
});
</script>
@endpush
