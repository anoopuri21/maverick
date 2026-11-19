{{-- MBA/Master's landing — single-page header.
     Section anchors only; same .navbar design contract so navigation.js keeps working. --}}
@php
    $mlpNavLinks = [
        ['label' => 'Overview', 'href' => '#mlp-overview'],
        ['label' => 'Why Maverick', 'href' => '#mlp-why'],
        ['label' => 'MBA', 'href' => '#mlp-mba'],
        ['label' => "Master's", 'href' => '#mlp-masters'],
        ['label' => 'Fees', 'href' => '#mlp-fees'],
        ['label' => 'FAQ', 'href' => '#mlp-faq'],
    ];
@endphp
<header id="navbar" class="navbar inner-navbar mlp-navbar" role="banner">
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

    <!-- Desktop: anchors to this page's sections -->
    <nav class="navbar__nav" role="navigation" aria-label="Page sections">
      <ul class="navbar__menu" role="menubar">
        @foreach($mlpNavLinks as $mlpLink)
        <li class="navbar__item" role="none">
          <a href="{{ $mlpLink['href'] }}" class="navbar__link" role="menuitem" data-mlp-nav>{{ $mlpLink['label'] }}</a>
        </li>
        @endforeach
      </ul>
    </nav>

    <!-- CTA -->
    <div class="navbar__cta">
      <a href="#mlp-enquire" class="btn btn--primary navbar__cta-btn"><span>Apply Now</span></a>
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
      <img src="{{ media_url($site->logo_white_url, 'assets/images/logo-white.png') }}" alt="Maverick Business Academy Logo" class="navbar__logo-img white-logo"
        style="position: absolute; top: 10px; left: 24px; width: calc(100vw - 120px); height: auto;" decoding="async" />
      <nav class="navbar__mobile-nav" aria-label="Page sections">
        <ul class="navbar__mobile-menu">
          @foreach($mlpNavLinks as $mlpLink)
          <li class="navbar__mobile-item">
            <a href="{{ $mlpLink['href'] }}" class="navbar__mobile-link">{{ $mlpLink['label'] }}</a>
          </li>
          @endforeach
          <li class="navbar__mobile-item navbar__mobile-item--cta">
            <a href="#mlp-enquire" class="navbar__mobile-link navbar__mobile-link--cta">Apply Now</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</header>
