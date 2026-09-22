{{-- §3 Overview — The Learning Blueprint (circular orbit) --}}
@php
  $items = collect($overview->items ?? [])
      ->filter(fn ($item) => filled($item['title'] ?? null))
      ->values();
  $itemCount = max(1, $items->count());
  $plate = mlp_image_url(settings_media_url($overview, 'plate_image'), [
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
    <span class="blueprint-overview__wash mlp-wash"></span>
    <span class="blueprint-overview__contour blueprint-overview__contour--one mlp-contour"></span>
    <span class="blueprint-overview__contour blueprint-overview__contour--two mlp-contour"></span>
  </div>

  <div class="blueprint-overview__frame container" data-overview-frame>
    <header class="blueprint-overview__intro mlp-intro-grid">
      @if(filled($overview->label))
      <p class="blueprint-overview__folio">{{ $overview->label }}</p>
      @endif
      @if(filled($overview->heading))
      <h2 class="blueprint-overview__heading mlp-h2" id="blueprint-overview-title">
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
      <p class="blueprint-overview__intro-copy">Read our <a href="{{ $overview->guide_url ?: 'https://mbalondon.org.uk/mba-in-uae-complete-guide-for-working-professionals-in-2026/' }}">{{ $overview->guide_label ?: 'MBA in UAE guide' }}</a>.</p>
    </header>

    <div class="blueprint-overview__system" data-overview-blueprint style="--blueprint-count: {{ $itemCount }}">
      <svg class="blueprint-overview__diagram blueprint-overview__diagram--grid" viewBox="0 0 1000 1000" preserveAspectRatio="xMidYMid meet" aria-hidden="true">
        <g class="blueprint-overview__grid-lines">
          <path d="M0 100H1000M0 200H1000M0 300H1000M0 400H1000M0 500H1000M0 600H1000M0 700H1000M0 800H1000M0 900H1000" />
          <path d="M100 0V1000M200 0V1000M300 0V1000M400 0V1000M500 0V1000M600 0V1000M700 0V1000M800 0V1000M900 0V1000" />
        </g>
      </svg>
      <svg class="blueprint-overview__diagram blueprint-overview__diagram--spokes" viewBox="0 0 1000 1000" preserveAspectRatio="none" aria-hidden="true">
        <circle class="blueprint-overview__orbit-ring" cx="500" cy="500" r="0" fill="none" data-overview-orbit-ring />
        <g class="blueprint-overview__connectors" data-overview-connectors></g>
      </svg>

      @php
        $coreKicker = filled($overview->core_kicker ?? null) ? $overview->core_kicker : 'Learners';
        $coreText = filled($overview->core_text ?? null) ? $overview->core_text : "and\nprofessionals";
        $coreLines = array_values(array_filter(
            preg_split('/\r\n|\r|\n/', $coreText) ?: [],
            fn ($line) => trim((string) $line) !== ''
        ));
        if ($coreLines === []) {
            $coreLines = ['and', 'professionals'];
        }
      @endphp
      <div class="blueprint-overview__core" data-overview-core aria-hidden="true">
        <span class="blueprint-overview__core-kicker">{{ $coreKicker }}</span>
        <strong>
          @foreach($coreLines as $coreIndex => $coreLine)
            @if($coreIndex > 0)<br>@endif{{ $coreLine }}
          @endforeach
        </strong>
      </div>

      @if($items->isNotEmpty())
      <ol class="blueprint-overview__foundations" aria-label="Program foundations">
        @foreach($items as $i => $item)
        @php
          $angleDeg = $i * (360 / $itemCount) - 90;
          $normalized = fmod($angleDeg + 360, 360);
          if ($i === 0) {
              $side = 'north';
          } elseif ($normalized > 90 && $normalized < 270) {
              $side = 'west';
          } else {
              $side = 'east';
          }
        @endphp
        <li
          class="blueprint-overview__foundation"
          data-overview-foundation
          data-side="{{ $side }}"
          style="--blueprint-index: {{ $i }}; --blueprint-angle: {{ $angleDeg }}deg"
        >
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
      <a href="{{ edu_href($overview->cta_primary_url) ?? '#mlp-enquire' }}" class="blueprint-overview__primary mlp-cta mlp-cta--primary">{{ $overview->cta_primary_label }} <span aria-hidden="true">↗</span></a>
      @endif
      @if(filled($overview->cta_secondary_label))
      <a href="{{ edu_href($overview->cta_secondary_url) ?? '#mlp-enquire' }}" class="blueprint-overview__secondary mlp-cta mlp-cta--ghost">{{ $overview->cta_secondary_label }}</a>
      @endif
    </div>
    @endif

  </div>
</section>
@endif
