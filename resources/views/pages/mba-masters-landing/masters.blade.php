{{-- §7 Master's programmes — PDF catalogue with separate programme and specification records --}}
@php
  $universities = collect($masters->universities ?? [])->filter(fn ($u) => filled($u['name'] ?? null))->values();
  $stage = mlp_image_url($masters->stage_image ?? null, ['w' => 1920, 'fallback' => 'assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg']);
  $fallbackCampus = 'assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg';
@endphp
@if($universities->isNotEmpty() || filled($masters->heading))
<section class="mlp-masters" id="mlp-masters" aria-label="Master's programmes">
  <div class="mlp-masters__wash" aria-hidden="true">
    <img class="mlp-masters__wash-img" src="{{ $stage }}" alt="" width="1920" height="1080" loading="lazy" decoding="async">
  </div>

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

    @if($universities->isNotEmpty())
    <div class="mlp-masters__gallery" data-mlp-reveal="masters-list">
      @foreach($universities as $ui => $uni)
      @php
        $logo = media_url($uni['logo'] ?? null, null);
        $photo = mlp_image_url($uni['image'] ?? null, ['w' => 1200, 'fallback' => $fallbackCampus]);
        $programs = collect($uni['programs'] ?? [])->filter(fn ($p) => filled($p['title'] ?? null))->values();
        $initials = collect(preg_split('/\s+/', (string) $uni['name']))
            ->filter()
            ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
            ->take(3)
            ->implode('');
        $flip = $ui % 2 === 1;
      @endphp
      <article class="mlp-masters__showcase{{ $flip ? ' mlp-masters__showcase--flip' : '' }}" data-mlp-masters-showcase>
        <figure class="mlp-masters__plate">
          <img class="mlp-masters__uni-photo" src="{{ $photo }}" alt="{{ $uni['name'] }}" width="1200" height="800" loading="lazy" decoding="async">
          <figcaption class="mlp-masters__plate-bar">
            <span class="mlp-masters__uni-logo">
              @if($logo)
              <img src="{{ $logo }}" alt="{{ $uni['name'] }} logo" loading="lazy" decoding="async">
              @else
              <span class="mlp-masters__uni-initials" aria-hidden="true">{{ $initials }}</span>
              @endif
            </span>
            <span class="mlp-masters__plate-copy">
              <span class="mlp-masters__uni-name">{{ $uni['name'] }}</span>
              <span class="mlp-masters__uni-count">{{ $programs->count() }} {{ $programs->count() === 1 ? 'programme' : 'programmes' }}</span>
            </span>
          </figcaption>
        </figure>

        <div class="mlp-masters__catalog-layout">
          <div class="mlp-masters__catalog">
            <p class="mlp-masters__catalog-label mlp-meta">Programmes</p>
            @if($programs->isNotEmpty())
            <ol class="mlp-masters__programs" aria-label="{{ $uni['name'] }} programmes">
              @foreach($programs as $pi => $program)
              <li class="mlp-masters__program">
                <span class="mlp-masters__program-index" aria-hidden="true">{{ str_pad((string) ($pi + 1), 2, '0', STR_PAD_LEFT) }}</span>
                <span class="mlp-masters__program-title">{{ $program['title'] }}</span>
              </li>
              @endforeach
            </ol>
            @else
            <p class="mlp-masters__empty">No programmes listed.</p>
            @endif
          </div>

        </div>
      </article>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
