@if(($ourStoryTestimonials ?? collect())->count() > 0)
@php
    // Optional overrides so the same shared slider can be reused across pages
    // with different ids, labels and headings.
    $osTestimonialsId      = $osTestimonialsId ?? 'testimonials';
    $osTestimonialsLabel   = $osTestimonialsLabel ?? 'Testimonials';
    $osTestimonialsHeading = $osTestimonialsHeading ?? 'What Our Students Say';
    // 'google' variant = Google-review card (programme detail). Anything else
    // keeps the original layout (our-story).
    $osTestimonialsVariant = $osTestimonialsVariant ?? '';
@endphp
<section id="{{ $osTestimonialsId }}" class="os-testimonials {{ $osTestimonialsVariant === 'google' ? 'os-testimonials--google' : '' }}" aria-label="{{ $osTestimonialsLabel }}">
  <div class="container">
    <div class="os-testimonials__header">
      <span class="os-section-label fade-up">{{ $osTestimonialsLabel }}</span>
      <h2 class="os-section-heading os-section-heading--center fade-up">
        {{ $osTestimonialsHeading }}
      </h2>
    </div>
    
    <div class="os-testimonials__slider" data-testimonials-slider>
      <div class="os-testimonials__viewport">
        <div class="os-testimonials__track">
          @foreach ($ourStoryTestimonials as $item)
          <article class="os-testimonials__card">
            @if($osTestimonialsVariant === 'google')
            {{-- Google-review card: author top (pic + Google badge), then name,
                 rating, then a clampable quote with read more/less --}}
            <div class="os-rev__head">
              <div class="os-rev__avatar">
                @if ($url = media_url($item->photo ?? null))
                <img src="{{ $url }}" alt="{{ $item->name }}" class="os-rev__photo" width="52" height="52" loading="lazy" />
                @else
                <span class="os-rev__initials" aria-hidden="true">{{ strtoupper(mb_substr($item->name, 0, 1)) }}</span>
                @endif
                <span class="os-rev__google" aria-hidden="true" title="Verified Google review">
                  <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0 0 12 23z"/><path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 0 1 0-4.2V7.06H2.18a11 11 0 0 0 0 9.88l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15A11 11 0 0 0 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                </span>
              </div>
              <div class="os-rev__meta">
                <span class="os-rev__name">{{ $item->name }}</span>
                <div class="os-testimonials__stars" aria-label="{{ $item->rating }} out of 5 stars">
                  @for ($i = 1; $i <= 5; $i++)
                  <span data-lucide="star" class="os-testimonials__star {{ $i <= $item->rating ? 'os-testimonials__star--filled' : 'os-testimonials__star--empty' }}" aria-hidden="true"></span>
                  @endfor
                </div>
              </div>
            </div>
            <div class="os-rev__body" data-clamp>
              <blockquote class="os-rev__quote">{!! rich_html($item->testimonial ?? null) !!}</blockquote>
            </div>
            @if(!empty(trim(strip_tags($item->testimonial ?? ''))))
            <button type="button" class="os-rev__toggle" data-clamp-toggle aria-expanded="false">Read more</button>
            @endif
            @else
            {{-- Original layout (our-story) --}}
            <div class="os-testimonials__stars" aria-label="{{ $item->rating }} out of 5 stars">
              @for ($i = 1; $i <= 5; $i++)
              <span data-lucide="star" class="os-testimonials__star {{ $i <= $item->rating ? 'os-testimonials__star--filled' : 'os-testimonials__star--empty' }}" aria-hidden="true"></span>
              @endfor
            </div>
            <blockquote class="os-testimonials__quote">{!! rich_html($item->testimonial ?? null) !!}</blockquote>
            <div class="os-testimonials__author">
              @if ($url = media_url($item->photo ?? null))
              <img src="{{ $url }}" alt="{{ $item->name }}" class="os-testimonials__photo" width="48" height="48" loading="lazy" />
              @else
              <span class="os-testimonials__initials" aria-hidden="true">{{ strtoupper(mb_substr($item->name, 0, 1)) }}</span>
              @endif
              <span class="os-testimonials__name">{{ $item->name }}</span>
            </div>
            @endif
          </article>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
@endif
