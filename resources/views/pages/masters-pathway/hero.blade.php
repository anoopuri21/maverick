@php
    $heroParagraphs = collect($hero->paragraphs ?? [])->filter(fn ($p) => html_filled(is_string($p) ? $p : null));
    $heroCtas = collect($hero->ctas ?? [])->filter(fn ($c) => filled($c['label'] ?? null) && filled($c['url'] ?? null));
    $routeSteps = collect($hero->route_steps ?? [])->filter(fn ($s) => filled($s['label'] ?? null));
    $showHero = filled($hero->tag ?? null)
        || filled($hero->heading ?? null)
        || filled($hero->heading_highlight ?? null)
        || html_filled($hero->sub ?? null)
        || $heroParagraphs->isNotEmpty()
        || filled($hero->background_image ?? null)
        || $heroCtas->isNotEmpty()
        || $routeSteps->isNotEmpty();
@endphp
@if($showHero)
<section class="cinematic-hero mp-hero" aria-label="International Master's Pathway Program" data-testid="mp-hero">
    <div class="cinematic-hero__bg" aria-hidden="true">
        @if(filled($hero->background_image))
        <div class="cinematic-hero__bg-image" style="background-image: url('{{ media_url($hero->background_image) }}')"></div>
        @endif
        <div class="cinematic-hero__gradient"></div>
        <div class="cinematic-hero__noise"></div>
        <div class="cinematic-hero__shapes">
            <svg class="cinematic-hero__shape cinematic-hero__shape--1" viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.15)" stroke-width="1"/></svg>
            <svg class="cinematic-hero__shape cinematic-hero__shape--2" viewBox="0 0 300 300" fill="none"><circle cx="150" cy="150" r="120" stroke="rgba(236,31,36,0.22)" stroke-width="1"/></svg>
            <svg class="cinematic-hero__shape cinematic-hero__shape--3" viewBox="0 0 100 100" fill="none"><rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.15)" stroke-width="1" transform="rotate(20 50 50)"/></svg>
        </div>
        <div class="cinematic-hero__particles">
            @for($i = 0; $i < 6; $i++)
                <div class="cinematic-hero__particle"></div>
            @endfor
        </div>
        <div class="cinematic-hero__scanline"></div>
    </div>

    <div class="container cinematic-hero__content">
        @if(filled($hero->tag))
        <span class="cinematic-hero__eyebrow">
            <span class="cinematic-hero__eyebrow-line"></span>
            {{ $hero->tag }}
        </span>
        @endif
        @if(filled($hero->heading) || filled($hero->heading_highlight))
        <h1 class="cinematic-hero__title">
            @if(filled($hero->heading)){{ $hero->heading }}@endif
            @if(filled($hero->heading) && filled($hero->heading_highlight))<br>@endif
            @if(filled($hero->heading_highlight))<span class="color-red">{{ $hero->heading_highlight }}</span>@endif
        </h1>
        @endif
        @if(html_filled($hero->sub ?? null))
        <div class="cinematic-hero__description mp-richtext">{!! $hero->sub !!}</div>
        @endif
        @foreach($heroParagraphs as $paragraph)
        <div class="mp-hero__desc mp-richtext">{!! $paragraph !!}</div>
        @endforeach
        @if($heroCtas->isNotEmpty())
        <div class="mp-hero__ctas">
            @foreach($heroCtas as $cta)
            <a href="{{ edu_href($cta['url']) }}" class="{{ edu_cta_class($cta['style'] ?? 'primary') }}">{{ $cta['label'] }}</a>
            @endforeach
        </div>
        @endif
        @if($routeSteps->isNotEmpty())
        <div class="mp-hero__route" aria-hidden="true">
            @foreach($routeSteps as $step)
            @if(! $loop->first)<span class="mp-hero__route-arrow">→</span>@endif
            <span>{{ $step['label'] }}</span>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
