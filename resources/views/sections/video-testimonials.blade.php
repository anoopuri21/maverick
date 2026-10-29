@php
  $homepageChrome = $homepageChrome ?? null;
  $testimonials = collect($testimonials ?? []);
@endphp
<section id="video-testimonials" class="testimonials section-wrapper section--light"
  aria-label="Video Testimonials">
  <div class="container testimonials__inner">
    <div class="testimonials__header">
      <div class="section-label"><span>{{ $homepageChrome->testimonials_label ?? '' }}</span></div>
      <h2 class="testimonials__heading section-title">
        <span class="testimonials__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">{{ $homepageChrome->testimonials_heading_line1 ?? '' }}</span>
          </span>
        </span>
        <span class="testimonials__heading-line hwdi__heading-line--red">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">{{ $homepageChrome->testimonials_heading_line2 ?? '' }}</span>
          </span>
        </span>
      </h2>
      <p class="testimonials__subtitle body-text">
        {{ $homepageChrome->testimonials_subtitle ?? '' }}
      </p>
    </div>

    <div class="scroll-row scroll-row--light" data-scroll-row>
      <button class="scroll-row__btn scroll-row__btn--prev" aria-label="Scroll left" data-scroll-prev>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M15 18l-6-6 6-6" />
        </svg>
      </button>
      <div class="testimonials__scroll" data-scroll-container>
        <div class="testimonials__track" id="testimonialsTrack">
          @foreach($testimonials as $testimonial)
            @php
              $videoUrl = $testimonial->embed_url;
              $thumbnailUrl = $testimonial->thumbnail_url;
            @endphp
            <article class="testimonials__card" data-video="{{ $videoUrl }}" data-testid="homepage-testimonial-card">
              <div class="testimonials__card-thumb">
                <div class="testimonials__card-poster">
                  <img src="{{ $thumbnailUrl }}" alt="{{ $testimonial->name }}" loading="lazy" decoding="async" width="320" height="220" />
                  <span class="testimonials__card-badge">{{ strtoupper($testimonial->company ?? 'STUDENT') }}</span>
                  @if(filled($videoUrl))
                    <button class="testimonials__play" type="button" aria-label="Play video by {{ $testimonial->name }}">
                      <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M8 5v14l11-7z"/>
                      </svg>
                    </button>
                  @endif
                </div>
                <div class="testimonials__card-player"></div>
              </div>
              <div class="testimonials__card-info">
                <h4 class="testimonials__card-name">{{ $testimonial->name }}</h4>
                <span class="testimonials__card-role">{{ $testimonial->designation }}</span>
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

  <!-- DYNAMIC MODAL: video-player -->
  <div class="testimonials__modal" id="videoModal">
    <div class="testimonials__modal-content">
      <button class="testimonials__modal-close" id="modalClose" aria-label="Close modal">
        x
      </button>
      <div id="modalPlayer"></div>
    </div>
  </div>

</section>
