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
      <!-- LEFT COLUMN  Global Opportunities -->
      <!-- DYNAMIC START: opportunities-column -->
      <div class="opportunities__column opportunities__column--left">
        <div class="opportunities__column-header">
          <span class="opportunities__column-index">01</span>
          <h3 class="opportunities__column-title">
            {{ $globalOpportunities->left_title }}
          </h3>
          <div class="opportunities__column-line"></div>
        </div>

        <ul class="opportunities__list">
          <!-- DYNAMIC START: opportunities-items -->
          <li class="opportunities__item">
            <a href="{{ $globalOpportunities->opp1_url }}" class="opportunities__link">
              <span class="opportunities__item-number">01</span>
              <div class="opportunities__item-content">
                <h4 class="opportunities__item-title">{{ $globalOpportunities->opp1_title }}</h4>
                <p class="opportunities__item-desc">
                  {{ $globalOpportunities->opp1_desc }}
                </p>
              </div>
              <span class="opportunities__item-arrow" aria-hidden="true">→</span>
            </a>
          </li>

          <li class="opportunities__item">
            <a href="{{ $globalOpportunities->opp2_url }}" class="opportunities__link">
              <span class="opportunities__item-number">02</span>
              <div class="opportunities__item-content">
                <h4 class="opportunities__item-title">
                  {{ $globalOpportunities->opp2_title }}
                </h4>
                <p class="opportunities__item-desc">
                  {{ $globalOpportunities->opp2_desc }}
                </p>
              </div>
              <span class="opportunities__item-arrow" aria-hidden="true">→</span>
            </a>
          </li>

          <li class="opportunities__item">
            <a href="{{ $globalOpportunities->opp3_url }}" class="opportunities__link">
              <span class="opportunities__item-number">03</span>
              <div class="opportunities__item-content">
                <h4 class="opportunities__item-title">{{ $globalOpportunities->opp3_title }}</h4>
                <p class="opportunities__item-desc">
                  {{ $globalOpportunities->opp3_desc }}
                </p>
              </div>
              <span class="opportunities__item-arrow" aria-hidden="true">→</span>
            </a>
          </li>

          <li class="opportunities__item">
            <a href="{{ $globalOpportunities->opp4_url }}" class="opportunities__link">
              <span class="opportunities__item-number">04</span>
              <div class="opportunities__item-content">
                <h4 class="opportunities__item-title">
                  {{ $globalOpportunities->opp4_title }}
                </h4>
                <p class="opportunities__item-desc">
                  {{ $globalOpportunities->opp4_desc }}
                </p>
              </div>
              <span class="opportunities__item-arrow" aria-hidden="true">→</span>
            </a>
          </li>
          <!-- DYNAMIC END: opportunities-items -->
        </ul>
      </div>
      <!-- DYNAMIC END: opportunities-column -->

      <!-- DIVIDER -->
      <div class="opportunities__divider" aria-hidden="true"></div>

      <!-- RIGHT COLUMN  Global Pathways -->
      <!-- DYNAMIC START: pathways-column -->
      <div class="opportunities__column opportunities__column--right">
        <div class="opportunities__column-header">
          <span class="opportunities__column-index">02</span>
          <h3 class="opportunities__column-title">{{ $globalOpportunities->right_title }}</h3>
          <div class="opportunities__column-line"></div>
        </div>

        <ul class="opportunities__list">
          <!-- DYNAMIC START: pathways-items -->
          <li class="opportunities__item">
            <a href="{{ $globalOpportunities->path1_url }}" class="opportunities__link">
              <span class="opportunities__item-number">01</span>
              <div class="opportunities__item-content">
                <h4 class="opportunities__item-title">{{ $globalOpportunities->path1_title }}</h4>
                <p class="opportunities__item-desc">
                  {{ $globalOpportunities->path1_desc }}
                </p>
              </div>
              <span class="opportunities__item-arrow" aria-hidden="true">→</span>
            </a>
          </li>

          <li class="opportunities__item">
            <a href="{{ $globalOpportunities->path2_url }}" class="opportunities__link">
              <span class="opportunities__item-number">02</span>
              <div class="opportunities__item-content">
                <h4 class="opportunities__item-title">{{ $globalOpportunities->path2_title }}</h4>
                <p class="opportunities__item-desc">
                  {{ $globalOpportunities->path2_desc }}
                </p>
              </div>
              <span class="opportunities__item-arrow" aria-hidden="true">→</span>
            </a>
          </li>

          <li class="opportunities__item">
            <a href="{{ $globalOpportunities->path3_url }}" class="opportunities__link">
              <span class="opportunities__item-number">03</span>
              <div class="opportunities__item-content">
                <h4 class="opportunities__item-title">
                  {{ $globalOpportunities->path3_title }}
                </h4>
                <p class="opportunities__item-desc">
                  {{ $globalOpportunities->path3_desc }}
                </p>
              </div>
              <span class="opportunities__item-arrow" aria-hidden="true">→</span>
            </a>
          </li>

          <li class="opportunities__item">
            <a href="{{ $globalOpportunities->path4_url }}" class="opportunities__link">
              <span class="opportunities__item-number">04</span>
              <div class="opportunities__item-content">
                <h4 class="opportunities__item-title">
                  {{ $globalOpportunities->path4_title }}
                </h4>
                <p class="opportunities__item-desc">
                  {{ $globalOpportunities->path4_desc }}
                </p>
              </div>
              <span class="opportunities__item-arrow" aria-hidden="true">→</span>
            </a>
          </li>
          <!-- DYNAMIC END: pathways-items -->
        </ul>
      </div>
      <!-- DYNAMIC END: pathways-column -->
    </div>
  </div>
</section>
