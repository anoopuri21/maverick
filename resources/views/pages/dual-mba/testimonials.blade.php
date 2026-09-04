{{-- ===== S8: TESTIMONIALS ===== --}}
@php
    $testimonialItems = collect($testimonials->items ?? [])->filter(fn ($item) => filled($item['name'] ?? null) || filled($item['quote'] ?? null));
    $showTestimonials = filled($testimonials->label ?? null)
        || filled($testimonials->title ?? null)
        || filled($testimonials->title_italic ?? null)
        || $testimonialItems->isNotEmpty();
@endphp
@if($showTestimonials)
<section class="dmba-testimonials section--light section-wrapper" aria-label="Student Success Stories" data-testid="dmba-testimonials-section">
  <div class="container">
    <div class="dmba-testimonials__header">
      @if(filled($testimonials->label))
      <div class="section-label"><span>{{ $testimonials->label }}</span></div>
      @endif
      @if(filled($testimonials->title) || filled($testimonials->title_italic))
      <h2 class="section-title">
        {{ $testimonials->title }}
        @if(filled($testimonials->title_italic))
          <em>{{ $testimonials->title_italic }}</em>
        @endif
      </h2>
      @endif
    </div>

    @if($testimonialItems->isNotEmpty())
    <div class="dmba-testimonials__carousel" aria-roledescription="carousel" aria-label="Graduate testimonials">
      <div class="dmba-testimonials__track" data-testid="dmba-testimonials-track" aria-live="polite">
        @foreach($testimonialItems as $item)
        <article class="dmba-testimonials__card" data-testid="dmba-testimonial-{{ $loop->iteration }}">
          <span class="dmba-testimonials__card-icon" aria-hidden="true" data-lucide="quote"></span>
          @if(filled($item['quote'] ?? null))
          <div class="dmba-testimonials__card-quote">{!! rich_html($item['quote'] ?? null) !!}</div>
          @endif
          <div class="dmba-testimonials__card-footer">
            <div class="dmba-testimonials__card-author">
              @php $avatarUrl = settings_media_url($item, 'avatar'); @endphp
              @if(filled($avatarUrl))
              <img src="{{ $avatarUrl }}" alt="{{ $item['name'] ?? '' }}" class="dmba-testimonials__card-avatar" loading="lazy" width="52" height="52" />
              @endif
              <div class="dmba-testimonials__card-info">
                @if(filled($item['name'] ?? null))
                <span class="dmba-testimonials__card-name">{{ $item['name'] }}</span>
                @endif
                @if(filled($item['role'] ?? null))
                <span class="dmba-testimonials__card-role">{{ $item['role'] }}</span>
                @endif
              </div>
            </div>
            @if(filled($item['programme'] ?? null))
            <span class="dmba-testimonials__card-programme">{{ $item['programme'] }}</span>
            @endif
          </div>
        </article>
        @endforeach
      </div>
    </div>

    <div class="dmba-testimonials__controls" data-testid="dmba-testimonials-controls">
      <button type="button" class="dmba-testimonials__btn" data-dmba-carousel="prev" aria-label="Previous testimonials" data-testid="dmba-carousel-prev">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      </button>
      <div class="dmba-testimonials__dots" role="tablist" aria-label="Testimonial pages" data-testid="dmba-testimonials-dots"></div>
      <button type="button" class="dmba-testimonials__btn" data-dmba-carousel="next" aria-label="Next testimonials" data-testid="dmba-carousel-next">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>
    </div>
    @endif
  </div>
</section>
@endif
