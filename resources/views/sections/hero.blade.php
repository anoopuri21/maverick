<section id="hero" class="hero" aria-label="Hero Maverick Business Academy">
    <div class="hero__video-wrapper" aria-hidden="true">
        <video class="hero__video" autoplay muted loop playsinline preload="auto">
            <source src="{{ $hero->video_url }}" type="video/webm" />
        </video>
        <div class="hero__video-fallback img-placeholder"></div>
    </div>

    <div class="hero__overlay" aria-hidden="true"></div>
    <div class="hero__grain" aria-hidden="true"></div>

    <div class="hero__content">
        <div class="container">
            <div class="hero__content-inner">
                <div class="hero__accent-bar" aria-hidden="true"></div>

                <div class="hero__eyebrow">
                    <div class="text-reveal-wrapper">
                        <span class="text-reveal-inner hero__eyebrow-text">
                            <span class="hero__eyebrow-line" aria-hidden="true"></span>
                            {{ $hero->eyebrow }}
                        </span>
                    </div>
                </div>

                <h1 class="hero__headline display-text">
                    <span class="hero__headline-line">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner" data-hero-word>{{ $hero->headline_line1 }}</span>
                        </span>
                    </span>
                    <span class="hero__headline-line">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner" data-hero-word>{{ $hero->headline_line2 }}</span>
                        </span>
                    </span>
                    <span class="hero__headline-line hero__headline-line--accent">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner" data-hero-word>{{ $hero->headline_line3 }}</span>
                        </span>
                    </span>
                </h1>

                <div class="hero__subheading fade-up" data-hero-sub>
                    <p class="hero__subheading-text">{{ $hero->subheading }}</p>
                </div>

                <div class="hero__ctas fade-up" data-hero-ctas>
                    <a href="{{ $hero->cta_primary_url }}" class="btn btn--primary hero__cta-primary">
                        {{ $hero->cta_primary_text }}
                    </a>
                    <a href="{{ $hero->cta_secondary_url }}" class="btn btn--secondary hero__cta-secondary">
                        {{ $hero->cta_secondary_text }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>