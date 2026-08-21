{{-- ===== HERO — Cinematic World Tour ===== --}}
@php
    $showHero = filled($hero->tag ?? null)
        || filled($hero->heading ?? null)
        || filled($hero->heading_italic ?? null)
        || html_filled($hero->description ?? null)
        || filled($hero->background_image ?? null);
@endphp
@if($showHero)
<section id="edu-hero" class="edu-hero" aria-label="Edutainment Hero">
  <div class="edu-hero__bg" aria-hidden="true">
    @if(filled($hero->background_image))
    <div class="edu-hero__bg-image" style="background-image: url('{{ media_url($hero->background_image) }}')"></div>
    @endif
    <div class="edu-hero__gradient"></div>
    <div class="edu-hero__noise"></div>

    <svg class="edu-hero__route" viewBox="0 0 800 400" fill="none" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
      <path class="edu-hero__route-line" d="M 40 340 Q 220 120 420 200 T 760 90" stroke="rgba(255,255,255,0.22)" stroke-width="1.5" stroke-dasharray="2 8" stroke-linecap="round"/>
      <circle class="edu-hero__route-dot" r="5" fill="#ffffff"/>
      <circle class="edu-hero__route-pulse" r="5" fill="none" stroke="#ffffff" stroke-width="1.5"/>
      <circle class="edu-hero__route-node" cx="40" cy="340" r="4" fill="rgba(178,2,2,0.9)"/>
      <circle class="edu-hero__route-node" cx="420" cy="200" r="4" fill="rgba(255,255,255,0.5)"/>
      <circle class="edu-hero__route-node" cx="760" cy="90" r="4" fill="rgba(178,2,2,0.9)"/>
    </svg>

    <div class="edu-hero__shapes">
      <svg class="edu-hero__shape edu-hero__shape--1" viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.14)" stroke-width="1"/></svg>
      <svg class="edu-hero__shape edu-hero__shape--2" viewBox="0 0 300 300" fill="none"><circle cx="150" cy="150" r="120" stroke="rgba(178,2,2,0.22)" stroke-width="1"/></svg>
      <svg class="edu-hero__shape edu-hero__shape--3" viewBox="0 0 100 100" fill="none"><rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.14)" stroke-width="1" transform="rotate(20 50 50)"/></svg>
    </div>

    <div class="edu-hero__particles">
      @for($i = 0; $i < 6; $i++)
        <div class="edu-hero__particle"></div>
      @endfor
    </div>
  </div>

  <div class="edu-hero__content">
    <div class="container">
      @if(filled($hero->tag))
      <span class="edu-hero__tag fade-up">
        <span class="edu-hero__tag-line"></span>
        {{ $hero->tag }}
      </span>
      @endif

      @if(filled($hero->heading) || filled($hero->heading_italic))
      <h1 class="edu-hero__title fade-up">
        @if(filled($hero->heading)){{ $hero->heading }}@endif
        @if(filled($hero->heading) && filled($hero->heading_italic))<br>@endif
        @if(filled($hero->heading_italic))<em>{{ $hero->heading_italic }}</em>@endif
      </h1>
      @endif

      @if(html_filled($hero->description ?? null))
      <div class="edu-hero__shortdesc fade-up edu-richtext">
        {!! $hero->description !!}
      </div>
      @endif
    </div>
  </div>

  <div class="edu-hero__scrollcue" aria-hidden="true">
    <span></span>
  </div>
</section>
@endif
