<!-- Navigation -->
<header id="navbar" class="navbar" role="banner">
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
          <div class="navbar__mega" data-mega="programs" aria-hidden="true">
            <div class="mega__backdrop"></div>
            <div class="mega__panel">
              <div class="mega__inner">
                <!-- Left Column: Label -->
                <div class="mega__label-col">
                  <span class="mega__eyebrow">Explore</span>
                  <h2 class="mega__heading">Programs</h2>
                  <p class="mega__desc">
                    Globally recognized qualifications designed for
                    ambitious learners and professionals.
                  </p>
                  <a href="{{ url('/programs/') }}" class="mega__view-all">
                    View All Programs
                    <span class="mega__view-all-arrow">→</span>
                  </a>
                </div>

                <!-- Center Column: Program Categories Grid -->
                <div class="mega__programs-col">
                  <div class="mega__programs-grid">
                    <a href="{{ url('/programs/diplomas/') }}" class="mega__program-card">
                      <div class="mega__program-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                          <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
                          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 
                                     19.5v-15A2.5 2.5 0 016.5 2z" />
                        </svg>
                      </div>
                      <div class="mega__program-info">
                        <span class="mega__program-name">Diplomas</span>
                        <span class="mega__program-desc">
                          Level 3 to Postgraduate qualifications
                        </span>
                      </div>
                      <span class="mega__program-arrow">→</span>
                    </a>

                    <a href="{{ url('/programs/bachelors-degrees/') }}" class="mega__program-card">
                      <div class="mega__program-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                          <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                          <path d="M6 12v5c3 3 9 3 12 0v-5" />
                        </svg>
                      </div>
                      <div class="mega__program-info">
                        <span class="mega__program-name">Bachelor's Degrees</span>
                        <span class="mega__program-desc">
                          Business, Technology &amp; Psychology
                        </span>
                      </div>
                      <span class="mega__program-arrow">→</span>
                    </a>

                    <a href="{{ url('/programs/masters-degrees/') }}" class="mega__program-card">
                      <div class="mega__program-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                          <polygon points="12 2 15.09 8.26 22 9.27 17 
                                             14.14 18.18 21.02 12 17.77 
                                             5.82 21.02 7 14.14 2 9.27 
                                             8.91 8.26 12 2" />
                        </svg>
                      </div>
                      <div class="mega__program-info">
                        <span class="mega__program-name">Master's Degrees</span>
                        <span class="mega__program-desc">
                          MBA, MSc &amp; MA programs
                        </span>
                      </div>
                      <span class="mega__program-arrow">→</span>
                    </a>

                    <a href="{{ url('/programs/doctorate-degrees/') }}" class="mega__program-card">
                      <div class="mega__program-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                          <circle cx="12" cy="8" r="6" />
                          <path d="M15.477 12.89L17 22l-5-3-5 3 
                                     1.523-9.11" />
                        </svg>
                      </div>
                      <div class="mega__program-info">
                        <span class="mega__program-name">Doctorate Degrees</span>
                        <span class="mega__program-desc">
                          DBA, PhD &amp; Professional Doctorates
                        </span>
                      </div>
                      <span class="mega__program-arrow">→</span>
                    </a>

                    <a href="{{ url('/programs/executive-education/') }}" class="mega__program-card">
                      <div class="mega__program-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                          <rect x="2" y="3" width="20" height="14" rx="2" />
                          <path d="M8 21h8M12 17v4" />
                        </svg>
                      </div>
                      <div class="mega__program-info">
                        <span class="mega__program-name">Executive Education</span>
                        <span class="mega__program-desc">
                          Mini MBA &amp; Leadership programs
                        </span>
                      </div>
                      <span class="mega__program-arrow">→</span>
                    </a>

                    <a href="{{ url('/programs/corporate-training/') }}" class="mega__program-card">
                      <div class="mega__program-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 
                                     0 00-4 4v2" />
                          <circle cx="9" cy="7" r="4" />
                          <path d="M23 21v-2a4 4 0 00-3-3.87" />
                          <path d="M16 3.13a4 4 0 010 7.75" />
                        </svg>
                      </div>
                      <div class="mega__program-info">
                        <span class="mega__program-name">Corporate Training</span>
                        <span class="mega__program-desc">
                          Customized organizational programs
                        </span>
                      </div>
                      <span class="mega__program-arrow">→</span>
                    </a>
                  </div>
                </div>

                <!-- Right Column: Featured Program -->
                <div class="mega__featured-col">
                  <span class="mega__featured-label">Featured Program</span>
                  <div class="mega__featured-card">
                    <div class="mega__featured-img">
                      <img src="{{ asset('assets/images/homepage/mba.jpg') }}" alt="Global MBA Program" />
                    </div>
                    <div class="mega__featured-content">
                      <span class="mega__featured-tag">Master's Degree</span>
                      <h3 class="mega__featured-title">Global MBA</h3>
                      <p class="mega__featured-partner">
                        University of the Creative Arts
                      </p>
                      <p class="mega__featured-badge">Triple Accredited</p>
                      <a href="{{ url('/programs/masters-degrees/global-mba/') }}" class="mega__featured-cta">
                        Explore Program →
                      </a>
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
              <a href="{{ url('/about-us/our-story/') }}" class="navbar__dropdown-link">Our Story</a>
            </li>
            <li>
              <a href="{{ url('/about-us/leadership-board/') }}" class="navbar__dropdown-link">Leadership &amp; Board</a>
            </li>
            <li>
              <a href="{{ url('/about-us/accreditations/') }}" class="navbar__dropdown-link">Accreditations &amp; Recognition</a>
            </li>
            <li>
              <a href="{{ url('/about-us/global-university-partners/') }}" class="navbar__dropdown-link">Global University
                Partners</a>
            </li>
            <li>
              <a href="{{ url('/about-us/csr-community-impact/') }}" class="navbar__dropdown-link">CSR &amp; Community Impact</a>
            </li>
            <li>
              <a href="{{ url('/about-us/media-gallery/') }}" class="navbar__dropdown-link">Media Gallery</a>
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
              <a href="{{ url('/global-pathways/pathway-programs/') }}" class="navbar__dropdown-link">Pathway Programs</a>
            </li>
            <li>
              <a href="{{ url('/global-pathways/global-opportunities/') }}" class="navbar__dropdown-link">Global Opportunities</a>
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
              <a href="{{ url('/insights/news/') }}" class="navbar__dropdown-link">News</a>
            </li>
            <li>
              <a href="{{ url('/insights/events/') }}" class="navbar__dropdown-link">Events</a>
            </li>
            <li>
              <a href="{{ url('/insights/blogs/') }}" class="navbar__dropdown-link">Blogs</a>
            </li>
            <li>
              <a href="{{ url('/insights/student-success-faculty-insights/') }}" class="navbar__dropdown-link">Student Success /
                Faculty Insights</a>
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
  <div class="navbar__mobile" id="mobile-menu" aria-hidden="true">
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
              <li>
                <a href="{{ url('/programs/diplomas/') }}" class="navbar__mobile-sublink">Diplomas</a>
              </li>
              <li>
                <a href="{{ url('/programs/bachelors-degrees/') }}" class="navbar__mobile-sublink">Bachelor's Degrees</a>
              </li>
              <li>
                <a href="{{ url('/programs/masters-degrees/') }}" class="navbar__mobile-sublink">Master's Degrees</a>
              </li>
              <li>
                <a href="{{ url('/programs/doctorate-degrees/') }}" class="navbar__mobile-sublink">Doctorate Degrees</a>
              </li>
              <li>
                <a href="{{ url('/programs/executive-education/') }}" class="navbar__mobile-sublink">Executive Education</a>
              </li>
              <li>
                <a href="{{ url('/programs/corporate-training/') }}" class="navbar__mobile-sublink">Corporate Training</a>
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
                <a href="{{ url('/about-us/our-story/') }}" class="navbar__mobile-sublink">Our Story</a>
              </li>
              <li>
                <a href="{{ url('/about-us/leadership-board/') }}" class="navbar__mobile-sublink">Leadership &amp; Board</a>
              </li>
              <li>
                <a href="{{ url('/about-us/accreditations/') }}" class="navbar__mobile-sublink">Accreditations &amp;
                  Recognition</a>
              </li>
              <li>
                <a href="{{ url('/about-us/global-university-partners/') }}" class="navbar__mobile-sublink">Global University
                  Partners</a>
              </li>
              <li>
                <a href="{{ url('/about-us/csr-community-impact/') }}" class="navbar__mobile-sublink">CSR &amp; Community
                  Impact</a>
              </li>
              <li>
                <a href="{{ url('/about-us/media-gallery/') }}" class="navbar__mobile-sublink">Media Gallery</a>
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
                <a href="{{ url('/global-pathways/pathway-programs/') }}" class="navbar__mobile-sublink">Pathway Programs</a>
              </li>
              <li>
                <a href="{{ url('/global-pathways/global-opportunities/') }}" class="navbar__mobile-sublink">Global
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
                <a href="{{ url('/insights/news/') }}" class="navbar__mobile-sublink">News</a>
              </li>
              <li>
                <a href="{{ url('/insights/events/') }}" class="navbar__mobile-sublink">Events</a>
              </li>
              <li>
                <a href="{{ url('/insights/blogs/') }}" class="navbar__mobile-sublink">Blogs</a>
              </li>
              <li>
                <a href="{{ url('/insights/student-success-faculty-insights/') }}" class="navbar__mobile-sublink">Student Success /
                  Faculty Insights</a>
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