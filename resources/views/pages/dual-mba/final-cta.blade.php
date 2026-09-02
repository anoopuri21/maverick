{{-- ===== S11: FINAL CTA ===== --}}
@php
    $ctaButtons = collect($finalCta->ctas ?? [])->filter(fn ($cta) => filled($cta['label'] ?? null) && filled($cta['url'] ?? null));
    $showBrochure = filled($finalCta->brochure_label ?? null)
        && filled($finalCta->brochure_url ?? null)
        && $finalCta->brochure_url !== '#';
    $finalCtaBackgroundUrl = settings_media_url($finalCta, 'background_image');
    $showFinalCta = filled($finalCta->heading ?? null)
        || filled($finalCta->heading_line2 ?? null)
        || html_filled($finalCta->sub ?? null)
        || filled($finalCtaBackgroundUrl)
        || $ctaButtons->isNotEmpty()
        || $showBrochure;
@endphp
@if($showFinalCta)
<section class="dmba-cta" aria-label="Apply for Dual MBA" data-testid="dmba-cta-section">
  <div class="dmba-cta__bg" aria-hidden="true">
    @if(filled($finalCtaBackgroundUrl))
    <img
      src="{{ $finalCtaBackgroundUrl }}"
      alt=""
      class="dmba-cta__bg-image"
      loading="lazy"
    />
    @endif
    <div class="dmba-cta__overlay"></div>
  </div>

  <div class="container">
    <div class="dmba-cta__content">
      @if(filled($finalCta->heading) || filled($finalCta->heading_line2))
      <h2 class="dmba-cta__heading" data-testid="dmba-cta-heading">
        {{ $finalCta->heading }}
        @if(filled($finalCta->heading) && filled($finalCta->heading_line2))<br>@endif
        {{ $finalCta->heading_line2 }}
      </h2>
      @endif

      @if(html_filled($finalCta->sub ?? null))
      <div class="dmba-cta__sub dmba-richtext">{!! $finalCta->sub !!}</div>
      @endif

      @if($ctaButtons->isNotEmpty())
      <div class="dmba-cta__buttons" data-testid="dmba-cta-buttons">
        @foreach($ctaButtons as $cta)
          <a href="{{ edu_href($cta['url']) }}" class="{{ edu_cta_class($cta['style'] ?? 'primary') }}" @if($loop->first) data-testid="dmba-cta-apply-btn" @elseif($loop->iteration === 2) data-testid="dmba-cta-consult-btn" @endif>{{ $cta['label'] }}</a>
        @endforeach
      </div>
      @endif

      @if($showBrochure)
      <a href="{{ edu_href($finalCta->brochure_url) }}" class="dmba-cta__link" data-testid="dmba-cta-brochure-link">{{ $finalCta->brochure_label }}</a>
      @endif
    </div>
  </div>
</section>
@endif
