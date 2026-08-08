@extends('layouts.app')

@section('title', 'Dual MBA Programme | Maverick Business Academy London')
@section('meta_description', 'Earn Two MBA Degrees in One Year. General MBA + Specialised MBA through one integrated programme. 100% Online, Weekend Classes. Apply Now.')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/dual-mba.css') }}" />
@endpush

@section('content')

{{-- ===== S1: HERO ===== --}}
<section id="dmba-hero" class="dmba-hero" aria-label="Dual MBA Programme Hero" data-testid="dmba-hero-section">
  <div class="dmba-hero__bg" aria-hidden="true">
    <img
      src="https://images.unsplash.com/photo-1630344745884-9c93c4593f70?w=1920&q=80"
      alt=""
      class="dmba-hero__bg-image"
      loading="eager"
    />
    <div class="dmba-hero__overlay"></div>
    <div class="dmba-hero__grain"></div>
  </div>

  <div class="dmba-hero__content">
    <div class="container">
      <div class="dmba-hero__inner">
        {{-- Text Side --}}
        <div class="dmba-hero__text">
          <span class="dmba-hero__tag" data-dmba-hero="tag" data-testid="dmba-hero-tag">Dual MBA Programme</span>

          <h1 class="dmba-hero__headline" data-dmba-hero="headline" data-testid="dmba-hero-headline">
            Earn Two MBA Degrees.<br>
            Expand Your Expertise.<br>
            <em>Accelerate Your Global Career.</em>
          </h1>

          <p class="dmba-hero__sub" data-dmba-hero="sub" data-testid="dmba-hero-sub">
            One Programme. Two International MBA Qualifications. Unlimited Career Opportunities.
          </p>

          <div class="dmba-hero__stats" data-dmba-hero="stats" data-testid="dmba-hero-stats">
            <div class="dmba-hero__stat">
              <span class="dmba-hero__stat-value">1 Year</span>
              <span class="dmba-hero__stat-label">Duration</span>
            </div>
            <div class="dmba-hero__stat">
              <span class="dmba-hero__stat-value">100%</span>
              <span class="dmba-hero__stat-label">Online</span>
            </div>
            <div class="dmba-hero__stat">
              <span class="dmba-hero__stat-value">Weekend</span>
              <span class="dmba-hero__stat-label">Classes</span>
            </div>
            <div class="dmba-hero__stat">
              <span class="dmba-hero__stat-value">2</span>
              <span class="dmba-hero__stat-label">MBA Degrees</span>
            </div>
          </div>

          <div class="dmba-hero__ctas" data-dmba-hero="ctas" data-testid="dmba-hero-ctas">
            <a href="{{ $site->apply_now_url ?? '/apply/' }}" class="btn btn--primary" data-testid="dmba-apply-btn">Apply Now</a>
            <a href="#dmba-overview" class="btn btn--secondary" data-testid="dmba-brochure-btn">Download Brochure</a>
          </div>
        </div>

        {{-- Visual Side --}}
        <div class="dmba-hero__visual" data-dmba-hero="visual">
          <div class="dmba-hero__image-frame">
            <img
              src="https://images.unsplash.com/photo-1763038311036-6d18805537e5?w=600&q=80"
              alt="Professional studying on laptop — Dual MBA Programme"
              loading="eager"
            />
          </div>
          <div class="dmba-hero__image-accent" aria-hidden="true"></div>
          <div class="dmba-hero__image-badge">
            <span class="dmba-hero__image-badge-title">2x MBA</span>
            <span class="dmba-hero__image-badge-sub">One Programme</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ===== S2: TRUST BAR ===== --}}
