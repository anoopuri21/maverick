<section id="how-we-do-it" class="hwdi section--light section-wrapper" aria-label="How we do it">
  <div class="container">
    <div class="hwdi__header">
      <div class="section-label">
        <span>How We Do It</span>
      </div>
      <h2 class="hwdi__heading section-title">
        <span class="hwdi__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">{{ $howWeDoIt->heading_line1 }}</span>
          </span>
        </span>
        <span class="hwdi__heading-line hwdi__heading-line--red">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">{{ $howWeDoIt->heading_line2 }}</span>
          </span>
        </span>
      </h2>
      <p class="hwdi__subtitle body-text fade-up">
        {{ $howWeDoIt->subtitle }}
      </p>
    </div>

    <div class="hwdi__steps">
      <!-- Step 1 -->
      <div class="hwdi__step">
        <div class="hwdi__step-inner">
          <div class="hwdi__step-accent" aria-hidden="true"></div>
          <span class="hwdi__step-number">01</span>
          <h3 class="hwdi__step-title">{{ $howWeDoIt->step1_title }}</h3>
          <span class="hwdi__step-subtitle">{{ $howWeDoIt->step1_subtitle }}</span>
          <p class="hwdi__step-desc">
            {{ $howWeDoIt->step1_desc }}
          </p>
        </div>
      </div>

      <!-- Connector 1-2 -->
      <div class="hwdi__connector hwdi__connector--1" aria-hidden="true">
        <div class="hwdi__connector-line"></div>
      </div>

      <!-- Step 2 -->
      <div class="hwdi__step">
        <div class="hwdi__step-inner">
          <div class="hwdi__step-accent" aria-hidden="true"></div>
          <span class="hwdi__step-number">02</span>
          <h3 class="hwdi__step-title">{{ $howWeDoIt->step2_title }}</h3>
          <span class="hwdi__step-subtitle">{{ $howWeDoIt->step2_subtitle }}</span>
          <p class="hwdi__step-desc">
            {{ $howWeDoIt->step2_desc }}
          </p>
        </div>
      </div>

      <!-- Connector 2-3 -->
      <div class="hwdi__connector hwdi__connector--2" aria-hidden="true">
        <div class="hwdi__connector-line"></div>
      </div>

      <!-- Step 3 -->
      <div class="hwdi__step">
        <div class="hwdi__step-inner">
          <div class="hwdi__step-accent" aria-hidden="true"></div>
          <span class="hwdi__step-number">03</span>
          <h3 class="hwdi__step-title">{{ $howWeDoIt->step3_title }}</h3>
          <span class="hwdi__step-subtitle">{{ $howWeDoIt->step3_subtitle }}</span>
          <p class="hwdi__step-desc">
            {{ $howWeDoIt->step3_desc }}
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
