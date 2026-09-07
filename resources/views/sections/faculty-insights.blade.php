@php
    $facultyInsights = collect($facultyInsights ?? []);
    $homepageChrome = $homepageChrome ?? null;
@endphp
@if($facultyInsights->isNotEmpty())
<section id="faculty-insights" class="insights section-wrapper section--light" aria-label="Faculty Insights">
  <div class="container insights__inner">
    <div class="insights__header">
      <div class="section-label"><span>{{ $homepageChrome->faculty_label ?? '' }}</span></div>
      <h2 class="insights__heading section-title">
        <span class="insights__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">{{ $homepageChrome->faculty_heading_line1 ?? '' }}</span>
          </span>
        </span>
        <span class="insights__heading-line hwdi__heading-line--red">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">{{ $homepageChrome->faculty_heading_line2 ?? '' }}</span>
          </span>
        </span>
      </h2>
      <p class="insights__subtitle body-text">
        {{ $homepageChrome->faculty_subtitle ?? '' }}
      </p>
    </div>

    <div class="scroll-row scroll-row--light" data-scroll-row>
      <button class="scroll-row__btn scroll-row__btn--prev" aria-label="Scroll left" data-scroll-prev>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M15 18l-6-6 6-6" />
        </svg>
      </button>
      <div class="insights__scroll" data-scroll-container>
        <div class="insights__track">
          @foreach($facultyInsights as $insight)
            <article class="insights__card fade-up" data-fi-card>
              <div class="insights__card-image">
                @if($url = media_url($insight->image_url ?? null))
                <img src="{{ $url }}"
                     alt="{{ $insight->title }}"
                     loading="lazy" decoding="async" width="320" height="240" />
                @endif
              </div>
              <div class="insights__card-body">
                <h3 class="insights__card-title">{{ $insight->title }}</h3>
                @if($insight->faculty_role)
                  <span class="insights__card-role">{{ $insight->faculty_role }}</span>
                @endif
                @if($insight->country)
                  <span class="insights__card-country">{{ $insight->country }}</span>
                @endif
                @if($description = strip_tags($insight->content ?? ''))
                  <p class="insights__card-excerpt" id="fi-excerpt-{{ $insight->id }}" data-fi-excerpt>{{ $description }}</p>
                  <button type="button" class="insights__card-toggle" data-fi-toggle="fi-excerpt-{{ $insight->id }}" aria-expanded="false" hidden>Read more</button>
                @endif
              </div>
            </article>
          @endforeach
        </div>
      </div>
      <button class="scroll-row__btn scroll-row__btn--next" aria-label="Scroll right" data-scroll-next>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M9 18l6-6-6-6" />
        </svg>
      </button>
    </div>
  </div>
</section>
@endif
