<!-- Navigation -->
@php
    // Single source of truth for the Programs mega-menu (cached).
    $navPrograms = \App\Support\NavMenu::programs();
@endphp
<header id="navbar" class="navbar {{ !request()->routeIs('home') ? 'inner-navbar' : '' }}" role="banner">
  <div class="navbar__container">
    <!-- Logo -->
    <a href="{{ route('home') }}" class="navbar__logo" aria-label="Maverick Business Academy Home">
      <div class="navbar__logo-placeholder">
        <img src="{{ media_url($site->logo_white_url, 'assets/images/logo-white.png') }}" alt="Maverick Business Academy Logo"
          class="navbar__logo-img white-logo" decoding="async" />
        <img src="{{ media_url($site->logo_url, 'assets/images/logo.png') }}" alt="Maverick Business Academy Logo"
          class="navbar__logo-img regular-logo" decoding="async" />
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

                  <!-- LEFT: Categories (DB-driven) -->
                  <div class="mega__categories" role="tablist" aria-label="Program Categories">
                    @foreach($navPrograms as $navCat)
                    <button type="button"
                      class="mega__category-item @if($loop->first) is-active @endif"
                      data-category="{{ $navCat['slug'] }}"
                      data-title="{{ $navCat['name'] }}"
                      data-href="{{ $navCat['viewAll'] }}"
                      role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                      <span class="mega__category-name">{{ $navCat['name'] }}</span>
                      <span class="mega__category-arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                      </span>
                    </button>
                    @endforeach
                  </div>

                  <!-- RIGHT: Panels (DB-driven) -->
                  <div class="mega__panels">
                    <div class="mega__panel-head">
                      <h3 class="mega__panel-title" data-panel-title>{{ $navPrograms[0]['name'] ?? 'Programs' }}</h3>
                      <a href="{{ $navPrograms[0]['viewAll'] ?? route('programs.index') }}" class="mega__panel-viewall" data-panel-link>
                        View All <span aria-hidden="true">→</span>
                      </a>
                    </div>
                    <div class="mega__panel-scroll">
                      @foreach($navPrograms as $navCat)
                      <div class="mega__panel-list @if($loop->first) is-active @endif" data-panel="{{ $navCat['slug'] }}">
                        @foreach(($navCat['programs'] ?? []) as $navProg)
                        <a href="{{ $navProg['url'] ?? '#' }}" class="mega__program-row">
                          <span class="mega__program-name">{{ $navProg['title'] ?? '' }}@if(!empty($navProg['university']))<span class="mega__program-uni"> · {{ $navProg['university'] }}</span>@endif</span>
                          <span class="mega__row-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span>
                        </a>
                        @endforeach
                        @if(empty($navCat['programs']))
                        <span class="mega__program-empty">No programmes yet.</span>
                        @endif
                      </div>
                      @endforeach
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
              <a href="{{ route('pathway-programs') }}" class="navbar__dropdown-link">Pathway Programs</a>
            </li>
            <li>
              <a href="{{ route('global-opportunities') }}" class="navbar__dropdown-link">Global Opportunities</a>
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

        <li class="navbar__item navbar__item--has-dropdown" role="none">
          <button class="navbar__link navbar__link--trigger" role="menuitem" aria-haspopup="true"
            aria-expanded="false" data-menu="student-login">
            Student Login
            <span class="navbar__arrow" aria-hidden="true"></span>
          </button>
          <ul class="navbar__dropdown navbar__dropdown--student" data-dropdown="student-login" aria-hidden="true">
            <li>
              <a href="http://studentportal.mbalondon.org.uk/" target="_blank" rel="noopener"
                class="navbar__dropdown-link">Undergraduate</a>
            </li>
            <li>
              <a href="https://courses.augment.org/enroll/2988173?price_id=3844078" target="_blank" rel="noopener"
                class="navbar__dropdown-link">Masters</a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>


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
      <img src="{{ media_url($site->logo_white_url, 'assets/images/logo-white.png') }}" alt="Maverick Business Academy Logo" class="navbar__logo-img white-logo"
        style="position: absolute; top: 10px; left: 24px; width: calc(100vw - 120px); height: auto;" decoding="async" />
      <nav class="navbar__mobile-nav">
        <ul class="navbar__mobile-menu">
          <li class="navbar__mobile-item">
  <button class="navbar__mobile-link navbar__mobile-trigger" data-mobile-menu="programs">
    Programs
    <span class="navbar__mobile-arrow"></span>
  </button>

  <ul class="navbar__mobile-submenu" data-mobile-submenu="programs">
    @foreach($navPrograms as $navCat)
    <li class="navbar__mobile-category">
      <button type="button" class="navbar__mobile-category-trigger" data-mobile-category="{{ $navCat['slug'] }}">
        {{ $navCat['name'] }}
        <span class="navbar__mobile-category-arrow" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
        </span>
      </button>
      <ul class="navbar__mobile-category-panel" data-mobile-category-panel="{{ $navCat['slug'] }}">
        @foreach(($navCat['programs'] ?? []) as $navProg)
        <li><a href="{{ $navProg['url'] ?? '#' }}" class="navbar__mobile-category-sublink">{{ $navProg['title'] ?? '' }}@if(!empty($navProg['university'])) <span class="navbar__mobile-category-uni">· {{ $navProg['university'] }}</span>@endif <span class="navbar__mobile-category-sublink-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg></span></a></li>
        @endforeach
        @if(empty($navCat['programs']))
        <li class="navbar__mobile-category-empty">No programmes yet.</li>
        @endif
      </ul>
    </li>
    @endforeach
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
                <a href="{{ route('pathway-programs') }}" class="navbar__mobile-sublink">Pathway Programs</a>
              </li>
              <li>
                <a href="{{ route('global-opportunities') }}" class="navbar__mobile-sublink">Global Opportunities</a>
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

          <li class="navbar__mobile-item">
            <button class="navbar__mobile-link navbar__mobile-trigger" data-mobile-menu="student-login">
              Student Login
              <span class="navbar__mobile-arrow"></span>
            </button>
            <ul class="navbar__mobile-submenu" data-mobile-submenu="student-login">
              <li>
                <a href="http://studentportal.mbalondon.org.uk/" target="_blank" rel="noopener"
                  class="navbar__mobile-sublink">Undergraduate</a>
              </li>
              <li>
                <a href="https://courses.augment.org/enroll/2988173?price_id=3844078" target="_blank" rel="noopener"
                  class="navbar__mobile-sublink">Masters</a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</header>