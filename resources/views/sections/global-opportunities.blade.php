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
      <div class="opportunities__column opportunities__column--right">
        <div class="opportunities__column-header">
          <span class="opportunities__column-index">01</span>
          <h3 class="opportunities__column-title">{{ $globalOpportunities->left_title }}</h3>
          <div class="opportunities__column-line"></div>
        </div>

        <ul class="opportunities__list">
          @foreach($opportunities as $i => $item)
          <li class="opportunities__item">
            <a href="{{ $item['url'] ?? '#' }}" class="opportunities__link">
              <span class="opportunities__item-number">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
              <div class="opportunities__item-content">
                <h4 class="opportunities__item-title">{{ $item['title'] ?? '' }}</h4>
                @if(!empty($item['desc']))
                <p class="opportunities__item-desc">{{ $item['desc'] }}</p>
                @endif
              </div>
              <span class="opportunities__item-arrow" aria-hidden="true">→</span>
            </a>
          </li>
          @endforeach
        </ul>
      </div>

      <!-- DIVIDER -->
      <div class="opportunities__divider" aria-hidden="true"></div>

      <!-- RIGHT COLUMN — Global Pathways -->
      <div class="opportunities__column opportunities__column--left">
        <div class="opportunities__column-header">
          <span class="opportunities__column-index">02</span>
          <h3 class="opportunities__column-title">{{ $globalOpportunities->right_title }}</h3>
          <div class="opportunities__column-line"></div>
        </div>

        <ul class="opportunities__list">
          @foreach($pathways as $i => $item)
          <li class="opportunities__item">
            <a href="{{ $item['url'] ?? '#' }}" class="opportunities__link">
              <span class="opportunities__item-number">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
              <div class="opportunities__item-content">
                <h4 class="opportunities__item-title">{{ $item['title'] ?? '' }}</h4>
                @if(!empty($item['desc']))
                <p class="opportunities__item-desc">{{ $item['desc'] }}</p>
                @endif
              </div>
              <span class="opportunities__item-arrow" aria-hidden="true">→</span>
            </a>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</section>
