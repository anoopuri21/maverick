{{-- ===== S1: HERO ===== --}}
@php
    $heroStats = collect($hero->stats ?? [])->filter(fn ($stat) => filled($stat['value'] ?? null) || filled($stat['label'] ?? null));
    $heroCtas = collect($hero->ctas ?? [])->filter(fn ($cta) => filled($cta['label'] ?? null) && filled($cta['url'] ?? null));
    $showHero = filled($hero->tag ?? null)
        || filled($hero->headline_line1 ?? null)
        || filled($hero->headline_line2 ?? null)
        || filled($hero->headline_italic ?? null)
        || html_filled($hero->sub ?? null)
        || filled($hero->background_image ?? null)
        || filled($hero->visual_image ?? null)
        || $heroStats->isNotEmpty()
        || $heroCtas->isNotEmpty();
@endphp
@if($showHero)
<section id="dmba-hero" class="dmba-hero" aria-label="Dual MBA Programme Hero" data-testid="dmba-hero-section">
  <div class="dmba-hero__bg" aria-hidden="true">
    @if(filled($hero->background_image))
    <img
      src="{{ media_url($hero->background_image) }}"
      alt="{{ $hero->background_image_alt ?? '' }}"
      class="dmba-hero__bg-image"
      loading="eager"
    />
    @endif
    <div class="dmba-hero__overlay"></div>
    <div class="dmba-hero__grain"></div>
  </div>

  <div class="dmba-hero__content">
    <div class="container">
      <div class="dmba-hero__inner">
        <div class="dmba-hero__text">
          @if(filled($hero->tag))
          <span class="dmba-hero__tag" data-dmba-hero="tag" data-testid="dmba-hero-tag">{{ $hero->tag }}</span>
          @endif

          @if(filled($hero->headline_line1) || filled($hero->headline_line2) || filled($hero->headline_italic))
          <h1 class="dmba-hero__headline" data-dmba-hero="headline" data-testid="dmba-hero-headline">
            @if(filled($hero->headline_line1)){{ $hero->headline_line1 }}<br>@endif
            @if(filled($hero->headline_line2)){{ $hero->headline_line2 }}<br>@endif
            @if(filled($hero->headline_italic))<em>{{ $hero->headline_italic }}</em>@endif
          </h1>
          @endif

          @if(html_filled($hero->sub ?? null))
          <div class="dmba-hero__sub dmba-richtext" data-dmba-hero="sub" data-testid="dmba-hero-sub">
            {!! $hero->sub !!}
          </div>
          @endif

          @include('pages.dual-mba.credentials', [
              'enabled' => $hero->credentials_enabled ?? false,
              'label' => $hero->credentials_label ?? null,
              'items' => $hero->credentials ?? [],
              'variant' => 'hero-strip',
          ])

          @if($heroStats->isNotEmpty())
          <div class="dmba-hero__stats" data-dmba-hero="stats" data-testid="dmba-hero-stats">
            @foreach($heroStats as $stat)
            <div class="dmba-hero__stat">
              @if(filled($stat['value'] ?? null))
              <span class="dmba-hero__stat-value">{{ $stat['value'] }}</span>
              @endif
              @if(filled($stat['label'] ?? null))
              <span class="dmba-hero__stat-label">{{ $stat['label'] }}</span>
              @endif
            </div>
            @endforeach
          </div>
          @endif

          @if($heroCtas->isNotEmpty())
          <div class="dmba-hero__ctas" data-dmba-hero="ctas" data-testid="dmba-hero-ctas">
            @foreach($heroCtas as $cta)
              <a href="{{ edu_href($cta['url']) }}" class="{{ edu_cta_class($cta['style'] ?? 'primary') }}" @if($loop->first) data-testid="dmba-apply-btn" @elseif($loop->iteration === 2) data-testid="dmba-brochure-btn" @endif>{{ $cta['label'] }}</a>
            @endforeach
          </div>
          @endif
        </div>

        @if(filled($hero->visual_image) || filled($hero->badge_title) || filled($hero->badge_sub))
        <div class="dmba-hero__visual" data-dmba-hero="visual">
          @if(filled($hero->visual_image))
          <div class="dmba-hero__image-frame">
            <img
              src="{{ media_url($hero->visual_image) }}"
              alt="{{ $hero->visual_image_alt ?? '' }}"
              loading="eager"
            />
          </div>
          <div class="dmba-hero__image-accent" aria-hidden="true"></div>
          @endif
          @if(filled($hero->badge_title) || filled($hero->badge_sub))
          <div class="dmba-hero__image-badge">
            @if(filled($hero->badge_title))
            <span class="dmba-hero__image-badge-title">{{ $hero->badge_title }}</span>
            @endif
            @if(filled($hero->badge_sub))
            <span class="dmba-hero__image-badge-sub">{{ $hero->badge_sub }}</span>
            @endif
          </div>
          @endif
        </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endif
