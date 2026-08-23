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
          {{-- #region agent log --}}
          @php
            if (!isset($GLOBALS['__dbg_alumni_logged'])) {
              $GLOBALS['__dbg_alumni_logged'] = true;
              file_put_contents(base_path('debug-8d936b.log'), json_encode(['sessionId' => '8d936b', 'runId' => 'post-fix', 'hypothesisId' => 'E', 'location' => 'alumni-network.blade.php:35', 'message' => 'Blade $logo item shape before name access', 'data' => ['logoType' => get_debug_type($logo), 'isArray' => is_array($logo), 'isObject' => is_object($logo), 'class' => is_object($logo) ? get_class($logo) : null, 'name' => is_object($logo) ? ($logo->name ?? null) : (is_array($logo) ? ($logo['name'] ?? null) : null)], 'timestamp' => (int) (microtime(true) * 1000)]).PHP_EOL, FILE_APPEND);
            }
          @endphp
          {{-- #endregion --}}
          <div class="network-card" data-name="{{ $logo->name }}">
            <div class="network-logo-wrapper">
              @if($url = media_url($logo->logo_url ?? null))
              <img src="{{ $url }}" alt="{{ $logo->name }}" loading="lazy" decoding="async" />
              @endif
            </div>
          </div>
        @empty
          {{-- Fallback static cards if no data --}}
          <div class="network-card" data-name="Goldman Sachs">
            <div class="network-logo-wrapper">
              <img src="{{ cached_asset('assets/images/alumni/alumn-7.png') }}" alt="Goldman Sachs" loading="lazy" decoding="async" />
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
