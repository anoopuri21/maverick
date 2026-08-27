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
  $metricIcons = ['users-round', 'briefcase-business', 'calendar-days', 'users-round'];
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
              <span>{{ $metric['label'] ?? 'Class metric' }}</span>
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
        <div class="mlp-class-snapshot__map" aria-hidden="true"></div>
      </section>
      @endif
    </div>
  </div>
</section>
@endif
