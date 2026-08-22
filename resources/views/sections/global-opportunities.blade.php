<section id="global-opportunities" class="opportunities section-wrapper section--light"
  aria-label="Global Opportunities and Pathways">
  <div class="container opportunities__inner">
    <div class="opportunities__header">
      <div class="section-label"><span>Beyond Borders</span></div>
      @if(filled($globalOpportunities->heading ?? null))
      <h2 class="opportunities__heading section-title">
        <span class="text-reveal-wrapper">
          <span class="text-reveal-inner">{{ $globalOpportunities->heading }}</span>
        </span>
      </h2>
      @endif
      @if(filled($globalOpportunities->subtitle ?? null))
      <p class="opportunities__subtitle body-text">
        {{ $globalOpportunities->subtitle }}
      </p>
      @endif
    </div>

    <div class="opportunities__split">
      @php
        $opportunities = collect(settings_array(data_get($globalOpportunities ?? null, 'opportunities', [])))
            ->filter(fn ($item) => is_array($item) && (filled($item['title'] ?? null) || filled($item['desc'] ?? null) || filled($item['url'] ?? null)));
        $pathways = collect(settings_array(data_get($globalOpportunities ?? null, 'pathways', [])))
            ->filter(fn ($item) => is_array($item) && (filled($item['title'] ?? null) || filled($item['desc'] ?? null) || filled($item['url'] ?? null)));
      @endphp

      <div class="opportunities__column opportunities__column--right" id="opportunities">
        <div class="opportunities__column-header">
          <span class="opportunities__column-index">01</span>
          @if(filled($globalOpportunities->left_title ?? null))
          <h3 class="opportunities__column-title">{{ $globalOpportunities->left_title }}</h3>
          @endif
          <div class="opportunities__column-line"></div>
        </div>

        @if($opportunities->isNotEmpty())
        <ul class="opportunities__list">
          @foreach($opportunities as $i => $item)
          @php $itemHref = edu_href($item['url'] ?? null); @endphp
          <li class="opportunities__item">
            @if($itemHref)
            <a href="{{ $itemHref }}" class="opportunities__link">
            @else
            <div class="opportunities__link">
            @endif
              <span class="opportunities__item-number">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
              <div class="opportunities__item-content">
                @if(filled($item['title'] ?? null))
                <h4 class="opportunities__item-title">{{ $item['title'] }}</h4>
                @endif
                @if(!empty($item['desc']))
                <p class="opportunities__item-desc">{!! rich_html($item['desc'] ?? null) !!}</p>
                @endif
              </div>
              <span class="opportunities__item-arrow" aria-hidden="true">→</span>
            @if($itemHref)
            </a>
            @else
            </div>
            @endif
          </li>
          @endforeach
        </ul>
        @endif
      </div>

      <div class="opportunities__divider" aria-hidden="true"></div>

      <div class="opportunities__column opportunities__column--left" id="pathways">
        <div class="opportunities__column-header">
          <span class="opportunities__column-index">02</span>
          @if(filled($globalOpportunities->right_title ?? null))
          <h3 class="opportunities__column-title">{{ $globalOpportunities->right_title }}</h3>
          @endif
          <div class="opportunities__column-line"></div>
        </div>

        @if($pathways->isNotEmpty())
        <ul class="opportunities__list">
          @foreach($pathways as $i => $item)
          @php $itemHref = edu_href($item['url'] ?? null); @endphp
          <li class="opportunities__item">
            @if($itemHref)
            <a href="{{ $itemHref }}" class="opportunities__link">
            @else
            <div class="opportunities__link">
            @endif
              <span class="opportunities__item-number">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
              <div class="opportunities__item-content">
                @if(filled($item['title'] ?? null))
                <h4 class="opportunities__item-title">{{ $item['title'] }}</h4>
                @endif
                @if(!empty($item['desc']))
                <p class="opportunities__item-desc">{!! rich_html($item['desc'] ?? null) !!}</p>
                @endif
              </div>
              <span class="opportunities__item-arrow" aria-hidden="true">→</span>
            @if($itemHref)
            </a>
            @else
            </div>
            @endif
          </li>
          @endforeach
        </ul>
        @endif
      </div>
    </div>
  </div>
</section>
