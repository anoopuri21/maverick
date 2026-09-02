{{-- ===== S5: OUR EDUTAINMENT PROGRAMMES ===== --}}
@php
    $programmeCards = collect($programmes->cards ?? [])->filter(fn ($card) => filled($card['title'] ?? null));
    $chinaItems = collect($programmes->china_items ?? [])->filter(fn ($item) => filled($item['title'] ?? null));
    $showProgrammes = filled($programmes->label ?? null)
        || filled($programmes->title ?? null)
        || filled($programmes->title_italic ?? null)
        || $programmeCards->isNotEmpty()
        || $chinaItems->isNotEmpty();
    $chinaRendered = false;
@endphp
@if($showProgrammes)
<section id="edu-programmes" class="edu-programmes section-wrapper section--light" aria-label="Our Edutainment Programmes">
  <div class="container">
    <div class="edu-programmes__header">
      @include('pages.edutainment._section-heading', [
        'label' => $programmes->label,
        'title' => $programmes->title,
        'titleLine2' => $programmes->title_line2,
        'titleItalic' => $programmes->title_italic,
        'titleBreak' => $programmes->title_break,
      ])
    </div>

    @if($programmeCards->isNotEmpty() || $chinaItems->isNotEmpty())
    <div class="edu-programmes__grid">
      @foreach($programmeCards as $card)
        @php
            $bullets = collect($card['bullets'] ?? [])->filter(fn ($item) => filled(is_array($item) ? ($item['item'] ?? null) : $item));
            $isFeatured = ! empty($card['is_featured']);
            $cardImageUrl = settings_media_url($card, 'image');
        @endphp
        <div class="edu-programmes__card{{ $isFeatured ? ' edu-programmes__card--featured' : '' }} fade-up">
          @if(filled($cardImageUrl))
          <div class="edu-programmes__card-image">
            <img src="{{ $cardImageUrl }}" alt="{{ $card['title'] }}" loading="lazy">
            <div class="edu-programmes__card-overlay"></div>
          </div>
          @endif
          <div class="edu-programmes__card-content">
            @if(filled($card['badge'] ?? null))
            <span class="edu-programmes__card-badge">{{ $card['badge'] }}</span>
            @endif
            <h3 class="edu-programmes__card-title">{{ $card['title'] }}</h3>
            @if(filled($card['description'] ?? null))
            <div class="edu-programmes__card-desc">{!! rich_html($card['description'] ?? null) !!}</div>
            @endif
            @if($bullets->isNotEmpty() && ! $isFeatured)
            <ul class="edu-programmes__card-list">
              @foreach($bullets as $bullet)
                <li>{{ is_array($bullet) ? ($bullet['item'] ?? '') : $bullet }}</li>
              @endforeach
            </ul>
            @endif
            @if(! $isFeatured && filled($card['cta_label'] ?? null) && filled($card['cta_url'] ?? null))
            <a href="{{ edu_href($card['cta_url']) }}" class="btn btn--secondary">{{ $card['cta_label'] }}</a>
            @endif
          </div>
        </div>

        @if($isFeatured && ! $chinaRendered && $chinaItems->isNotEmpty())
          @include('pages.edutainment._china-experiences')
          @php $chinaRendered = true; @endphp
        @endif
      @endforeach

      @if(! $chinaRendered && $chinaItems->isNotEmpty())
        @include('pages.edutainment._china-experiences')
      @endif
    </div>
    @endif
  </div>
</section>
@endif
