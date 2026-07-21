<section id="what-we-do" class="wwd section-wrapper section--light" aria-label="What we do">
  <div class="container">
    <div class="wwd__header">
      <div class="wwd__header-left">
        <div class="section-label">
          <span>What We Do</span>
        </div>
        <h2 class="wwd__heading section-title">
          <span class="wwd__heading-line">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">{{ $whatWeDo->heading_line1 }}</span>
            </span>
          </span>
          <span class="wwd__heading-line hwdi__heading-line--red">
            <span class="text-reveal-wrapper">
              <span class="text-reveal-inner">{{ $whatWeDo->heading_line2 }}</span>
            </span>
          </span>
        </h2>
      </div>
      <div class="wwd__header-right">
        <p class="wwd__context body-text fade-up">
          {{ $whatWeDo->context_text }}
        </p>
      </div>
    </div>

    <div class="wwd__grid grid-3">
      <!-- Card 1: Academic Qualifications -->
      <div class="wwd__card fade-up">
        <div class="wwd__card-inner">
          <span class="wwd__card-index accent-text">01</span>
          <h3 class="wwd__card-title">{{ $whatWeDo->pillar1_title }}</h3>
          <p class="wwd__card-desc">
            {{ $whatWeDo->pillar1_desc }}
          </p>
          <ul class="wwd__card-list">
            <li class="wwd__card-item">{{ $whatWeDo->pillar1_item1 }}</li>
            <li class="wwd__card-item">{{ $whatWeDo->pillar1_item2 }}</li>
            <li class="wwd__card-item">{{ $whatWeDo->pillar1_item3 }}</li>
          </ul>
          <a href="{{ $whatWeDo->pillar1_cta_url }}" class="wwd__card-cta">
            {{ $whatWeDo->pillar1_cta_text }}
            <span class="wwd__card-arrow" aria-hidden="true">→</span>
          </a>
        </div>
      </div>

      <!-- Card 2: Professional Development -->
      <div class="wwd__card fade-up">
        <div class="wwd__card-inner">
          <span class="wwd__card-index accent-text">02</span>
          <h3 class="wwd__card-title">{{ $whatWeDo->pillar2_title }}</h3>
          <p class="wwd__card-desc">
            {{ $whatWeDo->pillar2_desc }}
          </p>
          <ul class="wwd__card-list">
            <li class="wwd__card-item">{{ $whatWeDo->pillar2_item1 }}</li>
            <li class="wwd__card-item">{{ $whatWeDo->pillar2_item2 }}</li>
            <li class="wwd__card-item">{{ $whatWeDo->pillar2_item3 }}</li>
          </ul>
          <a href="{{ $whatWeDo->pillar2_cta_url }}" class="wwd__card-cta">
            {{ $whatWeDo->pillar2_cta_text }}
            <span class="wwd__card-arrow" aria-hidden="true">→</span>
          </a>
        </div>
      </div>

      <!-- Card 3: International Opportunities -->
      <div class="wwd__card fade-up">
        <div class="wwd__card-inner">
          <span class="wwd__card-index accent-text">03</span>
          <h3 class="wwd__card-title">{{ $whatWeDo->pillar3_title }}</h3>
          <p class="wwd__card-desc">
            {{ $whatWeDo->pillar3_desc }}
          </p>
          <ul class="wwd__card-list">
            <li class="wwd__card-item">{{ $whatWeDo->pillar3_item1 }}</li>
            <li class="wwd__card-item">{{ $whatWeDo->pillar3_item2 }}</li>
            <li class="wwd__card-item">{{ $whatWeDo->pillar3_item3 }}</li>
            <li class="wwd__card-item">{{ $whatWeDo->pillar3_item4 }}</li>
          </ul>
          <a href="{{ $whatWeDo->pillar3_cta_url }}" class="wwd__card-cta">
            {{ $whatWeDo->pillar3_cta_text }}
            <span class="wwd__card-arrow" aria-hidden="true">→</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
