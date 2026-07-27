@props([
    'icon',
    'label',
    'value',
    'link' => null,
    'secondary' => null,
    'linkText' => null,
])

<div class="contact-card contact-info-card" data-scroll-reveal>
    <div class="contact-info-card__icon-badge">
        @if($icon === 'address')
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="contact-svg-icon" aria-hidden="true">
                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
        @elseif($icon === 'email')
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="contact-svg-icon" aria-hidden="true">
                <rect width="20" height="16" x="2" y="4" rx="2"/>
                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
        @elseif($icon === 'phone')
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="contact-svg-icon" aria-hidden="true">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
        @elseif($icon === 'office_hours')
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="contact-svg-icon" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        @endif
    </div>

    <div class="contact-info-card__content">
        <span class="contact-info-card__label">{{ $label }}</span>

        <div class="contact-info-card__value">
            @if($link)
                <a href="{{ $link }}" class="contact-info-card__action-link" @if(str_starts_with($link, 'http')) target="_blank" rel="noopener noreferrer" @endif>
                    {{ $value }}
                </a>
            @else
                <span>{{ $value }}</span>
            @endif

            @if($secondary)
                <div class="contact-info-card__secondary-row">
                    @if($link && str_starts_with($link, 'tel:'))
                        <a href="tel:{{ str_replace(' ', '', $secondary) }}" class="contact-info-card__action-link">
                            {{ $secondary }}
                        </a>
                    @else
                        <span>{{ $secondary }}</span>
                    @endif
                </div>
            @endif
        </div>

        @if($link && $linkText)
            <div class="contact-info-card__footer-link">
                <a href="{{ $link }}" class="contact-info-card__cta-btn" @if(str_starts_with($link, 'http')) target="_blank" rel="noopener noreferrer" @endif>
                    <span>{{ $linkText }}</span>
                    <span class="cta-arrow" aria-hidden="true">→</span>
                </a>
            </div>
        @endif
    </div>
</div>
