{{-- §9 Class profile — The Cohort Room / PDF-refined content --}}
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
  $metricIcons = ['users', 'briefcase', 'calendar', 'users-round'];
  $industryIcons = [
    'IT & related fields' => 'cpu',
    'Consumer & Retail' => 'shopping-bag',
    'Engineering & Manufacturing' => 'factory',
    'Financial Services' => 'landmark',
    'Others / Misc.' => 'circle-dot',
  ];
@endphp

@if(filled($class->heading) || $metrics->isNotEmpty() || $regions->isNotEmpty() || $industries->isNotEmpty())
<section class="mlp-class cohort-room" id="mlp-class" aria-labelledby="cohort-room-title">
  <div class="cohort-room__background" aria-hidden="true">
    <span class="cohort-room__wash"></span>
    <span class="cohort-room__contour cohort-room__contour--one"></span>
    <span class="cohort-room__contour cohort-room__contour--two"></span>
  </div>

  <div class="cohort-room__frame container">
    <header class="cohort-room__intro">
      <div>
        @if(filled($class->label))
        <p class="cohort-room__label">{{ $class->label }}</p>
        @endif
        @if(filled($class->heading))
        <h2 class="cohort-room__heading" id="cohort-room-title">{{ $class->heading }}</h2>
        @endif
      </div>
      <div class="cohort-room__intro-copy">
        @if(filled($class->intro))
        <p>{{ $class->intro }}</p>
        @endif
        @if(filled($class->audience))
        <p class="cohort-room__audience">{{ $class->audience }}</p>
        @endif
      </div>
    </header>

    <div class="cohort-room__board" data-cohort-room>
      <div class="cohort-room__board-topline">
        <span>Inside the learning room</span>
        <span>People / places / perspectives</span>
      </div>

      @if($metrics->isNotEmpty())
      <section class="cohort-room__people" data-cohort-element aria-labelledby="cohort-room-people-title">
        <div class="cohort-room__zone-head">
          <span class="cohort-room__zone-icon" aria-hidden="true"><i data-lucide="users"></i></span>
          <h3 id="cohort-room-people-title">Who is in the room?</h3>
        </div>
        <dl class="cohort-room__metrics">
          @foreach($metrics as $mi => $metric)
          <div class="cohort-room__metric">
            <dt>
              <span class="cohort-room__metric-icon" aria-hidden="true"><i data-lucide="{{ $metricIcons[$mi] ?? 'users' }}"></i></span>
              <span>{{ $metric['label'] ?? 'Profile' }}</span>
            </dt>
            <dd>{{ $metric['value'] ?? '—' }}</dd>
          </div>
          @endforeach
        </dl>
      </section>
      @endif

      @if($regions->isNotEmpty())
      <section class="cohort-room__places" data-cohort-element aria-labelledby="cohort-room-places-title">
        <div class="cohort-room__zone-head">
          <span class="cohort-room__zone-icon" aria-hidden="true"><i data-lucide="globe"></i></span>
          <h3 id="cohort-room-places-title">Where the room comes from</h3>
        </div>
        <ul class="cohort-room__region-list">
          @foreach($regions as $region)
          <li class="cohort-room__region">
            <span class="cohort-room__region-name">{{ $region['name'] }}</span>
            @if(filled($region['note'] ?? null))
            <span class="cohort-room__region-note">{{ $region['note'] }}</span>
            @endif
          </li>
          @endforeach
        </ul>
      </section>
      @endif

      @if($industries->isNotEmpty())
      <section class="cohort-room__perspectives" data-cohort-element aria-labelledby="cohort-room-perspectives-title">
        <div class="cohort-room__zone-head">
          <span class="cohort-room__zone-icon" aria-hidden="true"><i data-lucide="briefcase"></i></span>
          <h3 id="cohort-room-perspectives-title">What the room brings</h3>
        </div>
        <ol class="cohort-room__industry-list">
          @foreach($industries as $industry)
          @php
            $share = max(0, min(100, (float) preg_replace('/[^0-9.]/', '', (string) ($industry['share'] ?? '0'))));
            $shareText = rtrim(rtrim(number_format($share, 1, '.', ''), '0'), '.');
            $industryIcon = $industryIcons[$industry['name'] ?? ''] ?? 'briefcase';
          @endphp
          <li class="cohort-room__industry" style="--cohort-share: {{ $share }}%;">
            <span class="cohort-room__industry-icon" aria-hidden="true"><i data-lucide="{{ $industryIcon }}"></i></span>
            <span class="cohort-room__industry-name">{{ $industry['name'] }}</span>
            <span class="cohort-room__industry-share">{{ $shareText }}%</span>
            <span class="cohort-room__industry-track" aria-hidden="true"><span></span></span>
          </li>
          @endforeach
        </ol>
      </section>
      @endif
    </div>
  </div>
</section>
@endif
