@php
    $heroPrimaryHref = edu_href($hero->cta_primary_url ?? null);
    $heroSecondaryHref = edu_href($hero->cta_secondary_url ?? null);
@endphp
<section id="hero" class="hero" aria-label="Hero Maverick Business Academy">
    <div class="hero__video-wrapper" aria-hidden="true">
        @if(isset($hero) && $hero && filled($hero->video_url ?? null))
            <video class="hero__video" autoplay muted loop playsinline preload="metadata">
                <source src="{{ $hero->video_url }}" type="video/webm" />
            </video>
            <div class="hero__video-fallback img-placeholder"></div>
        @else
            <div class="hero__video-fallback img-placeholder"></div>
        @endif
        <div class="hero__video-fallback img-placeholder"></div>
    </div>

    <div class="hero__overlay" aria-hidden="true"></div>
    <div class="hero__grain" aria-hidden="true"></div>

    <div class="hero__content">
        <div class="container">
            <div class="hero__content-inner">
                <div class="hero__accent-bar" aria-hidden="true"></div>

                @if(filled($hero->eyebrow ?? null))
                <div class="hero__eyebrow">
                    <div class="text-reveal-wrapper">
                        <span class="text-reveal-inner hero__eyebrow-text">
                            <span class="hero__eyebrow-line" aria-hidden="true"></span>
                            {{ $hero->eyebrow }}
                        </span>
                    </div>
                </div>
                @endif

                @if(filled($hero->headline_line1 ?? null) || filled($hero->headline_line2 ?? null) || filled($hero->headline_line3 ?? null))
                <h1 class="hero__headline display-text">
                    @if(filled($hero->headline_line1 ?? null))
                    <span class="hero__headline-line">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner" data-hero-word>{{ $hero->headline_line1 }}</span>
                        </span>
                    </span>
                    @endif
                    @if(filled($hero->headline_line2 ?? null))
                    <span class="hero__headline-line">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner" data-hero-word>{{ $hero->headline_line2 }}</span>
                        </span>
                    </span>
                    @endif
                    @if(filled($hero->headline_line3 ?? null))
                    <span class="hero__headline-line hero__headline-line--accent">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner" data-hero-word>{{ $hero->headline_line3 }}</span>
                        </span>
                    </span>
                    @endif
                </h1>
                @endif

                @if(filled($hero->subheading ?? null))
                <div class="hero__subheading fade-up" data-hero-sub>
                    <p class="hero__subheading-text">{{ $hero->subheading }}</p>
                </div>
                @endif

                @if(($heroPrimaryHref && filled($hero->cta_primary_text ?? null)) || ($heroSecondaryHref && filled($hero->cta_secondary_text ?? null)))
                <div class="hero__ctas fade-up" data-hero-ctas>
                    @if($heroPrimaryHref && filled($hero->cta_primary_text ?? null))
                    <a href="{{ $heroPrimaryHref }}" class="btn btn--primary hero__cta-primary">
                        {{ $hero->cta_primary_text }}
                    </a>
                    @endif
                    @if($heroSecondaryHref && filled($hero->cta_secondary_text ?? null))
                    <a href="{{ $heroSecondaryHref }}" class="btn btn--secondary hero__cta-secondary">
                        {{ $hero->cta_secondary_text }}
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
