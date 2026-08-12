@if(($ourStoryTestimonials ?? collect())->count() > 0)
@php
    // Optional overrides so the same shared slider can be reused across pages
    // with different ids, labels and headings.
    $osTestimonialsId      = $osTestimonialsId ?? 'testimonials';
    $osTestimonialsLabel   = $osTestimonialsLabel ?? 'Testimonials';
    $osTestimonialsHeading = $osTestimonialsHeading ?? 'What Our Students Say';
@endphp
<section id="{{ $osTestimonialsId }}" class="os-testimonials" aria-label="{{ $osTestimonialsLabel }}">
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
            <div class="os-testimonials__stars" aria-label="{{ $item->rating }} out of 5 stars">
              @for ($i = 1; $i <= 5; $i++)
              <span
                data-lucide="star"
                class="os-testimonials__star {{ $i <= $item->rating ? 'os-testimonials__star--filled' : 'os-testimonials__star--empty' }}"
                aria-hidden="true"
              ></span>
              @endfor
            </div>

            <blockquote class="os-testimonials__quote">
              {{ $item->testimonial }}
            </blockquote>

            <div class="os-testimonials__author">
              @if ($item->photo)
              <img
                src="{{ $item->photo }}"
                alt="{{ $item->name }}"
                class="os-testimonials__photo"
                width="48"
                height="48"
                loading="lazy"
              />
              @else
              <span class="os-testimonials__initials" aria-hidden="true">{{ strtoupper(mb_substr($item->name, 0, 1)) }}</span>
              @endif
              <span class="os-testimonials__name">{{ $item->name }}</span>
            </div>
          </article>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
@endif
