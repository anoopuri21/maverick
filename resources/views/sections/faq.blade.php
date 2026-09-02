@php
    $homepageFaqs = collect($homepageFaqs ?? []);
    $homepageChrome = $homepageChrome ?? null;
    $faqImage = settings_media_url($homepageChrome, 'faq_image_url') ?: cached_asset('assets/images/homepage/mba-management.jpg');
@endphp
@if($homepageFaqs->isNotEmpty())
<section id="faq" class="faq section-wrapper section--light" aria-label="Frequently Asked Questions">

  <div class="container">

    <div class="faq__grid">

      <div class="faq__intro">

        <div class="section-label">
          <span>{{ $homepageChrome->faq_label ?? '' }}</span>
        </div>

        <h2 class="faq__heading section-title">
          <span class="faq__heading-line">
            <span>
              {{ $homepageChrome->faq_heading_line1 ?? '' }}

            </span>
          </span>

          <span class="faq__heading-line hwdi__heading-line--red">
            <span>
              {{ $homepageChrome->faq_heading_line2 ?? '' }}
            </span>
          </span>
        </h2>

        <p class="faq__subtitle body-text">
          {{ $homepageChrome->faq_subtitle ?? '' }}
        </p>
        <div>
          <img
            src="{{ $faqImage }}"
            class="faq-img" alt="{{ $homepageChrome->faq_heading_line1 ?? 'Frequently Asked Questions' }}" loading="lazy" decoding="async" />
        </div>

      </div>

      <div class="faq__accordion">
        @foreach($homepageFaqs as $index => $faq)
          <div class="faq__item {{ $index === 0 ? 'active' : '' }}">
            <button class="faq__question">
              <span>{{ $faq->question }}</span>
              <span class="faq__icon">+</span>
            </button>
            <div class="faq__answer">
              {!! rich_html($faq->answer ?? null) !!}
            </div>
          </div>
        @endforeach
      </div>

    </div>

  </div>

</section>
@endif
