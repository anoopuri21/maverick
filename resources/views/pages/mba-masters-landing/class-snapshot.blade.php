@php
  $snapshotMetrics = collect($class->metrics ?? [])
      ->filter(fn ($metric) => filled($metric['value'] ?? null) || filled($metric['label'] ?? null))
      ->values();
  $snapshotRegions = collect($class->regions ?? [])
      ->filter(fn ($region) => filled($region['name'] ?? null))
      ->values();
  $countryCatalog = collect(\App\Support\IsoCountries::all())
      ->mapWithKeys(fn (array $country) => [mb_strtolower($country['name']) => $country]);
  $countryAliases = [
    'uae' => 'AE',
    'u.a.e.' => 'AE',
    'usa' => 'US',
    'u.s.a.' => 'US',
    'uk' => 'GB',
    'u.k.' => 'GB',
  ];
  $metricIcons = ['users-round', 'briefcase', 'calendar', 'users-round'];
@endphp

@if($snapshotMetrics->isNotEmpty() || $snapshotRegions->isNotEmpty())
<section class="mlp-class-snapshot" id="mlp-class-snapshot" aria-labelledby="mlp-class-snapshot-title">
  <div class="mlp-class-snapshot__frame container">
    <header class="mlp-class-snapshot__intro">
      @if(filled($class->label ?? null))
      <p class="mlp-class-snapshot__label mlp-meta">{{ $class->label }}</p>
      @endif
      @if(filled($class->heading ?? null))
      <h2 class="mlp-class-snapshot__heading" id="mlp-class-snapshot-title">{{ $class->heading }}</h2>
      @endif
      @if(filled($class->intro ?? null))
      <p class="mlp-class-snapshot__intro-copy">{{ $class->intro }}</p>
      @endif
    </header>

    <div class="mlp-class-snapshot__grid">
      @if($snapshotMetrics->isNotEmpty())
      <section class="mlp-class-snapshot__overview" aria-labelledby="mlp-class-snapshot-overview-title">
        <h3 id="mlp-class-snapshot-overview-title">Overview</h3>
        <dl class="mlp-class-snapshot__metrics">
          @foreach($snapshotMetrics as $index => $metric)
          <div class="mlp-class-snapshot__metric">
            <dt>
              <span class="mlp-class-snapshot__metric-icon" aria-hidden="true">
                <i data-lucide="{{ $metricIcons[$index] ?? 'circle-dot' }}"></i>
              </span>
              <span class="mlp-class-snapshot__metric-label">{{ $metric['label'] ?? 'Class metric' }}</span>
            </dt>
            <dd>{{ $metric['value'] ?? '—' }}</dd>
          </div>
          @endforeach
        </dl>
      </section>
      @endif

      @if($snapshotRegions->isNotEmpty())
      <section class="mlp-class-snapshot__global" aria-labelledby="mlp-class-snapshot-global-title">
        <h3 id="mlp-class-snapshot-global-title">Countries our students represent</h3>
        <ul class="mlp-class-snapshot__countries">
          @foreach($snapshotRegions as $region)
          @php
            $countryName = trim((string) ($region['name'] ?? ''));
            $countryKey = mb_strtolower($countryName);
            $countryRecord = $countryCatalog->get($countryKey);
            $iso2 = $countryAliases[$countryKey] ?? ($countryRecord['iso2'] ?? null);
          @endphp
          <li class="mlp-class-snapshot__country">
            @if($iso2)
            <img src="https://flagcdn.com/w40/{{ strtolower($iso2) }}.png" alt="Flag of {{ $countryName }}" width="24" height="18" loading="lazy" decoding="async">
            @else
            <span class="mlp-class-snapshot__country-flag-fallback" aria-hidden="true"></span>
            @endif
            <span>{{ $countryName }}</span>
          </li>
          @endforeach
        </ul>
        <div class="mlp-class-snapshot__map" aria-hidden="true">
          <svg viewBox="0 0 800 280" preserveAspectRatio="none" role="presentation">
            <g class="mlp-class-snapshot__map-grid">
              <path d="M0 70H800M0 140H800M0 210H800" />
              <path d="M100 0V280M300 0V280M500 0V280M700 0V280" />
            </g>
            <g class="mlp-class-snapshot__map-continents">
              <path d="M82 56 116 35l51 10 24 30-22 22-27-5-21 22-31-17-26-28Z" />
              <path d="m215 140 27 18 11 37-18 45-18-22-7-40-16-19 21-19Z" />
              <path d="m361 76 34-19 36 9 21-11 46 17 35-1 40 22-12 20-44 1-27 20-40-9-24 23-34-20-23-29 32-23Z" />
              <path d="m432 130 35 10 23 35-13 43-25 28-18-38-19-29 17-49Z" />
              <path d="m627 199 30-14 39 12 25 25-24 19-46-3-27-18 3-21Z" />
              <path d="m726 87 18 7 5 15-16 6-14-12 7-16Z" />
            </g>
            <g class="mlp-class-snapshot__map-points">
              <circle cx="152" cy="79" r="3" />
              <circle cx="420" cy="92" r="3" />
              <circle cx="464" cy="171" r="3" />
              <circle cx="655" cy="213" r="3" />
              <circle cx="739" cy="102" r="3" />
            </g>
          </svg>
        </div>
      </section>
      @endif
    </div>
  </div>
</section>
@endif
