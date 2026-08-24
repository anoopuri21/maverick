{{-- §13 University partners — The Light Archive / Kinetic Partner Wall --}}
@php
  $logos = collect($universityPartnerLogos ?? [])
      ->filter(fn ($logo) => filled(media_url($logo->logo_url ?? null)))
      ->values();
  $fallbackLogos = [
    ['name' => 'Rushford Business School', 'src' => 'assets/images/universities/placeholder-logo.png'],
    ['name' => 'Girne American University', 'src' => 'assets/images/universities/placeholder-logo.png'],
    ['name' => 'University for the Creative Arts', 'src' => 'assets/images/universities/placeholder-logo.png'],
    ['name' => 'University of Wolverhampton', 'src' => 'assets/images/universities/placeholder-logo.png'],
  ];
  $renderLogos = $logos->isNotEmpty()
    ? $logos->map(fn ($logo) => ['name' => $logo->name ?? '', 'src' => media_url($logo->logo_url)])
    : collect($fallbackLogos)->map(fn ($logo) => ['name' => $logo['name'], 'src' => cached_asset($logo['src'])]);
@endphp

@if(filled($partners->heading) || $renderLogos->isNotEmpty())
<section class="mlp-partners archive-partners" id="mlp-partners" aria-labelledby="archive-partners-title">
  <div class="archive-partners__frame container">
    <header class="archive-partners__intro">
      <div>
        @if(filled($partners->label))
        <p class="archive-partners__label">{{ $partners->label }}</p>
        @endif
        @if(filled($partners->heading))
        <h2 class="archive-partners__heading" id="archive-partners-title">{{ $partners->heading }}</h2>
        @endif
      </div>
      @if(filled($partners->intro))
      <p class="archive-partners__intro-copy">{{ $partners->intro }}</p>
      @endif
    </header>

    <div class="archive-partners__wall" data-partner-wall>
      <div class="archive-partners__wall-head">
        <span>Academic network</span>
        <div class="archive-partners__controls">
          <span class="archive-partners__hint">Drag / scroll to explore</span>
          <button type="button" class="archive-partners__toggle" data-partner-toggle aria-pressed="false">Pause</button>
        </div>
      </div>
      <div class="archive-partners__viewport" data-partner-viewport tabindex="0" aria-label="Partner university logos. Use arrow keys or drag to explore.">
        <div class="archive-partners__track" data-partner-track>
          <ul class="archive-partners__logo-list" aria-label="Partner universities">
            @foreach($renderLogos as $logo)
            <li class="archive-partners__logo-item">
              <img src="{{ $logo['src'] }}" alt="{{ $logo['name'] }}" width="220" height="88" loading="lazy" decoding="async">
              <span>{{ $logo['name'] }}</span>
            </li>
            @endforeach
          </ul>
          <ul class="archive-partners__logo-list archive-partners__logo-list--clone" aria-hidden="true">
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

    @if(filled($partners->trust_line))
    <p class="archive-partners__trust">{{ $partners->trust_line }}</p>
    @endif
  </div>
</section>
@endif
