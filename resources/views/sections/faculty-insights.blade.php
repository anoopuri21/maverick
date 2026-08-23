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
      <div class="insights__scroll" data-scroll-container>
        <div class="insights__track">
          @forelse($facultyInsights as $insight)
            @php
              $preview = $insight->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($insight->content ?? ''), 320);
            @endphp
            <article class="insights__card fade-up"
                     data-fv-card
                     data-fv-title="{{ $insight->title }}"
                     data-fv-badge="{{ $insight->badge ?? '' }}"
                     data-fv-faculty-name="{{ $insight->faculty_name ?? '' }}"
                     data-fv-faculty-role="{{ $insight->faculty_role ?? '' }}"
                     data-fv-avatar="{{ media_url($insight->faculty_avatar_url ?? null) ?? '' }}">
              <div class="insights__card-image">
                @if($url = media_url($insight->image_url ?? null))
                <img src="{{ $url }}"
                     alt="{{ $insight->title }}"
                     loading="lazy" decoding="async" width="320" height="240" />
                @endif
              </div>
              <div class="insights__card-body">
                @if($insight->badge)
                  <span class="insights__card-badge">{{ $insight->badge }}</span>
                @endif
                <h3 class="insights__card-title">{{ $insight->title }}</h3>
                @if($insight->faculty_name)
                  <div class="insights__card-faculty">
                    @if($avatarUrl = media_url($insight->faculty_avatar_url ?? null))
                      <img src="{{ $avatarUrl }}" alt="" class="insights__card-avatar" width="28" height="28" loading="lazy" decoding="async" />
                    @endif
                    <span class="insights__card-faculty-text">
                      <span class="insights__card-faculty-name">{{ $insight->faculty_name }}</span>
                      @if($insight->faculty_role)
                        <span class="insights__card-faculty-role">{{ $insight->faculty_role }}</span>
                      @endif
                    </span>
                  </div>
                @endif
                @if($preview)
                  <p class="insights__card-excerpt">{{ $preview }}</p>
                @endif
                <div class="insights__card-footer">
                  <button type="button" class="insights__card-read-more" data-fv-open>
                    Read Full Insight <span class="inline-icon" data-lucide="move-right"></span>
                  </button>
                </div>
              </div>
              <template data-fv-content>
                @if($insight->pull_quote)
                  <blockquote class="fv-modal__quote-source">{{ $insight->pull_quote }}</blockquote>
                @endif
                {!! $insight->content !!}
              </template>
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

  <div class="fv-modal" id="facultyVoiceModal" role="dialog" aria-modal="true" aria-labelledby="fvModalTitle" hidden>
    <div class="fv-modal__backdrop" data-fv-close></div>
    <div class="fv-modal__dialog">
      <button type="button" class="fv-modal__close" data-fv-close aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path d="M18 6L6 18M6 6l12 12" />
        </svg>
      </button>
      <header class="fv-modal__head">
        <img src="" alt="" class="fv-modal__avatar" width="48" height="48" hidden />
        <div class="fv-modal__meta">
          <span class="fv-modal__badge" hidden></span>
          <span class="fv-modal__faculty-name"></span>
          <span class="fv-modal__faculty-role"></span>
        </div>
      </header>
      <h2 id="fvModalTitle" class="fv-modal__title"></h2>
      <blockquote class="fv-modal__quote" hidden></blockquote>
      <div class="fv-modal__body" data-lenis-prevent></div>
    </div>
  </div>
</section>
