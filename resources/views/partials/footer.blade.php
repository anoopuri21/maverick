<!-- Footer -->
<footer id="footer" class="footer" aria-label="Site footer">
  <div class="container">
    <!-- ========== MAIN FOOTER CONTENT ========== -->
    <div class="footer__main">
      <!-- COLUMN 1: Logo + About + Newsletter -->
      <div class="footer__col footer__col--brand">
        <a href="{{ route('home') }}" class="footer__logo" aria-label="Maverick Business Academy Home">
          <img src="{{ media_url($site->logo_white_url, 'assets/images/logo-white.png') }}" alt="Maverick Business Academy Logo" class="footer__logo-img" loading="lazy" decoding="async">
        </a>

        <p class="footer__about">
          Transforming learners into global leaders through internationally
          recognised qualifications and transformative learning experiences.
        </p>

        <div class="footer__newsletter">
          <h3 class="footer__newsletter-title">Stay Updated</h3>
          <p class="footer__newsletter-desc">
            Get insights on programs, events, and industry trends.
          </p>

          <form class="footer__newsletter-form" data-newsletter-form action="{{ route('newsletter.subscribe') }}" method="POST">
            @csrf
            <input type="email" name="email" class="footer__newsletter-input" placeholder="Your email address" required
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
        <h4 class="footer__heading">Programmes</h4>
        <ul class="footer__links">
          @forelse(($footerProgramCategories ?? collect()) as $category)
          <li>
            <a href="{{ route('programs.index') }}" class="footer__link">{{ $category->name }}</a>
          </li>
          @empty
          <li>
            <a href="{{ route('programs.index') }}" class="footer__link">All Programmes</a>
          </li>
          @endforelse
        </ul>
      </div>
      <!-- DYNAMIC END: footer-programs -->

      <!-- COLUMN 3: Contact + Social -->
      <div class="footer__col">
        <h4 class="footer__heading">Contact</h4>

        <address class="footer__contact">
          @if(filled($site->address ?? null) || filled($site->phone ?? null) || filled($site->phone_secondary ?? null) || filled($site->email ?? null))
          <div class="footer__locale">
            <div class="footer__locale-title">
              <span class="footer__locale-flag" aria-hidden="true">🇦🇪</span>
              <span>UAE</span>
            </div>

            @if(filled($site->address ?? null))
            <div class="footer__contact-item">
              <span class="footer__contact-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
              </span>
              <span class="footer__contact-value">{{ $site->address }}</span>
            </div>
            @endif

            @if(filled($site->phone ?? null))
            <div class="footer__contact-item">
              <span class="footer__contact-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              </span>
              <a href="tel:{{ preg_replace('/[^\d+]/', '', $site->phone) }}" class="footer__contact-value footer__contact-link">{{ $site->phone }}</a>
            </div>
            @endif

            @if(filled($site->phone_secondary ?? null))
            <div class="footer__contact-item">
              <span class="footer__contact-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              </span>
              <a href="tel:{{ preg_replace('/[^\d+]/', '', $site->phone_secondary) }}" class="footer__contact-value footer__contact-link">{{ $site->phone_secondary }}</a>
            </div>
            @endif

            @if(filled($site->email ?? null))
            <div class="footer__contact-item">
              <span class="footer__contact-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              </span>
              <a href="mailto:{{ $site->email }}" class="footer__contact-value footer__contact-link">{{ $site->email }}</a>
            </div>
            @endif
          </div>
          @endif

          @if(filled($site->uk_address ?? null) || filled($site->uk_phone ?? null) || filled($site->uk_email ?? null))
          <div class="footer__locale">
            <div class="footer__locale-title">
              <span class="footer__locale-flag" aria-hidden="true">🇬🇧</span>
              <span>UK</span>
            </div>

            @if(filled($site->uk_address ?? null))
            <div class="footer__contact-item">
              <span class="footer__contact-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
              </span>
              <span class="footer__contact-value">{{ $site->uk_address }}</span>
            </div>
            @endif

            @if(filled($site->uk_phone ?? null))
            <div class="footer__contact-item">
              <span class="footer__contact-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              </span>
              <a href="tel:{{ preg_replace('/[^\d+]/', '', $site->uk_phone) }}" class="footer__contact-value footer__contact-link">{{ $site->uk_phone }}</a>
            </div>
            @endif

            @if(filled($site->uk_email ?? null))
            <div class="footer__contact-item">
              <span class="footer__contact-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              </span>
              <a href="mailto:{{ $site->uk_email }}" class="footer__contact-value footer__contact-link">{{ $site->uk_email }}</a>
            </div>
            @endif
          </div>
          @endif

          @if(filled($site->whatsapp_number ?? null))
          <div class="footer__contact-item">
            <span class="footer__contact-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </span>
            <a href="https://wa.me/{{ $site->whatsapp_number }}" class="footer__contact-value footer__contact-link" target="_blank" rel="noopener noreferrer">
              +{{ ltrim($site->whatsapp_number, '+') }}
            </a>
          </div>
          @endif
        </address>

        <!-- DYNAMIC START: footer-social -->
        <div class="footer__social">
          @if($href = edu_href($site->facebook_url ?? null))
          <a href="{{ $href }}" class="footer__social-link" target="_blank" rel="noopener"
            aria-label="Facebook">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path
                d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
            </svg>
          </a>
          @endif
          @if($href = edu_href($site->instagram_url ?? null))
          <a href="{{ $href }}" class="footer__social-link" target="_blank" rel="noopener"
            aria-label="Instagram">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path
                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
            </svg>
          </a>
          @endif
          @if($href = edu_href($site->linkedin_url ?? null))
          <a href="{{ $href }}" class="footer__social-link"
            target="_blank" rel="noopener" aria-label="LinkedIn">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path
                d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" />
            </svg>
          </a>
          @endif
          @if($href = edu_href($site->youtube_url ?? null))
          <a href="{{ $href }}" class="footer__social-link" target="_blank"
            rel="noopener" aria-label="YouTube">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path
                d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
            </svg>
          </a>
          @endif
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
          <a href="{{ route('privacy-policy') }}" class="footer__legal-link">Privacy Policy</a>
        </li>
        <li>
          <a href="{{ route('terms-of-use') }}" class="footer__legal-link">Terms of Use</a>
        </li>
      </ul>
    </div>
  </div>
</footer>