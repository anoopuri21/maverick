@php
    $alumniLogos = collect($alumniLogos ?? []);
    $homepageChrome = $homepageChrome ?? null;
@endphp
@if($alumniLogos->isNotEmpty())
<section id="alumni-network" class="alumni section-wrapper section--light" aria-label="Alumni Network">
  <div class=" container alumni__inner">
    <div class="network__header">
      <div class="network__header-content">
        <div class="section-label">
          <span>{{ $homepageChrome->alumni_label ?? '' }}</span>
        </div>

        <h2 class="alumni__heading section-title">
          <span class="hwdi__heading-line">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">{{ $homepageChrome->alumni_heading ?? '' }}
              <span class="color-red">{{ $homepageChrome->alumni_heading_accent ?? '' }}</span></span>
            </span>
          </span>
        </h2>
        <p class="alumni__subtitle network__subheading body-text fade-up">
          {{ $homepageChrome->alumni_subtitle ?? '' }}
        </p>
        <p class="network__description body-text fade-up">
          {{ $homepageChrome->alumni_description ?? '' }}
        </p>
      </div>
    </div>

    <!-- Company Logo Slider -->
    <div class="network-slider-wrapper">
      <div class="network-slider-track">
        @foreach($alumniLogos as $logo)
          <div class="network-card" data-name="{{ $logo->name }}">
            <div class="network-logo-wrapper">
              @if($url = media_url($logo->logo_url ?? null))
              <img src="{{ $url }}" alt="{{ $logo->name }}" loading="lazy" decoding="async" />
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Trust Statement -->
    <div class="network__trust fade-up">
      <p class="network__trust-text">
        {{ $homepageChrome->alumni_trust ?? '' }}
      </p>
    </div>
  </div>
</section>
@endif
