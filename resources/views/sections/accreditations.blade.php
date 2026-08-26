@php $accreditationLogos = collect($accreditationLogos ?? []); @endphp
@if($accreditationLogos->isNotEmpty())
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
              <span class="text-reveal-inner">Accreditations & </span>
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

    </div>

    <!-- Logo Slider -->
    <div class="accred-slider-wrapper">
      <div class="accred-slider-track">
        @foreach($accreditationLogos as $logo)
          <div class="accred-card" data-name="{{ $logo->name }}">
            <div class="accred-card__logo-wrapper">
              @if($url = media_url($logo->logo_url ?? null))
              <img src="{{ $url }}" alt="{{ $logo->name }}" loading="lazy" decoding="async" />
              @endif
            </div>
          </div>
        @endforeach
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
@endif
