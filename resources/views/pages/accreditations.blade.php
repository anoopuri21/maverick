@extends('layouts.app')

@section('title', 'Media Gallery - Maverick Business Academy')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/accreditations.css') }}">
@endpush

@section('content')
@php
    /* ============================================================
       DATA BLOCKS — Static placeholder (CMS-ready structure)
       ============================================================ */

    $accreditations = [
        ['title' => 'University of Wolverhampton', 'desc' => 'Official academic partnership for validated undergraduate and postgraduate degree programmes delivered at Maverick Business Academy.', 'category' => 'University Accreditations', 'image' => '/images/placeholders/accr-wolverhampton.png'],
        ['title' => 'University of Chichester', 'desc' => 'Accredited partnership enabling delivery of business and management degree courses with full UK university recognition.', 'category' => 'University Accreditations', 'image' => '/images/placeholders/accr-chichester.png'],
        ['title' => 'OTHM Qualifications', 'desc' => 'Ofqual-regulated awarding body providing Level 4–7 qualifications recognised across the UK and internationally.', 'category' => 'Institutional Memberships', 'image' => '/images/placeholders/accr-othm.png'],
        ['title' => 'Qualifi Ltd', 'desc' => 'Approved centre for Qualifi regulated qualifications in business, management, health and social care.', 'category' => 'Institutional Memberships', 'image' => '/images/placeholders/accr-qualifi.png'],
        ['title' => 'British Accreditation Council', 'desc' => 'BAC accreditation confirming Maverick meets the highest UK standards for independent higher education institutions.', 'category' => 'Regulatory Approvals', 'image' => '/images/placeholders/accr-bac.png'],
        ['title' => 'UK ENIC (NARIC)', 'desc' => 'Listed as a recognised institution with the UK National Information Centre for global credential evaluation.', 'category' => 'International Recognition', 'image' => '/images/placeholders/accr-ukenic.png'],
        ['title' => 'Quality Assurance Agency (QAA)', 'desc' => 'Reviewed by QAA for educational oversight, meeting expectations across all areas of quality and standards.', 'category' => 'Regulatory Approvals', 'image' => '/images/placeholders/accr-qaa.png'],
        ['title' => 'Home Office Sponsor License', 'desc' => 'Licensed Student Sponsor with the UK Home Office for international student recruitment under the Student Route.', 'category' => 'Regulatory Approvals', 'image' => '/images/placeholders/accr-homeoffice.png'],
        ['title' => 'Office for Students (OfS)', 'desc' => 'Registered with the independent regulator for higher education in England ensuring student protection.', 'category' => 'Regulatory Approvals', 'image' => '/images/placeholders/accr-ofs.png'],
        ['title' => 'ASIC Accreditation', 'desc' => 'Accreditation Service for International Schools, Colleges and Universities — premier status awarded.', 'category' => 'International Recognition', 'image' => '/images/placeholders/accr-asic.png'],
    ];

    $awards = [
        ['title' => 'Best Higher Education Provider 2024', 'desc' => 'Awarded at the National Education Awards for exceptional student outcomes and teaching quality.', 'category' => 'Education Awards', 'image' => '/images/placeholders/award-education.jpg'],
        ['title' => 'Times Higher Education Spotlight', 'desc' => 'Featured in THE as a rising UK institution delivering industry-relevant business programmes.', 'category' => 'Media Features', 'image' => '/images/placeholders/award-the.jpg'],
        ['title' => 'Global Business Education Award', 'desc' => 'Recognised for innovation in business education delivery across international markets.', 'category' => 'Industry Recognition', 'image' => '/images/placeholders/award-global.jpg'],
        ['title' => 'QS Reimagine Education Finalist', 'desc' => 'Shortlisted for pioneering blended learning approaches in undergraduate business education.', 'category' => 'Education Awards', 'image' => '/images/placeholders/award-qs.jpg'],
        ['title' => 'UK Education Conference Speaker', 'desc' => 'Invited keynote at the UK Higher Education Innovation Conference 2024.', 'category' => 'Conference Participation', 'image' => '/images/placeholders/award-conference.jpg'],
        ['title' => 'BBC Education Series Feature', 'desc' => 'Profiled in BBC\'s series on innovative UK education institutions reshaping business learning.', 'category' => 'Media Features', 'image' => '/images/placeholders/award-bbc.jpg'],
        ['title' => 'Employer Partnership Excellence', 'desc' => 'Industry recognition for building UK\'s most effective employer-linked curriculum pathways.', 'category' => 'Industry Recognition', 'image' => '/images/placeholders/award-employer.jpg'],
        ['title' => 'Student Experience Award 2023', 'desc' => 'Awarded for outstanding student support services, mentoring programmes and pastoral care.', 'category' => 'Education Awards', 'image' => '/images/placeholders/award-student.jpg'],
    ];

    $recognitions = [
        ['title' => 'Forbes Education Innovator List', 'desc' => 'Named among top 50 education innovators in Europe by Forbes for transformative learning models.', 'category' => 'Media Features', 'image' => '/images/placeholders/recog-forbes.jpg'],
        ['title' => 'Department for Education Commendation', 'desc' => 'Commended by the UK DfE for exceptional graduate employment rates and industry partnerships.', 'category' => 'Industry Recognition', 'image' => '/images/placeholders/recog-dfe.jpg'],
        ['title' => 'London Mayor\'s Education Partner', 'desc' => 'Official education partner for London\'s Skills for Londoners programme fostering employability.', 'category' => 'Industry Recognition', 'image' => '/images/placeholders/recog-mayor.jpg'],
        ['title' => 'ICEF Certified Agency Partner', 'desc' => 'ICEF Monitor certified for international student recruitment excellence and ethical standards.', 'category' => 'International Recognition', 'image' => '/images/placeholders/recog-icef.jpg'],
        ['title' => 'Education Investor Awards Finalist', 'desc' => 'Shortlisted for Most Innovative Business School category at the EI Awards 2024.', 'category' => 'Education Awards', 'image' => '/images/placeholders/recog-ei.jpg'],
        ['title' => 'The Guardian University Guide Feature', 'desc' => 'Featured in The Guardian\'s spotlight on progressive higher education institutions in London.', 'category' => 'Media Features', 'image' => '/images/placeholders/recog-guardian.jpg'],
        ['title' => 'BETT EdTech Innovation Award', 'desc' => 'Recognised at BETT for integration of AI-powered learning analytics in curriculum delivery.', 'category' => 'Conference Participation', 'image' => '/images/placeholders/recog-bett.jpg'],
        ['title' => 'CBI Employment Skills Partner', 'desc' => 'Confederation of British Industry partnership for employer-driven skills development programmes.', 'category' => 'Industry Recognition', 'image' => '/images/placeholders/recog-cbi.jpg'],
    ];

    $categories1 = ['All', 'University Accreditations', 'Institutional Memberships', 'International Recognition', 'Regulatory Approvals'];
