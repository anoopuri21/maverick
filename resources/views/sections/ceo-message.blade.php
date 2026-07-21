<section id="ceo-message" class="ceo section-wrapper section--light" aria-label="Founder and CEO Message">
  <div class="container">

    <div class="ceo__grid">

      <!-- CEO IMAGE -->
      <div class="ceo__image-col">
        <div class="ceo__image-wrapper fade-up">

          <!-- Replace with actual image later -->
          <div class="ceo__image">
            <img src="{{ $ceo->image_url ?? asset('assets/images/placeholder.jpg') }}"
              alt="{{ $ceo->name }}, {{ $ceo->designation }}" />
          </div>

          <div class="ceo__badge">
            {{ $ceo->badge_text }}
          </div>

        </div>
      </div>

      <!-- CONTENT -->
      <div class="ceo__content">

        <div class="section-label fade-up">
          <span>Leadership Message</span>
        </div>

        <h2 class="ceo__heading section-title">
          <span class="ceo__heading-line">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">
                A Message from
              </span>
            </span>
          </span>

          <span class="ceo__heading-line hwdi__heading-line--red">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">
                Our Founder & CEO
              </span>
            </span>
          </span>
        </h2>

        <blockquote class="ceo__quote fade-up">
          "{{ $ceo->quote }}"
        </blockquote>

        <div class="ceo__body fade-up">

          <p>
            {{ $ceo->body_paragraph1 }}
          </p>

          <p>
            {{ $ceo->body_paragraph2 }}
          </p>

        </div>

        <div class="ceo__signature fade-up">

          <div class="ceo__signature-line"></div>

          <h3 class="ceo__name">
            {{ $ceo->name }}
          </h3>

          <p class="ceo__designation">
            {{ $ceo->designation }}
          </p>

        </div>

      </div>

    </div>

  </div>
</section>
