{{-- §9 Class profile — The Cohort Portrait --}}
@php
  $metrics = collect($class->metrics ?? [])
      ->filter(fn ($metric) => filled($metric['value'] ?? null) || filled($metric['label'] ?? null))
      ->values();
  $regions = collect($class->regions ?? [])
      ->filter(fn ($region) => filled($region['name'] ?? null))
      ->values();
  $industries = collect($class->industries ?? [])
      ->filter(fn ($industry) => filled($industry['name'] ?? null))
      ->values();
  $fallbackIndustry = 'assets/images/homepage/business.jpg';
@endphp

@if(filled($class->heading) || $metrics->isNotEmpty() || $regions->isNotEmpty() || $industries->isNotEmpty())
<section class="mlp-class cohort-portrait" id="mlp-class" aria-labelledby="cohort-portrait-title">
  <div class="cohort-portrait__background" aria-hidden="true">
    @foreach($industries->take(3) as $ii => $industry)
    <img
      class="cohort-portrait__image cohort-portrait__image--{{ $ii + 1 }}"
      src="{{ media_url($industry['image'] ?? null, $fallbackIndustry) }}"
      alt=""
      width="720"
      height="520"
      loading="lazy"
      decoding="async"
    >
    @endforeach
    <span class="cohort-portrait__wash"></span>
    <span class="cohort-portrait__contour cohort-portrait__contour--one"></span>
    <span class="cohort-portrait__contour cohort-portrait__contour--two"></span>
  </div>

  <div class="cohort-portrait__frame container">
    <header class="cohort-portrait__intro">
      <div>
        <p class="cohort-portrait__folio">
          @if(filled($class->index))<span>{{ $class->index }}</span>@endif
          @if(filled($class->label))<span>{{ $class->label }}</span>@endif
        </p>
        @if(filled($class->heading))
        <h2 class="cohort-portrait__heading" id="cohort-portrait-title">{{ $class->heading }}</h2>
        @endif
      </div>
      <div class="cohort-portrait__intro-copy">
        @if(filled($class->intro))
        <p>{{ $class->intro }}</p>
        @endif
        @if(filled($class->audience))
        <p class="cohort-portrait__audience">{{ $class->audience }}</p>
        @endif
      </div>
    </header>

    <div class="cohort-portrait__board" data-cohort-portrait>
      <div class="cohort-portrait__board-topline">
        <span>Cohort portrait / current record</span>
        <span>Read the room</span>
      </div>

      <div class="cohort-portrait__canvas">
        <div class="cohort-portrait__canvas-image" aria-hidden="true">
          <span class="cohort-portrait__word">COHORT</span>
          <span class="cohort-portrait__canvas-line"></span>
          <span class="cohort-portrait__canvas-note">A room shaped by work, ambition and different points of view.</span>
        </div>

        @if($metrics->isNotEmpty())
        <dl class="cohort-portrait__metrics" aria-label="Cohort metrics">
          @foreach($metrics as $metric)
          <div class="cohort-portrait__metric" data-cohort-element>
            <dt>{{ $metric['label'] ?? 'Profile' }}</dt>
            <dd>{{ $metric['value'] ?? '—' }}</dd>
          </div>
          @endforeach
        </dl>
        @endif
      </div>

      @if($regions->isNotEmpty())
      <div class="cohort-portrait__regions" data-cohort-element>
        <div class="cohort-portrait__section-label">
          <span>01</span>
          <span>Where the room begins</span>
        </div>
        <ul class="cohort-portrait__region-list" aria-label="Cohort regions">
          @foreach($regions as $region)
          <li class="cohort-portrait__region">
            <span class="cohort-portrait__region-name">{{ $region['name'] }}</span>
            @if(filled($region['note'] ?? null))
            <span class="cohort-portrait__region-note">{{ $region['note'] }}</span>
            @endif
          </li>
          @endforeach
        </ul>
      </div>
      @endif

      @if($industries->isNotEmpty())
      <div class="cohort-portrait__industries" data-cohort-element>
        <div class="cohort-portrait__section-label">
          <span>02</span>
          <span>What the room brings</span>
        </div>
        <ol class="cohort-portrait__industry-list" aria-label="Professional backgrounds">
          @foreach($industries as $ii => $industry)
          @php
            $share = max(0, min(100, (float) preg_replace('/[^0-9.]/', '', (string) ($industry['share'] ?? '0'))));
            $shareText = rtrim(rtrim(number_format($share, 1, '.', ''), '0'), '.');
          @endphp
          <li class="cohort-portrait__industry" style="--cohort-share: {{ $share }}%;" data-cohort-industry>
            <span class="cohort-portrait__industry-index" aria-hidden="true">{{ str_pad((string) ($ii + 1), 2, '0', STR_PAD_LEFT) }}</span>
            <span class="cohort-portrait__industry-name">{{ $industry['name'] }}</span>
            <span class="cohort-portrait__industry-share">{{ $shareText }}%</span>
            <span class="cohort-portrait__industry-track" aria-hidden="true"><span></span></span>
          </li>
          @endforeach
        </ol>
      </div>
      @endif
    </div>
  </div>
</section>
@endif
