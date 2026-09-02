<section id="ceo-message" class="ceo section-wrapper section--light" aria-label="Founder and CEO Message">
  @isset($ceo)
  <div class="container">

    <div class="section-label fade-up">
      <span>{{ $ceo->label ?? '' }}</span>
    </div>

    <h2 class="ceo__heading section-title">
      <span class="ceo__heading-line">
        <span class="text-reveal-wrapper">
          <span class="text-reveal-inner">
            {{ $ceo->heading_line1 ?? '' }}
          </span>
        </span>
      </span>

      <span class="ceo__heading-line hwdi__heading-line--red">
        <span class="text-reveal-wrapper">
          <span class="text-reveal-inner">
            {{ $ceo->heading_line2 ?? '' }}
          </span>
        </span>
      </span>
    </h2>

    <div class="ceo__grid">

      <!-- CEO IMAGE -->
      <div class="ceo__image-col">
        <div class="ceo__image-wrapper fade-up">

          <!-- Replace with actual image later -->
          <div class="ceo__image">
            @if($url = settings_media_url($ceo, 'image_url') ?: cached_asset('assets/images/homepage/mba-management.jpg'))
            <img src="{{ $url }}"
              alt="{{ trim(($ceo->name ?? '').', '.($ceo->designation ?? ''), ', ') }}"
              loading="lazy" decoding="async" />
            @endif
          </div>

          @if(filled($ceo->badge_text ?? null))
          <div class="ceo__badge">
            {{ $ceo->badge_text }}
          </div>
          @endif

        </div>
      </div>

      <!-- CONTENT -->
      <div class="ceo__content">

        @if(html_filled($ceo->quote ?? null))
        <blockquote class="ceo__quote fade-up">
          "{!! rich_html($ceo->quote ?? null) !!}"
        </blockquote>
        @endif

        @if(html_filled($ceo->body_paragraph1 ?? null) || html_filled($ceo->body_paragraph2 ?? null))
        <div class="ceo__body fade-up">
          @if(html_filled($ceo->body_paragraph1 ?? null))
          <div>
            {!! rich_html($ceo->body_paragraph1 ?? null) !!}
          </div>
          @endif
          @if(html_filled($ceo->body_paragraph2 ?? null))
          <div>
            {!! rich_html($ceo->body_paragraph2 ?? null) !!}
          </div>
          @endif
        </div>
        @endif

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
  @endisset
</section>
