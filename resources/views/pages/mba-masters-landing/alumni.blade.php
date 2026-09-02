{{-- §11 Alumni / company proof — The Light Archive / Moving Employer Ribbon --}}
@php
  $logos = collect($alumniLogos ?? [])
      ->filter(fn ($logo) => filled(media_url($logo->logo_url ?? null)))
      ->values();
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
<section class="mlp-alumni archive-alumni" id="mlp-alumni" aria-labelledby="archive-alumni-title">
  <div class="archive-alumni__frame container">
    <header class="archive-alumni__intro">
      <div>
        @if(filled($alumni->label))
        <p class="archive-alumni__label mlp-eyebrow">{{ $alumni->label }}</p>
        @endif
        @if(filled($alumni->heading))
        <h2 class="archive-alumni__heading" id="archive-alumni-title">{{ $alumni->heading }}</h2>
        @endif
      </div>
      @if(filled($alumni->intro))
      <p class="archive-alumni__intro-copy">{{ $alumni->intro }}</p>
      @endif
    </header>
  </div>

  <div class="archive-alumni__ribbon" aria-label="Employer logos">
    <div class="archive-alumni__track">
      <ul class="archive-alumni__logos">
        @foreach($renderLogos as $logo)
        <li class="archive-alumni__logo-frame">
          <img src="{{ $logo['src'] }}" alt="{{ $logo['name'] }}" width="180" height="72" loading="lazy" decoding="async">
        </li>
        @endforeach
      </ul>
      <ul class="archive-alumni__logos archive-alumni__logos--clone" aria-hidden="true">
        @foreach($renderLogos as $logo)
        <li class="archive-alumni__logo-frame">
          <img src="{{ $logo['src'] }}" alt="" width="180" height="72" loading="lazy" decoding="async">
        </li>
        @endforeach
      </ul>
    </div>
  </div>

  @if(filled($alumni->trust_line))
  <div class="archive-alumni__footer container">
    <p>{{ $alumni->trust_line }}</p>
  </div>
  @endif
</section>
@endif
