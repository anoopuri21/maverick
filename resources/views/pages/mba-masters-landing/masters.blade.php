{{-- §7 Master's programmes — The Prospectus Ledger
     Light, clean, professional directory of every Master's programme
     (all universities combined). No university names, no counts. --}}
@php
  $programs = collect($masters->universities ?? [])
      ->flatMap(fn ($uni) => collect($uni['programs'] ?? []))
      ->map(fn ($program) => trim((string) ($program['title'] ?? '')))
      ->filter(fn ($title) => $title !== '')
      ->unique(fn ($title) => mb_strtolower($title))
      ->values();
  $trendingRows = collect($masters->trending ?? [])
      ->filter(fn ($row) => filled($row['label'] ?? null))
      ->values();
  $trendingTitle = filled($masters->trending_title ?? null)
      ? (string) $masters->trending_title
      : 'Trending|Specialisations';
  $trendingParts = explode('|', $trendingTitle, 2);
  $plate = mlp_image_url(settings_media_url($masters, 'stage_image'), [
    'w' => 1920,
    'fallback' => 'assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg',
  ]);
  $heading = filled($masters->heading) ? $masters->heading : "Master's Programs";
  $label = filled($masters->label) ? $masters->label : 'Programme directory';
@endphp
@if($programs->isNotEmpty() || filled($masters->heading))
<section class="mlp-masters mlp-masters--prospectus" id="mlp-masters" aria-label="Master's programmes">
  <div class="container mlp-masters__inner">
    <header class="mlp-masters__head mlp-intro-grid" data-mlp-reveal="masters-head">
      <div>
        <p class="mlp-masters__label mlp-eyebrow">{{ $label }}</p>
        <h2 class="mlp-masters__heading mlp-h2">{{ $heading }}</h2>
      </div>
      @if(filled($masters->intro))
      <p class="mlp-masters__intro">{{ $masters->intro }}</p>
      @endif
    </header>

    <div class="mlp-masters__split{{ $trendingRows->isNotEmpty() ? '' : ' mlp-masters__split--full' }}" data-mlp-reveal="masters-split">
      @if($programs->isNotEmpty())
      <ol class="mlp-masters__ledger" data-mlp-reveal="masters-list" aria-label="All Master's programmes">
        @foreach($programs as $title)
        <li class="mlp-masters__item">
          <span class="mlp-masters__item-mark" aria-hidden="true"></span>
          <span class="mlp-masters__item-title">{{ $title }}</span>
        </li>
        @endforeach
      </ol>
      @endif

      @if($trendingRows->isNotEmpty())
      <aside class="mlp-trending" aria-label="Trending specialisations">
        <h3 class="mlp-trending__title">
          @php $trendingDark = trim($trendingParts[0] ?? ''); @endphp
          <span class="mlp-trending__title-dark">{{ $trendingDark !== '' ? $trendingDark : 'Trending' }}</span>
          @if(isset($trendingParts[1]) && trim($trendingParts[1]) !== '')
          <span class="mlp-trending__title-gold">{{ trim($trendingParts[1]) }}</span>
          @endif
        </h3>
        <ul class="mlp-trending__list">
          @foreach($trendingRows as $row)
          @php
            $percent = (int) ($row['percent'] ?? 0);
            $percent = max(0, min(100, $percent));
          @endphp
          <li class="mlp-trending__row" style="--trend: {{ $percent }}%">
            <span class="mlp-trending__label">{{ $row['label'] }}</span>
            <span class="mlp-trending__track" aria-hidden="true">
              <span class="mlp-trending__fill"><span class="mlp-trending__value">{{ $percent }}%</span></span>
            </span>
          </li>
          @endforeach
        </ul>
      </aside>
      @endif
    </div>

    <div class="mlp-masters__cta-row">
      <a href="#mlp-enquire" class="mlp-masters__cta mlp-cta mlp-cta--primary">Check eligibility <span aria-hidden="true">↗</span></a>
      <p class="mlp-masters__cta-note">Every programme above is open to enquiry — admissions team will confirm eligibility and next steps.</p>
    </div>
  </div>
</section>
@endif
