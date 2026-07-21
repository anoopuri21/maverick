<section id="faculty-insights" class="insights section-wrapper section--light" aria-label="Faculty Insights">
  <div class="container insights__inner">
    <div class="insights__header">
      <div class="section-label"><span>Faculty Voice</span></div>
      <h2 class="insights__heading section-title">
        <span class="insights__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">Insights From</span>
          </span>
        </span>
        <span class="insights__heading-line hwdi__heading-line--red">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">Industry Experts</span>
          </span>
        </span>
      </h2>
      <p class="insights__subtitle body-text">
        Real-world perspectives from the minds shaping global business
        education
      </p>
    </div>

    <div class="scroll-row scroll-row--light" data-scroll-row>
      <button class="scroll-row__btn scroll-row__btn--prev" aria-label="Scroll left" data-scroll-prev>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M15 18l-6-6 6-6" />
        </svg>
      </button>
      <div class="insights__scroll" data-scroll-container data-lenis-prevent>
        <div class="insights__track">
          @forelse($facultyInsights as $insight)
            <article class="insights__card fade-up">
              <a href="{{ $insight->link_url ?? '#' }}" class="insights__card-link">
                <div class="insights__card-image">
                  <img src="{{ $insight->image_url }}"
                       alt="{{ $insight->title }}"
                       loading="lazy" decoding="async" width="320" height="280" />
                </div>
                <div class="insights__card-body">
                  @if($insight->badge)
                    <span class="insights__card-badge">{{ $insight->badge }}</span>
                  @endif
                  <h3 class="insights__card-title">{{ $insight->title }}</h3>
                  <span class="insights__card-read-more">
                    Read More <span class="inline-icon" data-lucide="move-right"></span>
                  </span>
                </div>
              </a>
            </article>
          @empty
            <p class="body-text" style="padding: 2rem;">No insights available yet.</p>
          @endforelse
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
