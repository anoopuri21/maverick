{{-- §17 Final CTA — The Closing Archive / Closing Admissions Desk --}}
@php
  $plate = mlp_image_url($final->plate_image ?? null, ['w' => 1600, 'fallback' => 'assets/images/edutainment/cta-cinematic.jpg']);
  $showForm = (bool) ($final->show_form ?? true);
@endphp

@if(filled($final->heading) || $showForm)
<section class="mlp-final archive-closing" id="mlp-final" aria-labelledby="archive-closing-title">
  <div class="archive-closing__background" aria-hidden="true">
    <span class="archive-closing__wash"></span>
    @if($plate)
    <span class="archive-closing__image-layer archive-closing__image-layer--back"><img src="{{ $plate }}" alt="" width="960" height="720" loading="lazy" decoding="async"></span>
    <span class="archive-closing__image-layer archive-closing__image-layer--front"><img src="{{ $plate }}" alt="" width="960" height="720" loading="lazy" decoding="async"></span>
    @endif
  </div>

  <div class="archive-closing__frame container">
    <header class="archive-closing__intro">
      @if(filled($final->label))
      <p class="archive-closing__label">{{ $final->label }}</p>
      @endif
      @if(filled($final->heading))
      <h2 class="archive-closing__heading" id="archive-closing-title">{{ $final->heading }}</h2>
      @endif
      @if(filled($final->intro))
      <div class="archive-closing__intro-copy mlp-prose">{!! \App\Support\MlpProse::html($final->intro) !!}</div>
      @endif
      @if(filled($final->cta_primary_label) || filled($final->cta_secondary_label))
      <div class="archive-closing__actions">
        @if(filled($final->cta_primary_label))
        <a href="{{ edu_href($final->cta_primary_url) ?? '#mlp-enquire' }}" class="prospectus-cover__primary">{{ $final->cta_primary_label }} <span aria-hidden="true">↗</span></a>
        @endif
        @if(filled($final->cta_secondary_label))
        <a href="{{ edu_href($final->cta_secondary_url) ?? '#mlp-enquire' }}" class="pricing-cards__secondary">{{ $final->cta_secondary_label }}</a>
        @endif
      </div>
      @endif
    </header>

    @if($showForm)
    <aside class="archive-closing__form" aria-label="Final enquiry form">
      <div class="archive-closing__form-head">
        <span>{{ $final->form_title ?? 'Start your enquiry' }}</span>
        <i data-lucide="arrow-up-right" aria-hidden="true"></i>
      </div>
      <div class="mlp-form mlp-form--panel mlp-form--on-void">
        @include('pages.mba-masters-landing.partials.enquire-form')
      </div>
    </aside>
    @endif
  </div>
</section>
@endif
