@php
  $gapSettings = $gapSettings ?? safe_settings(\App\Settings\GlobalAccessPointsSettings::class);
  $globalAccessPointsCountries = collect($globalAccessPointsCountries ?? []);
  $gapCountriesJson = $globalAccessPointsCountries->map(fn ($country) => [
      'id' => $country->iso_numeric ?? ($country['iso_numeric'] ?? $country['id'] ?? ''),
      'code' => $country->iso2 ?? ($country['iso2'] ?? $country['code'] ?? ''),
      'name' => $country->name ?? ($country['name'] ?? ''),
  ])->values()->all();
  $gapCountryCount = count($gapCountriesJson);
  $gapCanvasAria = trim(($gapSettings->canvas_aria ?? 'Interactive globe showing Maverick Access Points').($gapCountryCount > 0 ? " in {$gapCountryCount} selected countries" : ''));
@endphp

<section id="global-access-points" class="gap section-wrapper section--light" aria-label="Our Global Maverick Access Points">
  <div class="container gap__inner">
    <div class="gap__header">
      <div class="section-label">
        <span>{{ $gapSettings->label ?? 'Global Reach' }}</span>
      </div>
      <h2 class="gap__heading section-title">
        <span class="hwdi__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">{{ $gapSettings->heading_line1 ?? 'Our Global' }}</span>
          </span>
        </span>
        <span class="hwdi__heading-line hwdi__heading-line--red">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">{{ $gapSettings->heading_line2 ?? 'Maverick Access Points' }}</span>
          </span>
        </span>
      </h2>
    </div>

    @if($gapCountryCount > 0)
    <div class="gap-globe-stage" data-gap-globe data-lenis-prevent>
      <aside class="gap-globe__country-panel" aria-label="Maverick Access Point countries">
        <ol class="gap-globe__country-list">
          @foreach($globalAccessPointsCountries as $country)
          @php
            $isoNumeric = $country->iso_numeric ?? ($country['iso_numeric'] ?? '');
            $iso2 = strtolower(trim((string) ($country->iso2 ?? ($country['iso2'] ?? ''))));
            $name = $country->name ?? ($country['name'] ?? '');
          @endphp
          <li>
            <button type="button" data-gap-country="{{ $isoNumeric }}" aria-pressed="false">
              <img class="gap-globe__country-flag" src="https://flagcdn.com/w20/{{ $iso2 }}.png" alt="" width="20" height="15" loading="lazy" decoding="async">
              <strong>{{ $name }}</strong>
            </button>
          </li>
          @endforeach
        </ol>
      </aside>

      <div class="gap-globe__story">
        <p class="gap-globe__story-label">{{ $gapSettings->story_label ?? 'A world in motion' }}</p>
        <h3>{{ $gapSettings->story_heading ?? 'Learning that travels with you.' }}</h3>
        @if(html_filled($gapSettings->story_body ?? null))
        <div class="gap-globe__story-body">{!! rich_html($gapSettings->story_body) !!}</div>
        @else
        <p>From the Gulf to the wider world, the Access Points network keeps the learning conversation open across borders.</p>
        <p>Select a country to bring its point into focus, then drag the globe to explore the wider constellation.</p>
        @endif
      </div>

      <div class="gap-globe__visual">
        <div class="gap-globe__atmosphere" aria-hidden="true"></div>
        <div class="gap-globe__halo" aria-hidden="true"></div>

        <canvas
          id="gap-globe"
          class="gap-globe__canvas"
          role="img"
          aria-label="{{ $gapCanvasAria }}"
          tabindex="0"
        ></canvas>

        <p class="gap-globe__hint" data-gap-globe-status aria-live="polite">{{ $gapSettings->hint ?? 'Grab the globe to explore' }}</p>

        <ul class="gap-globe__fallback-list" data-gap-globe-fallback aria-label="Maverick Access Point countries">
          @foreach($globalAccessPointsCountries as $country)
          <li>{{ $country->name ?? ($country['name'] ?? '') }}</li>
          @endforeach
        </ul>
      </div>
    </div>

    <script type="application/json" id="gap-countries-json">{!! json_encode($gapCountriesJson) !!}</script>
    <script>
      window.globalAccessPointsCountries = JSON.parse(document.getElementById('gap-countries-json').textContent);
    </script>
    @endif
  </div>
</section>
