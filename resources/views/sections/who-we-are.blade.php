@php
    $wwaImage = media_url($whoWeAre->image_url ?? null);
    $wwaCtaHref = edu_href($whoWeAre->cta_url ?? null);
    $wwaStats = collect([
        ['value' => $whoWeAre->stat1_value ?? null, 'suffix' => $whoWeAre->stat1_suffix ?? null, 'label' => $whoWeAre->stat1_label ?? null],
        ['value' => $whoWeAre->stat2_value ?? null, 'suffix' => $whoWeAre->stat2_suffix ?? null, 'label' => $whoWeAre->stat2_label ?? null],
    ])->filter(fn ($stat) => filled($stat['value']) || filled($stat['label']));
@endphp
<section id="who-we-are" class="wwa section--light section-wrapper" aria-label="Who we are">
  <div class="container">
    <div class="wwa__grid grid-2">
      <div class="wwa__content">
        <div class="section-label">
          <span>Who We Are</span>
        </div>

        @if(filled($whoWeAre->heading_line1 ?? null) || filled($whoWeAre->heading_line2 ?? null))
        <h2 class="wwa__heading section-title">
          @if(filled($whoWeAre->heading_line1 ?? null))
          <span class="wwa__heading-line">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">{{ $whoWeAre->heading_line1 }}</span>
            </span>
          </span>
          @endif
          @if(filled($whoWeAre->heading_line2 ?? null))
          <span class="wwa__heading-line hwdi__heading-line--red">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">{{ $whoWeAre->heading_line2 }}</span>
            </span>
          </span>
          @endif
        </h2>
        @endif

        @if(filled($whoWeAre->body_text ?? null))
        <p class="wwa__body body-text fade-up">
          {{ $whoWeAre->body_text }}
        </p>
        @endif

        @if($wwaStats->isNotEmpty())
        <div class="wwa__stats fade-up">
          @foreach($wwaStats as $stat)
          <div class="wwa__stat">
            @if(filled($stat['value']))
            <span class="wwa__stat-value">{{ $stat['value'] }}</span>
            @endif
            @if(filled($stat['suffix']))
            <span class="wwa__stat-suffix accent-text">{{ $stat['suffix'] }}</span>
            @endif
            <span class="wwa__stat-divider"></span>
            @if(filled($stat['label']))
            <span class="wwa__stat-label">{{ $stat['label'] }}</span>
            @endif
          </div>
          @endforeach
        </div>
        @endif

        @if($wwaCtaHref && filled($whoWeAre->cta_text ?? null))
        <a href="{{ $wwaCtaHref }}" class="wwa__cta btn btn--ghost fade-up">
          {{ $whoWeAre->cta_text }}
        </a>
        @endif
      </div>

      @if($wwaImage)
      <div class="wwa__image-col">
        <div class="wwa__image-wrapper">
          <img src="{{ $wwaImage }}" alt="Maverick Business Academy Team"
            class="wwa__image" loading="lazy" decoding="async" />
          <div class="wwa__image-accent" aria-hidden="true"></div>
        </div>
      </div>
      @endif
    </div>
  </div>
</section>
