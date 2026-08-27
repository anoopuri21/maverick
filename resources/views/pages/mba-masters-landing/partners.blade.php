{{-- §13 University partners — The Light Archive / Kinetic Partner Wall --}}
@php
  $storedLogos = collect($universityPartnerLogos ?? [])
      ->filter(fn ($logo) => filled(media_url($logo->logo_url ?? null)))
      ->values();
  $listingUniversities = [
    [
      'name' => 'Rushford Business School',
      'src' => 'https://rushford.ch/wp-content/uploads/2022/12/RUSHFORD-LOGO-COLOR-1.png',
    ],
    [
      'name' => 'Girne American University',
      'src' => 'https://www.gau.edu.tr/template/gau/assets/img/logo2_en.png',
    ],
    [
      'name' => 'University for the Creative Arts',
      'src' => 'https://www.uca.ac.uk/media/uca-2020/site-assets/media/logos/uca-logo-black.png',
    ],
    [
      'name' => 'University of Wolverhampton',
      'src' => 'https://upload.wikimedia.org/wikipedia/en/1/19/University_of_Wolverhampton_logo.jpg',
    ],
  ];
  $renderLogos = collect($listingUniversities)->map(function (array $university) use ($storedLogos): array {
    $name = mb_strtolower($university['name']);
    $stored = $storedLogos->first(function ($logo) use ($name) {
      $storedName = mb_strtolower(trim((string) ($logo->name ?? '')));

      return $storedName !== '' && (str_contains($storedName, $name) || str_contains($name, $storedName));
    });

    return [
      'name' => $university['name'],
      'src' => $stored ? media_url($stored->logo_url) : $university['src'],
    ];
  });
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
              @if(filled($logo['src']))
              <img src="{{ $logo['src'] }}" alt="{{ $logo['name'] }}" width="220" height="88" loading="lazy" decoding="async">
              @endif
              <span>{{ $logo['name'] }}</span>
            </li>
            @endforeach
          </ul>
          <ul class="archive-partners__logo-list archive-partners__logo-list--clone" aria-hidden="true">
            @foreach($renderLogos as $logo)
            <li class="archive-partners__logo-item">
              @if(filled($logo['src']))
              <img src="{{ $logo['src'] }}" alt="" width="220" height="88" loading="lazy" decoding="async">
              @endif
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
