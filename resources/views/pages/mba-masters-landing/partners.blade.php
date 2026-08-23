{{-- §13 University partners — premium logo stage; UniversityPartner data (no duplicate PartnerLogo) --}}
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
<section class="mlp-partners" id="mlp-partners" aria-label="University partners">
  <div class="mlp-partners__deco" aria-hidden="true">
    <span class="mlp-partners__orb mlp-partners__orb--a"></span>
    <span class="mlp-partners__orb mlp-partners__orb--b"></span>
    <span class="mlp-partners__deco-rule"></span>
  </div>

  <div class="container mlp-partners__inner">
    <header class="mlp-partners__head" data-mlp-reveal="partners-head">
      <div class="mlp-partners__meta">
        @if(filled($partners->label))
        <p class="mlp-partners__label mlp-meta">{{ $partners->label }}</p>
        @endif
      </div>
      @if(filled($partners->heading))
      <h2 class="mlp-partners__heading mlp-headline">{{ $partners->heading }}</h2>
      @endif
      @if(filled($partners->intro))
      <p class="mlp-partners__intro mlp-lede">{{ $partners->intro }}</p>
      @endif
    </header>

    <ul class="mlp-partners__stage" data-mlp-reveal="partners-stage" aria-label="Partner university logos">
      @foreach($renderLogos as $logo)
      <li class="mlp-partners__cell">
        <span class="mlp-partners__frame">
          <img
            class="mlp-partners__logo"
            src="{{ $logo['src'] }}"
            alt="{{ $logo['name'] }}"
            width="200"
            height="80"
            loading="lazy"
            decoding="async"
          >
        </span>
        @if(filled($logo['name']))
        <span class="mlp-partners__name">{{ $logo['name'] }}</span>
        @endif
      </li>
      @endforeach
    </ul>

    @if(filled($partners->trust_line))
    <p class="mlp-partners__trust" data-mlp-reveal="partners-trust">{{ $partners->trust_line }}</p>
    @endif
  </div>
</section>
@endif
