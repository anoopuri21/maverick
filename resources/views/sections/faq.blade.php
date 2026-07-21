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
            src="https://img.magnific.com/free-vector/tiny-business-people-with-giant-faq-letters-gadget-users-searching-instructions-useful-information-flat-vector-illustration-customer-support-solution-concept-banner-landing-web-page_74855-23409.jpg"
            class="faq-img" alt="Frequently Asked Questions" />
        </div>

      </div>

      <div class="faq__accordion">
        @forelse($homepageFaqs as $index => $faq)
          <div class="faq__item {{ $index === 0 ? 'active' : '' }}">
            <button class="faq__question">
              <span>{{ $faq->question }}</span>
              <span class="faq__icon">+</span>
            </button>
            <div class="faq__answer">
              <p>{{ $faq->answer }}</p>
            </div>
          </div>
        @empty
          {{-- Fallback static FAQs --}}
          <div class="faq__item active">
            <button class="faq__question">
              <span>Are Maverick qualifications internationally recognised?</span>
              <span class="faq__icon">+</span>
            </button>
            <div class="faq__answer">
              <p>Maverick Business Academy partners with internationally recognised awarding bodies and universities, ensuring qualifications hold global value and credibility.</p>
            </div>
          </div>
        @endforelse
      </div>

    </div>

  </div>

</section>
