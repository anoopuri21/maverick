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
                  <div class="insights__card-divider" aria-hidden="true"></div>
                  <p class="insights__card-excerpt" id="fi-excerpt-{{ $insight->id }}" data-fi-excerpt>{{ $description }}</p>
                @endif
                @if(strip_tags($insight->content ?? '') || $insight->link_url)
                  <div class="insights__card-footer">
                    @if(strip_tags($insight->content ?? ''))
                      <button type="button" class="insights__card-toggle" data-fi-toggle="fi-excerpt-{{ $insight->id }}" aria-expanded="false" hidden>Read more</button>
                    @endif
                    @if($insight->link_url)
                      <a class="insights__card-linkedin" href="{{ $insight->link_url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $insight->title }} on LinkedIn">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        <span>LinkedIn</span>
                      </a>
                    @endif
                  </div>
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
