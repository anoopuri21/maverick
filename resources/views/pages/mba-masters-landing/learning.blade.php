{{-- §14 Learning — The Light Archive / study desk --}}
@php
  $points = collect($learning->points ?? [])
      ->filter(fn ($p) => filled($p['title'] ?? null))
      ->values();
  $plate = settings_media_url($learning, 'plate_image')
      ?: cached_asset('assets/images/homepage/mba-management.jpg');
  $icons = ['radio', 'user-check', 'laptop', 'briefcase', 'clipboard-check'];
@endphp

@if(filled($learning->heading) || $points->isNotEmpty())
<section class="mlp-learning archive-learning" id="mlp-learning" aria-labelledby="archive-learning-title" data-archive-learning>
  <div class="archive-learning__background" aria-hidden="true">
    <span class="archive-learning__wash mlp-wash"></span>
    <span class="archive-learning__rule archive-learning__rule--one"></span>
  </div>

  <div class="archive-learning__frame container">
    <header class="archive-learning__intro mlp-intro-grid" data-mlp-reveal="learning-copy">
      <div>
        @if(filled($learning->label))
        <p class="archive-learning__label mlp-eyebrow">{{ $learning->label }}</p>
        @endif
        <span class="mlp-learning__kicker" aria-hidden="true"></span>
        @if(filled($learning->heading))
        <h2 class="archive-learning__heading mlp-h2" id="archive-learning-title">{{ $learning->heading }}</h2>
        @endif
      </div>
      @if(filled($learning->intro))
      <p class="archive-learning__intro-copy">{{ $learning->intro }}</p>
      @endif
    </header>

    <div class="archive-learning__desk">
      <figure class="archive-learning__stack" data-mlp-reveal="learning-media" data-mlp-learning-plate>
        <span class="archive-learning__stack-layer archive-learning__stack-layer--back" aria-hidden="true">
          <img src="{{ $plate }}" alt="" width="720" height="900" loading="lazy" decoding="async">
        </span>
        <span class="archive-learning__stack-layer archive-learning__stack-layer--middle" aria-hidden="true">
          <img src="{{ $plate }}" alt="" width="720" height="900" loading="lazy" decoding="async">
        </span>
        <span class="archive-learning__stack-layer archive-learning__stack-layer--front">
          <img src="{{ $plate }}" alt="" width="720" height="900" loading="lazy" decoding="async">
        </span>
        @if(filled($learning->plate_caption))
        <figcaption class="archive-learning__caption">{{ $learning->plate_caption }}</figcaption>
        @endif
      </figure>

      @if($points->isNotEmpty())
      <ol class="archive-learning__points" aria-label="How online learning works">
        @foreach($points as $i => $point)
        <li class="archive-learning__point mlp-hairline" data-archive-element style="--mlp-i: {{ $i }}">
          <span class="archive-learning__point-icon mlp-icon-box" aria-hidden="true">
            <i data-lucide="{{ $icons[$i % count($icons)] }}"></i>
          </span>
          <div>
            <h3>{{ $point['title'] }}</h3>
            @if(filled($point['text'] ?? null))
            <div class="archive-learning__point-text mlp-prose">{!! \App\Support\MlpProse::html($point['text']) !!}</div>
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
      <a href="{{ edu_href($learning->cta_primary_url) ?? '#mlp-enquire' }}" class="archive-learning__primary mlp-cta mlp-cta--primary">{{ $learning->cta_primary_label }} <span aria-hidden="true">↗</span></a>
      @endif
      @if(filled($learning->cta_secondary_label))
      <a href="{{ edu_href($learning->cta_secondary_url) ?? '#mlp-enquire' }}" class="archive-learning__secondary">{{ $learning->cta_secondary_label }}</a>
      @endif
    </div>
    @endif
  </div>
</section>
@endif