<section class="dmba-trust" aria-label="Accreditation Partners" data-testid="dmba-trust-section">
  <div class="container">
    <div class="dmba-trust__inner">
      <span class="dmba-trust__label">Awarded By</span>
      <div class="dmba-trust__divider" aria-hidden="true"></div>
      <div class="dmba-trust__logos">
        {{-- Logo placeholders for the 3 partner universities --}}
        <img src="{{ asset('assets/images/universities/gau-logo.png') }}" alt="Girne American University" class="dmba-trust__logo" onerror="this.style.display='none'" data-testid="dmba-logo-gau" />
        <img src="{{ asset('assets/images/universities/rbs-logo.png') }}" alt="Rushford Business School" class="dmba-trust__logo" onerror="this.style.display='none'" data-testid="dmba-logo-rbs" />
        <img src="{{ asset('assets/images/universities/uca-logo.png') }}" alt="University for the Creative Arts" class="dmba-trust__logo" onerror="this.style.display='none'" data-testid="dmba-logo-uca" />
      </div>
    </div>
  </div>
</section>

{{-- ===== S3: PROGRAMME OVERVIEW ===== --}}
<section id="dmba-overview" class="dmba-overview section--light section-wrapper" aria-label="Programme Overview" data-testid="dmba-overview-section">
  <div class="container">
    <div class="dmba-overview__header">
      <div class="section-label"><span>Programme Overview</span></div>
      <h2 class="dmba-overview__heading section-title">One Programme.<br>Two Degrees.</h2>
      <p class="dmba-overview__desc body-text">
        Instead of choosing between broad business knowledge and specialist expertise, you graduate with both &mdash; giving you a significant competitive advantage in today&rsquo;s global employment market.
      </p>
    </div>

    <div class="dmba-overview__pathway" data-testid="dmba-pathway">
      {{-- General MBA Card --}}
      <div class="dmba-overview__card fade-up" data-testid="dmba-card-general">
        <div class="dmba-overview__card-icon dmba-overview__card-icon--general">
          <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
        </div>
        <h3 class="dmba-overview__card-title">MBA (General)</h3>
        <p class="dmba-overview__card-text">
          Build a comprehensive foundation in strategic leadership, finance, marketing, operations, and organisational behaviour.
        </p>
        <div class="dmba-overview__card-check">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
          Broad Business Leadership
        </div>
      </div>

      {{-- Bridge --}}
      <div class="dmba-overview__bridge" aria-hidden="true">
        <div class="dmba-overview__bridge-line"></div>
        <div class="dmba-overview__bridge-icon">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        </div>
        <span class="dmba-overview__bridge-label">Integrated Pathway</span>
        <div class="dmba-overview__bridge-line"></div>
      </div>

      {{-- Specialised MBA Card --}}
      <div class="dmba-overview__card fade-up" data-testid="dmba-card-specialist">
        <div class="dmba-overview__card-icon dmba-overview__card-icon--specialist">
          <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <h3 class="dmba-overview__card-title">MBA (Specialisation)</h3>
        <p class="dmba-overview__card-text">
          Gain advanced expertise in a specific discipline aligned with your career goals &mdash; from AI and Finance to Healthcare and HR.
        </p>
        <div class="dmba-overview__card-check">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
          Industry Specialist
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ===== S4: WHY CHOOSE DUAL MBA ===== --}}
<section class="dmba-why section--light section--warm section-wrapper" aria-label="Why Choose Dual MBA" data-testid="dmba-why-section">
  <div class="container">
    <div class="dmba-why__header">
      <div class="section-label"><span>Why Choose</span></div>
      <h2 class="section-title">Why Choose the<br>Dual MBA Programme?</h2>
    </div>

    <div class="dmba-why__grid" data-testid="dmba-why-grid">
      <div class="dmba-why__card" data-testid="dmba-why-card-1">
        <div class="dmba-why__card-icon">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l2.4 7.2H22l-6 4.8 2.4 7.2L12 16.8 5.6 21.2 8 14 2 9.2h7.6z"/></svg>
        </div>
        <h3 class="dmba-why__card-title">Build Leadership That Drives Results</h3>
        <p class="dmba-why__card-desc">Develop the confidence and strategic thinking required to lead teams, manage change, and influence organisational growth.</p>
      </div>

      <div class="dmba-why__card" data-testid="dmba-why-card-2">
        <div class="dmba-why__card-icon">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
        </div>
        <h3 class="dmba-why__card-title">Two MBA Qualifications</h3>
        <p class="dmba-why__card-desc">Earn both a General MBA and a Specialised MBA through one integrated programme, enhancing your profile and credibility.</p>
      </div>

      <div class="dmba-why__card" data-testid="dmba-why-card-3">
        <div class="dmba-why__card-icon">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        </div>
        <h3 class="dmba-why__card-title">Designed for Working Professionals</h3>
        <p class="dmba-why__card-desc">Study while continuing your career through flexible 100% online learning and weekend classes.</p>
      </div>

      <div class="dmba-why__card" data-testid="dmba-why-card-4">
        <div class="dmba-why__card-icon">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
        </div>
        <h3 class="dmba-why__card-title">Industry-Relevant Curriculum</h3>
        <p class="dmba-why__card-desc">Learn practical concepts that can be immediately applied within your workplace and professional environment.</p>
      </div>

      <div class="dmba-why__card" data-testid="dmba-why-card-5">
        <div class="dmba-why__card-icon">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 9h6v6H9z"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/></svg>
        </div>
        <h3 class="dmba-why__card-title">Future-Focused Specialisations</h3>
        <p class="dmba-why__card-desc">Develop expertise in rapidly growing fields including AI, Business Analytics, Healthcare, IT, Finance, and Human Resources.</p>
      </div>

      <div class="dmba-why__card" data-testid="dmba-why-card-6">
        <div class="dmba-why__card-icon">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/><path d="M2 12h20"/></svg>
        </div>
        <h3 class="dmba-why__card-title">Global Career Opportunities</h3>
        <p class="dmba-why__card-desc">Prepare for leadership positions across multinational corporations, government organisations, startups, and consulting firms.</p>
      </div>

      <div class="dmba-why__card" data-testid="dmba-why-card-7">
        <div class="dmba-why__card-icon">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <h3 class="dmba-why__card-title">Affordable Investment</h3>
        <p class="dmba-why__card-desc">Complete two internationally recognised MBA qualifications with flexible payment options and scholarship opportunities.</p>
      </div>
    </div>
  </div>
