{{--
    Standalone Voices Testimonials Component (`<x-voices-testimonials page="..." />`)
    100% controlled via Filament Admin Panel (`Global Content -> Testimonials` & DB tables
    `our_story_testimonials` + `settings`) with zero hardcoded text or fallback images.
--}}
@php
    $totalCount = $items->count();
@endphp

@once
    @push('styles')
        <link rel="stylesheet" href="{{ cached_asset('assets/css/voices-testimonials.css') }}" />
    @endpush
    @push('scripts')
        <script src="{{ cached_asset('assets/js/voices-testimonials.js') }}" defer></script>
    @endpush
@endonce

<section
    class="mlp-testimonials archive-voices"
    id="{{ $id }}"
    data-voices-section="{{ $page }}"
    @if(filled($heading)) aria-labelledby="{{ $id }}-heading" @endif
>
    <div class="archive-voices__background" aria-hidden="true">
        <span class="archive-voices__wash mlp-wash"></span>
        <span class="archive-voices__rule archive-voices__rule--one"></span>
    </div>

    <div class="archive-voices__frame container">
        @if(filled($label) || filled($heading) || filled($intro))
            <div class="archive-voices__intro mlp-intro-grid" data-mlp-reveal="testimonials-head">
                <div>
                    @if(filled($label))
                        <p class="archive-voices__label mlp-eyebrow">{{ $label }}</p>
                    @endif
                    @if(filled($heading))
                        <h2 class="archive-voices__heading mlp-h2" id="{{ $id }}-heading">{{ $heading }}</h2>
                    @endif
                </div>
                @if(filled($intro))
                    <p class="archive-voices__intro-copy">{{ $intro }}</p>
                @endif
            </div>
        @endif

        <div class="archive-voices__stage" data-closing-voices>
            <div class="archive-voices__stack" data-testimonial-portraits aria-hidden="true">
                @foreach($items as $index => $item)
                    <div
                        class="archive-voices__stack-image testimonial-portrait {{ $index === 0 ? 'is-active' : '' }}"
                        data-testimonial-portrait="{{ $index }}"
                    >
                        @if(filled($item['photo'] ?? null))
                            <img
                                src="{{ $item['photo'] }}"
                                alt=""
                                width="760"
                                height="960"
                                loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                fetchpriority="{{ $index === 0 ? 'auto' : 'low' }}"
                                decoding="async"
                            />
                        @else
                            <span class="testimonial-portrait__placeholder">{{ $item['initial'] ?? '' }}</span>
                        @endif
                    </div>
                @endforeach
                @if(filled($stackCaption))
                    <span class="archive-voices__stack-caption">{{ $stackCaption }}</span>
                @endif
            </div>

            <div
                class="archive-voices__list luxury-testimonials"
                role="region"
                aria-roledescription="carousel"
                aria-label="{{ filled($heading) ? $heading : 'Testimonials' }}"
                data-mlp-reveal="testimonials-rail"
                data-luxury-testimonials
            >
                <div class="luxury-testimonials__slides" aria-live="polite">
                    @foreach($items as $index => $item)
                        <blockquote
                            class="luxury-testimonial mlp-hairline {{ $index === 0 ? 'is-active' : '' }}"
                            role="group"
                            aria-roledescription="slide"
                            aria-label="{{ ($index + 1) . ' of ' . $totalCount }}"
                            data-testimonial-slide="{{ $index }}"
                            data-closing-element
                            @if($index > 0) hidden aria-hidden="true" @endif
                        >
                            <span class="luxury-testimonial__quote-icon" aria-hidden="true">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849H0V3h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849H14V3h10z"/>
                                </svg>
                            </span>
                            <p class="luxury-testimonial__quote">&ldquo;{{ $item['quote'] }}&rdquo;</p>
                            <footer class="luxury-testimonial__author">
                                @if(filled($item['photo'] ?? null))
                                    <img
                                        src="{{ $item['photo'] }}"
                                        alt="{{ $item['name'] ?? '' }}"
                                        width="64"
                                        height="64"
                                        loading="lazy"
                                        decoding="async"
                                    />
                                @else
                                    <span class="luxury-testimonial__initial" aria-hidden="true">{{ $item['initial'] ?? '' }}</span>
                                @endif
                                <span>
                                    @if(filled($item['name'] ?? null))
                                        <cite>{{ $item['name'] }}</cite>
                                    @endif
                                    @if(filled($item['role'] ?? null))
                                        <small>{{ $item['role'] }}</small>
                                    @endif
                                </span>
                            </footer>
                        </blockquote>
                    @endforeach
                </div>

                @if($totalCount > 1)
                    <div class="luxury-testimonials__controls" aria-label="Testimonial controls">
                        <button type="button" class="luxury-testimonials__control" data-testimonial-prev aria-label="Previous testimonial">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                        </button>
                        <button type="button" class="luxury-testimonials__control" data-testimonial-next aria-label="Next testimonial">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </button>
                        <p class="luxury-testimonials__progress" aria-live="off">
                            <span data-testimonial-current>01</span>
                            <span aria-hidden="true">/</span>
                            <span data-testimonial-total>{{ str_pad((string) $totalCount, 2, '0', STR_PAD_LEFT) }}</span>
                        </p>
                        <button type="button" class="luxury-testimonials__toggle" data-testimonial-toggle aria-pressed="false">Pause</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
