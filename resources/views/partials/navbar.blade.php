<!-- Navigation -->
<header id="navbar" class="navbar {{ !request()->routeIs('home') ? 'inner-navbar' : '' }}" role="banner">
  <div class="navbar__container">
    <!-- Logo -->
    <a href="{{ route('home') }}" class="navbar__logo" aria-label="Maverick Business Academy Home">
      <div class="navbar__logo-placeholder">
        <img src="{{ $site->logo_white_url ?? asset('assets/images/logo-white.png') }}" alt="Maverick Business Academy Logo"
          class="navbar__logo-img white-logo" />
        <img src="{{ $site->logo_url ?? asset('assets/images/logo.png') }}" alt="Maverick Business Academy Logo"
          class="navbar__logo-img regular-logo" />
      </div>
    </a>

    <!-- Desktop Navigation -->
    <nav class="navbar__nav" role="navigation" aria-label="Main navigation">
      <ul class="navbar__menu" role="menubar">
        <li class="navbar__item navbar__item--has-mega" role="none">
          <button class="navbar__link navbar__link--trigger" role="menuitem" aria-haspopup="true"
            aria-expanded="false" data-menu="programs">
            Programs
            <span class="navbar__arrow" aria-hidden="true"></span>
          </button>
          <!-- Programs Mega Menu -->
          <!-- Programs Mega Menu -->
          <div class="navbar__mega" data-mega="programs" aria-hidden="true">
            <div class="mega__backdrop"></div>
            <div class="mega__panel">
              <div class="mega__inner">
                <div class="mega__body">

                  <!-- LEFT: Categories -->
                  <div class="mega__categories" role="tablist" aria-label="Program Categories">

                    <button type="button" class="mega__category-item is-active"
                      data-category="diplomas"
                      data-title="Diplomas"
                      role="tab" aria-selected="true">
                      <span class="mega__category-name">Diplomas</span>
                      <span class="mega__category-arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                      </span>
                    </button>

                    <button type="button" class="mega__category-item"
                      data-category="bachelors"
                      data-title="Bachelor's Degrees"
                      role="tab" aria-selected="false">
                      <span class="mega__category-name">Bachelor's Degrees</span>
                      <span class="mega__category-arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                      </span>
                    </button>

                    <button type="button" class="mega__category-item"
                      data-category="masters"
                      data-title="Master's Degrees"
                      data-href="{{ url('/masters-degrees/') }}"
                      role="tab" aria-selected="false">
                      <span class="mega__category-name">Master's Degrees</span>
                      <span class="mega__category-arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                      </span>
                    </button>

                    <button type="button" class="mega__category-item"
                      data-category="doctorate"
                      data-title="Doctorate Degrees"
                      data-href="{{ url('/doctorate-degrees/') }}"
                      role="tab" aria-selected="false">
                      <span class="mega__category-name">Doctorate Degrees</span>
                      <span class="mega__category-arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                      </span>
                    </button>

                    <button type="button" class="mega__category-item"
                      data-category="executive"
                      data-title="Executive Education"
                      data-href="{{ url('/executive-education/') }}"
                      role="tab" aria-selected="false">
                      <span class="mega__category-name">Executive Education</span>
                      <span class="mega__category-arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                      </span>
                    </button>

                    <button type="button" class="mega__category-item"
                      data-category="corporate"
                      data-title="Corporate Training"
                      data-href="{{ url('/corporate-training/') }}"
                      role="tab" aria-selected="false">
                      <span class="mega__category-name">Corporate Training</span>
                      <span class="mega__category-arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                      </span>
                    </button>

                    <button type="button" class="mega__category-item"
                      data-category="certifications"
                      data-title="Certifications"
                      data-href="{{ url('/certifications/') }}"
                      role="tab" aria-selected="false">
                      <span class="mega__category-name">Certifications</span>
                      <span class="mega__category-arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                      </span>
                    </button>

                  </div>

                  <!-- RIGHT: Panels -->
                  <div class="mega__panels">

                    <div class="mega__panel-head">
                      <h3 class="mega__panel-title" data-panel-title>Diplomas</h3>
                      <a href="{{ url('/diplomas/') }}" class="mega__panel-viewall" data-panel-link>
                        View All <span aria-hidden="true">→</span>
                      </a>
                    </div>

                    <div class="mega__panel-scroll">

                      <!-- Diplomas -->
                      <div class="mega__panel-list is-active" data-panel="diplomas">
                        <a href="{{ url('/programs/diploma-business-management/') }}" class="mega__program-row">
                          <span class="mega__program-name">Diploma in Business Management</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/diploma-marketing/') }}" class="mega__program-row">
                          <span class="mega__program-name">Diploma in Marketing</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/diploma-hr-management/') }}" class="mega__program-row">
                          <span class="mega__program-name">Diploma in HR Management</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/diploma-project-management/') }}" class="mega__program-row">
                          <span class="mega__program-name">Diploma in Project Management</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/diploma-logistics-supply-chain/') }}" class="mega__program-row">
                          <span class="mega__program-name">Diploma in Logistics &amp; Supply Chain</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/diploma-finance/') }}" class="mega__program-row">
                          <span class="mega__program-name">Diploma in Finance</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/diploma-it-management/') }}" class="mega__program-row">
                          <span class="mega__program-name">Diploma in IT Management</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/diploma-hospitality-management/') }}" class="mega__program-row">
                          <span class="mega__program-name">Diploma in Hospitality Management</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                      </div>

                      <!-- Bachelor's -->
                      <div class="mega__panel-list" data-panel="bachelors">
                        <a href="{{ url('/programs/bba/') }}" class="mega__program-row">
                          <span class="mega__program-name">Bachelor of Business Administration (BBA)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/bsc/') }}" class="mega__program-row">
                          <span class="mega__program-name">Bachelor of Science (BSc)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/ba/') }}" class="mega__program-row">
                          <span class="mega__program-name">Bachelor of Arts (BA)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/bba-marketing/') }}" class="mega__program-row">
                          <span class="mega__program-name">BBA in Marketing</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/bba-finance/') }}" class="mega__program-row">
                          <span class="mega__program-name">BBA in Finance</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/bsc-computer-science/') }}" class="mega__program-row">
                          <span class="mega__program-name">BSc in Computer Science</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/bsc-psychology/') }}" class="mega__program-row">
                          <span class="mega__program-name">BSc in Psychology</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/bba-hr-management/') }}" class="mega__program-row">
                          <span class="mega__program-name">BBA in HR Management</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                      </div>

                      <!-- Master's -->
                      <div class="mega__panel-list" data-panel="masters">
                        <a href="{{ url('/programs/mba/') }}" class="mega__program-row">
                          <span class="mega__program-name">Master of Business Administration (MBA)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/msc/') }}" class="mega__program-row">
                          <span class="mega__program-name">Master of Science (MSc)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/ma/') }}" class="mega__program-row">
                          <span class="mega__program-name">Master of Arts (MA)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/executive-mba/') }}" class="mega__program-row">
                          <span class="mega__program-name">Executive MBA</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/mba-finance/') }}" class="mega__program-row">
                          <span class="mega__program-name">MBA in Finance</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/mba-marketing/') }}" class="mega__program-row">
                          <span class="mega__program-name">MBA in Marketing</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/msc-data-analytics/') }}" class="mega__program-row">
                          <span class="mega__program-name">MSc in Data Analytics</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/ma-psychology/') }}" class="mega__program-row">
                          <span class="mega__program-name">MA in Psychology</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                      </div>

                      <!-- Doctorate -->
                      <div class="mega__panel-list" data-panel="doctorate">
                        <a href="{{ url('/programs/dba/') }}" class="mega__program-row">
                          <span class="mega__program-name">Doctor of Business Administration (DBA)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/phd-management/') }}" class="mega__program-row">
                          <span class="mega__program-name">PhD in Management</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/phd-business-administration/') }}" class="mega__program-row">
                          <span class="mega__program-name">PhD in Business Administration</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/professional-doctorate-leadership/') }}" class="mega__program-row">
                          <span class="mega__program-name">Professional Doctorate in Leadership</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/dba-strategic-management/') }}" class="mega__program-row">
                          <span class="mega__program-name">DBA in Strategic Management</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/phd-education/') }}" class="mega__program-row">
                          <span class="mega__program-name">PhD in Education</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                      </div>

                      <!-- Executive Education -->
                      <div class="mega__panel-list" data-panel="executive">
                        <a href="{{ url('/programs/mini-mba/') }}" class="mega__program-row">
                          <span class="mega__program-name">Mini MBA</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/executive-leadership-program/') }}" class="mega__program-row">
                          <span class="mega__program-name">Executive Leadership Program</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/strategic-management-certificate/') }}" class="mega__program-row">
                          <span class="mega__program-name">Strategic Management Certificate</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/executive-certificate-finance/') }}" class="mega__program-row">
                          <span class="mega__program-name">Executive Certificate in Finance</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/executive-certificate-marketing/') }}" class="mega__program-row">
                          <span class="mega__program-name">Executive Certificate in Marketing</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/leadership-excellence-program/') }}" class="mega__program-row">
                          <span class="mega__program-name">Leadership Excellence Program</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                      </div>

                      <!-- Corporate Training -->
                      <div class="mega__panel-list" data-panel="corporate">
                        <a href="{{ url('/programs/corporate-leadership-training/') }}" class="mega__program-row">
                          <span class="mega__program-name">Corporate Leadership Training</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/team-building-management/') }}" class="mega__program-row">
                          <span class="mega__program-name">Team Building &amp; Management</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/sales-excellence-training/') }}" class="mega__program-row">
                          <span class="mega__program-name">Sales Excellence Training</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/customer-service-excellence/') }}" class="mega__program-row">
                          <span class="mega__program-name">Customer Service Excellence</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/project-management-training/') }}" class="mega__program-row">
                          <span class="mega__program-name">Project Management Training</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/programs/digital-transformation-training/') }}" class="mega__program-row">
                          <span class="mega__program-name">Digital Transformation Training</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                      </div>

                      <!-- Certifications -->
                      <div class="mega__panel-list" data-panel="certifications">
                        <a href="{{ url('/certifications/digital-marketing-professional/') }}" class="mega__program-row">
                          <span class="mega__program-name">Certified Digital Marketing Professional</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/certifications/project-management-professional/') }}" class="mega__program-row">
                          <span class="mega__program-name">Certified Project Management Professional (CPMP)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/certifications/investment-management-analyst/') }}" class="mega__program-row">
                          <span class="mega__program-name">Certified Investment Management Analyst (CIMA)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/certifications/purchasing-procurement-manager/') }}" class="mega__program-row">
                          <span class="mega__program-name">Certified Purchasing and Procurement Manager (CPPM)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/certifications/purchasing-procurement-professional/') }}" class="mega__program-row">
                          <span class="mega__program-name">Certified Purchasing and Procurement Professional (CPPP)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/certifications/logistics-manager/') }}" class="mega__program-row">
                          <span class="mega__program-name">Certified Logistics Manager (CLM)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/certifications/sustainability-leadership-management/') }}" class="mega__program-row">
                          <span class="mega__program-name">Certified Sustainability Leadership and Management</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        <a href="{{ url('/certifications/training-development-professional/') }}" class="mega__program-row">
                          <span class="mega__program-name">Certified Training and Development Professional (CTDP)</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                      </div>

                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </li>

        <li class="navbar__item navbar__item--has-dropdown" role="none">
          <button class="navbar__link navbar__link--trigger" role="menuitem" aria-haspopup="true"
            aria-expanded="false" data-menu="about">
            About Us
            <span class="navbar__arrow" aria-hidden="true"></span>
          </button>
          <ul class="navbar__dropdown" data-dropdown="about" aria-hidden="true">
            <li>
              <a href="{{ url('/our-story/') }}" class="navbar__dropdown-link">Our Story</a>
            </li>
            <li>
              <a href="{{ url('/leadership-board/') }}" class="navbar__dropdown-link">Leadership &amp; Board</a>
            </li>
            <li>
              <a href="{{ url('/accreditations/') }}" class="navbar__dropdown-link">Accreditations &amp; Recognition</a>
            </li>
            <li>
              <a href="{{ url('/global-university-partners/') }}" class="navbar__dropdown-link">Global University
                Partners</a>
            </li>
            <li>
              <a href="{{ url('/csr-community-impact/') }}" class="navbar__dropdown-link">CSR &amp; Community Impact</a>
            </li>
            <li>
              <a href="{{ url('/media-gallery/') }}" class="navbar__dropdown-link">Media Gallery</a>
            </li>
          </ul>
        </li>

        <li class="navbar__item navbar__item--has-dropdown" role="none">
          <button class="navbar__link navbar__link--trigger" role="menuitem" aria-haspopup="true"
            aria-expanded="false" data-menu="pathways">
            Global Pathways
            <span class="navbar__arrow" aria-hidden="true"></span>
          </button>
          <ul class="navbar__dropdown" data-dropdown="pathways" aria-hidden="true">
            <li>
              <a href="{{ url('/pathway-programs/') }}" class="navbar__dropdown-link">Pathway Programs</a>
            </li>
            <li>
              <a href="{{ url('/global-opportunities/') }}" class="navbar__dropdown-link">Global Opportunities</a>
            </li>
          </ul>
        </li>

        <li class="navbar__item navbar__item--has-dropdown" role="none">
          <button class="navbar__link navbar__link--trigger" role="menuitem" aria-haspopup="true"
            aria-expanded="false" data-menu="insights">
            Insights
            <span class="navbar__arrow" aria-hidden="true"></span>
          </button>
          <ul class="navbar__dropdown" data-dropdown="insights" aria-hidden="true">
            <li>
              <a href="{{ url('/news/') }}" class="navbar__dropdown-link">News</a>
            </li>
            <li>
              <a href="{{ url('/events/') }}" class="navbar__dropdown-link">Events</a>
            </li>
            <li>
              <a href="{{ url('/blogs/') }}" class="navbar__dropdown-link">Blogs</a>
            </li>
            <li>
              <a href="{{ url('/student-success/') }}" class="navbar__dropdown-link">Student Success</a>
            </li>
          </ul>
        </li>

        <li class="navbar__item" role="none">
          <a href="{{ route('contact') }}" class="navbar__link" role="menuitem">Contact Us</a>
        </li>
      </ul>
    </nav>

    <!-- CTA Button -->
    <div class="navbar__cta">
      <a href="{{ $site->apply_now_url }}" class="btn btn--primary navbar__cta-btn">
        Apply Now
      </a>
    </div>

    <!-- Mobile Hamburger -->
    <button class="navbar__hamburger" aria-label="Toggle mobile menu" aria-expanded="false"
      aria-controls="mobile-menu">
      <span class="navbar__hamburger-line"></span>
      <span class="navbar__hamburger-line"></span>
      <span class="navbar__hamburger-line"></span>
    </button>
  </div>

  <!-- Mobile Menu Overlay -->
  <div class="navbar__mobile" id="mobile-menu" data-lenis-prevent aria-hidden="true">
    <div class="navbar__mobile-inner">
      <img src="{{ $site->logo_white_url ?? asset('assets/images/logo-white.png') }}" alt="Maverick Business Academy Logo" class="navbar__logo-img white-logo"
        style="position: absolute; top: 10px; left: 24px; width: calc(100vw - 120px); height: auto;" />
      <nav class="navbar__mobile-nav">
        <ul class="navbar__mobile-menu">
          <li class="navbar__mobile-item">
  <button class="navbar__mobile-link navbar__mobile-trigger" data-mobile-menu="programs">
    Programs
    <span class="navbar__mobile-arrow"></span>
  </button>

  <ul class="navbar__mobile-submenu" data-mobile-submenu="programs">

    <!-- Diplomas -->
    <li class="navbar__mobile-category">
      <button type="button" class="navbar__mobile-category-trigger" data-mobile-category="diplomas">
        Diplomas
        <span class="navbar__mobile-category-arrow" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
        </span>
      </button>
      <ul class="navbar__mobile-category-panel" data-mobile-category-panel="diplomas">
        <li><a href="{{ url('/programs/diploma-business-management/') }}" class="navbar__mobile-category-sublink">Diploma in Business Management <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/diploma-marketing/') }}" class="navbar__mobile-category-sublink">Diploma in Marketing <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/diploma-hr-management/') }}" class="navbar__mobile-category-sublink">Diploma in HR Management <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/diploma-project-management/') }}" class="navbar__mobile-category-sublink">Diploma in Project Management <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/diploma-finance/') }}" class="navbar__mobile-category-sublink">Diploma in Finance <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
      </ul>
    </li>

    <!-- Bachelor's Degrees -->
    <li class="navbar__mobile-category">
      <button type="button" class="navbar__mobile-category-trigger" data-mobile-category="bachelors">
        Bachelor's Degrees
        <span class="navbar__mobile-category-arrow" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
        </span>
      </button>
      <ul class="navbar__mobile-category-panel" data-mobile-category-panel="bachelors">
        <li><a href="{{ url('/programs/bba/') }}" class="navbar__mobile-category-sublink">Bachelor of Business Administration (BBA) <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/bsc/') }}" class="navbar__mobile-category-sublink">Bachelor of Science (BSc) <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/ba/') }}" class="navbar__mobile-category-sublink">Bachelor of Arts (BA) <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/bba-marketing/') }}" class="navbar__mobile-category-sublink">BBA in Marketing <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/bsc-computer-science/') }}" class="navbar__mobile-category-sublink">BSc in Computer Science <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
      </ul>
    </li>

    <!-- Master's Degrees -->
    <li class="navbar__mobile-category">
      <button type="button" class="navbar__mobile-category-trigger" data-mobile-category="masters">
        Master's Degrees
        <span class="navbar__mobile-category-arrow" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
        </span>
      </button>
      <ul class="navbar__mobile-category-panel" data-mobile-category-panel="masters">
        <li><a href="{{ url('/programs/mba/') }}" class="navbar__mobile-category-sublink">Master of Business Administration (MBA) <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/msc/') }}" class="navbar__mobile-category-sublink">Master of Science (MSc) <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/ma/') }}" class="navbar__mobile-category-sublink">Master of Arts (MA) <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/executive-mba/') }}" class="navbar__mobile-category-sublink">Executive MBA <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/mba-finance/') }}" class="navbar__mobile-category-sublink">MBA in Finance <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
      </ul>
    </li>

    <!-- Doctorate Degrees -->
    <li class="navbar__mobile-category">
      <button type="button" class="navbar__mobile-category-trigger" data-mobile-category="doctorate">
        Doctorate Degrees
        <span class="navbar__mobile-category-arrow" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
        </span>
      </button>
      <ul class="navbar__mobile-category-panel" data-mobile-category-panel="doctorate">
        <li><a href="{{ url('/programs/dba/') }}" class="navbar__mobile-category-sublink">Doctor of Business Administration (DBA) <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/phd-management/') }}" class="navbar__mobile-category-sublink">PhD in Management <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/phd-business-administration/') }}" class="navbar__mobile-category-sublink">PhD in Business Administration <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/professional-doctorate-leadership/') }}" class="navbar__mobile-category-sublink">Professional Doctorate in Leadership <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
      </ul>
    </li>

    <!-- Executive Education -->
    <li class="navbar__mobile-category">
      <button type="button" class="navbar__mobile-category-trigger" data-mobile-category="executive">
        Executive Education
        <span class="navbar__mobile-category-arrow" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
        </span>
      </button>
      <ul class="navbar__mobile-category-panel" data-mobile-category-panel="executive">
        <li><a href="{{ url('/programs/mini-mba/') }}" class="navbar__mobile-category-sublink">Mini MBA <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/executive-leadership-program/') }}" class="navbar__mobile-category-sublink">Executive Leadership Program <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/strategic-management-certificate/') }}" class="navbar__mobile-category-sublink">Strategic Management Certificate <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
      </ul>
    </li>

    <!-- Corporate Training -->
    <li class="navbar__mobile-category">
      <button type="button" class="navbar__mobile-category-trigger" data-mobile-category="corporate">
        Corporate Training
        <span class="navbar__mobile-category-arrow" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
        </span>
      </button>
      <ul class="navbar__mobile-category-panel" data-mobile-category-panel="corporate">
        <li><a href="{{ url('/programs/corporate-leadership-training/') }}" class="navbar__mobile-category-sublink">Corporate Leadership Training <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/team-building-management/') }}" class="navbar__mobile-category-sublink">Team Building &amp; Management <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/programs/sales-excellence-training/') }}" class="navbar__mobile-category-sublink">Sales Excellence Training <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
      </ul>
    </li>

    <!-- Certifications -->
    <li class="navbar__mobile-category">
      <button type="button" class="navbar__mobile-category-trigger" data-mobile-category="certifications">
        Certifications
        <span class="navbar__mobile-category-arrow" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
        </span>
      </button>
      <ul class="navbar__mobile-category-panel" data-mobile-category-panel="certifications">
        <li><a href="{{ url('/certifications/digital-marketing-professional/') }}" class="navbar__mobile-category-sublink">Certified Digital Marketing Professional <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/certifications/project-management-professional/') }}" class="navbar__mobile-category-sublink">Certified Project Management Professional (CPMP) <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        <li><a href="{{ url('/certifications/logistics-manager/') }}" class="navbar__mobile-category-sublink">Certified Logistics Manager (CLM) <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
      </ul>
    </li>

  </ul>