</section>

{{-- ===== S5: SPECIALISATIONS GRID ===== --}}
<section class="dmba-specs section--light section-wrapper" aria-label="MBA Specialisations" data-testid="dmba-specs-section">
  <div class="container">
    <div class="dmba-specs__header">
      <div class="section-label"><span>Specialisations</span></div>
      <h2 class="section-title">Choose Your<br>Specialisation</h2>
      <p class="body-text" style="margin-top: 16px; color: rgba(0,0,0,0.6);">Gain advanced expertise in a specific business discipline aligned with your career goals.</p>
    </div>

    <div class="dmba-specs__grid" data-testid="dmba-specs-grid">
      <div class="dmba-specs__card" data-testid="dmba-spec-ai">
        <div class="dmba-specs__card-icon">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 9h6v6H9z"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/></svg>
        </div>
        <h3 class="dmba-specs__card-title">Artificial Intelligence</h3>
        <span class="dmba-specs__card-tag">MBA Specialisation</span>
      </div>

      <div class="dmba-specs__card" data-testid="dmba-spec-finance">
        <div class="dmba-specs__card-icon">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
        </div>
        <h3 class="dmba-specs__card-title">Finance</h3>
        <span class="dmba-specs__card-tag">MBA Specialisation</span>
      </div>

      <div class="dmba-specs__card" data-testid="dmba-spec-hr">
        <div class="dmba-specs__card-icon">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <h3 class="dmba-specs__card-title">Human Resource Management</h3>
        <span class="dmba-specs__card-tag">MBA Specialisation</span>
      </div>

      <div class="dmba-specs__card" data-testid="dmba-spec-supply">
        <div class="dmba-specs__card-icon">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        </div>
        <h3 class="dmba-specs__card-title">Supply Chain Management</h3>
        <span class="dmba-specs__card-tag">MBA Specialisation</span>
      </div>

      <div class="dmba-specs__card" data-testid="dmba-spec-pm">
        <div class="dmba-specs__card-icon">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
        </div>
        <h3 class="dmba-specs__card-title">Project Management</h3>
        <span class="dmba-specs__card-tag">MBA Specialisation</span>
      </div>

      <div class="dmba-specs__card" data-testid="dmba-spec-it">
        <div class="dmba-specs__card-icon">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        </div>
        <h3 class="dmba-specs__card-title">Information Technology</h3>
        <span class="dmba-specs__card-tag">MBA Specialisation</span>
      </div>

      <div class="dmba-specs__card" data-testid="dmba-spec-health">
        <div class="dmba-specs__card-icon">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        </div>
        <h3 class="dmba-specs__card-title">Healthcare Management</h3>
        <span class="dmba-specs__card-tag">MBA Specialisation</span>
      </div>

      <div class="dmba-specs__card" data-testid="dmba-spec-analytics">
        <div class="dmba-specs__card-icon">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
        </div>
        <h3 class="dmba-specs__card-title">Business Analytics</h3>
        <span class="dmba-specs__card-tag">MBA Specialisation</span>
      </div>
    </div>
  </div>
