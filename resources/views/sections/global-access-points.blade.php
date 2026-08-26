@php
  $globalAccessPointsCountries = [
    ['id' => '498', 'code' => 'MD', 'name' => 'Moldova'],
    ['id' => '348', 'code' => 'HU', 'name' => 'Hungary'],
    ['id' => '458', 'code' => 'MY', 'name' => 'Malaysia'],
    ['id' => '156', 'code' => 'CN', 'name' => 'China'],
    ['id' => '462', 'code' => 'MV', 'name' => 'Maldives'],
    ['id' => '344', 'code' => 'HK', 'name' => 'Hong Kong'],
    ['id' => '104', 'code' => 'MM', 'name' => 'Myanmar'],
    ['id' => '036', 'code' => 'AU', 'name' => 'Australia'],
    ['id' => '288', 'code' => 'GH', 'name' => 'Ghana'],
    ['id' => '566', 'code' => 'NG', 'name' => 'Nigeria'],
    ['id' => '818', 'code' => 'EG', 'name' => 'Egypt'],
    ['id' => '760', 'code' => 'SY', 'name' => 'Syria'],
    ['id' => '887', 'code' => 'YE', 'name' => 'Yemen'],
    ['id' => '642', 'code' => 'RO', 'name' => 'Romania'],
    ['id' => '178', 'code' => 'CG', 'name' => 'Congo'],
    ['id' => '682', 'code' => 'SA', 'name' => 'Saudi Arabia'],
    ['id' => '784', 'code' => 'AE', 'name' => 'UAE'],
    ['id' => '356', 'code' => 'IN', 'name' => 'India'],
    ['id' => '144', 'code' => 'LK', 'name' => 'Sri Lanka'],
    ['id' => '826', 'code' => 'GB', 'name' => 'UK'],
    ['id' => '840', 'code' => 'US', 'name' => 'USA'],
    ['id' => '756', 'code' => 'CH', 'name' => 'Switzerland'],
    ['id' => '152', 'code' => 'CL', 'name' => 'Chile'],
    ['id' => '604', 'code' => 'PE', 'name' => 'Peru'],
    ['id' => '800', 'code' => 'UG', 'name' => 'Uganda'],
    ['id' => '716', 'code' => 'ZW', 'name' => 'Zimbabwe'],
    ['id' => '704', 'code' => 'VN', 'name' => 'Vietnam'],
  ];
@endphp

<section id="global-access-points" class="gap section-wrapper section--light" aria-label="Our Global Maverick Access Points">
  <div class="container gap__inner">
    <div class="gap__header">
      <div class="section-label">
        <span>Global Reach</span>
      </div>
      <h2 class="gap__heading section-title">
        <span class="hwdi__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">Our Global</span>
          </span>
        </span>
        <span class="hwdi__heading-line hwdi__heading-line--red">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">Maverick Access Points</span>
          </span>
        </span>
      </h2>
    </div>

    <div class="gap-globe-stage" data-gap-globe data-lenis-prevent>
      <aside class="gap-globe__country-panel" aria-label="Maverick Access Point countries">
        <ol class="gap-globe__country-list">
          @foreach($globalAccessPointsCountries as $country)
          @php $countryCode = strtolower(trim((string) ($country['code'] ?? ''))); @endphp
          <li>
            <button type="button" data-gap-country="{{ $country['id'] }}" aria-pressed="false">
              <img class="gap-globe__country-flag" src="https://flagcdn.com/w20/{{ $countryCode }}.png" alt="" width="20" height="15" loading="lazy" decoding="async">
              <strong>{{ $country['name'] }}</strong>
            </button>
          </li>
          @endforeach
        </ol>
      </aside>

      <div class="gap-globe__story">
        <p class="gap-globe__story-label">A world in motion</p>
        <h3>Learning that travels with you.</h3>
        <p>From the Gulf to the wider world, the Access Points network keeps the learning conversation open across borders.</p>
        <p>Select a country to bring its point into focus, then drag the globe to explore the wider constellation.</p>
      </div>

      <div class="gap-globe__visual">
        <div class="gap-globe__atmosphere" aria-hidden="true"></div>
        <div class="gap-globe__halo" aria-hidden="true"></div>

        <canvas
          id="gap-globe"
          class="gap-globe__canvas"
          role="img"
          aria-label="Interactive globe showing Maverick Access Points in 27 selected countries"
          tabindex="0"
        ></canvas>

        <p class="gap-globe__hint" data-gap-globe-status aria-live="polite">Grab the globe to explore</p>

        <ul class="gap-globe__fallback-list" data-gap-globe-fallback aria-label="Maverick Access Point countries">
          @foreach($globalAccessPointsCountries as $country)
          <li>{{ $country['name'] }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  <script>
    window.globalAccessPointsCountries = @json($globalAccessPointsCountries);
  </script>
</section>
