{{-- §13 University partners — International MBA Degrees from Our University Partners - PDF Exact --}}
@php
  $storedLogos = collect($universityPartnerLogos ?? [])
      ->filter(fn ($logo) => filled(media_url($logo->logo_url ?? null)))
      ->values();
  // PDF exact 5 partners
  $pdfPartners = [
    [
      'name' => 'Rushford Business School',
      'location' => 'Switzerland, EduQua and IACBE',
      'detail' => 'Twelve MBAs and nine MSc programmes, delivered fully online.',
      'src' => 'https://rushford.ch/wp-content/uploads/2022/12/RUSHFORD-LOGO-COLOR-1.png',
    ],
    [
      'name' => 'Girne American University',
      'location' => 'North Cyprus, YÖDAK and YÖK, IACBE',
      'detail' => 'Six MBAs, sixteen Executive MBAs, and four thesis-based MSc programmes.',
      'src' => 'https://www.gau.edu.tr/template/gau/assets/img/logo2_en.png',
    ],
    [
      'name' => 'University for the Creative Arts',
      'location' => 'UK public university',
      'detail' => 'Awards the Global MBA, delivered with Rushford Business School.',
      'src' => 'https://www.uca.ac.uk/media/uca-2020/site-assets/media/logos/uca-logo-black.png',
    ],
    [
      'name' => 'University of Wolverhampton',
      'location' => 'UK public university',
      'detail' => 'Awards the Master of Laws.',
      'src' => 'https://upload.wikimedia.org/wikipedia/en/1/19/University_of_Wolverhampton_logo.jpg',
    ],
    [
      'name' => 'University of the West of Scotland',
      'location' => 'UK public university',
      'detail' => 'Awards the MBA in International Business.',
      'src' => 'https://www.uws.ac.uk/assets/uws-logo.png',
    ],
  ];
  $renderLogos = collect($pdfPartners)->map(function (array $university) use ($storedLogos): array {
    $name = mb_strtolower($university['name']);
    $stored = $storedLogos->first(function ($logo) use ($name) {
      $storedName = mb_strtolower(trim((string) ($logo->name ?? '')));
      return $storedName !== '' && (str_contains($storedName, $name) || str_contains($name, $storedName));
    });
    return [
      'name' => $university['name'],
      'location' => $university['location'],
      'detail' => $university['detail'],
      'src' => $stored ? media_url($stored->logo_url) : $university['src'],
    ];
  });
@endphp

@if(filled($partners->heading) || $renderLogos->isNotEmpty())
<section class="mlp-partners archive-partners" id="mlp-partners" aria-labelledby="archive-partners-title">
  <div class="archive-partners__frame container">
    <header class="archive-partners__intro mlp-intro-grid" data-mlp-reveal="partners-head">
      <div>
        @if(filled($partners->label))
        <p class="archive-partners__label mlp-eyebrow mlp-meta">{{ $partners->label }}</p>
        @endif
        @if(filled($partners->heading))
        <h2 class="archive-partners__heading mlp-h2" id="archive-partners-title">{{ $partners->heading ?? 'International MBA Degrees from Our University Partners' }}</h2>
        @endif
      </div>
      @if(filled($partners->intro))
      <p class="archive-partners__intro-copy mlp-lede">{{ $partners->intro }}</p>
      @endif
    </header>

    <div class="archive-partners__grid" data-mlp-reveal="partners-grid" aria-label="University partners">
      @foreach($renderLogos as $partner)
      <article class="archive-partners__card mlp-hairline">
        <div class="archive-partners__card-logo">
          @if(filled($partner['src']))
          <img src="{{ $partner['src'] }}" alt="{{ $partner['name'] }}" width="200" height="80" loading="lazy" decoding="async">
          @endif
        </div>
        <div class="archive-partners__card-copy">
          <h3>{{ $partner['name'] }}</h3>
          <p class="archive-partners__card-location">{{ $partner['location'] }}</p>
          <p class="archive-partners__card-detail">{{ $partner['detail'] }}</p>
        </div>
      </article>
      @endforeach
    </div>

    {{-- PDF Exact Checklist --}}
    <div class="archive-partners__checklist" data-mlp-reveal="partners-checklist" aria-label="Partner checklist">
      <h3>Why this matters</h3>
      <ul>
        <li><i data-lucide="check-circle-2" aria-hidden="true"></i> The university awards your degree, not a third party</li>
        <li><i data-lucide="check-circle-2" aria-hidden="true"></i> Your certificate is the same as an on-campus graduate's</li>
        <li><i data-lucide="check-circle-2" aria-hidden="true"></i> Accreditation status is confirmed in writing before you enrol</li>
        <li><i data-lucide="check-circle-2" aria-hidden="true"></i> Recognition guidance for the UAE is available on request</li>
      </ul>
    </div>

    @if(filled($partners->trust_line))
    <p class="archive-partners__trust">{{ $partners->trust_line }}</p>
    @endif
  </div>
</section>
@endif
