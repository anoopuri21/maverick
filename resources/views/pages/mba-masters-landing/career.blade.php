{{-- §10 Career progression — researched career direction dossiers --}}
@php
  $stories = collect($career->stories ?? [])
      ->filter(fn ($story) => filled($story['name'] ?? null))
      ->values();
  $fallbackPortrait = 'assets/images/homepage/business.jpg';
@endphp

@if(filled($career->heading) || $stories->isNotEmpty())
<section class="mlp-career archive-career" id="mlp-career" aria-labelledby="archive-career-title">
  <div class="archive-career__background" aria-hidden="true">
    <span class="archive-career__wash"></span>
    <span class="archive-career__rule archive-career__rule--one"></span>
  </div>

  <div class="archive-career__frame container">
    <header class="archive-career__intro">
      <div>
        @if(filled($career->label))
        <p class="archive-career__label">{{ $career->label }}</p>
        @endif
        @if(filled($career->heading))
        <h2 class="archive-career__heading" id="archive-career-title">{{ $career->heading }}</h2>
        @endif
      </div>
      @if(filled($career->intro))
      <p class="archive-career__intro-copy">{{ $career->intro }}</p>
      @endif
    </header>

    @if($stories->isNotEmpty())
    <div class="archive-career__stage" data-archive-career>
      <div class="archive-career__stack" aria-hidden="true">
        @foreach($stories->take(3) as $si => $story)
        <span class="archive-career__stack-image archive-career__stack-image--{{ $si + 1 }}">
          <img src="{{ media_url($story['portrait'] ?? null, $fallbackPortrait) }}" alt="" width="640" height="800" loading="lazy" decoding="async">
        </span>
        @endforeach
        <span class="archive-career__stack-caption">Skills that travel across industries.</span>
      </div>

      <div class="mlp-uae-badge" role="note" aria-label="Top-rated online learning experience in UAE">
        <p class="mlp-uae-badge__title">Top-rated online learning<br>experience in UAE</p>
        <div class="mlp-uae-badge__row">
          <div class="mlp-uae-badge__country">
            <strong>UAE</strong>
            <span>Top-Rated</span>
          </div>
          <span class="mlp-uae-badge__flag" aria-hidden="true">
            <span class="mlp-uae-badge__flag-red"></span>
            <span class="mlp-uae-badge__flag-stripes"><span></span><span></span><span></span></span>
          </span>
        </div>
      </div>

      <div class="archive-career__dossiers">
        @foreach($stories as $story)
        <article class="archive-career__dossier" data-archive-element>
          <header class="archive-career__dossier-head">
            <span class="archive-career__dossier-icon" aria-hidden="true"><i data-lucide="arrow-up-right"></i></span>
            <div>
              <h3>{{ $story['name'] }}</h3>
              <p>
                @if(filled($story['country'] ?? null)){{ $story['country'] }}@endif
                @if(filled($story['country'] ?? null) && filled($story['program'] ?? null)) · @endif
                @if(filled($story['program'] ?? null)){{ $story['program'] }}@endif
              </p>
            </div>
          </header>

          <div class="archive-career__move">
            @if(filled($story['previous_role'] ?? null))
            <div>
              <span>Build from</span>
              <strong>{{ $story['previous_role'] }}</strong>
            </div>
            @endif
            @if(filled($story['current_role'] ?? null))
            <div>
              <span>Possible direction</span>
              <strong>{{ $story['current_role'] }}</strong>
            </div>
            @endif
          </div>

          @if(filled($story['quote'] ?? null))
          <blockquote class="archive-career__quote">{{ $story['quote'] }}</blockquote>
          @endif
        </article>
        @endforeach
      </div>
    </div>
    @endif
  </div>
</section>
@endif
