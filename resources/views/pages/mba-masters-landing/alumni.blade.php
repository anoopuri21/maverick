{{-- §11 Alumni / company proof — navy logo rail, fixed frames, shared PartnerLogo data --}}
@php
  $logos = collect($alumniLogos ?? [])->filter(fn ($logo) => filled(media_url($logo->logo_url ?? null)))->values();
  $fallbackLogos = [
    ['name' => 'Goldman Sachs', 'src' => 'assets/images/alumni/alumn-7.png'],
    ['name' => 'Deloitte', 'src' => 'assets/images/alumni/alumn-8.png'],
    ['name' => 'World Bank', 'src' => 'assets/images/alumni/alumn-9.png'],
    ['name' => 'DHL', 'src' => 'assets/images/alumni/alumn-10.png'],
    ['name' => 'Apple', 'src' => 'assets/images/alumni/alumn-11.png'],
    ['name' => 'stc', 'src' => 'assets/images/alumni/alumn-12.png'],
  ];
  $renderLogos = $logos->isNotEmpty()
    ? $logos->map(fn ($logo) => ['name' => $logo->name ?? '', 'src' => media_url($logo->logo_url)])
    : collect($fallbackLogos)->map(fn ($logo) => ['name' => $logo['name'], 'src' => cached_asset($logo['src'])]);
@endphp
@if(filled($alumni->heading) || $renderLogos->isNotEmpty())
<section class="mlp-alumni" id="mlp-alumni" aria-label="Alumni employers">
  <div class="mlp-alumni__deco" aria-hidden="true">
    <span class="mlp-alumni__orb mlp-alumni__orb--a"></span>
    <span class="mlp-alumni__orb mlp-alumni__orb--b"></span>
    <span class="mlp-alumni__deco-rule"></span>
  </div>

  <div class="container mlp-alumni__inner">
    <header class="mlp-alumni__head" data-mlp-reveal="alumni-head">
      <div class="mlp-alumni__meta">
        @if(filled($alumni->label))
        <p class="mlp-alumni__label mlp-meta">{{ $alumni->label }}</p>
        @endif
      </div>
      @if(filled($alumni->heading))
      <h2 class="mlp-alumni__heading mlp-headline">{{ $alumni->heading }}</h2>
      @endif
      @if(filled($alumni->intro))
      <p class="mlp-alumni__intro mlp-lede">{{ $alumni->intro }}</p>
      @endif
    </header>
  </div>

  <div class="mlp-alumni__rail" data-mlp-alumni-rail aria-label="Employer logos">
    <div class="mlp-alumni__marquee" data-mlp-alumni-marquee>
      <ul class="mlp-alumni__track">
        @foreach($renderLogos as $logo)
        <li class="mlp-alumni__cell">
          <span class="mlp-alumni__frame">
            <img
              class="mlp-alumni__logo"
              src="{{ $logo['src'] }}"
              alt="{{ $logo['name'] }}"
              width="180"
              height="72"
              loading="lazy"
              decoding="async"
            >
          </span>
        </li>
        @endforeach
      </ul>
      <ul class="mlp-alumni__track mlp-alumni__track--clone" aria-hidden="true">
        @foreach($renderLogos as $logo)
        <li class="mlp-alumni__cell">
          <span class="mlp-alumni__frame">
            <img
              class="mlp-alumni__logo"
              src="{{ $logo['src'] }}"
              alt=""
              width="180"
              height="72"
              loading="lazy"
              decoding="async"
            >
          </span>
        </li>
        @endforeach
      </ul>
    </div>
  </div>

  @if(filled($alumni->trust_line))
  <div class="container">
    <p class="mlp-alumni__trust" data-mlp-reveal="alumni-trust">{{ $alumni->trust_line }}</p>
  </div>
  @endif
</section>
@endif
