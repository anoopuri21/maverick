@extends('layouts.app')

@section('title', ($csrSeo->meta_title ?? 'CSR & Community Impact | Maverick Business Academy London'))
@section('meta_description', ($csrSeo->meta_description ?? 'Creating Positive Impact Through Education, Community Engagement, and Social Responsibility.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $csrSeo])
@endpush

@if(!empty($csrSeo->custom_body_scripts))
@push('scripts')
    {!! $csrSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('css/pages/csr-community-impact.css') }}" />
    <link rel="stylesheet" href="{{ cached_asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
@php
    $csrSeo = $csrSeo ?? safe_settings(\App\Settings\CsrSeoSettings::class);
    $focus = $focus ?? safe_settings(\App\Settings\CsrFocusSettings::class);
    $gallery = $gallery ?? safe_settings(\App\Settings\CsrGallerySettings::class);
    $impact = $impact ?? safe_settings(\App\Settings\CsrImpactSettings::class);
    $scholarship = $scholarship ?? safe_settings(\App\Settings\CsrScholarshipSettings::class);
@endphp
<div class="csr-page">

    {{-- Abstract ambient/decorative ambient SVGs & washes in navy/red tints --}}
    <div class="csr-decorative-blob csr-decorative-blob--1"></div>
    <div class="csr-decorative-blob csr-decorative-blob--2"></div>
    <div class="csr-decorative-blob csr-decorative-blob--3"></div>

    {{-- ==========================================
         PAGE BANNER (HERO) - Cinematic Design
         ========================================== --}}
    <section class="cinematic-hero cinematic-hero--short" aria-label="CSR Hero">
        <div class="cinematic-hero__bg" aria-hidden="true">
            @if(filled($hero->background_image))
            <div class="cinematic-hero__bg-image" style="background-image: url('{{ $hero->background_image }}')"></div>
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
            @if(filled($hero->heading_line1 ?? null) || filled($hero->heading_italic ?? null))
            <h1 class="cinematic-hero__title">
                @if(filled($hero->heading_line1 ?? null)){{ $hero->heading_line1 }}@endif
                @if(filled($hero->heading_italic ?? null))<br><em>{{ $hero->heading_italic }}</em>@endif
            </h1>
            @endif
            @if(html_filled($hero->description ?? null))
            <p class="cinematic-hero__description">{!! rich_html($hero->description ?? null) !!}</p>
            @endif
            <div class="cinematic-hero__scroll-hint" aria-hidden="true">
                <span class="cinematic-hero__scroll-text">Scroll to explore</span>
                <span class="cinematic-hero__scroll-arrow" data-lucide="chevron-down"></span>
            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 1: OUR COMMITMENT
         ========================================== --}}
    <section class="csr-commitment section--light">
        <div class="container">
            <div class="csr-commitment__grid">
                <div class="csr-commitment__content">
                    @if(filled($commitment->label))
                    <div class="section-label"><span>{{ $commitment->label }}</span></div>
                    @endif
                    <h2 class="csr-section-heading">{{ $commitment->heading ?? '' }}<span class="csr-text-accent">{{ $commitment->heading_italic ?? '' }}</span></h2>
                    @if(html_filled($commitment->body ?? null))
                    <div class="csr-body-text">{!! rich_html($commitment->body ?? null) !!}</div>
                    @endif
                </div>
                <div class="csr-commitment__visual">
                    <div class="csr-commitment__image-container">
                        @if(filled($commitment->image_url))
                        <img src="{{ $commitment->image_url }}" alt="Students and educators community engagement" class="csr-commitment__img" loading="lazy">
                        @endif
                        <div class="csr-decorative-pattern"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 2: CSR FOCUS AREAS (Icon Cards)
         ========================================== --}}
    @if(!empty($focus->items))
    <section class="csr-focus section--light">
        <div class="container">
            <div class="csr-section-header">
                @if(filled($focus->label))
                <div class="section-label"><span>{{ $focus->label }}</span></div>
                @endif
                <h2 class="csr-section-heading">{{ $focus->heading }}<span class="csr-text-accent">{{ $focus->heading_italic }}</span></h2>
            </div>

            <div class="csr-focus__grid">
                @foreach(collect($focus->items ?? []) as $card)
                @if(!is_array($card)) @continue @endif
                <div class="csr-focus-card">
                    <div class="csr-focus-card__icon-wrapper">
                        <span class="csr-focus-card__icon" data-lucide="{{ $card['icon'] ?? 'circle' }}"></span>
                    </div>
                    <h3 class="csr-focus-card__title">{{ $card['title'] ?? '' }}</h3>
                    @if(collect($card['activities'] ?? [])->isNotEmpty())
                    <ul class="csr-focus-card__list">
                        @foreach(collect($card['activities'] ?? []) as $activity)
                        <li class="csr-focus-card__item">
                            <span class="csr-focus-card__item-dot"></span>
                            {{ is_array($activity) ? ($activity['activity'] ?? '') : $activity }}
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ==========================================
         SECTION 3: CSR ACTIVITIES GALLERY (⭐ MAIN SECTION)
         ========================================== --}}
    @if(!empty($gallery->items))
    <section class="csr-gallery section--light">
        <div class="container">
            <div class="csr-section-header">
                @if(filled($gallery->label))
                <div class="section-label"><span>{{ $gallery->label }}</span></div>
                @endif
                <h2 class="csr-section-heading">{{ $gallery->heading }}<span class="csr-text-accent">{{ $gallery->heading_italic }}</span></h2>
            </div>

            <div class="csr-gallery__grid">
                @foreach(collect($gallery->items ?? []) as $index => $item)
                @php
                    $cardClass = ($index % 4 === 0 || $index % 4 === 3) ? 'csr-gallery-card--large' : 'csr-gallery-card--medium';
                @endphp
                <div class="csr-gallery-card {{ $cardClass }}">
                    <div class="csr-gallery-card__image-wrapper">
                        @if(filled($item['image'] ?? null))
                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] ?? '' }}" class="csr-gallery-card__image" loading="lazy">
                        @endif
                    </div>
                    <div class="csr-gallery-card__content">
                        <h3 class="csr-gallery-card__title">{{ $item['title'] ?? '' }}</h3>
                        @if(html_filled($item['description'] ?? null))
                        <div class="csr-gallery-card__desc">{!! rich_html($item['description'] ?? null) !!}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ==========================================
         SECTION 4: IMPACT NUMBERS (Counters)
         ========================================== --}}
    @if(!empty($impact->items))
    <section class="csr-impact section--dark">
        <div class="csr-impact__pattern"></div>
        <div class="container">
            <div class="csr-impact__grid">
                @foreach(collect($impact->items ?? []) as $counter)
                @if(! is_array($counter) || (! filled($counter['value'] ?? null) && ! filled($counter['label'] ?? null))) @continue @endif
                @php $impactTarget = is_numeric($counter['value'] ?? null) ? $counter['value'] : 0; @endphp
                <div class="csr-impact-card">
                    <div class="csr-impact-card__number-wrapper">
                        <span class="csr-impact-card__number" data-target="{{ $impactTarget }}">0</span><span class="csr-impact-card__suffix">{{ $counter['suffix'] ?? '' }}</span>
                    </div>
                    <div class="csr-impact-card__divider"></div>
                    <div class="csr-impact-card__label">{{ $counter['label'] ?? '' }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ==========================================
         SECTION 5: SCHOLARSHIP & EDUCATIONAL SUPPORT
         ========================================== --}}
    <section class="csr-scholarship section--light">
        <div class="container">
            <div class="csr-scholarship__split">
                <div class="csr-scholarship__intro">
                    @if(filled($scholarship->label))
                    <div class="section-label"><span>{{ $scholarship->label }}</span></div>
                    @endif
                    <h2 class="csr-section-heading">{{ $scholarship->heading }}<span class="csr-text-accent">{{ $scholarship->heading_italic }}</span></h2>
                    @if(html_filled($scholarship->body ?? null))
                    <div class="csr-body-text">{!! rich_html($scholarship->body ?? null) !!}</div>
                    @endif
                </div>

                @if(!empty($scholarship->items))
                <div class="csr-scholarship__checklist">
                    <div class="csr-checklist-grid">
                        @foreach(collect($scholarship->items ?? []) as $item)
                        <div class="csr-checklist-card">
                            <div class="csr-checklist-card__icon-wrapper">
                                <span class="csr-checklist-card__icon" data-lucide="check"></span>
                            </div>
                            <span class="csr-checklist-card__text">{{ is_array($item) ? ($item['item'] ?? '') : $item }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ==========================================
         FINAL CTA
         ========================================== --}}
    @include('sections.final-cta')

</div>
@endsection

@push('scripts')
    <script src="{{ cached_asset('assets/js/pages/csr-community-impact.js') }}" defer></script>
@endpush
