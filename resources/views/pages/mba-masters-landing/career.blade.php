{{-- §10 Career — cinematic trajectories: ghost numeral, drawn spine, 3-beat scenes --}}
@php
  $stories = collect($career->stories ?? [])
      ->filter(fn ($s) => filled($s['name'] ?? null))
      ->values();
  $count = $stories->count();
  $fallbackPortrait = 'assets/images/alumni/alumn-1.png';
@endphp
@if(filled($career->heading) || $stories->isNotEmpty())
<section class="mlp-career" id="mlp-career" aria-label="Career progression">
  <div class="mlp-career__deco" aria-hidden="true">
    <span class="mlp-career__orb mlp-career__orb--a"></span>
    <span class="mlp-career__orb mlp-career__orb--b"></span>
    <span class="mlp-career__deco-rule"></span>
  </div>

  <div class="container mlp-career__inner">
    <header class="mlp-career__head" data-mlp-reveal="career-head">
      <div class="mlp-career__meta">
        @if(filled($career->label))
        <p class="mlp-career__label mlp-meta">{{ $career->label }}</p>
        @endif
      </div>
      @if(filled($career->heading))
      <h2 class="mlp-career__heading mlp-headline">{{ $career->heading }}</h2>
      @endif
      @if(filled($career->intro))
      <p class="mlp-career__intro mlp-lede">{{ $career->intro }}</p>
      @endif
    </header>

    @if($stories->isNotEmpty())
    <div
      class="mlp-career__list"
      data-mlp-career-stage
      data-count="{{ $count }}"
    >
      <span class="mlp-career__spine" aria-hidden="true" data-mlp-career-spine></span>

      @foreach($stories as $si => $story)
      @php
        $portrait = media_url($story['portrait'] ?? null, $fallbackPortrait);
        $isFeature = $si === 0;
        $tone = $si % 2 === 0 ? 'warm' : 'blue';
      @endphp
      <article
        class="mlp-career__item mlp-career__item--{{ $tone }}{{ $isFeature ? ' mlp-career__item--feature' : '' }}"
        data-mlp-career-story
        data-mlp-career-rail
      >
        <span class="mlp-career__node" aria-hidden="true" data-mlp-career-node></span>
        <div class="mlp-career__frame" data-mlp-career-frame>
          <img
            class="mlp-career__photo"
            src="{{ $portrait }}"
            alt="{{ $story['name'] }}"
            width="112"
            height="140"
            loading="lazy"
            decoding="async"
          >
        </div>

        <div class="mlp-career__content">
          <div class="mlp-career__who" data-mlp-career-who>
            <h3 class="mlp-career__name">{{ $story['name'] }}</h3>
            <span class="mlp-career__name-rule" aria-hidden="true" data-mlp-career-rule></span>
            <p class="mlp-career__meta-line">
              @if(filled($story['country'] ?? null))
              <span>{{ $story['country'] }}</span>
              @endif
              @if(filled($story['program'] ?? null))
              <span>{{ $story['program'] }}</span>
              @endif
            </p>
          </div>

          <div class="mlp-career__move">
            @if(filled($story['previous_role'] ?? null))
            <div class="mlp-career__role mlp-career__role--before" data-mlp-career-role>
              <span class="mlp-career__role-tag">Previous</span>
              <p class="mlp-career__role-text">{{ $story['previous_role'] }}</p>
            </div>
            @endif
            <span class="mlp-career__arrow" aria-hidden="true" data-mlp-career-arrow></span>
            @if(filled($story['current_role'] ?? null))
            <div class="mlp-career__role mlp-career__role--after" data-mlp-career-role>
              <span class="mlp-career__role-tag">Now</span>
              <p class="mlp-career__role-text">{{ $story['current_role'] }}</p>
            </div>
            @endif
          </div>

          @if(filled($story['quote'] ?? null))
          <blockquote class="mlp-career__quote" data-mlp-career-quote>
            <p>{{ $story['quote'] }}</p>
          </blockquote>
          @endif
        </div>
      </article>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
