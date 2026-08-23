{{-- §17 Final CTA — full-bleed plate + optional mini enquiry (shared enquire route) --}}
@php
  $plate = mlp_image_url($final->plate_image ?? null, ['w' => 1920, 'fallback' => 'assets/images/edutainment/cta-cinematic.jpg']);
  $showForm = (bool) ($final->show_form ?? true);
@endphp
@if(filled($final->heading) || $showForm)
<section class="mlp-final" id="mlp-final" aria-label="Final call to action">
  <div class="mlp-final__stage" aria-hidden="true">
    <img class="mlp-final__bg" src="{{ $plate }}" alt="" width="1920" height="1080" loading="lazy" decoding="async">
    <span class="mlp-final__veil"></span>
  </div>

  <div class="container mlp-final__inner">
    <div class="mlp-final__grid">
      <div class="mlp-final__copy" data-mlp-reveal="final-copy">
        <div class="mlp-final__meta">
          @if(filled($final->label))
          <p class="mlp-final__label mlp-meta">{{ $final->label }}</p>
          @endif
        </div>
        @if(filled($final->heading))
        <h2 class="mlp-final__heading mlp-headline">{{ $final->heading }}</h2>
        @endif
        @if(filled($final->intro))
        <div class="mlp-final__intro mlp-lede mlp-prose mlp-prose--on-dark">{!! \App\Support\MlpProse::html($final->intro) !!}</div>
        @endif

        @if(filled($final->cta_primary_label) || filled($final->cta_secondary_label))
        <div class="mlp-final__ctas">
          @if(filled($final->cta_primary_label))
          <a href="{{ edu_href($final->cta_primary_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--primary">{{ $final->cta_primary_label }}</a>
          @endif
          @if(filled($final->cta_secondary_label))
          <a href="{{ edu_href($final->cta_secondary_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--ghost">{{ $final->cta_secondary_label }}</a>
          @endif
        </div>
        @endif
      </div>

      @if($showForm)
      <aside class="mlp-final__form-wrap" data-mlp-reveal="final-form" aria-label="Final enquiry form">
        <div class="mlp-form mlp-form--panel mlp-form--on-void">
          <header class="mlp-form__head">
            <div>
              <h3 class="mlp-form__title">{{ $final->form_title ?? 'Start your enquiry' }}</h3>
              <p class="mlp-form__hint">Same admissions team — eligibility, fees &amp; start dates.</p>
            </div>
          </header>

          @include('pages.mba-masters-landing.partials.enquire-form')
        </div>
      </aside>
      @endif
    </div>
  </div>
</section>
@endif
