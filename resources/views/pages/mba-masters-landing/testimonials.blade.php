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
      'photo' => media_url($testimonial['photo'] ?? null),
    ]);
  $items = $fromSettings->isNotEmpty() ? $fromSettings : $fromDb;
  $fallbackPhoto = cached_asset('assets/images/alumni/alumn-1.png');
@endphp

@if(filled($testimonials->heading) || $items->isNotEmpty())
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

      <div class="archive-voices__list">
        @foreach($items as $item)
        <blockquote class="archive-voices__voice" data-closing-element>
          <span class="archive-voices__quote-icon" aria-hidden="true"><i data-lucide="quote"></i></span>
          <p class="archive-voices__quote">“{{ $item['quote'] }}”</p>
          <footer>
            @if(filled($item['photo']))
            <img src="{{ $item['photo'] }}" alt="" width="48" height="48" loading="lazy" decoding="async">
            @else
            <span class="archive-voices__initial" aria-hidden="true">{{ strtoupper(mb_substr($item['name'] ?: 'M', 0, 1)) }}</span>
            @endif
            <span>
              @if(filled($item['name']))<cite>{{ $item['name'] }}</cite>@endif
              @if(filled($item['role']))<small>{{ $item['role'] }}</small>@endif
            </span>
          </footer>
        </blockquote>
        @endforeach
      </div>
    </div>
    @endif
  </div>
</section>
@endif
