{{-- §3 Program overview — editorial plate + numbered benefit rails --}}

@php

  $items = collect($overview->items ?? [])->filter(fn ($item) => filled($item['title'] ?? null))->values();

  $plate = mlp_image_url($overview->plate_image ?? null, [
    'w' => 1200,
    'fallback' => 'assets/images/homepage/mba-management.jpg',
  ]);

  $hasCtas = filled($overview->cta_primary_label) || filled($overview->cta_secondary_label);

  $showSection = filled($overview->heading) || $items->isNotEmpty() || $hasCtas || filled($plate);

@endphp

@if($showSection)

<section class="mlp-overview mlp-section mlp-section--paper" id="mlp-overview" aria-label="Program overview">

  <div class="mlp-overview__deco" aria-hidden="true">

    <span class="mlp-overview__deco-rule"></span>

  </div>



  <div class="container mlp-overview__inner">

    <div class="mlp-overview__split">

      <figure class="mlp-overview__media" data-mlp-reveal="overview-media">

        <div class="mlp-overview__plate">

          <img

            class="mlp-overview__plate-img"

            src="{{ $plate }}"

            alt="{{ $overview->heading ?? 'MBA and Master\'s programs for working professionals' }}"

            width="640"

            height="800"

            loading="lazy"

            decoding="async"

          >

          <span class="mlp-overview__plate-veil" aria-hidden="true"></span>

          @if(filled($overview->index))

          <span class="mlp-overview__plate-index mlp-meta" aria-hidden="true">{{ $overview->index }}</span>

          @endif

        </div>

      </figure>



      <div class="mlp-overview__content">

        <header class="mlp-overview__head" data-mlp-reveal="overview-head">

          <div class="mlp-overview__meta">

            @if(filled($overview->label))

            <p class="mlp-overview__label mlp-meta">{{ $overview->label }}</p>

            @endif

          </div>

          <span class="mlp-overview__kicker" aria-hidden="true"></span>

          @if(filled($overview->heading))

          <h2 class="mlp-overview__heading mlp-headline">{{ $overview->heading }}</h2>

          @endif

          @if(filled($overview->intro))

          <p class="mlp-overview__intro mlp-lede">{{ $overview->intro }}</p>

          @endif

        </header>



        @if($items->isNotEmpty())

        <ol class="mlp-overview__rails" data-mlp-reveal="overview-rails">

          @foreach($items as $i => $item)

          <li class="mlp-overview__rail" style="--mlp-i: {{ $i }}">

            <span class="mlp-overview__num" aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>

            <div class="mlp-overview__body">

              <h3 class="mlp-overview__title">{{ $item['title'] }}</h3>

              @if(filled($item['text'] ?? null))

              <div class="mlp-prose mlp-overview__text">{!! \App\Support\MlpProse::html($item['text']) !!}</div>

              @endif

            </div>

          </li>

          @endforeach

        </ol>

        @endif



        @if($hasCtas)

        <div class="mlp-overview__ctas" data-mlp-reveal="overview-ctas">

          @if(filled($overview->cta_primary_label))

          <a href="{{ edu_href($overview->cta_primary_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--primary">{{ $overview->cta_primary_label }}</a>

          @endif

          @if(filled($overview->cta_secondary_label))

          <a href="{{ edu_href($overview->cta_secondary_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--ghost mlp-btn--on-paper">{{ $overview->cta_secondary_label }}</a>

          @endif

        </div>

        @endif

      </div>

    </div>

  </div>

</section>

@endif

