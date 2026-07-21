<!-- Footer -->
<footer id="footer" class="footer" aria-label="Site footer">
  <div class="container">
    <!-- ========== MAIN FOOTER CONTENT ========== -->
    <div class="footer__main">
      <!-- COLUMN 1: Logo + About + Newsletter -->
      <div class="footer__col footer__col--brand">
        <a href="{{ route('home') }}" class="footer__logo" aria-label="Maverick Business Academy Home">
          <img src="{{ $site->logo_white_url ?? asset('assets/images/logo-white.png') }}" alt="Maverick Business Academy Logo" class="footer__logo-img">
        </a>

        <p class="footer__about">
          Transforming learners into global leaders through internationally
          recognised qualifications and transformative learning experiences.
        </p>

        <div class="footer__newsletter">
          <h4 class="footer__newsletter-title">Stay Updated</h4>
          <p class="footer__newsletter-desc">
            Get insights on programs, events, and industry trends.
          </p>

          <form class="footer__newsletter-form" data-newsletter-form>
            <input type="email" class="footer__newsletter-input" placeholder="Your email address" required
              aria-label="Email for newsletter" />
            <button type="submit" class="footer__newsletter-btn" aria-label="Subscribe">
              <span>Subscribe</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M5 12h14M13 5l7 7-7 7" />
              </svg>
            </button>
          </form>
        </div>
      </div>

      <!-- COLUMN 2: Programs -->
      <!-- DYNAMIC START: footer-programs -->
      <div class="footer__col">
        <h4 class="footer__heading">Programs</h4>
        <ul class="footer__links">
          <li>
            <a href="{{ url('/programs/diplomas/') }}" class="footer__link">Diplomas</a>
          </li>
          <li>
            <a href="{{ url('/programs/bachelors-degrees/') }}" class="footer__link">Bachelor's Degrees</a>
          </li>
          <li>
            <a href="{{ url('/programs/masters-degrees/') }}" class="footer__link">Master's Degrees</a>
          </li>
          <li>
            <a href="{{ url('/programs/doctorate-degrees/') }}" class="footer__link">Doctorate Degrees</a>
          </li>
          <li>
            <a href="{{ url('/programs/executive-education/') }}" class="footer__link">Executive Education</a>
          </li>
          <li>
            <a href="{{ url('/programs/corporate-training/') }}" class="footer__link">Corporate Training</a>
          </li>
        </ul>
      </div>
      <!-- DYNAMIC END: footer-programs -->

      <!-- COLUMN 3: Contact + Social -->
      <div class="footer__col">
        <h4 class="footer__heading">Contact</h4>

        <address class="footer__contact">
          <div class="footer__contact-item">
            <span class="footer__contact-label">Address</span>
            <span class="footer__contact-value">
              {{ $site->address }}
            </span>
          </div>

          <div class="footer__contact-item">
            <span class="footer__contact-label">Email</span>
            <a href="mailto:{{ $site->email }}" class="footer__contact-value footer__contact-link">
              {{ $site->email }}
            </a>
          </div>

          <div class="footer__contact-item">
            <span class="footer__contact-label">Phone</span>
            <a href="tel:+{{ $site->phone }}" class="footer__contact-value footer__contact-link">
              {{ $site->phone }}
            </a>
            <a href="tel:+{{ $site->phone }}" class="footer__contact-value footer__contact-link">
              +{{ $site->phone }}
            </a>
          </div>
        </address>

        <!-- DYNAMIC START: footer-social -->
        <div class="footer__social">
          <a href="{{ $site->facebook_url }}" class="footer__social-link" target="_blank" rel="noopener"
            aria-label="Facebook">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path
                d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
            </svg>
          </a>
          <a href="{{ $site->instagram_url }}" class="footer__social-link" target="_blank" rel="noopener"
            aria-label="Instagram">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path
                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
            </svg>
          </a>
          <a href="{{ $site->linkedin_url }}" class="footer__social-link"
            target="_blank" rel="noopener" aria-label="LinkedIn">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path
                d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" />
            </svg>
          </a>
          <a href="{{ $site->youtube_url }}" class="footer__social-link" target="_blank"
            rel="noopener" aria-label="YouTube">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path
                d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
            </svg>
          </a>
        </div>
        <!-- DYNAMIC END: footer-social -->
      </div>
    </div>

    <!-- ========== BOTTOM ROW (Copyright + Legal) ========== -->
    <div class="footer__bottom">
      <p class="footer__copyright">
        &copy; <span data-current-year>2026</span> Maverick Business
        Academy. All rights reserved.
      </p>

      <ul class="footer__legal">
        <li>
          <a href="{{ url('/privacy-policy/') }}" class="footer__legal-link">Privacy Policy</a>
        </li>
        <li>
          <a href="{{ url('/terms-of-service/') }}" class="footer__legal-link">Terms of Service</a>
        </li>
      </ul>
    </div>
  </div>
</footer>