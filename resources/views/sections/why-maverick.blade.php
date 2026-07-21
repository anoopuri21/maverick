<section id="why-maverick" class="why section-wrapper section--light" aria-label="Why Maverick">
  <div class="container why__inner">
    <div class="why__header">
      <div class="section-label">
        <span>Why Maverick</span>
      </div>
      <h2 class="why__heading section-title">
        <span class="hwdi__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">{{ $whyMaverick->heading_line1 }}</span>
          </span>
        </span>
        <span class="hwdi__heading-line hwdi__heading-line--red">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">{{ $whyMaverick->heading_line2 }}</span>
          </span>
        </span>
      </h2>
      <p class="why__subtitle body-text fade-up">
        {{ $whyMaverick->subtitle }}
      </p>
    </div>

    <div class="why__grid">
      <!-- Tile 1: International Qualifications (LARGE) -->
      <div class="why__tile why__tile--large">
        <div class="why__tile-background"></div>
        <div class="why__tile-top">
          <svg class="why__tile-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
            </path>
            <path d="M2 12h20"></path>
          </svg>
          <span class="why__tile-index">01</span>
        </div>
        <div class="why__tile-bottom">
          <h3 class="why__tile-title">{{ $whyMaverick->tile1_title }}</h3>
          <p class="why__tile-description">
            {{ $whyMaverick->tile1_desc }}
          </p>
          <div class="why__tile-line"></div>
        </div>
      </div>

      <!-- Tile 2: Global University Network -->
      <div class="why__tile why__tile--wide">
        <div class="why__tile-background"></div>
        <div class="why__tile-top">
          <svg class="why__tile-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="18" cy="5" r="3"></circle>
            <circle cx="6" cy="12" r="3"></circle>
            <circle cx="18" cy="19" r="3"></circle>
            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
          </svg>
          <span class="why__tile-index">02</span>
        </div>
        <div class="why__tile-bottom">
          <h3 class="why__tile-title">{{ $whyMaverick->tile2_title }}</h3>
          <p class="why__tile-description">{{ $whyMaverick->tile2_desc }}</p>
          <div class="why__tile-line"></div>
        </div>
      </div>

      <!-- Tile 3: Flexible Learning -->
      <div class="why__tile">
        <div class="why__tile-background"></div>
        <div class="why__tile-top">
          <svg class="why__tile-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
            <line x1="8" y1="21" x2="16" y2="21"></line>
            <line x1="12" y1="17" x2="12" y2="21"></line>
            <polygon points="10 7 15 10 10 13 10 7"></polygon>
          </svg>
          <span class="why__tile-index">03</span>
        </div>
        <div class="why__tile-bottom">
          <h3 class="why__tile-title">{{ $whyMaverick->tile3_title }}</h3>
          <p class="why__tile-description">{{ $whyMaverick->tile3_desc }}</p>
          <div class="why__tile-line"></div>
        </div>
      </div>

      <!-- Tile 4: Career Advancement -->
      <div class="why__tile">
        <div class="why__tile-background"></div>
        <div class="why__tile-top">
          <svg class="why__tile-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="12" y1="19" x2="12" y2="5"></line>
            <polyline points="5 12 12 5 19 12"></polyline>
          </svg>
          <span class="why__tile-index">04</span>
        </div>
        <div class="why__tile-bottom">
          <h3 class="why__tile-title">{{ $whyMaverick->tile4_title }}</h3>
          <p class="why__tile-description">{{ $whyMaverick->tile4_desc }}</p>
          <div class="why__tile-line"></div>
        </div>
      </div>

      <!-- Tile 5: Industry Engagement -->
      <div class="why__tile why__tile--wide">
        <div class="why__tile-background"></div>
        <div class="why__tile-top">
          <svg class="why__tile-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <polyline points="16 11 18 13 22 9"></polyline>
          </svg>
          <span class="why__tile-index">05</span>
        </div>
        <div class="why__tile-bottom">
          <h3 class="why__tile-title">{{ $whyMaverick->tile5_title }}</h3>
          <p class="why__tile-description">{{ $whyMaverick->tile5_desc }}</p>
          <div class="why__tile-line"></div>
        </div>
      </div>

      <!-- Tile 6: Academic Excellence -->
      <div class="why__tile why__tile--wide">
        <div class="why__tile-background"></div>
        <div class="why__tile-top">
          <svg class="why__tile-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon
              points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
            </polygon>
          </svg>
          <span class="why__tile-index">06</span>
        </div>
        <div class="why__tile-bottom">
          <h3 class="why__tile-title">{{ $whyMaverick->tile6_title }}</h3>
          <p class="why__tile-description">{{ $whyMaverick->tile6_desc }}</p>
          <div class="why__tile-line"></div>
        </div>
      </div>
    </div>
  </div>
</section>
