{{-- §12 Learning — diagonal media plane + flexible study points --}}
@php
  $points = collect($learning->points ?? [])->filter(fn ($p) => filled($p['title'] ?? null))->values();
  $plate = media_url($learning->plate_image ?? null, 'assets/images/homepage/mba-management.jpg');
@endphp
@if(filled($learning->heading) || $points->isNotEmpty())
<section class="mlp-learning" id="mlp-learning" aria-label="Learning experience">
  <div class="mlp-learning__deco" aria-hidden="true">
    <span class="mlp-learning__orb mlp-learning__orb--a"></span>
    <span class="mlp-learning__orb mlp-learning__orb--b"></span>
    <span class="mlp-learning__deco-rule"></span>
  </div>

  <div class="container mlp-learning__inner">
    <div class="mlp-learning__split">
      <div class="mlp-learning__copy" data-mlp-reveal="learning-copy">
        <div class="mlp-learning__meta">
          @if(filled($learning->label))
          <p class="mlp-learning__label mlp-meta">{{ $learning->label }}</p>
          @endif
        </div>
        <span class="mlp-learning__kicker" aria-hidden="true"></span>
        @if(filled($learning->heading))
        <h2 class="mlp-learning__heading mlp-headline">{{ $learning->heading }}</h2>
        @endif
        @if(filled($learning->intro))
        <p class="mlp-learning__intro mlp-lede">{{ $learning->intro }}</p>
        @endif

        @if($points->isNotEmpty())
        <ol class="mlp-learning__points">
          @foreach($points as $pi => $point)
          <li class="mlp-learning__point">
            <span class="mlp-learning__point-index" aria-hidden="true">{{ str_pad((string) ($pi + 1), 2, '0', STR_PAD_LEFT) }}</span>
            <div class="mlp-learning__point-body">
              <h3 class="mlp-learning__point-title">{{ $point['title'] }}</h3>
              @if(filled($point['text'] ?? null))
              <div class="mlp-prose mlp-learning__point-text">{!! \App\Support\MlpProse::html($point['text']) !!}</div>
              @endif
            </div>
          </li>
          @endforeach
        </ol>
        @endif

        @if(filled($learning->cta_primary_label) || filled($learning->cta_secondary_label))
        <div class="mlp-learning__ctas">
          @if(filled($learning->cta_primary_label))
          <a href="{{ edu_href($learning->cta_primary_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--primary">{{ $learning->cta_primary_label }}</a>
          @endif
          @if(filled($learning->cta_secondary_label))
          <a href="{{ edu_href($learning->cta_secondary_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--ghost mlp-btn--on-paper">{{ $learning->cta_secondary_label }}</a>
          @endif
        </div>
        @endif
      </div>

      <figure class="mlp-learning__media" data-mlp-reveal="learning-media">
        <div class="mlp-learning__plate" data-mlp-learning-plate>
          <img
            class="mlp-learning__plate-img"
            src="{{ $plate }}"
            alt="{{ $learning->plate_caption ?? 'Online learning experience' }}"
            width="720"
            height="900"
            loading="lazy"
            decoding="async"
          >
          <span class="mlp-learning__plate-veil" aria-hidden="true"></span>
        </div>
        @if(filled($learning->plate_caption))
        <figcaption class="mlp-learning__caption">{{ $learning->plate_caption }}</figcaption>
        @endif
      </figure>
    </div>
  </div>
</section>
@endif
