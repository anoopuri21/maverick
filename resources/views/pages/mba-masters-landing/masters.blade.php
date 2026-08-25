{{-- §7 Master's programs — big campus plates + typographic program catalog --}}
@php
  $universities = collect($masters->universities ?? [])->filter(fn ($u) => filled($u['name'] ?? null))->values();
  $stage = mlp_image_url($masters->stage_image ?? null, ['w' => 1920, 'fallback' => 'assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg']);
  $fallbackCampus = 'assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg';
@endphp
@if($universities->isNotEmpty() || filled($masters->heading))
<section class="mlp-masters" id="mlp-masters" aria-label="Master's programs">
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
          <img class="mlp-masters__uni-photo" src="{{ $photo }}" alt="{{ $uni['name'] }} campus" width="1200" height="800" loading="lazy" decoding="async">
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
              <span class="mlp-masters__uni-count">{{ $programs->count() }} {{ $programs->count() === 1 ? 'program' : 'programs' }}</span>
            </span>
          </figcaption>
        </figure>

        <div class="mlp-masters__catalog">
          <p class="mlp-masters__catalog-label mlp-meta">Programme specifications</p>
          @if($programs->isNotEmpty())
          <ol class="mlp-masters__programs">
            @foreach($programs as $pi => $program)
            @php
              $specification = is_array($program['specification'] ?? null) ? $program['specification'] : [];
            @endphp
            <li class="mlp-masters__program" data-program-spec>
              <details>
                <summary>
                  <span class="mlp-masters__program-index" aria-hidden="true">{{ str_pad((string) ($pi + 1), 2, '0', STR_PAD_LEFT) }}</span>
                  <span class="mlp-masters__program-title">{{ $program['title'] }}</span>
                  <span class="mlp-masters__program-mark" aria-hidden="true"></span>
                </summary>
                @if($specification !== [])
                <div class="mlp-masters__specification">
                  <div class="mlp-masters__specification-focus">
                    <span>Programme focus</span>
                    <strong>{{ $specification['focus'] ?? $program['title'] }}</strong>
                  </div>
                  <dl>
                    @foreach([
                      'qualification' => 'Qualification',
                      'partner' => 'Partner / awarding route',
                      'duration' => 'Duration',
                      'delivery' => 'Delivery mode',
                      'assessment' => 'Assessment',
                      'entry' => 'Entry route',
                    ] as $specKey => $specLabel)
                    @if(filled($specification[$specKey] ?? null))
                    <div>
                      <dt>{{ $specLabel }}</dt>
                      <dd>{{ $specification[$specKey] }}</dd>
                    </div>
                    @endif
                    @endforeach
                  </dl>
                  @if(filled($specification['source_status'] ?? null))
                  <p class="mlp-masters__specification-note">{{ $specification['source_status'] }}</p>
                  @endif
                </div>
                @endif
              </details>
            </li>
            @endforeach
          </ol>
          @else
          <p class="mlp-masters__empty">Programs for this university will appear here.</p>
          @endif
        </div>
      </article>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