</section>

{{-- ===== S6: WHY EMPLOYERS VALUE ===== --}}
<section class="dmba-employers section--light section--warm section-wrapper" aria-label="Why Employers Value a Dual MBA" data-testid="dmba-employers-section">
  <div class="container">
    <div class="dmba-employers__grid">
      <div class="dmba-employers__image-col">
        <div class="dmba-employers__image-wrapper">
          <img
            src="https://images.unsplash.com/photo-1758876201598-67fd2a5570ed?w=800&q=80"
            alt="Business professionals in a collaborative work environment"
            loading="lazy"
          />
        </div>
        <div class="dmba-employers__counter">
          <span class="dmba-employers__counter-value" data-dmba-counter="8">0</span>
          <span class="dmba-employers__counter-label">Key Competencies<br>Employers Seek</span>
        </div>
      </div>

      <div class="dmba-employers__content">
        <div class="section-label"><span>Employer Value</span></div>
        <h2 class="dmba-employers__heading section-title">Why Employers Value a <span class="highlight"><em>Dual MBA</em></span></h2>
        <p class="dmba-employers__desc body-text">
          Today&rsquo;s employers increasingly seek professionals who combine strategic leadership with specialised expertise. Graduating with two MBA qualifications demonstrates:
        </p>

        <ul class="dmba-employers__list" data-testid="dmba-employers-list">
          <li class="dmba-employers__item">
            <span class="dmba-employers__item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
            Broader business understanding
          </li>
          <li class="dmba-employers__item">
            <span class="dmba-employers__item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
            Advanced industry knowledge
          </li>
          <li class="dmba-employers__item">
            <span class="dmba-employers__item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
            Strong leadership capability
          </li>
          <li class="dmba-employers__item">
            <span class="dmba-employers__item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
            Analytical thinking
          </li>
          <li class="dmba-employers__item">
            <span class="dmba-employers__item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
            Strategic decision-making
          </li>
          <li class="dmba-employers__item">
            <span class="dmba-employers__item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
            Cross-functional management
          </li>
          <li class="dmba-employers__item">
            <span class="dmba-employers__item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
            Adaptability in changing industries
          </li>
          <li class="dmba-employers__item">
            <span class="dmba-employers__item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
            Professional development commitment
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- ===== S7: TESTIMONIALS ===== --}}
<section class="dmba-testimonials section--light section-wrapper" aria-label="Student Success Stories" data-testid="dmba-testimonials-section">
  <div class="container">
    <div class="dmba-testimonials__header">
      <div class="section-label"><span>Success Stories</span></div>
      <h2 class="section-title">What Our <em>Graduates Say</em></h2>
    </div>

    <div class="dmba-testimonials__carousel" aria-roledescription="carousel" aria-label="Graduate testimonials">
      <div class="dmba-testimonials__track" data-testid="dmba-testimonials-track" aria-live="polite">
        <article class="dmba-testimonials__card" data-testid="dmba-testimonial-1">
          <span class="dmba-testimonials__card-icon" aria-hidden="true" data-lucide="quote"></span>
          <p class="dmba-testimonials__card-quote">The Dual MBA programme gave me both the strategic breadth and the AI specialisation I needed to transition into a tech leadership role. The flexible format was perfect for my schedule.</p>
          <div class="dmba-testimonials__card-footer">
            <div class="dmba-testimonials__card-author">
              <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="James M." class="dmba-testimonials__card-avatar" loading="lazy" width="52" height="52" />
              <div class="dmba-testimonials__card-info">
                <span class="dmba-testimonials__card-name">James M.</span>
                <span class="dmba-testimonials__card-role">Tech Director, London</span>
              </div>
            </div>
            <span class="dmba-testimonials__card-programme">Dual MBA — AI Specialisation</span>
          </div>
        </article>

        <article class="dmba-testimonials__card" data-testid="dmba-testimonial-2">
          <span class="dmba-testimonials__card-icon" aria-hidden="true" data-lucide="quote"></span>
          <p class="dmba-testimonials__card-quote">Having two MBA qualifications on my CV opened doors I never expected. I was promoted within 6 months of graduating. The programme is truly world-class.</p>
          <div class="dmba-testimonials__card-footer">
            <div class="dmba-testimonials__card-author">
              <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&h=100&fit=crop" alt="Priya S." class="dmba-testimonials__card-avatar" loading="lazy" width="52" height="52" />
              <div class="dmba-testimonials__card-info">
                <span class="dmba-testimonials__card-name">Priya S.</span>
                <span class="dmba-testimonials__card-role">VP Finance, Dubai</span>
              </div>
            </div>
            <span class="dmba-testimonials__card-programme">Dual MBA — Finance</span>
          </div>
        </article>

        <article class="dmba-testimonials__card" data-testid="dmba-testimonial-3">
          <span class="dmba-testimonials__card-icon" aria-hidden="true" data-lucide="quote"></span>
          <p class="dmba-testimonials__card-quote">As an entrepreneur, the General MBA gave me strategy and the Healthcare specialisation gave me domain expertise. This combination helped me launch my healthcare startup.</p>
          <div class="dmba-testimonials__card-footer">
            <div class="dmba-testimonials__card-author">
              <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop" alt="Ahmed K." class="dmba-testimonials__card-avatar" loading="lazy" width="52" height="52" />
              <div class="dmba-testimonials__card-info">
                <span class="dmba-testimonials__card-name">Ahmed K.</span>
                <span class="dmba-testimonials__card-role">Founder &amp; CEO, Riyadh</span>
              </div>
            </div>
            <span class="dmba-testimonials__card-programme">Dual MBA — Healthcare</span>
          </div>
        </article>

        <article class="dmba-testimonials__card" data-testid="dmba-testimonial-4">
          <span class="dmba-testimonials__card-icon" aria-hidden="true" data-lucide="quote"></span>
          <p class="dmba-testimonials__card-quote">The weekend class format allowed me to continue my career while studying. I gained deep knowledge in HR management alongside a solid business foundation.</p>
          <div class="dmba-testimonials__card-footer">
            <div class="dmba-testimonials__card-author">
              <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?w=100&h=100&fit=crop" alt="Sarah L." class="dmba-testimonials__card-avatar" loading="lazy" width="52" height="52" />
              <div class="dmba-testimonials__card-info">
                <span class="dmba-testimonials__card-name">Sarah L.</span>
                <span class="dmba-testimonials__card-role">HR Director, Singapore</span>
              </div>
            </div>
            <span class="dmba-testimonials__card-programme">Dual MBA — HR Management</span>
          </div>
        </article>

        <article class="dmba-testimonials__card" data-testid="dmba-testimonial-5">
          <span class="dmba-testimonials__card-icon" aria-hidden="true" data-lucide="quote"></span>
          <p class="dmba-testimonials__card-quote">The Dual MBA was the best investment in my career. The international recognition of the qualifications allowed me to move into a senior role in a multinational firm.</p>
          <div class="dmba-testimonials__card-footer">
            <div class="dmba-testimonials__card-author">
              <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=100&h=100&fit=crop" alt="David R." class="dmba-testimonials__card-avatar" loading="lazy" width="52" height="52" />
              <div class="dmba-testimonials__card-info">
                <span class="dmba-testimonials__card-name">David R.</span>
                <span class="dmba-testimonials__card-role">Senior Manager, New York</span>
              </div>
            </div>
            <span class="dmba-testimonials__card-programme">Dual MBA — Project Management</span>
          </div>
        </article>
      </div>
    </div>

    <div class="dmba-testimonials__controls" data-testid="dmba-testimonials-controls">
      <button type="button" class="dmba-testimonials__btn" data-dmba-carousel="prev" aria-label="Previous testimonials" data-testid="dmba-carousel-prev">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      </button>
      <div class="dmba-testimonials__dots" role="tablist" aria-label="Testimonial pages" data-testid="dmba-testimonials-dots"></div>
      <button type="button" class="dmba-testimonials__btn" data-dmba-carousel="next" aria-label="Next testimonials" data-testid="dmba-carousel-next">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>
    </div>
  </div>
