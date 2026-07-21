<section id="alumni-network" class="alumni section-wrapper section--light" aria-label="Alumni Network">
  <div class=" container alumni__inner">
    <div class="network__header">
      <div class="network__header-content">
        <div class="section-label">
          <span>Our Network</span>
        </div>

        <h2 class="alumni__heading section-title">
          <span class="hwdi__heading-line">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">Our Alumni</span>
            </span>
          </span>
          <span class="hwdi__heading-line hwdi__heading-line--red">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">Work With</span>
            </span>
          </span>
        </h2>
        <p class="alumni__subtitle network__subheading body-text fade-up">
          Join professionals across leading organizations worldwide
        </p>
        <p class="network__description body-text fade-up">
          Graduates placing at world-class organisations across aviation,
          energy, finance and government
        </p>
      </div>
    </div>

    <!-- Company Logo Slider -->
    <div class="network-slider-wrapper">
      <div class="network-slider-track">
        @forelse($alumniLogos as $logo)
          <div class="network-card" data-name="{{ $logo->name }}">
            <div class="network-logo-wrapper">
              <img src="{{ $logo->logo_url }}" alt="{{ $logo->name }}" />
            </div>
          </div>
        @empty
          {{-- Fallback static cards if no data --}}
          <div class="network-card" data-name="Goldman Sachs">
            <div class="network-logo-wrapper">
              <img src="{{ asset('assets/images/alumni/alumn-7.png') }}" alt="Goldman Sachs" />
            </div>
          </div>
        @endforelse
      </div>
    </div>

    <!-- Trust Statement -->
    <div class="network__trust fade-up">
      <p class="network__trust-text">
        Join our growing global network of industry leaders
      </p>
    </div>
  </div>
</section>
