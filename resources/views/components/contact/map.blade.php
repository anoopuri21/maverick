@props(['address'])

@php
    $escapedAddress = urlencode($address ?? 'Sharjah, United Arab Emirates');
    $embedUrl = "https://maps.google.com/maps?q={$escapedAddress}&t=&z=15&ie=UTF8&iwloc=&output=embed";
@endphp

<div class="contact-map-section" data-scroll-reveal>
    <div class="contact-map-container">
        <iframe
            class="contact-map-iframe"
            title="Office Location Map"
            src="{{ $embedUrl }}"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>

        @if($address)
            <div class="contact-map-overlay-card" data-scroll-reveal>
                <div class="contact-map-overlay-card__badge">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="overlay-icon" aria-hidden="true">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span>Our Campus</span>
                </div>
                <p class="contact-map-overlay-card__address">{{ $address }}</p>
                <a href="https://www.google.com/maps/search/?api=1&query={{ $escapedAddress }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="contact-map-overlay-card__btn">
                    <span>Get Directions</span>
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        @endif
    </div>
</div>
