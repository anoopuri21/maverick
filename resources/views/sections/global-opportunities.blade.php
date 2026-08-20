<section id="global-opportunities" class="opportunities section-wrapper section--light"
  aria-label="Global Opportunities and Pathways">
  <div class="container opportunities__inner">
    <!-- ========== HEADER (Editorial Style) ========== -->
    <div class="opportunities__header">
      <div class="section-label"><span>Beyond Borders</span></div>
      <h2 class="opportunities__heading section-title">
        <span class="text-reveal-wrapper">
          <span class="text-reveal-inner">{{ $globalOpportunities->heading }}</span>
        </span>
      </h2>
      <p class="opportunities__subtitle body-text">
        {{ $globalOpportunities->subtitle }}
      </p>
    </div>

    <!-- ========== SPLIT SCREEN ========== -->
    <div class="opportunities__split">
      @php
        $opportunities = $globalOpportunities->opportunities ?? [];
        $pathways = $globalOpportunities->pathways ?? [];
      @endphp

      <!-- LEFT COLUMN — Global Opportunities -->
      <div class="opportunities__column opportunities__column--right" id="opportunities">
        <div class="opportunities__column-header">
          <span class="opportunities__column-index">01</span>
          <h3 class="opportunities__column-title">{{ $globalOpportunities->left_title }}</h3>
          <div class="opportunities__column-line"></div>
        </div>

        <div class="go-cards">
          @foreach($opportunities as $i => $item)
          <a href="{{ $item['url'] ?? '#' }}" class="go-card">
            @if(!empty($item['image_url']))
            <div class="go-card__img"><img src="{{ $item['image_url'] }}" alt="{{ $item['title'] ?? '' }}" loading="lazy"></div>
            @endif
            <div class="go-card__body">
              <span class="go-card__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
              <h4 class="go-card__title">{{ $item['title'] ?? '' }}</h4>
              @if(!empty($item['desc']))
              <p class="go-card__desc">{{ $item['desc'] }}</p>
              @endif
              <span class="go-card__link">Explore →</span>
            </div>
          </a>
          @endforeach
        </div>
      </div>

      <!-- DIVIDER -->
      <div class="opportunities__divider" aria-hidden="true"></div>

      <!-- RIGHT COLUMN — Global Pathways -->
      <div class="opportunities__column opportunities__column--left" id="pathways">
        <div class="opportunities__column-header">
          <span class="opportunities__column-index">02</span>
          <h3 class="opportunities__column-title">{{ $globalOpportunities->right_title }}</h3>
          <div class="opportunities__column-line"></div>
        </div>

        <div class="go-cards">
          @foreach($pathways as $i => $item)
          <a href="{{ $item['url'] ?? '#' }}" class="go-card">
            @if(!empty($item['image_url']))
            <div class="go-card__img"><img src="{{ $item['image_url'] }}" alt="{{ $item['title'] ?? '' }}" loading="lazy"></div>
            @endif
            <div class="go-card__body">
              <span class="go-card__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
              <h4 class="go-card__title">{{ $item['title'] ?? '' }}</h4>
              @if(!empty($item['desc']))
              <p class="go-card__desc">{{ $item['desc'] }}</p>
              @endif
              <span class="go-card__link">Explore →</span>
            </div>
          </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