</li>

          <li class="navbar__mobile-item">
            <button class="navbar__mobile-link navbar__mobile-trigger" data-mobile-menu="about">
              About Us
              <span class="navbar__mobile-arrow"></span>
            </button>
            <ul class="navbar__mobile-submenu" data-mobile-submenu="about">
              <li>
                <a href="{{ route('our-story') }}" class="navbar__mobile-sublink">Our Story</a>
              </li>
              <li>
                <a href="{{ url('/leadership-board/') }}" class="navbar__mobile-sublink">Leadership &amp; Board</a>
              </li>
              <li>
                <a href="{{ url('/accreditations/') }}" class="navbar__mobile-sublink">Accreditations &amp;
                  Recognition</a>
              </li>
              <li>
                <a href="{{ url('/global-university-partners/') }}" class="navbar__mobile-sublink">Global University
                  Partners</a>
              </li>
              <li>
                <a href="{{ url('/csr-community-impact/') }}" class="navbar__mobile-sublink">CSR &amp; Community
                  Impact</a>
              </li>
              <li>
                <a href="{{ url('/media-gallery/') }}" class="navbar__mobile-sublink">Media Gallery</a>
              </li>
            </ul>
          </li>

          <li class="navbar__mobile-item">
            <button class="navbar__mobile-link navbar__mobile-trigger" data-mobile-menu="pathways">
              Global Pathways
              <span class="navbar__mobile-arrow"></span>
            </button>
            <ul class="navbar__mobile-submenu" data-mobile-submenu="pathways">
              <li>
                <a href="{{ url('/pathway-programs/') }}" class="navbar__mobile-sublink">Pathway Programs</a>
              </li>
              <li>
                <a href="{{ url('/global-opportunities/') }}" class="navbar__mobile-sublink">Global
                  Opportunities</a>
              </li>
            </ul>
          </li>

          <li class="navbar__mobile-item">
            <button class="navbar__mobile-link navbar__mobile-trigger" data-mobile-menu="insights">
              Insights
              <span class="navbar__mobile-arrow"></span>
            </button>
            <ul class="navbar__mobile-submenu" data-mobile-submenu="insights">
              <li>
                <a href="{{ url('/news/') }}" class="navbar__mobile-sublink">News</a>
              </li>
              <li>
                <a href="{{ url('/events/') }}" class="navbar__mobile-sublink">Events</a>
              </li>
              <li>
                <a href="{{ url('/blogs/') }}" class="navbar__mobile-sublink">Blogs</a>
              </li>
              <li>
                <a href="{{ url('/student-success/') }}" class="navbar__mobile-sublink">Student Success</a>
              </li>
            </ul>
          </li>

          <li class="navbar__mobile-item">
            <a href="{{ route('contact') }}" class="navbar__mobile-link">Contact Us</a>
          </li>
        </ul>
      </nav>

      <div class="navbar__mobile-cta">
        <a href="{{ url('/apply/') }}" class="btn btn--primary">Apply Now</a>
      </div>
    </div>
  </div>
</header>