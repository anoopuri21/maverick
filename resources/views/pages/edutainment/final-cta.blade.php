{{-- ===== S12: FINAL CTA ===== --}}
@php
    $ctaButtons = collect($finalCta->ctas ?? [])->filter(fn ($cta) => filled($cta['label'] ?? null) && filled($cta['url'] ?? null));
    $showWhatsapp = ($finalCta->show_whatsapp ?? false)
        && filled($finalCta->whatsapp_label ?? null)
        && filled($site->whatsapp_number ?? null);
    $showFinalCta = filled($finalCta->heading ?? null)
        || filled($finalCta->heading_italic ?? null)
        || html_filled($finalCta->body ?? null)
        || filled($finalCta->emphasis ?? null)
        || filled($finalCta->background_image ?? null)
        || $ctaButtons->isNotEmpty()
        || $showWhatsapp;
@endphp
@if($showFinalCta)
<section id="edu-cta" class="edu-cta" aria-label="Transform a Student Trip">
  <div class="edu-cta__bg" aria-hidden="true">
    @if(filled($finalCta->background_image))
    <div class="edu-cta__bg-image" style="background-image: url('{{ media_url($finalCta->background_image) }}')"></div>
    @endif
    <div class="edu-cta__overlay"></div>
  </div>

  <div class="container">
    <div class="edu-cta__content">
      @if(filled($finalCta->heading) || filled($finalCta->heading_italic))
      <h2 class="edu-cta__heading fade-up">
        @if(filled($finalCta->heading)){{ $finalCta->heading }}@endif
        @if(filled($finalCta->heading) && filled($finalCta->heading_italic))<br>@endif
        @if(filled($finalCta->heading_italic))<em>{{ $finalCta->heading_italic }}</em>@endif
      </h2>
      @endif

      @if(html_filled($finalCta->body ?? null))
      <div class="edu-richtext fade-up">{!! rich_html($finalCta->body ?? null) !!}</div>
      @endif

      @if(filled($finalCta->emphasis))
      <p class="edu-cta__emphasis fade-up">
        <strong>{{ $finalCta->emphasis }}</strong>
      </p>
      @endif

      @if($ctaButtons->isNotEmpty() || $showWhatsapp)
      <div class="edu-cta__buttons fade-up">
        @foreach($ctaButtons as $cta)
          <a href="{{ edu_href($cta['url']) }}" class="{{ edu_cta_class($cta['style'] ?? 'primary') }}">{{ $cta['label'] }}</a>
        @endforeach
        @if($showWhatsapp)
        <a href="https://wa.me/{{ $site->whatsapp_number }}" class="btn btn--outline" target="_blank" rel="noopener noreferrer">{{ $finalCta->whatsapp_label }}</a>
        @endif
      </div>
      @endif
    </div>
  </div>
</section>
@endif
