@extends('layouts.app')

@section('title', ($globalPartnersSeo->meta_title ?? 'Global University Partners - Maverick Business Academy'))
@section('meta_description', ($globalPartnersSeo->meta_description ?? 'Explore Maverick Business Academy\'s global university partnerships.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $globalPartnersSeo])
@endpush

@if(!empty($globalPartnersSeo->custom_body_scripts))
@push('scripts')
    {!! $globalPartnersSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('css/pages/global-university-partners.css') }}">
    <link rel="stylesheet" href="{{ cached_asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
<div class="page-gup gup">

@php
    $partnerUniversities = collect($partnerUniversities ?? []);
    $galleryItems = collect($galleryItems ?? []);
    $galleryCategories = collect($galleryCategories ?? []);
    $whyPartnerships = $whyPartnerships ?? safe_settings(\App\Settings\GlobalPartnersWhySettings::class);
    $benefits = $benefits ?? safe_settings(\App\Settings\GlobalPartnersBenefitsSettings::class);
    $whyItems = collect(settings_array($whyPartnerships->items ?? []));
    $benefitItems = collect(settings_array($benefits->items ?? []));
@endphp

{{-- ═══════════════════════════════════════════
     1. HERO SECTION (Cinematic Design)
═══════════════════════════════════════════ --}}
@if(filled($hero->heading_line1) || filled($hero->heading_italic))
<section class="cinematic-hero" aria-label="Global University Partners Hero">
    <div class="cinematic-hero__bg" aria-hidden="true">
        @if($heroBg = media_url($hero->background_image))
        <div class="cinematic-hero__bg-image" style="background-image: url('{{ $heroBg }}')"></div>
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
        <h1 class="cinematic-hero__title">
            @if(filled($hero->heading_line1)){{ $hero->heading_line1 }}<br>@endif
            @if(filled($hero->heading_italic))<em>{{ $hero->heading_italic }}</em>@endif
        </h1>
        @if(filled($hero->description))
        <p class="cinematic-hero__description">{!! rich_html($hero->description ?? null) !!}</p>
        @endif
        <div class="cinematic-hero__scroll-hint" aria-hidden="true">
            <span class="cinematic-hero__scroll-text">{{ $hero->scroll_hint ?? 'Scroll to explore' }}</span>
            <span class="cinematic-hero__scroll-arrow" data-lucide="chevron-down"></span>
        </div>
    </div>
</section>
@endif


{{-- ═══════════════════════════════════════════
     2. PARTNERSHIP OVERVIEW (Split with Stats)
═══════════════════════════════════════════ --}}
@if(filled($overview->heading) || filled($overview->paragraph))
<section class="gup-overview section-wrapper">
    <div class="container">
        <div class="gup-overview__grid">
            <div class="gup-overview__main">
                @if(filled($overview->tag))
                <span class="section-label gup-overview__label">{{ $overview->tag }}</span>
                @endif
                <h2 class="gup-overview__heading">
                    {{ $overview->heading }}
                    @if(filled($overview->heading_italic))<em>{{ $overview->heading_italic }}</em>@endif
                </h2>
                @if(filled($overview->paragraph))
                <p class="gup-overview__paragraph">{!! rich_html($overview->paragraph ?? null) !!}</p>
                @endif

                @if($overviewImg = media_url($overview->image))
                <div class="gup-overview__image-wrapper">
                    <img src="{{ $overviewImg }}"
                         alt="{{ $overview->image_alt ?? 'Partnership' }}"
                         class="gup-overview__image"
                         loading="lazy">
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

@include('sections.university-partners')


{{-- ═══════════════════════════════════════════
     PARTNER UNIVERSITIES — 3D Cards with Aura
═══════════════════════════════════════════ --}}
@if($partnerUniversities->isNotEmpty())
<section class="gup-partner-cards section-wrapper" data-testid="gup-partner-cards" aria-label="Partner Universities">
    <div class="container">

        @if(filled($cards->label) || filled($cards->heading))
        <div class="section-heading-block">
            @if(filled($cards->label))
            <span class="section-label"><span>{{ $cards->label }}</span></span>
            @endif
            <h2 class="section-heading">
                {{ $cards->heading }}@if(filled($cards->heading_italic))<em>{{ $cards->heading_italic }}</em>@endif
            </h2>
            @if(filled($cards->subheading))
            <p class="section-subheading">{{ $cards->subheading }}</p>
            @endif
        </div>
        @endif

        <div class="gup-partner-cards__grid">
            @foreach($partnerUniversities as $uni)
            <article class="gup-uni-card" data-testid="uni-card-{{ $uni->slug }}">
                <div class="gup-uni-card__aura" aria-hidden="true"></div>
                <div class="gup-uni-card__border" aria-hidden="true"></div>
                <div class="gup-uni-card__inner">
                    <div class="gup-uni-card__logo-wrap">
                        @if($uni->logo)
                        <img
                            src="{{ $uni->logo }}"
                            alt="{{ $uni->name }} logo"
                            class="gup-uni-card__logo"
                            loading="lazy"
                            width="120"
                            height="60"
                            onerror="this.style.display='none'; this.nextElementSibling.hidden=false;"
                        >
                        @endif
                        <span class="gup-uni-card__logo-fallback" @if($uni->logo) hidden @endif aria-hidden="true">{{ $uni->display_abbreviation }}</span>
                    </div>

                    <h3 class="gup-uni-card__name">{{ $uni->name }}</h3>

                    <p class="gup-uni-card__country">
                        @if(filled($uni->flag_emoji))
                        <span class="gup-uni-card__flag" aria-hidden="true">{{ $uni->flag_emoji }}</span>
                        @endif
                        {{ $uni->country }}
                    </p>

                    @if(filled($uni->recognition))
                    <div class="gup-uni-card__recognition">
                        <span class="gup-uni-card__recognition-label">{{ $cards->recognition_label ?? 'Recognition' }}</span>
                        <p class="gup-uni-card__recognition-text">{!! rich_html($uni->recognition ?? null) !!}</p>
                    </div>
                    @endif

                    <a href="{{ $uni->cta_link }}" class="gup-uni-card__cta btn btn--primary" data-testid="uni-cta-{{ $uni->slug }}">
                        {{ $uni->cta_label ?: ($cards->cta_label ?? 'Explore Programs') }}
                    </a>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>
@endif


{{-- ═══════════════════════════════════════════
     3. WHY OUR PARTNERSHIPS MATTER (Sticky Left)
═══════════════════════════════════════════ --}}
@if($whyItems->isNotEmpty() || filled($whyPartnerships->heading))
<section class="gup-why section-wrapper">
    <div class="container">
        <div class="gup-why__grid">

            <div class="gup-why__sticky">
                <div class="gup-why__sticky-inner">
                    @if(filled($whyPartnerships->tag))
                    <span class="section-label">{{ $whyPartnerships->tag }}</span>
                    @endif
                    <h2 class="gup-why__heading">
                        {{ $whyPartnerships->heading }}
                        @if(filled($whyPartnerships->heading_italic))<em>{{ $whyPartnerships->heading_italic }}</em>@endif
                    </h2>
                    @if(filled($whyPartnerships->quote))
                    <blockquote class="gup-why__quote">
                        {!! rich_html($whyPartnerships->quote ?? null) !!}
                    </blockquote>
                    @endif
                </div>
            </div>

            @if($whyItems->isNotEmpty())
            <div class="gup-why__cards">
                @foreach($whyItems as $index => $item)
                @if(filled($item['title'] ?? null))
                <article class="gup-why-card">
                    @if(filled($item['icon'] ?? null))
                    <div class="gup-why-card__icon">
                        <span data-lucide="{{ $item['icon'] }}"></span>
                    </div>
                    @endif
                    <div class="gup-why-card__content">
                        <h3 class="gup-why-card__title">{{ $item['title'] }}</h3>
                        @if(filled($item['description'] ?? null))
                        <p class="gup-why-card__description">{!! rich_html($item['description'] ?? null) !!}</p>
                        @endif
                    </div>
                    <span class="gup-why-card__number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                </article>
                @endif
                @endforeach
            </div>
            @endif

        </div>
    </div>
</section>
@endif


{{-- ═══════════════════════════════════════════
     4. STUDENT BENEFITS (Checklist + Image Collage)
═══════════════════════════════════════════ --}}
@if($benefitItems->isNotEmpty() || filled($benefits->heading))
<section class="gup-benefits section-wrapper section--light">
    <div class="container">
        <div class="gup-benefits__grid">

            <div class="gup-benefits__content">
                @if(filled($benefits->tag))
                <span class="section-label gup-benefits__label">{{ $benefits->tag }}</span>
                @endif
                <h2 class="gup-benefits__heading">
                    {{ $benefits->heading }}
                    @if(filled($benefits->heading_italic))<em>{{ $benefits->heading_italic }}</em>@endif
                </h2>

                @if($benefitItems->isNotEmpty())
                <ul class="gup-benefits__list">
                    @foreach($benefitItems as $item)
                    @if(filled($item['title'] ?? null))
                    <li class="gup-benefit {{ !empty($item['highlighted']) ? 'gup-benefit--highlighted' : '' }}">
                        <span class="gup-benefit__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                        <div class="gup-benefit__content">
                            <h4 class="gup-benefit__title">{{ $item['title'] }}</h4>
                            @if(filled($item['description'] ?? null))
                            <p class="gup-benefit__description">{!! rich_html($item['description'] ?? null) !!}</p>
                            @endif
                        </div>
                    </li>
                    @endif
                    @endforeach
                </ul>
                @endif
            </div>

            @php
                $benefitsMain = media_url($benefits->main_image);
                $benefitsSecondary = media_url($benefits->secondary_image);
            @endphp
            @if($benefitsMain || $benefitsSecondary || filled($benefits->stat_number) || filled($benefits->stat_label))
            <div class="gup-benefits__visual">
                <div class="gup-benefits__dots"></div>

                @if($benefitsMain)
                <div class="gup-benefits__main-image">
                    <img src="{{ $benefitsMain }}"
                         alt="{{ $benefits->main_image_alt ?? 'Students' }}"
                         loading="lazy">
                </div>
                @endif

                @if($benefitsSecondary)
                <div class="gup-benefits__secondary-image">
                    <img src="{{ $benefitsSecondary }}"
                         alt="{{ $benefits->secondary_image_alt ?? 'Students walking' }}"
                         loading="lazy">
                </div>
                @endif

                @if(filled($benefits->stat_number) || filled($benefits->stat_label))
                <div class="gup-benefits__floating-stat">
                    @if(filled($benefits->stat_number))
                    <div class="gup-benefits__floating-number">{{ $benefits->stat_number }}</div>
                    @endif
                    @if(filled($benefits->stat_label))
                    <div class="gup-benefits__floating-label">{{ $benefits->stat_label }}</div>
                    @endif
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>
</section>
@endif


{{-- ═══════════════════════════════════════════
     5. PARTNERSHIP JOURNEY (Gallery)
═══════════════════════════════════════════ --}}
@if($galleryItems->isNotEmpty())
<section class="gup-journey section-wrapper">
    <div class="container">

        @if(filled($journey->label) || filled($journey->heading))
        <div class="section-heading-block">
            @if(filled($journey->label))
            <span class="section-label">{{ $journey->label }}</span>
            @endif
            <h2 class="section-heading">
                {{ $journey->heading }}@if(filled($journey->heading_italic))<em>{{ $journey->heading_italic }}</em>@endif
            </h2>
            @if(filled($journey->subheading))
            <p class="section-subheading">{{ $journey->subheading }}</p>
            @endif
        </div>
        @endif

        @if($galleryCategories->count() > 1)
        <div class="gup-journey__filters">
            @foreach($galleryCategories as $category)
            <button class="gup-filter {{ $category['slug'] === 'all' ? 'is-active' : '' }}"
                    data-filter="{{ $category['slug'] }}">
                {{ $category['name'] }}
                <span class="gup-filter__count">{{ $category['count'] }}</span>
            </button>
            @endforeach
        </div>
        @endif

        <div class="gup-journey__gallery">
            @foreach($galleryItems as $item)
            @if($item->image)
            <div class="gup-gallery-item gup-gallery-item--{{ $item->size }}"
                 data-category="{{ $item->category }}">
                <img src="{{ $item->image }}"
                     alt="{{ $item->title ?? $item->badge }}"
                     loading="lazy"
                     class="gup-gallery-item__image">

                <div class="gup-gallery-item__top">
                    <span class="gup-gallery-item__badge">{{ $item->badge }}</span>
                    @if($item->formatted_date)
                    <span class="gup-gallery-item__date">{{ $item->formatted_date }}</span>
                    @endif
                </div>

                @if($item->title || $item->caption)
                <div class="gup-gallery-item__bottom">
                    @if($item->title)
                    <h3 class="gup-gallery-item__title">{{ $item->title }}</h3>
                    @endif
                    @if($item->caption)
                    <p class="gup-gallery-item__caption">{{ $item->caption }}</p>
                    @endif
                </div>
                @endif
            </div>
            @endif
            @endforeach
        </div>

    </div>
</section>
@endif

</div>

@include('sections.final-cta')
@endsection

@push('scripts')
    <script src="{{ cached_asset('assets/js/pages/global-university-partners.js') }}" defer></script>
@endpush
