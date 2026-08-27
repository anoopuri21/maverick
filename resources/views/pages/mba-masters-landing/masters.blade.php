{{-- §7 Master's programmes — The Couture Index (luxury redesign)
     All listed programmes as a couture registry on a deep-void chapter.
     No university names, no counts; a single framed cinematic plate. --}}
@php
  $programs = collect($masters->universities ?? [])
      ->flatMap(fn ($uni) => collect($uni['programs'] ?? []))
      ->map(fn ($program) => trim((string) ($program['title'] ?? '')))
      ->filter(fn ($title) => $title !== '')
      ->unique(fn ($title) => mb_strtolower($title))
      ->values();
  $plate = mlp_image_url($masters->stage_image ?? null, [
    'w' => 1600,
    'fallback' => 'assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg',
  ]);
@endphp
@if($programs->isNotEmpty() || filled($masters->heading))
<section class="mlp-masters mlp-masters--couture" id="mlp-masters" aria-label="Master's programmes">
  <div class="container mlp-masters__inner">
    <header class="mlp-masters__head" data-mlp-reveal="masters-head">
      <div class="mlp-masters__meta">
        @if(filled($masters->label))
        <p class="mlp-masters__label mlp-meta">{{ $masters->label }}</p>
        @endif
      </div>
      @if(filled($masters->heading))
      <h2 class="mlp-masters__heading mlp-headline">{{ $masters->heading }}</h2>
      @endif
      @if(filled($masters->intro))
      <p class="mlp-masters__intro mlp-lede">{{ $masters->intro }}</p>
      @endif
    </header>

    <div class="mlp-masters__couture" data-mlp-reveal="masters-list">
      @if($plate)
      <figure class="mlp-masters__plate-lux" data-mlp-masters-showcase>
        <span class="mlp-masters__plate-lux-frame" aria-hidden="true"></span>
        <span class="mlp-masters__plate-lux-rule" aria-hidden="true"></span>
        <div class="mlp-masters__plate-lux-window">
          <img src="{{ $plate }}" alt="Master's programmes — a global standard of study" width="1600" height="1000" loading="lazy" decoding="async">
        </div>
        <figcaption class="mlp-masters__plate-lux-caption">
          <span>One standard</span>
          <span>Global cohort</span>
        </figcaption>
      </figure>
      @endif

      @if($programs->isNotEmpty())
      <ol class="mlp-masters__index" aria-label="Master's programmes index">
        @foreach($programs as $title)
        <li class="mlp-masters__row">
          <span class="mlp-masters__row-title">{{ $title }}</span>
          <span class="mlp-masters__row-mark" aria-hidden="true"></span>
        </li>
        @endforeach
      </ol>
      @endif
    </div>
  </div>
</section>
@endif
