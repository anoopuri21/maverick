{{-- §3 Overview — The Learning Blueprint --}}
@php
  $items = collect($overview->items ?? [])
      ->filter(fn ($item) => filled($item['title'] ?? null))
      ->values();
  $plate = mlp_image_url($overview->plate_image ?? null, [
    'w' => 1200,
    'fallback' => 'assets/images/homepage/mba-management.jpg',
  ]);
  $hasCtas = filled($overview->cta_primary_label) || filled($overview->cta_secondary_label);
  $overviewHeading = (string) ($overview->heading ?? '');
  $overviewHeadingAccent = 'Designed for Working Professionals';
  $hasOverviewHeadingAccent = str_contains($overviewHeading, $overviewHeadingAccent);
@endphp

@if(filled($overview->heading) || $items->isNotEmpty() || $hasCtas)
<section class="blueprint-overview" id="mlp-overview" aria-labelledby="blueprint-overview-title">
  <div class="blueprint-overview__background" aria-hidden="true">
    @if($plate)
    <img class="blueprint-overview__plate" src="{{ $plate }}" alt="" width="1200" height="800" loading="lazy" decoding="async">
    @endif
    <span class="blueprint-overview__wash"></span>
    <span class="blueprint-overview__contour blueprint-overview__contour--one"></span>
    <span class="blueprint-overview__contour blueprint-overview__contour--two"></span>
  </div>

  <div class="blueprint-overview__frame container">
    <header class="blueprint-overview__intro">
      @if(filled($overview->label))
      <p class="blueprint-overview__folio">{{ $overview->label }}</p>
      @endif
      @if(filled($overview->heading))
      <h2 class="blueprint-overview__heading" id="blueprint-overview-title">
        @if($hasOverviewHeadingAccent)
        @php [$overviewHeadingLead, $overviewHeadingTail] = explode($overviewHeadingAccent, $overviewHeading, 2); @endphp
        {{ $overviewHeadingLead }}<span class="blueprint-overview__heading-accent">{{ $overviewHeadingAccent }}</span>{{ $overviewHeadingTail }}
        @else
        {{ $overviewHeading }}
        @endif
      </h2>
      @endif
      @if(filled($overview->intro))
      <p class="blueprint-overview__intro-copy">{{ $overview->intro }}</p>
      @endif
    </header>

    <div class="blueprint-overview__system" data-overview-blueprint>
      <svg class="blueprint-overview__diagram" viewBox="0 0 1200 620" preserveAspectRatio="none" aria-hidden="true">
        <g class="blueprint-overview__grid-lines">
          <path d="M0 80H1200M0 180H1200M0 280H1200M0 380H1200M0 480H1200M0 580H1200" />
          <path d="M100 0V620M300 0V620M500 0V620M700 0V620M900 0V620M1100 0V620" />
        </g>
        <g class="blueprint-overview__connectors">
          <path d="M600 310 C475 240 360 160 130 108" />
          <path d="M600 310 C725 240 840 160 1070 108" />
          <path d="M600 310 C470 380 350 470 130 520" />
          <path d="M600 310 C730 380 850 470 1070 520" />
          <path d="M600 310 C600 390 600 492 600 578" />
        </g>
        <circle class="blueprint-overview__diagram-core" cx="600" cy="310" r="78" />
        <circle class="blueprint-overview__diagram-core-dot" cx="600" cy="310" r="6" />
        <g class="blueprint-overview__diagram-nodes">
          <circle cx="130" cy="108" r="7" />
          <circle cx="1070" cy="108" r="7" />
          <circle cx="130" cy="520" r="7" />
          <circle cx="1070" cy="520" r="7" />
          <circle cx="600" cy="578" r="7" />
        </g>
      </svg>

      <div class="blueprint-overview__core" aria-hidden="true">
        <span class="blueprint-overview__core-kicker">The learner</span>
        <strong>Working<br>professional</strong>
      </div>

      @if($items->isNotEmpty())
      <ol class="blueprint-overview__foundations" aria-label="Programme foundations">
        @foreach($items as $i => $item)
        <li class="blueprint-overview__foundation" data-overview-foundation style="--blueprint-index: {{ $i }}">
          <span class="blueprint-overview__foundation-node" aria-hidden="true"></span>
          <div class="blueprint-overview__foundation-copy">
            <h3 class="blueprint-overview__foundation-title">{{ $item['title'] }}</h3>
            @if(filled($item['text'] ?? null))
            <div class="blueprint-overview__foundation-text">{!! \App\Support\MlpProse::html($item['text']) !!}</div>
            @endif
          </div>
        </li>
        @endforeach
      </ol>
      @endif
    </div>

    @if($hasCtas)
    <div class="blueprint-overview__actions">
      @if(filled($overview->cta_primary_label))
      <a href="{{ edu_href($overview->cta_primary_url) ?? '#mlp-enquire' }}" class="blueprint-overview__primary">{{ $overview->cta_primary_label }} <span aria-hidden="true">↗</span></a>
      @endif
      @if(filled($overview->cta_secondary_label))
      <a href="{{ edu_href($overview->cta_secondary_url) ?? '#mlp-enquire' }}" class="blueprint-overview__secondary">{{ $overview->cta_secondary_label }}</a>
      @endif
    </div>
    @endif

    <div class="blueprint-overview__class-2025" id="mlp-overview-class-2025" role="group" aria-labelledby="mlp-overview-class-2025-title">
      <h3 class="blueprint-overview__class-2025-heading" id="mlp-overview-class-2025-title">
        <span>MBA Class of</span> <strong>2025</strong>
      </h3>

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

        <div class="blueprint-overview__class-2025-center" role="img" aria-label="MBA - 2025">
          
          <strong>MBA - 2025</strong>
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
              <span class="blueprint-overview__class-2025-label">MBA Students</span>
              <strong class="blueprint-overview__class-2025-value">979</strong>
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
              <span class="blueprint-overview__class-2025-label">Countries Represented</span>
              <strong class="blueprint-overview__class-2025-value">77</strong>
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
              <span class="blueprint-overview__class-2025-label">Pass Rate</span>
              <strong class="blueprint-overview__class-2025-value">98.70%</strong>
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
              <span class="blueprint-overview__class-2025-label">Average Age</span>
              <strong class="blueprint-overview__class-2025-value">33.7</strong>
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
              <span class="blueprint-overview__class-2025-label">Average Years of Professional Experience</span>
              <strong class="blueprint-overview__class-2025-value">11.2</strong>
            </div>
          </li>
        </ol>
      </div>
    </div>
  </div>
</section>
@endif