</section>

{{-- ===== S8: APPLICATION PROCESS ===== --}}
<section class="dmba-process section--light section--warm section-wrapper" aria-label="Application Process" data-testid="dmba-process-section">
  <div class="container">
    <div class="dmba-process__header">
      <div class="section-label"><span>How It Works</span></div>
      <h2 class="section-title">Your Path to a Dual MBA</h2>
    </div>

    <div class="dmba-process__steps" data-testid="dmba-process-steps">
      <div class="dmba-process__step" data-testid="dmba-step-1">
        <div class="dmba-process__step-circle">1</div>
        <div class="dmba-process__step-content">
          <h3 class="dmba-process__step-title">Submit Application</h3>
          <p class="dmba-process__step-desc">Complete the online application form with your academic and professional details.</p>
        </div>
      </div>

      <div class="dmba-process__step" data-testid="dmba-step-2">
        <div class="dmba-process__step-circle">2</div>
        <div class="dmba-process__step-content">
          <h3 class="dmba-process__step-title">Review &amp; Admission</h3>
          <p class="dmba-process__step-desc">Our admissions team reviews your profile and responds within 48 hours.</p>
        </div>
      </div>

      <div class="dmba-process__step" data-testid="dmba-step-3">
        <div class="dmba-process__step-circle">3</div>
        <div class="dmba-process__step-content">
          <h3 class="dmba-process__step-title">Enrolment &amp; Onboarding</h3>
          <p class="dmba-process__step-desc">Secure your place, access learning materials, and meet your cohort.</p>
        </div>
      </div>

      <div class="dmba-process__step" data-testid="dmba-step-4">
        <div class="dmba-process__step-circle">4</div>
        <div class="dmba-process__step-content">
          <h3 class="dmba-process__step-title">Begin Your Journey</h3>
          <p class="dmba-process__step-desc">Start classes and work towards your Dual MBA qualification.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ===== S9: FAQ ACCORDION ===== --}}