@endphp

<div class="accreditations-page">

    {{-- ================================================================
         PAGE BANNER — Blue cinematic with living animated elements
         ================================================================ --}}
    <section class="page-banner">
        <div class="page-banner__bg">
            <img src="https://images.pexels.com/photos/7212946/pexels-photo-7212946.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=1920" alt="" class="page-banner__bg-img">
            <div class="page-banner__bg-overlay"></div>
            <div class="page-banner__bg-grain"></div>
        </div>

        <div class="page-banner__orb page-banner__orb--1"></div>
        <div class="page-banner__orb page-banner__orb--2"></div>
        <div class="page-banner__orb page-banner__orb--3"></div>

        <div class="page-banner__particles">
            @for($i = 0; $i < 8; $i++)
                <div class="page-banner__particle"></div>
            @endfor
        </div>

        <div class="page-banner__grid-lines">
            <div class="page-banner__grid-line page-banner__grid-line--h page-banner__grid-line--h1"></div>
            <div class="page-banner__grid-line page-banner__grid-line--h page-banner__grid-line--h2"></div>
            <div class="page-banner__grid-line page-banner__grid-line--v page-banner__grid-line--v1"></div>
            <div class="page-banner__grid-line page-banner__grid-line--v page-banner__grid-line--v2"></div>
            <div class="page-banner__grid-line page-banner__grid-line--v page-banner__grid-line--v3"></div>
        </div>

        <div class="page-banner__scanline"></div>

        <div class="page-banner__corner page-banner__corner--tl"></div>
        <div class="page-banner__corner page-banner__corner--tr"></div>
        <div class="page-banner__corner page-banner__corner--bl"></div>
        <div class="page-banner__corner page-banner__corner--br"></div>

        <div class="page-banner__content">
            <div class="page-banner__breadcrumb">
                <a href="#">Home</a>
                <span class="page-banner__breadcrumb-sep">/</span>
                <span class="page-banner__breadcrumb-current">Accreditations & Recognitions</span>
            </div>
            <h1 class="page-banner__title">Accreditations &amp; <em>Recognitions</em></h1>
            <p class="page-banner__desc">Globally accredited, nationally trusted — discover the credentials that make Maverick Business Academy a benchmark for educational excellence.</p>
        </div>

        <div class="page-banner__marquee">
            <div class="page-banner__marquee-track">
                @for($i = 0; $i < 3; $i++)
                    <span>BAC Accredited · QAA Reviewed · OfS Registered · ASIC Premier · Home Office Sponsor · UK ENIC Listed · </span>
                @endfor
            </div>
        </div>
    </section>

    {{-- ================================================================
         HERO SECTION (Accreditations intro)
         ================================================================ --}}
    <section class="accreditations-hero">
        <div class="accreditations-hero__bg-shape"></div>
        <div class="accreditations-hero__bg-shape accreditations-hero__bg-shape--2"></div>
        <span class="accreditations-vlabel accreditations-hero__vlabel">Accreditations & Recognitions</span>

        <div class="accreditations-container">
            <div class="accreditations-hero__grid">
                {{-- Left: Content --}}
                <div class="accreditations-hero__content">
                    <div class="accreditations-hero__num">01</div>

                    <div class="accreditations-eyebrow">
                        <span class="accreditations-eyebrow__line"></span>
                        <span>Trusted & Recognised</span>
                    </div>

                    <h1 class="accreditations-hero__title">
                        Accredited by the<br><em>Best in Education</em>
                    </h1>

                    <p class="accreditations-hero__desc">
                        Maverick Business Academy London holds accreditations from the UK's most respected educational bodies, ensuring our programmes meet the highest standards of academic excellence and professional relevance.
                    </p>

                    <div class="accreditations-hero__stats">
                        <div class="accreditations-hero__stat">
                            <div class="accreditations-hero__stat-num">10<span>+</span></div>
                            <div class="accreditations-hero__stat-label">Accreditations</div>
                        </div>
                        <div class="accreditations-hero__stat">
                            <div class="accreditations-hero__stat-num">15<span>+</span></div>
                            <div class="accreditations-hero__stat-label">Awards Won</div>
                        </div>
                        <div class="accreditations-hero__stat">
                            <div class="accreditations-hero__stat-num">30<span>+</span></div>
                            <div class="accreditations-hero__stat-label">Countries Served</div>
                        </div>
                    </div>
                </div>

                {{-- Right: Floating badge cards --}}
                <div class="accreditations-hero__badges">
                    @php
                        $heroBadges = [
                            ['icon' => 'shield-check', 'title' => 'UK Government Approved', 'text' => 'OfS registered & Home Office sponsored'],
                            ['icon' => 'award', 'title' => 'QAA Reviewed', 'text' => 'Meets expectations across all areas'],
                            ['icon' => 'globe', 'title' => 'Globally Recognised', 'text' => 'ASIC premier status accreditation'],
                            ['icon' => 'graduation-cap', 'title' => 'University Partnered', 'text' => 'Validated UK degree programmes'],
                        ];
                    @endphp
                    @foreach($heroBadges as $b)
                        <div class="accreditations-hero__badge">
                            <div class="accreditations-hero__badge-icon">
                                <i data-lucide="{{ $b['icon'] }}"></i>
                            </div>
                            <div class="accreditations-hero__badge-title">{{ $b['title'] }}</div>
                            <div class="accreditations-hero__badge-text">{{ $b['text'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Marquee --}}
            <div class="accreditations-hero__marquee">
                <div class="accreditations-hero__marquee-track">
                    @for($i = 0; $i < 4; $i++)
                        <span>Accredited · Recognised · Trusted · Excellence · Quality · Innovation · Integrity · </span>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================================
         SECTION 1: ACCREDITATIONS — Auto-play horizontal slider
         ================================================================ --}}
    <section class="accreditations-section" id="accreditations">
        <span class="accreditations-vlabel accreditations-section__vlabel">Section 01</span>
        <div class="accreditations-section__deco accreditations-section__deco--dots"></div>
        <div class="accreditations-section__deco accreditations-section__deco--circle"></div>

        <div class="accreditations-container">
            <div class="accreditations-section__header">
                <div class="accreditations-section-num">01</div>
                <div class="accreditations-eyebrow">
                    <span class="accreditations-eyebrow__line"></span>
                    <span>Our Accreditations</span>
                </div>
                <h2 class="accreditations-heading">Standards of <em>Excellence</em></h2>
                <p class="accreditations-desc" style="margin:0 auto;">Every accreditation represents years of rigorous evaluation, continuous improvement, and unwavering commitment to educational quality.</p>

                <div class="accreditations-section__categories">
                    @foreach($categories1 as $cat)
                        <button class="accreditations-section__cat-btn {{ $loop->first ? 'active' : '' }}">{{ $cat }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Auto-sliding track --}}
        <div class="accreditations-slider">
            <div class="accreditations-slider__track">
                {{-- Duplicate for infinite loop --}}
                @for($r = 0; $r < 2; $r++)
                    @foreach($accreditations as $accr)
                        <div class="accreditations-card">
                            <div class="accreditations-card__tag">{{ $accr['category'] }}</div>
                            <div class="accreditations-card__logo">
                                <img src="{{ $accr['image'] }}" alt="{{ $accr['title'] }}">
                            </div>
                            <div class="accreditations-card__title">{{ $accr['title'] }}</div>
                            <div class="accreditations-card__desc">{{ $accr['desc'] }}</div>
                            <div class="accreditations-card__glow"></div>
                        </div>
                    @endforeach
                @endfor
            </div>
        </div>
    </section>

    {{-- ================================================================
         SECTION 2: AWARDS — Auto-slide carousel with 3D cards
         ================================================================ --}}
    <section class="awards-section" id="awards">
        <span class="accreditations-vlabel awards-section__vlabel">Section 02</span>
        <div class="accreditations-section-num awards-section__num" style="color:rgba(26,43,122,0.03);">02</div>

        <div class="accreditations-container">
            <div class="awards-section__top">
                <div class="awards-section__header">
                    <div class="accreditations-eyebrow">
                        <span class="accreditations-eyebrow__line"></span>
                        <span>Awards & Achievements</span>
                    </div>
                    <h2 class="accreditations-heading">Celebrated for <em>Impact</em></h2>
                    <p class="accreditations-desc">Recognition from the industry's most prestigious bodies validates our mission to transform education and empower futures.</p>
                </div>
                <div class="awards-section__nav">
                    <button class="awards-section__nav-btn awards-nav--prev" aria-label="Previous">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button class="awards-section__nav-btn awards-nav--next" aria-label="Next">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            </div>

            <div class="awards-carousel">
                <div class="awards-carousel__track">
                    @foreach($awards as $aw)
                        <div class="awards-card">
                            <div class="awards-card__image">
                                <img src="{{ $aw['image'] }}" alt="{{ $aw['title'] }}" loading="lazy">
                                <div class="awards-card__image-overlay"></div>
                                <div class="awards-card__tag">{{ $aw['category'] }}</div>
                            </div>
                            <div class="awards-card__body">
                                <div class="awards-card__title">{{ $aw['title'] }}</div>
                                <div class="awards-card__desc">{{ $aw['desc'] }}</div>
                            </div>
                            <div class="awards-card__shine"></div>
                        </div>
                    @endforeach
                </div>
                <div class="awards-carousel__dots"></div>
            </div>
        </div>
    </section>

    {{-- ================================================================
         SECTION 3: RECOGNITION — Dark glass cards horizontal drag scroll
         ================================================================ --}}
    <section class="recognition-section" id="recognition">
        <div class="recognition-section__deco-ring recognition-section__deco-ring--1"></div>
        <div class="recognition-section__deco-ring recognition-section__deco-ring--2"></div>
        <div class="recognition-section__deco-glow"></div>
        <span class="accreditations-vlabel recognition-section__vlabel">Section 03</span>
        <div class="recognition-section__num">03</div>

        <div class="accreditations-container">
            <div class="recognition-section__header">
                <div class="accreditations-eyebrow">
                    <span class="accreditations-eyebrow__line"></span>
                    <span>Recognition & Features</span>
                </div>
                <h2 class="accreditations-heading">Recognised by <em>Leaders</em></h2>
                <p class="accreditations-desc" style="margin:0 auto;">From global media features to government commendations — our impact resonates far beyond the classroom.</p>
            </div>
        </div>

        {{-- Horizontal drag-scroll track --}}
        <div class="recognition-section__scroll">
            <div class="recognition-section__track">
                @foreach($recognitions as $rec)
                    <div class="recognition-card">
                        <div class="recognition-card__image">
                            <img src="{{ $rec['image'] }}" alt="{{ $rec['title'] }}" loading="lazy">
                            <div class="recognition-card__image-gradient"></div>
                            <div class="recognition-card__tag">{{ $rec['category'] }}</div>
                        </div>
                        <div class="recognition-card__body">
                            <div class="recognition-card__title">{{ $rec['title'] }}</div>
                            <div class="recognition-card__desc">{{ $rec['desc'] }}</div>
                        </div>
                        <div class="recognition-card__line"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    {{-- ================================================================
         FINAL CTA (existing partial)
         ================================================================ --}}
     @include('sections.final-cta') 

</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/accreditations-animations.js') }}" defer></script>
@endpush