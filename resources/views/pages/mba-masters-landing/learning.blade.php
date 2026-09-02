{{-- §12 Learning experience — The Light Archive / Study Desk --}}
@php
  $points = collect($learning->points ?? [])
      ->filter(fn ($point) => filled($point['title'] ?? null))
      ->values();
  $plate = settings_media_url($learning, 'plate_image') ?: cached_asset('assets/images/homepage/mba-management.jpg');
  $supportingImages = [
    'assets/images/edutainment/learning-beyond.png',
    'assets/images/programs/enquire-seminar.jpg',
  ];
  $pointIcons = ['play', 'calendar', 'life-buoy', 'route'];
@endphp

@if(filled($learning->heading) || $points->isNotEmpty())
<section class="mlp-learning archive-learning" id="mlp-learning" aria-labelledby="archive-learning-title">
  <div class="archive-learning__background" aria-hidden="true">
    <span class="archive-learning__wash"></span>
    <span class="archive-learning__rule archive-learning__rule--one"></span>
  </div>

  <div class="archive-learning__frame container">
    <header class="archive-learning__intro mlp-intro-grid">
      <div>
        @if(filled($learning->label))
        <p class="archive-learning__label mlp-eyebrow">{{ $learning->label }}</p>
        @endif
        @if(filled($learning->heading))
        <h2 class="archive-learning__heading mlp-h2" id="archive-learning-title">{{ $learning->heading }}</h2>
        @endif
      </div>
      @if(filled($learning->intro))
      <p class="archive-learning__intro-copy">{{ $learning->intro }}</p>
      @endif
    </header>

    <div class="archive-learning__desk" data-archive-learning>
      <figure class="archive-learning__stack" aria-labelledby="archive-learning-caption">
        <span class="archive-learning__stack-layer archive-learning__stack-layer--back">
          <img src="{{ cached_asset($supportingImages[0]) }}" alt="" width="720" height="900" loading="lazy" decoding="async">
        </span>
        <span class="archive-learning__stack-layer archive-learning__stack-layer--middle">
          <img src="{{ cached_asset($supportingImages[1]) }}" alt="" width="720" height="900" loading="lazy" decoding="async">
        </span>
        <span class="archive-learning__stack-layer archive-learning__stack-layer--front">
          <img src="{{ $plate }}" alt="{{ $learning->plate_caption ?? 'Online learning experience' }}" width="720" height="900" loading="lazy" decoding="async">
        </span>
        @if(filled($learning->plate_caption))
        <figcaption class="archive-learning__caption" id="archive-learning-caption">{{ $learning->plate_caption }}</figcaption>
        @endif
      </figure>

      @if($points->isNotEmpty())
      <ol class="archive-learning__points" aria-label="Learning experience points">
        @foreach($points as $pi => $point)
        <li class="archive-learning__point" data-archive-element>
          <span class="archive-learning__point-icon mlp-icon-box" aria-hidden="true"><i data-lucide="{{ $pointIcons[$pi] ?? 'sparkles' }}"></i></span>
          <div>
            <h3>{{ $point['title'] }}</h3>
            @if(filled($point['text'] ?? null))
            <div class="archive-learning__point-text">{!! \App\Support\MlpProse::html($point['text']) !!}</div>
            @endif
          </div>
        </li>
        @endforeach
      </ol>
      @endif
    </div>

    @if(filled($learning->cta_primary_label) || filled($learning->cta_secondary_label))
    <div class="archive-learning__actions">
      @if(filled($learning->cta_primary_label))
      <a href="{{ edu_href($learning->cta_primary_url) ?? '#mlp-enquire' }}" class="archive-learning__primary">{{ $learning->cta_primary_label }} <span aria-hidden="true">↗</span></a>
      @endif
      @if(filled($learning->cta_secondary_label))
      <a href="{{ edu_href($learning->cta_secondary_url) ?? '#mlp-enquire' }}" class="archive-learning__secondary">{{ $learning->cta_secondary_label }}</a>
      @endif
    </div>
    @endif
  </div>
</section>
@endif
