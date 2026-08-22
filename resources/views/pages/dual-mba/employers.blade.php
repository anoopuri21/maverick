{{-- ===== S7: WHY EMPLOYERS VALUE ===== --}}
@php
    $collageItems = collect($employers->collage ?? [])->filter(fn ($item) => filled($item['image'] ?? null));
    $employerItems = collect($employers->items ?? [])->filter(fn ($item) => filled(is_array($item) ? ($item['item'] ?? null) : $item));
    $showEmployers = filled($employers->label ?? null)
        || filled($employers->heading ?? null)
        || filled($employers->heading_italic ?? null)
        || html_filled($employers->description ?? null)
        || $collageItems->isNotEmpty()
        || $employerItems->isNotEmpty();
@endphp
@if($showEmployers)
<section class="dmba-employers section--light section--warm section-wrapper" aria-label="Why Employers Value a Dual MBA" data-testid="dmba-employers-section">
  <div class="container">
    <div class="dmba-employers__grid">
      @if($collageItems->isNotEmpty() || $employerItems->isNotEmpty())
      <div class="dmba-employers__image-col">
        @if($collageItems->isNotEmpty())
        <div class="dmba-employers__collage" data-testid="dmba-employers-collage">
          @foreach($collageItems as $item)
            @php $role = in_array($item['role'] ?? '', ['lead', 'team', 'growth'], true) ? $item['role'] : 'lead'; @endphp
            <figure class="dmba-employers__collage-item dmba-employers__collage-item--{{ $role }}">
              <img
                src="{{ media_url($item['image']) }}"
                alt="{{ $item['alt'] ?? '' }}"
                loading="lazy"
              />
            </figure>
          @endforeach
        </div>
        @endif
        @if($employerItems->isNotEmpty())
        <div class="dmba-employers__counter">
          <span class="dmba-employers__counter-value" data-dmba-counter="{{ $employerItems->count() }}">0</span>
          @if(filled($employers->counter_label))
          <span class="dmba-employers__counter-label">{!! $employers->counter_label !!}</span>
          @endif
        </div>
        @endif
      </div>
      @endif

      <div class="dmba-employers__content">
        @if(filled($employers->label))
        <div class="section-label"><span>{{ $employers->label }}</span></div>
        @endif
        @if(filled($employers->heading) || filled($employers->heading_italic))
        <h2 class="dmba-employers__heading section-title">
          {{ $employers->heading }}
          @if(filled($employers->heading_italic))
            <span class="highlight"><em>{{ $employers->heading_italic }}</em></span>
          @endif
        </h2>
        @endif
        @if(html_filled($employers->description ?? null))
        <div class="dmba-employers__desc body-text dmba-richtext">{!! rich_html($employers->description ?? null) !!}</div>
        @endif

        @if($employerItems->isNotEmpty())
        <ul class="dmba-employers__list" data-testid="dmba-employers-list">
          @foreach($employerItems as $item)
          <li class="dmba-employers__item">
            <span class="dmba-employers__item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
            {{ is_array($item) ? ($item['item'] ?? '') : $item }}
          </li>
          @endforeach
        </ul>
        @endif
      </div>
    </div>
  </div>
</section>
@endif
