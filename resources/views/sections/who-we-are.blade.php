<section id="who-we-are" class="wwa section--light section-wrapper" aria-label="Who we are">
  <div class="container">
    <div class="wwa__grid grid-2">
      <!-- Left Column: Content -->
      <div class="wwa__content">
        <div class="section-label">
          <span>Who We Are</span>
        </div>

        <h2 class="wwa__heading section-title">
          <span class="wwa__heading-line">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">{{ $whoWeAre->heading_line1 }}</span>
            </span>
          </span>
          <span class="wwa__heading-line hwdi__heading-line--red">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">{{ $whoWeAre->heading_line2 }}</span>
            </span>
          </span>
        </h2>

        <p class="wwa__body body-text fade-up">
          {{ $whoWeAre->body_text }}
        </p>

        <div class="wwa__stats fade-up">
          <div class="wwa__stat">
            <span class="wwa__stat-value">{{ $whoWeAre->stat1_value }}</span>
            <span class="wwa__stat-suffix accent-text">{{ $whoWeAre->stat1_suffix }}</span>
            <span class="wwa__stat-divider"></span>
            <span class="wwa__stat-label">{{ $whoWeAre->stat1_label }}</span>
          </div>
          <div class="wwa__stat">
            <span class="wwa__stat-value">{{ $whoWeAre->stat2_value }}</span>
            <span class="wwa__stat-suffix accent-text">{{ $whoWeAre->stat2_suffix }}</span>
            <span class="wwa__stat-divider"></span>
            <span class="wwa__stat-label">{{ $whoWeAre->stat2_label }}</span>
          </div>
        </div>

        <a href="{{ $whoWeAre->cta_url }}" class="wwa__cta btn btn--ghost fade-up">
          {{ $whoWeAre->cta_text }}
        </a>
      </div>

      <!-- Right Column: Image -->
      <div class="wwa__image-col">
        <div class="wwa__image-wrapper">
          <img src="{{ $whoWeAre->image_url }}" alt="Maverick Business Academy Team"
            class="wwa__image" />
          <div class="wwa__image-accent" aria-hidden="true"></div>
        </div>
      </div>
    </div>
  </div>
</section>
