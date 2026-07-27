@props(['variant' => 'hero'])

<div class="blog-decor blog-decor--{{ $variant }}" aria-hidden="true">
    @if($variant === 'hero')
        <!-- Blobs -->
        <div class="blog-decor__blob blog-decor__blob--1"></div>
        <div class="blog-decor__blob blog-decor__blob--2 blog-decor__blob--secondary"></div>

        <!-- Floating SVG Icons -->
        <!-- 1. Book -->
        <svg class="blog-decor__icon blog-decor__icon--1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
        </svg>

        <!-- 2. Graduation Cap -->
        <svg class="blog-decor__icon blog-decor__icon--2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
            <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
        </svg>

        <!-- 3. Pen/Quill -->
        <svg class="blog-decor__icon blog-decor__icon--3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 20h9"></path>
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
        </svg>

        <!-- 4. Chat Bubble -->
        <svg class="blog-decor__icon blog-decor__icon--4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>

    @elseif($variant === 'cta')
        <!-- Dotted Grid Pattern -->
        <div class="blog-decor__dots"></div>
        <!-- Smaller Floating Blobs -->
        <div class="blog-decor__blob blog-decor__blob--cta-1"></div>
        <div class="blog-decor__blob blog-decor__blob--cta-2 blog-decor__blob--secondary"></div>

    @elseif($variant === 'grid-bg')
        <!-- Single Very Faint Blob -->
        <div class="blog-decor__blob blog-decor__blob--grid"></div>
    @endif
</div>
