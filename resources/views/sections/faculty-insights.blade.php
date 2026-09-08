@php
    $facultyInsights = collect($facultyInsights ?? []);
    $homepageChrome = $homepageChrome ?? null;
@endphp
@if($facultyInsights->isNotEmpty())
<section id="faculty-insights" class="insights section-wrapper section--light" aria-label="Faculty Insights">
  <div class="container insights__inner">
    {{-- Header --}}
    <div class="insights__header">
      <div class="section-label"><span>{{ $homepageChrome->faculty_label ?? 'Faculty' }}</span></div>
      <h2 class="insights__heading section-title">
        <span class="insights__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">{{ $homepageChrome->faculty_heading_line1 ?? 'Insights From' }}</span>
          </span>
        </span>
        <span class="insights__heading-line hwdi__heading-line--red">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">{{ $homepageChrome->faculty_heading_line2 ?? 'Industry Experts' }}</span>
          </span>
        </span>
      </h2>
      <p class="insights__subtitle body-text">
        {{ $homepageChrome->faculty_subtitle ?? 'Learn from global faculty who bring real-world experience into the classroom.' }}
      </p>
    </div>

    {{-- Single Card Slider - No fade-up to avoid invisible bug, GSAP will handle 40% trigger --}}
    <div class="insights__slider" data-fi-slider data-scroll-row>
      {{-- Scroll Container --}}
      <div class="insights__scroll" data-scroll-container data-fi-container>
        <div class="insights__track" data-fi-track>
          @foreach($facultyInsights as $index => $insight)
            @php
              $desc = trim(strip_tags($insight->content ?? ''));
              $hasDesc = filled($desc);
              $isFirst = $index === 0;
            @endphp
            <article class="insights__card" data-fi-card data-fi-index="{{ $index }}">
              {{-- Top accent --}}
              <div class="insights__card-accent" aria-hidden="true"></div>

              {{-- Graduation Cap Watermark - big, low opacity, top-right --}}
              <div class="insights__card-watermark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M22 10L12 5L2 10L12 15L22 10Z"/>
                  <path d="M6 12V16C6 16 8.5 18.5 12 18.5C15.5 18.5 18 16 18 16V12"/>
                  <path d="M22 10V16"/>
                  <path d="M12 15V18.5"/>
                </svg>
              </div>

              {{-- Left: 1/3 Media - CLS safe with aspect-ratio --}}
              <div class="insights__card-media">
                <div class="insights__card-image">
                  @if($url = media_url($insight->image_url ?? null))
                    <img src="{{ $url }}"
                         alt="{{ $insight->title }}"
                         width="480"
                         height="600"
                         style="aspect-ratio:480/600"
                         @if($isFirst) loading="eager" fetchpriority="high" @else loading="lazy" @endif
                         decoding="async" />
                  @else
                    <div class="insights__card-image-fallback">
                      <span>{{ strtoupper(substr($insight->title ?? 'F',0,1)) }}</span>
                    </div>
                  @endif
                </div>
                <div class="insights__card-media-overlay" aria-hidden="true"></div>
                @if($insight->country)
                  <div class="insights__card-media-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 21s-6-5.373-6-10a6 6 0 1 1 12 0c0 4.627-6 10-6 10z"/><circle cx="12" cy="11" r="2"/></svg>
                    <span>{{ $insight->country }}</span>
                  </div>
                @endif
              </div>

              {{-- Right: Content - Full content visible, no toggle, CLS safe --}}
              <div class="insights__card-body">
                <div class="insights__card-top">
                  <div class="insights__card-quote" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 17h3l2-4V7H5v6h3l-2 4zm8 0h3l2-4V7h-6v6h3l-2 4z"/></svg>
                  </div>
                  <span class="insights__card-kicker">Faculty Insight • {{ str_pad($index+1,2,'0',STR_PAD_LEFT) }}</span>
                </div>

                <h3 class="insights__card-title">{{ $insight->title }}</h3>

                <div class="insights__card-meta">
                  @if($insight->faculty_role)
                    <span class="insights__card-role">{{ $insight->faculty_role }}</span>
                  @endif
                  @if($insight->country)
                    <span class="insights__card-country">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="14" height="14" aria-hidden="true"><path d="M12 21s-6-5.373-6-10a6 6 0 1 1 12 0c0 4.627-6 10-6 10z"/><circle cx="12" cy="11" r="2"/></svg>
                      {{ $insight->country }}
                    </span>
                  @endif
                </div>

                @if($hasDesc)
                  <div class="insights__card-divider" aria-hidden="true"></div>
                  {{-- Full content visible, no clamp, no toggle — prevents CLS --}}
                  <p class="insights__card-excerpt insights__card-excerpt--full">{{ $desc }}</p>
                @endif

                @if($insight->link_url)
                  <div class="insights__card-footer">
                    <a class="insights__card-linkedin" href="{{ $insight->link_url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $insight->title }} on LinkedIn">
                      <span class="insights__card-linkedin-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                      </span>
                      <span>Connect on LinkedIn</span>
                      <span class="insights__card-linkedin-arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M7 17L17 7M7 7h10v10"/></svg>
                      </span>
                    </a>
                  </div>
                @endif
              </div>
            </article>
          @endforeach
        </div>
      </div>

      {{-- Controls: Dots + Counter + Nav - Static dots in Blade to prevent CLS --}}
      <div class="insights__controls">
        <div class="insights__pagination" data-fi-pagination aria-label="Faculty slider pagination">
          @foreach($facultyInsights as $i => $fi)
            <button type="button" class="insights__pagination-dot {{ $i===0 ? 'is-active' : '' }}" data-fi-dot="{{ $i }}" aria-label="Go to faculty {{ $i+1 }}"></button>
          @endforeach
        </div>

        <div class="insights__counter" aria-live="polite">
          <span class="insights__counter-current" data-fi-current>01</span>
          <span class="insights__counter-sep">/</span>
          <span class="insights__counter-total" data-fi-total>{{ str_pad($facultyInsights->count(),2,'0',STR_PAD_LEFT) }}</span>
        </div>

        <div class="insights__nav">
          <button class="insights__nav-btn insights__nav-btn--prev" type="button" data-fi-prev data-scroll-prev aria-label="Previous faculty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
          </button>
          <button class="insights__nav-btn insights__nav-btn--next" type="button" data-fi-next data-scroll-next aria-label="Next faculty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</section>
@endif

@push('scripts')
<script src="{{ cached_asset('assets/js/faculty-insights-slider.js') }}" defer></script>
@endpush
