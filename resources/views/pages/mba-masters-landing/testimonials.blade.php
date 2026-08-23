{{-- §14 Testimonials — MLP settings when non-empty; Our Story composer fallback --}}
@php
  $fromDb = collect($storyTestimonials ?? [])
    ->filter(fn ($t) => filled($t->name ?? null) && filled($t->testimonial ?? null))
    ->take(6)
    ->map(fn ($t) => [
      'name' => $t->name,
      'role' => collect([$t->position ?? null, $t->organisation ?? null])->filter()->implode(' · '),
      'quote' => strip_tags((string) ($t->testimonial ?? '')),
      'photo' => media_url($t->photo ?? null),
    ]);
  $fromSettings = collect($testimonials->items ?? [])
    ->filter(fn ($t) => filled($t['name'] ?? null) || filled($t['quote'] ?? null))
    ->map(fn ($t) => [
      'name' => $t['name'] ?? '',
      'role' => $t['role'] ?? '',
      'quote' => $t['quote'] ?? '',
      'photo' => media_url($t['photo'] ?? null),
    ]);
  $items = $fromSettings->isNotEmpty() ? $fromSettings : $fromDb;
@endphp
@if(filled($testimonials->heading) || $items->isNotEmpty())
<section class="mlp-testimonials" id="mlp-testimonials" aria-label="Testimonials">
  <div class="mlp-testimonials__deco" aria-hidden="true">
    <span class="mlp-testimonials__deco-rule"></span>
  </div>

  <div class="container mlp-testimonials__inner">
    <header class="mlp-testimonials__head" data-mlp-reveal="testimonials-head">
      <div class="mlp-testimonials__meta">
        @if(filled($testimonials->label))
        <p class="mlp-testimonials__label mlp-meta">{{ $testimonials->label }}</p>
        @endif
      </div>
      @if(filled($testimonials->heading))
      <h2 class="mlp-testimonials__heading mlp-headline">{{ $testimonials->heading }}</h2>
      @endif
      @if(filled($testimonials->intro))
      <p class="mlp-testimonials__intro mlp-lede">{{ $testimonials->intro }}</p>
      @endif
    </header>

    @if($items->isNotEmpty())
    <div class="mlp-testimonials__rail" data-mlp-reveal="testimonials-rail">
      @foreach($items as $item)
      <blockquote class="mlp-testimonials__quote">
        <p class="mlp-testimonials__text">“{{ $item['quote'] }}”</p>
        <footer class="mlp-testimonials__who">
          @if(filled($item['photo']))
          <img
            class="mlp-testimonials__photo"
            src="{{ $item['photo'] }}"
            alt=""
            width="56"
            height="56"
            loading="lazy"
            decoding="async"
          >
          @else
          <span class="mlp-testimonials__initial" aria-hidden="true">{{ strtoupper(mb_substr($item['name'] ?: 'M', 0, 1)) }}</span>
          @endif
          <div>
            @if(filled($item['name']))
            <cite class="mlp-testimonials__name">{{ $item['name'] }}</cite>
            @endif
            @if(filled($item['role']))
            <span class="mlp-testimonials__role">{{ $item['role'] }}</span>
            @endif
          </div>
        </footer>
      </blockquote>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
