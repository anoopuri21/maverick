{{-- §8 Class of 2025 — cinematic graphic kept; PDF heading/lede --}}
@php
  $classYearDefaults = [
      ['label' => 'MBA Students', 'value' => '979'],
      ['label' => 'Countries Represented', 'value' => '77'],
      ['label' => 'Pass Rate', 'value' => '98.70%'],
      ['label' => 'Average Age', 'value' => '33.7'],
      ['label' => 'Average Years of Professional Experience', 'value' => '11.2'],
  ];
  $classYearStored = collect($class->class_year_stats ?? [])
      ->filter(fn ($row) => filled($row['label'] ?? null) || filled($row['value'] ?? null))
      ->take(5)
      ->values();
  $classYearStats = collect($classYearDefaults)->map(function (array $default, int $index) use ($classYearStored) {
      $row = $classYearStored->get($index);
      if (! is_array($row)) {
          return $default;
      }

      return [
          'label' => filled($row['label'] ?? null) ? $row['label'] : $default['label'],
          'value' => filled($row['value'] ?? null) ? $row['value'] : $default['value'],
      ];
  });
@endphp
<section class="mlp-class-2025" id="mlp-class-2025" aria-labelledby="mlp-overview-class-2025-title">
  <div class="container">
    <div class="blueprint-overview__class-2025" id="mlp-overview-class-2025" role="group" aria-labelledby="mlp-overview-class-2025-title">
      <h3 class="blueprint-overview__class-2025-heading" id="mlp-overview-class-2025-title">
        <span>{{ $class->class_year_lead ?: 'Class of 2025:' }}</span> <strong>{{ $class->class_year_strong ?: 'Built for the GCC Region' }}</strong>
      </h3>
      <p class="blueprint-overview__class-2025-lede">
        {{ $class->class_year_body ?: 'A Maverick cohort of founders, bankers, government specialists, and senior operators who keep working while they study.' }}
      </p>

      <div class="blueprint-overview__class-2025-stage">
        <svg class="blueprint-overview__class-2025-lines" viewBox="0 0 1000 620" preserveAspectRatio="none" aria-hidden="true">
          <path class="blueprint-overview__class-2025-arc" d="M350 230 A150 150 0 0 0 650 230" />
          <circle class="blueprint-overview__class-2025-node" cx="350" cy="230" r="4" />
          <circle class="blueprint-overview__class-2025-node" cx="393.934" cy="336.066" r="4" />
          <circle class="blueprint-overview__class-2025-node" cx="500" cy="380" r="4" />
          <circle class="blueprint-overview__class-2025-node" cx="606.066" cy="336.066" r="4" />
          <circle class="blueprint-overview__class-2025-node" cx="650" cy="230" r="4" />
          <path class="blueprint-overview__class-2025-connector" d="M210 150 H128" />
          <path class="blueprint-overview__class-2025-connector" d="M790 150 H872" />
          <path class="blueprint-overview__class-2025-connector" d="M220 386 L170 420" />
          <path class="blueprint-overview__class-2025-connector" d="M780 386 L830 420" />
          <path class="blueprint-overview__class-2025-connector" d="M500 470 V520" />
        </svg>

        <div class="blueprint-overview__class-2025-center" role="img" aria-label="{{ $class->class_year_center ?: 'MBA - 2025' }}">
          <strong>{{ $class->class_year_center ?: 'MBA - 2025' }}</strong>
        </div>

        <ol class="blueprint-overview__class-2025-stats" aria-label="MBA Class of 2025 statistics">
          <li class="blueprint-overview__class-2025-stat blueprint-overview__class-2025-stat--students">
            <article class="blueprint-overview__class-2025-card" aria-hidden="true">
              <svg class="blueprint-overview__class-2025-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 9 9-4 9 4-9 4-9-4Z" />
                <path d="M6 10.5V14c1.8 1.5 4 2.2 6 2.2s4.2-.7 6-2.2v-3.5M12 13v7" />
                <path d="M9.5 20h5" />
              </svg>
            </article>
            <div class="blueprint-overview__class-2025-stat-copy" id="mlp-overview-stat-students">
              <span class="blueprint-overview__class-2025-label">{{ $classYearStats[0]['label'] }}</span>
              <strong class="blueprint-overview__class-2025-value">{{ $classYearStats[0]['value'] }}</strong>
            </div>
          </li>

          <li class="blueprint-overview__class-2025-stat blueprint-overview__class-2025-stat--countries">
            <article class="blueprint-overview__class-2025-card" aria-hidden="true">
              <svg class="blueprint-overview__class-2025-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z" />
                <circle cx="12" cy="10" r="2.5" />
                <path d="M7 20h10" />
              </svg>
            </article>
            <div class="blueprint-overview__class-2025-stat-copy" id="mlp-overview-stat-countries">
              <span class="blueprint-overview__class-2025-label">{{ $classYearStats[1]['label'] }}</span>
              <strong class="blueprint-overview__class-2025-value">{{ $classYearStats[1]['value'] }}</strong>
            </div>
          </li>

          <li class="blueprint-overview__class-2025-stat blueprint-overview__class-2025-stat--pass-rate">
            <article class="blueprint-overview__class-2025-card" aria-hidden="true">
              <svg class="blueprint-overview__class-2025-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linecap="round" stroke-linejoin="round">
                <rect x="5" y="3" width="14" height="18" rx="2" />
                <path d="M9 7h6M9 11h3M9 15l1.7 1.7L15 12.5" />
              </svg>
            </article>
            <div class="blueprint-overview__class-2025-stat-copy" id="mlp-overview-stat-pass-rate">
              <span class="blueprint-overview__class-2025-label">{{ $classYearStats[2]['label'] }}</span>
              <strong class="blueprint-overview__class-2025-value">{{ $classYearStats[2]['value'] }}</strong>
            </div>
          </li>

          <li class="blueprint-overview__class-2025-stat blueprint-overview__class-2025-stat--age">
            <article class="blueprint-overview__class-2025-card" aria-hidden="true">
              <svg class="blueprint-overview__class-2025-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="8" r="3" />
                <circle cx="17" cy="9" r="2.5" />
                <path d="M3 20c.6-3.3 2.5-5 6-5s5.4 1.7 6 5M15 15c2.8.2 4.3 1.8 5 4" />
              </svg>
            </article>
            <div class="blueprint-overview__class-2025-stat-copy" id="mlp-overview-stat-age">
              <span class="blueprint-overview__class-2025-label">{{ $classYearStats[3]['label'] }}</span>
              <strong class="blueprint-overview__class-2025-value">{{ $classYearStats[3]['value'] }}</strong>
            </div>
          </li>

          <li class="blueprint-overview__class-2025-stat blueprint-overview__class-2025-stat--experience">
            <article class="blueprint-overview__class-2025-card" aria-hidden="true">
              <svg class="blueprint-overview__class-2025-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="7" width="18" height="13" rx="2" />
                <path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18M10 12v2h4v-2" />
              </svg>
            </article>
            <div class="blueprint-overview__class-2025-stat-copy" id="mlp-overview-stat-experience">
              <span class="blueprint-overview__class-2025-label">{{ $classYearStats[4]['label'] }}</span>
              <strong class="blueprint-overview__class-2025-value">{{ $classYearStats[4]['value'] }}</strong>
            </div>
          </li>
        </ol>
      </div>
    </div>
  </div>
</section>
