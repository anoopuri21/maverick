{{-- §13 Shared dynamic university partner wall. --}}
@php
  $renderLogos = collect($universityPartnerLogos ?? [])
      ->map(fn ($logo) => [
          'name' => trim((string) data_get($logo, 'name', '')),
          'src' => media_url(data_get($logo, 'src') ?? data_get($logo, 'logo_url')),
      ])
      ->filter(fn ($logo) => filled($logo['name']) && filled($logo['src']))
      ->values();
  $checklist = collect($partners->checklist ?? [])
      ->filter(fn ($item) => filled($item['text'] ?? null))
      ->values();
  $headingLine1 = $partners->heading_line1 ?? null;
  $headingLine2 = $partners->heading_line2 ?? null;
  if (! filled($headingLine1) && ! filled($headingLine2) && filled($partners->heading ?? null)) {
      $headingLine1 = $partners->heading;
  }
@endphp

@if(filled($headingLine1) || filled($headingLine2) || $renderLogos->isNotEmpty())
<section class="mlp-partners archive-partners" id="mlp-partners"
  @if(filled($headingLine1) || filled($headingLine2)) aria-labelledby="archive-partners-title" @else aria-label="University partners" @endif>
  <div class="archive-partners__frame container">
    <header class="archive-partners__intro mlp-intro-grid">
      <div>
        @if(filled($partners->label))
        <p class="section-label archive-partners__label mlp-eyebrow">{{ $partners->label }}</p>
        @endif
        @if(filled($headingLine1) || filled($headingLine2))
        <h2 class="archive-partners__heading section-title hwdi__heading" id="archive-partners-title">
          @if(filled($headingLine1))
          <span class="hwdi__heading-line">{{ $headingLine1 }}</span>
          @endif
          @if(filled($headingLine2))
          <span class="hwdi__heading-line hwdi__heading-line--red">{{ $headingLine2 }}</span>
          @endif
        </h2>
        @endif
      </div>
      @if(filled($partners->intro))
      <p class="archive-partners__intro-copy">{{ $partners->intro }}</p>
      @endif
    </header>

    @if($renderLogos->isNotEmpty())
    <div class="archive-partners__wall mlp-hairline" data-partner-wall>
      <div class="archive-partners__wall-head">
        <span>Academic network</span>
        <div class="archive-partners__controls" data-partner-controls hidden>
          <span class="archive-partners__hint">Auto-scroll</span>
          <button
            type="button"
            class="archive-partners__toggle"
            data-partner-toggle
            aria-label="Pause or resume partner logo auto-scroll"
            aria-pressed="false"
          >Pause</button>
        </div>
      </div>
      <div
        class="archive-partners__viewport"
        data-partner-viewport
        role="region"
        aria-label="Partner university logos"
        tabindex="0"
      >
        <div class="archive-partners__track" data-partner-track>
          <ul class="archive-partners__logo-list" data-partner-list aria-label="Partner universities">
            @foreach($renderLogos as $logo)
            <li class="archive-partners__logo-item">
              <img src="{{ $logo['src'] }}" alt="" width="220" height="88" loading="lazy" decoding="async">
              <span>{{ $logo['name'] }}</span>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    @endif

    @if($checklist->isNotEmpty())
    <ul class="archive-partners__checklist" aria-label="Partner guarantees">
      @foreach($checklist as $item)
      <li>{{ $item['text'] }}</li>
      @endforeach
    </ul>
    @endif

    @if(filled($partners->trust_line))
    <p class="archive-partners__trust">{{ $partners->trust_line }}</p>
    @endif
  </div>
</section>
@endif
