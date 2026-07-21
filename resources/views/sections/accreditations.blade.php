<section id="accreditations" class="accreditations section-wrapper section--light"
  aria-label="Accreditations, Partnerships & Recognitions">
  <div class="accred-container">
    <div class="accreditations__header">
      <div class="accreditations__header-content">
        <div class="section-label">
          <span>Trust & Excellence</span>
        </div>
        <h2 class="programs__heading section-title">
          <span class="hwdi__heading-line">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">Accreditations, Partnerships & </span>
            </span>
          </span>
          <span class="hwdi__heading-line hwdi__heading-line--red">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">Recognitions</span>
            </span>
          </span>
        </h2>
        <p class="accreditations__subheading body-text fade-up">
          Globally recognized and strategically partnered with leading institutions worldwide
        </p>
      </div>

      <!-- Badge Cards -->
      <div class="accreditations__badges fade-up">
        <div class="accred-card accred-card--badge">
          <span class="accred-card__icon">🏆</span>
          <span class="accred-card__text">Accredited</span>
        </div>
        <div class="accred-card accred-card--badge">
          <span class="accred-card__icon">🤝</span>
          <span class="accred-card__text">Partnerships</span>
        </div>
        <div class="accred-card accred-card--badge">
          <span class="accred-card__icon">⭐</span>
          <span class="accred-card__text">Recognized</span>
        </div>
      </div>
    </div>

    <!-- Logo Slider -->
    <div class="accred-slider-wrapper">
      <div class="accred-slider-track">
        @forelse($accreditationLogos as $logo)
          <div class="accred-card" data-name="{{ $logo->name }}">
            <div class="accred-card__logo-wrapper">
              <img src="{{ $logo->logo_url }}" alt="{{ $logo->name }}" />
            </div>
          </div>
        @empty
          <div class="accred-card" data-name="Accreditation">
            <div class="accred-card__logo-wrapper">
              <img src="{{ asset('assets/images/alumni/alumn-1.png') }}" alt="Accreditation" />
            </div>
          </div>
        @endforelse
      </div>
    </div>

    <!-- Trust Statement -->
    <div class="accreditations__trust fade-up">
      <p class="accreditations__trust-text">
        Trusted by global organizations and recognized by leading industry bodies
      </p>
    </div>
  </div>
</section>
