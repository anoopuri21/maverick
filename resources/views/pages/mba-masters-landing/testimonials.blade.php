{{-- §14 Testimonials — The Closing Archive / Voices Contact Sheet --}}
@php
  $fromDb = collect($storyTestimonials ?? [])
    ->filter(fn ($testimonial) => filled($testimonial->name ?? null) && filled($testimonial->testimonial ?? null))
    ->take(6)
    ->map(fn ($testimonial) => [
      'name' => $testimonial->name,
      'role' => collect([$testimonial->position ?? null, $testimonial->organisation ?? null])->filter()->implode(' · '),
      'quote' => strip_tags((string) ($testimonial->testimonial ?? '')),
      'photo' => media_url($testimonial->photo ?? null),
    ]);
  $fromSettings = collect($testimonials->items ?? [])
    ->filter(fn ($testimonial) => filled($testimonial['name'] ?? null) || filled($testimonial['quote'] ?? null))
    ->map(fn ($testimonial) => [
      'name' => $testimonial['name'] ?? '',
      'role' => $testimonial['role'] ?? '',
      'quote' => $testimonial['quote'] ?? '',
      'photo' => settings_media_url($testimonial, 'photo'),
    ]);
  $items = $fromSettings->isNotEmpty() ? $fromSettings : $fromDb;
  $fallbackPhoto = cached_asset('assets/images/alumni/alumn-1.png');
@endphp

@if($items->isNotEmpty())
<section class="mlp-testimonials archive-voices" id="mlp-testimonials" aria-labelledby="archive-voices-title">
  <div class="archive-voices__background" aria-hidden="true">
    <span class="archive-voices__wash"></span>
    <span class="archive-voices__rule archive-voices__rule--one"></span>
  </div>

  <div class="archive-voices__frame container">
    <header class="archive-voices__intro">
      <div>
        @if(filled($testimonials->label))
        <p class="archive-voices__label">{{ $testimonials->label }}</p>
        @endif
        @if(filled($testimonials->heading))
        <h2 class="archive-voices__heading" id="archive-voices-title">{{ $testimonials->heading }}</h2>
        @endif
      </div>
      @if(filled($testimonials->intro))
      <p class="archive-voices__intro-copy">{{ $testimonials->intro }}</p>
      @endif
    </header>

    @if($items->isNotEmpty())
    <div class="archive-voices__stage" data-closing-voices>
      <div class="archive-voices__stack" aria-hidden="true">
        @foreach($items->take(3) as $vi => $item)
        <span class="archive-voices__stack-image archive-voices__stack-image--{{ $vi + 1 }}">
          <img src="{{ $item['photo'] ?: $fallbackPhoto }}" alt="" width="640" height="800" loading="lazy" decoding="async">
        </span>
        @endforeach
        <span class="archive-voices__stack-caption">The experience, in their words.</span>
      </div>

      <div class="archive-voices__list luxury-testimonials" data-luxury-testimonials aria-roledescription="carousel" aria-label="Student testimonials">
        <div class="luxury-testimonials__slides" aria-live="polite">
          @foreach($items as $ti => $item)
          <blockquote
            class="luxury-testimonial{{ $ti === 0 ? ' is-active' : '' }}"
            data-testimonial-slide
            data-closing-element
            @if($ti !== 0) hidden aria-hidden="true" @else aria-hidden="false" @endif
          >
            <span class="luxury-testimonial__quote-icon" aria-hidden="true"><i data-lucide="quote"></i></span>
            <p class="luxury-testimonial__quote">“{{ $item['quote'] }}”</p>
            <footer class="luxury-testimonial__author">
              @if(filled($item['photo']))
              <img src="{{ $item['photo'] }}" alt="" width="56" height="56" loading="lazy" decoding="async">
              @else
              <span class="luxury-testimonial__initial" aria-hidden="true">{{ strtoupper(mb_substr($item['name'] ?: 'M', 0, 1)) }}</span>
              @endif
              <span>
                @if(filled($item['name']))<cite>{{ $item['name'] }}</cite>@endif
                @if(filled($item['role']))<small>{{ $item['role'] }}</small>@endif
              </span>
            </footer>
          </blockquote>
          @endforeach
        </div>

        <div class="luxury-testimonials__controls">
          <button type="button" class="luxury-testimonials__control" data-testimonial-prev aria-label="Previous testimonial"><i data-lucide="arrow-left" aria-hidden="true"></i></button>
          <span class="luxury-testimonials__progress"><span data-testimonial-current>01</span><span aria-hidden="true"> / </span><span data-testimonial-total>{{ str_pad((string) $items->count(), 2, '0', STR_PAD_LEFT) }}</span></span>
          <button type="button" class="luxury-testimonials__toggle" data-testimonial-toggle aria-pressed="false">Pause</button>
          <button type="button" class="luxury-testimonials__control" data-testimonial-next aria-label="Next testimonial"><i data-lucide="arrow-right" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>
    @endif
  </div>
</section>
@endif
