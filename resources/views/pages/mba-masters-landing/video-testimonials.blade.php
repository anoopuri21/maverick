@php
  $videos = collect($videoTestimonials->videos ?? [])
      ->filter(fn ($v) => filled($v['video_url'] ?? null))
      ->values();
  $vtLabel = filled($videoTestimonials->label) ? $videoTestimonials->label : 'Maverick Shorts';
  $vtHeading = filled($videoTestimonials->heading) ? $videoTestimonials->heading : 'Hear it from our students';
@endphp
@if($videos->isNotEmpty())
<section id="video-testimonials" class="testimonials section-wrapper section--light" aria-label="Video Testimonials">
  <div class="container testimonials__inner">
    <div class="testimonials__header">
      <div class="section-label"><span>{{ $vtLabel }}</span></div>
      <h2 class="testimonials__heading section-title">
        <span class="testimonials__heading-line">{{ $vtHeading }}</span>
      </h2>
      @if(filled($videoTestimonials->intro))
      <p class="testimonials__subtitle body-text">{{ $videoTestimonials->intro }}</p>
      @endif
    </div>

    <div class="scroll-row scroll-row--light" data-scroll-row>
      <button class="scroll-row__btn scroll-row__btn--prev" aria-label="Scroll left" data-scroll-prev>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6" /></svg>
      </button>
      <div class="testimonials__scroll" data-scroll-container>
        <div class="testimonials__track" id="testimonialsTrack">
          {{-- Dynamically rendered by testimonials.js from window.testimonialsData --}}
        </div>
      </div>
      <button class="scroll-row__btn scroll-row__btn--next" aria-label="Scroll right" data-scroll-next>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6" /></svg>
      </button>
    </div>
  </div>

  {{-- DYNAMIC MODAL: video-player --}}
  <div class="testimonials__modal" id="videoModal">
    <div class="testimonials__modal-content">
      <button class="testimonials__modal-close" id="modalClose" aria-label="Close modal">x</button>
      <div id="modalPlayer"></div>
    </div>
  </div>

  <script>
    window.testimonialsData = @json($testimonialsJson ?? []);
  </script>
</section>
@endif
