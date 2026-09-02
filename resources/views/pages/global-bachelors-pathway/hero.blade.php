@php
    $heroBackgroundUrl = settings_media_url($hero, 'background_image');
    $showHero = filled($hero->tag ?? null)
        || filled($hero->heading ?? null)
        || filled($hero->heading_italic ?? null)
        || html_filled($hero->sub ?? null)
        || filled($heroBackgroundUrl);
@endphp
@if($showHero)
<section class="cinematic-hero" aria-label="Global Bachelor's Pathway Hero" data-testid="gbp-hero">
    <div class="cinematic-hero__bg" aria-hidden="true">
        @if(filled($heroBackgroundUrl))
        <div class="cinematic-hero__bg-image" style="background-image: url('{{ $heroBackgroundUrl }}')"></div>
        @endif
        <div class="cinematic-hero__gradient"></div>
        <div class="cinematic-hero__noise"></div>
        <div class="cinematic-hero__shapes">
            <svg class="cinematic-hero__shape cinematic-hero__shape--1" viewBox="0 0 200 200" fill="none">
                <circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.15)" stroke-width="1"/>
            </svg>
            <svg class="cinematic-hero__shape cinematic-hero__shape--2" viewBox="0 0 300 300" fill="none">
                <circle cx="150" cy="150" r="120" stroke="rgba(220,38,38,0.2)" stroke-width="1"/>
            </svg>
            <svg class="cinematic-hero__shape cinematic-hero__shape--3" viewBox="0 0 100 100" fill="none">
                <rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.15)" stroke-width="1" transform="rotate(20 50 50)"/>
            </svg>
        </div>
        <div class="cinematic-hero__particles">
            @for($i = 0; $i < 6; $i++)
                <div class="cinematic-hero__particle"></div>
            @endfor
        </div>
        <div class="cinematic-hero__scanline"></div>
        <div class="cinematic-hero__corners">
            <div class="cinematic-hero__corner cinematic-hero__corner--tl"></div>
            <div class="cinematic-hero__corner cinematic-hero__corner--tr"></div>
            <div class="cinematic-hero__corner cinematic-hero__corner--bl"></div>
            <div class="cinematic-hero__corner cinematic-hero__corner--br"></div>
        </div>
    </div>

    <div class="container cinematic-hero__content">
        @if(filled($hero->tag))
        <span class="cinematic-hero__eyebrow">
            <span class="cinematic-hero__eyebrow-line"></span>
            {{ $hero->tag }}
        </span>
        @endif
        @if(filled($hero->heading) || filled($hero->heading_italic))
        <h1 class="cinematic-hero__title">
            @if(filled($hero->heading)){{ $hero->heading }}@endif
            @if(filled($hero->heading) && filled($hero->heading_italic))<br>@endif
            @if(filled($hero->heading_italic))<em>{{ $hero->heading_italic }}</em>@endif
        </h1>
        @endif
        @if(html_filled($hero->sub ?? null))
        <div class="cinematic-hero__description gbp-richtext">{!! $hero->sub !!}</div>
        @endif
    </div>
</section>
@endif
