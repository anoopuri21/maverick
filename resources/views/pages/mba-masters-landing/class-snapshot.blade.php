@php
  $snapshotMetrics = collect($class->metrics ?? [])
      ->filter(fn ($metric) => filled($metric['value'] ?? null) || filled($metric['label'] ?? null))
      ->take(5)
      ->values();
  $regionIso = [
      'uae' => 'AE',
      'saudi arabia' => 'SA',
      'oman' => 'OM',
      'qatar' => 'QA',
  ];
  $snapshotRegions = collect($class->regions ?? [])
      ->filter(fn ($region) => filled($region['name'] ?? null))
      ->map(function ($region) use ($regionIso) {
          $name = trim((string) $region['name']);
          $iso = $regionIso[mb_strtolower($name)] ?? null;

          return [
              'name' => $name,
              'note' => $region['note'] ?? null,
              'iso2' => $iso,
          ];
      })
      ->values();
  $snapshotCountries = [
      ['name' => 'Moldova', 'iso2' => 'MD'],
      ['name' => 'Hungary', 'iso2' => 'HU'],
      ['name' => 'Malaysia', 'iso2' => 'MY'],
      ['name' => 'China', 'iso2' => 'CN'],
      ['name' => 'Maldives', 'iso2' => 'MV'],
      ['name' => 'Hong Kong', 'iso2' => 'HK'],
      ['name' => 'Myanmar', 'iso2' => 'MM'],
      ['name' => 'Australia', 'iso2' => 'AU'],
      ['name' => 'Ghana', 'iso2' => 'GH'],
      ['name' => 'Nigeria', 'iso2' => 'NG'],
      ['name' => 'Egypt', 'iso2' => 'EG'],
      ['name' => 'Syria', 'iso2' => 'SY'],
      ['name' => 'Yemen', 'iso2' => 'YE'],
      ['name' => 'Romania', 'iso2' => 'RO'],
      ['name' => 'Congo', 'iso2' => 'CG'],
      ['name' => 'Saudi Arabia', 'iso2' => 'SA'],
      ['name' => 'UAE', 'iso2' => 'AE'],
      ['name' => 'India', 'iso2' => 'IN'],
      ['name' => 'Sri Lanka', 'iso2' => 'LK'],
      ['name' => 'UK', 'iso2' => 'GB'],
      ['name' => 'USA', 'iso2' => 'US'],
      ['name' => 'Switzerland', 'iso2' => 'CH'],
      ['name' => 'Chile', 'iso2' => 'CL'],
      ['name' => 'Peru', 'iso2' => 'PE'],
      ['name' => 'Uganda', 'iso2' => 'UG'],
      ['name' => 'Zimbabwe', 'iso2' => 'ZW'],
      ['name' => 'Vietnam', 'iso2' => 'VN'],
  ];
  $metricIcons = ['users-round', 'briefcase', 'calendar', 'users-round', 'handshake'];
@endphp

@if($snapshotMetrics->isNotEmpty() || $snapshotRegions->isNotEmpty() || filled($class->heading ?? null))
<section class="mlp-class-snapshot" id="mlp-class-snapshot" aria-labelledby="mlp-class-snapshot-title">
  <div class="mlp-class-snapshot__frame container">
    <header class="mlp-class-snapshot__intro">
      @if(filled($class->label ?? null))
      <p class="mlp-class-snapshot__label mlp-meta mlp-eyebrow">{{ $class->label }}</p>
      @endif
      @if(filled($class->heading ?? null))
      <h2 class="mlp-class-snapshot__heading mlp-h2" id="mlp-class-snapshot-title">{{ $class->heading }}</h2>
      @endif
      @if(filled($class->intro ?? null))
      <p class="mlp-class-snapshot__intro-copy">{{ $class->intro }}</p>
      @endif
      @if(filled($class->audience ?? null))
      <p class="mlp-class-snapshot__audience">{{ $class->audience }}</p>
      @endif
    </header>

    <div class="mlp-class-snapshot__grid">
      <section class="mlp-class-snapshot__global" aria-labelledby="mlp-class-snapshot-global-title">
        <h3 id="mlp-class-snapshot-global-title">UAE and GCC cohorts</h3>

        <ul class="mlp-class-snapshot__countries" aria-label="Countries our students represent">
          @foreach($snapshotCountries as $country)
          <li class="mlp-class-snapshot__country mlp-hairline">
            <img src="https://flagcdn.com/w40/{{ strtolower($country['iso2']) }}.png" alt="Flag of {{ $country['name'] }}" width="24" height="18" loading="lazy" decoding="async">
            <span>{{ $country['name'] }}</span>
          </li>
          @endforeach
        </ul>

        @if($snapshotMetrics->isNotEmpty())
        <dl class="mlp-class-snapshot__metrics" aria-labelledby="mlp-class-snapshot-overview-title">
          @foreach($snapshotMetrics as $index => $metric)
          <div class="mlp-class-snapshot__metric mlp-hairline">
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
        @endif

        @if($snapshotRegions->isNotEmpty())
        <ul class="mlp-class-snapshot__regions">
          @foreach($snapshotRegions as $region)
          <li class="mlp-class-snapshot__region mlp-hairline">
            @if(filled($region['iso2']))
            <img src="https://flagcdn.com/w40/{{ strtolower($region['iso2']) }}.png" alt="" width="24" height="18" loading="lazy" decoding="async">
            @endif
            <span>
              <strong>{{ $region['name'] }}</strong>
              @if(filled($region['note']))
              <em>{{ $region['note'] }}</em>
              @endif
            </span>
          </li>
          @endforeach
        </ul>
        @endif
      </section>
    </div>
  </div>
</section>
@endif
