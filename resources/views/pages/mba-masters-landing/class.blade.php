{{-- §9 Class profile — The Light Archive / Cohort Portrait --}}
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
  $industryImages = [
    'Marketing' => 'assets/images/homepage/business.jpg',
    'Logistics' => 'assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg',
    'Cyber Security' => 'assets/images/homepage/dba.jpg',
    'Finance' => 'assets/images/homepage/swiss-mba.jpg',
    'IT' => 'assets/images/homepage/mba-management.jpg',
  ];
@endphp

@if(filled($class->heading) || $metrics->isNotEmpty() || $regions->isNotEmpty() || $industries->isNotEmpty())
<section class="mlp-class archive-class" id="mlp-class" aria-labelledby="archive-class-title">
  <div class="archive-class__background" aria-hidden="true">
    <span class="archive-class__wash"></span>
    <span class="archive-class__rule archive-class__rule--one"></span>
    <span class="archive-class__rule archive-class__rule--two"></span>
  </div>

  <div class="archive-class__frame container">
    <header class="archive-class__intro">
      <div>
        @if(filled($class->label))
        <p class="archive-class__label">{{ $class->label }}</p>
        @endif
        @if(filled($class->heading))
        <h2 class="archive-class__heading" id="archive-class-title">{{ $class->heading }}</h2>
        @endif
      </div>
      <div class="archive-class__copy">
        @if(filled($class->intro))
        <p>{{ $class->intro }}</p>
        @endif
        @if(filled($class->audience))
        <p class="archive-class__audience">{{ $class->audience }}</p>
        @endif
      </div>
    </header>

    <div class="archive-class__archive" data-archive-class>
      <div class="archive-class__stack" aria-hidden="true">
        @foreach($industries->take(3) as $ii => $industry)
        <span class="archive-class__stack-image archive-class__stack-image--{{ $ii + 1 }}">
          <img src="{{ media_url($industry['image'] ?? null, 'assets/images/homepage/business.jpg') }}" alt="" width="640" height="420" loading="lazy" decoding="async">
        </span>
        @endforeach
        <span class="archive-class__stack-caption">The room is bigger than one background.</span>
      </div>

      @if($metrics->isNotEmpty())
      <dl class="archive-class__metrics" aria-label="Executive MBA class profile">
        @foreach($metrics as $mi => $metric)
        <div class="archive-class__metric" data-archive-element>
          <dt>
            <span class="archive-class__icon" aria-hidden="true"><i data-lucide="{{ $metricIcons[$mi] ?? 'users' }}"></i></span>
            <span>{{ $metric['label'] ?? 'Profile' }}</span>
          </dt>
          <dd>{{ $metric['value'] ?? '—' }}</dd>
        </div>
        @endforeach
      </dl>
      @endif

      @if($regions->isNotEmpty())
      <div class="archive-class__regions" data-archive-element>
        <div class="archive-class__zone-head">
          <span class="archive-class__zone-icon" aria-hidden="true"><i data-lucide="globe"></i></span>
          <h3>Where the room comes from</h3>
        </div>
        <ul class="archive-class__region-list" aria-label="Cohort regions">
          @foreach($regions as $region)
          <li>
            <span>{{ $region['name'] }}</span>
            @if(filled($region['note'] ?? null))<small>{{ $region['note'] }}</small>@endif
          </li>
          @endforeach
        </ul>
      </div>
      @endif

      @if($industries->isNotEmpty())
      <div class="archive-class__industries" data-archive-element>
        <div class="archive-class__industry-heading">
          <p>Professional backgrounds</p>
          <h3>Different industries. One learning room.</h3>
        </div>
        <div class="archive-class__industry-gallery" aria-label="Professional backgrounds">
          @foreach($industries as $industry)
          @php
            $share = max(0, min(100, (float) preg_replace('/[^0-9.]/', '', (string) ($industry['share'] ?? '0'))));
            $shareText = rtrim(rtrim(number_format($share, 1, '.', ''), '0'), '.');
            $industryName = (string) ($industry['name'] ?? 'Industry');
            $industryImage = $industryImages[$industryName] ?? 'assets/images/homepage/business.jpg';
          @endphp
          <article class="archive-class__industry-card">
            <div class="archive-class__industry-image">
              <img src="{{ media_url($industry['image'] ?? null, $industryImage) }}" alt="{{ $industryName }} professional background" width="720" height="480" loading="lazy" decoding="async">
            </div>
            <div class="archive-class__industry-copy">
              <h4>{{ $industryName }}</h4>
              <p>{{ $shareText }}% of the cohort</p>
            </div>
          </article>
          @endforeach
        </div>
      </div>
      @endif
    </div>
  </div>
</section>
@endif
