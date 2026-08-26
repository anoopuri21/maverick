@php $homepageFaqs = collect($homepageFaqs ?? []); @endphp
@if($homepageFaqs->isNotEmpty())
<section id="faq" class="faq section-wrapper section--light" aria-label="Frequently Asked Questions">

  <div class="container">

    <div class="faq__grid">

      <div class="faq__intro">

        <div class="section-label">
          <span>FAQ</span>
        </div>

        <h2 class="faq__heading section-title">
          <span class="faq__heading-line">
            <span>
              Your Questions

            </span>
          </span>

          <span class="faq__heading-line hwdi__heading-line--red">
            <span>
              Answered
            </span>
          </span>
        </h2>

        <p class="faq__subtitle body-text">
          Everything you need to know before beginning your Maverick journey.
        </p>
        <div>
          <img
            src="{{ cached_asset('assets/images/homepage/mba-management.jpg') }}"
            class="faq-img" alt="Frequently Asked Questions" loading="lazy" decoding="async" />
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