<section class="dmba-faq section--light section-wrapper" aria-label="Frequently Asked Questions" data-testid="dmba-faq-section">
  <div class="container">
    <div class="dmba-faq__header">
      <div class="section-label"><span>FAQs</span></div>
      <h2 class="section-title">Frequently Asked Questions</h2>
    </div>

    <div class="dmba-faq__list" data-testid="dmba-faq-list">
      <div class="dmba-faq__item" data-testid="dmba-faq-item-1">
        <button class="dmba-faq__question" data-testid="dmba-faq-q-1">
          What is the Dual MBA Programme?
          <svg class="dmba-faq__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="dmba-faq__answer">
          <div class="dmba-faq__answer-inner">
            The Dual MBA Programme allows you to earn two internationally recognised MBA qualifications &mdash; a General MBA and a Specialised MBA &mdash; through one integrated learning pathway in just 1 year. You gain both broad business knowledge and specialist expertise.
          </div>
        </div>
      </div>

      <div class="dmba-faq__item" data-testid="dmba-faq-item-2">
        <button class="dmba-faq__question" data-testid="dmba-faq-q-2">
          Who is this programme designed for?
          <svg class="dmba-faq__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="dmba-faq__answer">
          <div class="dmba-faq__answer-inner">
            The programme is designed for ambitious professionals, entrepreneurs, managers, and future executives seeking to advance their careers with internationally recognised qualifications while continuing to work.
          </div>
        </div>
      </div>

      <div class="dmba-faq__item" data-testid="dmba-faq-item-3">
        <button class="dmba-faq__question" data-testid="dmba-faq-q-3">
          How long does the programme take to complete?
          <svg class="dmba-faq__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="dmba-faq__answer">
          <div class="dmba-faq__answer-inner">
            The Dual MBA Programme is designed to be completed in 1 year, with 100% online delivery and weekend classes to accommodate working professionals.
          </div>
        </div>
      </div>

      <div class="dmba-faq__item" data-testid="dmba-faq-item-4">
        <button class="dmba-faq__question" data-testid="dmba-faq-q-4">
          Can I study while working full-time?
          <svg class="dmba-faq__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="dmba-faq__answer">
          <div class="dmba-faq__answer-inner">
            Absolutely. The programme is specifically designed for working professionals, with 100% online delivery and classes scheduled on weekends so you can continue your career without interruption.
          </div>
        </div>
      </div>

      <div class="dmba-faq__item" data-testid="dmba-faq-item-5">
        <button class="dmba-faq__question" data-testid="dmba-faq-q-5">
          What specialisations are available?
          <svg class="dmba-faq__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="dmba-faq__answer">
          <div class="dmba-faq__answer-inner">
            Specialisations include Artificial Intelligence, Finance, Human Resource Management, Supply Chain Management, Project Management, Information Technology, Healthcare Management, and Business Analytics.
          </div>
        </div>
      </div>

      <div class="dmba-faq__item" data-testid="dmba-faq-item-6">
        <button class="dmba-faq__question" data-testid="dmba-faq-q-6">
          Are the degrees internationally recognised?
          <svg class="dmba-faq__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="dmba-faq__answer">
          <div class="dmba-faq__answer-inner">
            Yes. The Dual MBA is awarded by Girne American University (GAU), Rushford Business School (RBS), and the University for the Creative Arts &mdash; all internationally recognised institutions.
          </div>
        </div>
      </div>

      <div class="dmba-faq__item" data-testid="dmba-faq-item-7">
        <button class="dmba-faq__question" data-testid="dmba-faq-q-7">
          Are scholarships available?
          <svg class="dmba-faq__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="dmba-faq__answer">
          <div class="dmba-faq__answer-inner">
            Yes. Maverick Business Academy London offers flexible payment options and scholarship opportunities. Contact our admissions team to learn more about available financial support.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ===== S10: FINAL CTA ===== --}}
