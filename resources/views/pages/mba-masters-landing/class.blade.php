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
  $industryDescriptions = [
    'Marketing' => 'A perspective shaped by customers, brands and the decisions that move markets.',
    'Logistics' => 'A perspective shaped by operations, supply chains and the systems behind movement.',
    'Cyber Security' => 'A perspective shaped by digital risk, resilience and responsible leadership.',
    'Finance' => 'A perspective shaped by numbers, investment decisions and commercial clarity.',
    'IT' => 'A perspective shaped by technology, systems and the strategy behind change.',
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
        <div class="topic-desk" data-topic-desk>
          <div class="topic-desk__topics" role="tablist" aria-label="Professional background topics">
            @foreach($industries as $ii => $industry)
            @php
              $industryName = (string) ($industry['name'] ?? 'Industry');
              $topicId = 'topic-'.\Illuminate\Support\Str::slug($industryName);
            @endphp
            <button
              type="button"
              class="topic-desk__topic{{ $ii === 0 ? ' is-active' : '' }}"
              id="{{ $topicId }}-tab"
              role="tab"
              aria-selected="{{ $ii === 0 ? 'true' : 'false' }}"
              aria-controls="{{ $topicId }}-panel"
              data-topic-tab="{{ $topicId }}"
            >{{ $industryName }}</button>
            @endforeach
          </div>

          <div class="topic-desk__panels">
            @foreach($industries as $ii => $industry)
            @php
              $industryName = (string) ($industry['name'] ?? 'Industry');
              $topicId = 'topic-'.\Illuminate\Support\Str::slug($industryName);
              $share = max(0, min(100, (float) preg_replace('/[^0-9.]/', '', (string) ($industry['share'] ?? '0'))));
              $shareText = rtrim(rtrim(number_format($share, 1, '.', ''), '0'), '.');
              $description = $industryDescriptions[$industryName] ?? 'A professional perspective that adds depth to the learning room.';
              $industryImage = $industryImages[$industryName] ?? 'assets/images/homepage/business.jpg';
              $stackImages = $industries->reject(fn ($candidate) => ($candidate['name'] ?? '') === $industryName)->take(2);
            @endphp
            <article
              class="topic-desk__panel{{ $ii === 0 ? ' is-active' : '' }}"
              id="{{ $topicId }}-panel"
              role="tabpanel"
              aria-labelledby="{{ $topicId }}-tab"
              data-topic-panel="{{ $topicId }}"
              @if($ii !== 0) hidden @endif
            >
              <div class="topic-desk__visual" aria-label="{{ $industryName }} visual">
                @foreach($stackImages as $si => $stackIndustry)
                @php
                  $stackName = (string) ($stackIndustry['name'] ?? 'Industry');
                  $stackImage = $industryImages[$stackName] ?? 'assets/images/homepage/business.jpg';
                @endphp
                <span class="topic-desk__stack topic-desk__stack--{{ $si + 1 }}" aria-hidden="true">
                  <img src="{{ media_url($stackIndustry['image'] ?? null, $stackImage) }}" alt="" width="720" height="480" loading="lazy" decoding="async">
                </span>
                @endforeach
                <figure class="topic-desk__hero-image">
                  <img src="{{ media_url($industry['image'] ?? null, $industryImage) }}" alt="{{ $industryName }} professional background" width="960" height="640" loading="lazy" decoding="async">
                </figure>
              </div>
              <div class="topic-desk__details">
                <p class="topic-desk__detail-label">{{ $shareText }}% of the cohort</p>
                <h3>{{ $industryName }}</h3>
                <p class="topic-desk__description">{{ $description }}</p>
              </div>
            </article>
            @endforeach
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</section>
@endif