<section class="dmba-cta" aria-label="Apply for Dual MBA" data-testid="dmba-cta-section">
  <div class="dmba-cta__bg" aria-hidden="true">
    <img
      src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920&q=80"
      alt=""
      class="dmba-cta__bg-image"
      loading="lazy"
    />
    <div class="dmba-cta__overlay"></div>
  </div>

  <div class="container">
    <div class="dmba-cta__content">
      <h2 class="dmba-cta__heading" data-testid="dmba-cta-heading">Your Future Starts Here.<br>Apply for the Dual MBA Programme Today.</h2>
      <p class="dmba-cta__sub">Two internationally recognised MBA qualifications. One integrated programme. Unlimited career potential.</p>

      <div class="dmba-cta__buttons" data-testid="dmba-cta-buttons">
        <a href="{{ $site->apply_now_url ?? '/apply/' }}" class="btn btn--primary" data-testid="dmba-cta-apply-btn">Apply Now</a>
        <a href="{{ route('contact') }}" class="btn btn--secondary" data-testid="dmba-cta-consult-btn">Book a Free Consultation</a>
      </div>
      <a href="#" class="dmba-cta__link" data-testid="dmba-cta-brochure-link">Download Programme Brochure</a>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/dual-mba.js') }}" defer></script>
@endpush
