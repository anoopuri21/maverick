{{-- §6 MBA specializations — big campus plates + typographic program catalog --}}
@php
  $tabs = collect($mba->tabs ?? [])->filter(fn ($t) => filled($t['label'] ?? null))->values();
  $generatedImageBase = 'assets/images/mba-masters-landing/mba/';
  $generatedStage = $generatedImageBase.'mba-stage.jpg';
  $generatedImagesByTab = [
    'general' => [
      $generatedImageBase.'general-mba.jpg',
      $generatedImageBase.'business-management-mba.jpg',
    ],
    'specialized' => [$generatedImageBase.'specialized-mba.jpg'],
    'executive' => [$generatedImageBase.'executive-mba.jpg'],
    'global' => [$generatedImageBase.'global-mba.jpg'],
  ];
  // The MBA section is intentionally locked to its generated visual system.
  // This prevents stale admin/settings payloads from showing the old stock images.
  $stage = mlp_image_url($generatedStage, ['w' => 1920]);
  $fallbackCampus = $generatedStage;
@endphp
@if($tabs->isNotEmpty() || filled($mba->heading))
<section class="mlp-mba" id="mlp-mba" aria-label="MBA specializations">
  <div class="mlp-mba__wash" aria-hidden="true">
    <img class="mlp-mba__wash-img" src="{{ $stage }}" alt="" width="1920" height="1080" loading="lazy" decoding="async">
  </div>

  <div class="container mlp-mba__inner">
    <header class="mlp-mba__head" data-mlp-reveal="mba-head">
      <div class="mlp-mba__meta">
        @if(filled($mba->label))
        <p class="mlp-mba__label mlp-meta">{{ $mba->label }}</p>
        @endif
      </div>
      @if(filled($mba->heading))
      <h2 class="mlp-mba__heading mlp-headline">{{ $mba->heading }}</h2>
      @endif
      @if(filled($mba->intro))
      <p class="mlp-mba__intro mlp-lede">{{ $mba->intro }}</p>
      @endif
    </header>

    @if($tabs->isNotEmpty())
    <div class="mlp-mba__chrome" data-mlp-mba-tabs data-mlp-reveal="mba-chrome">
      <div class="mlp-mba__tablist" role="tablist" aria-label="MBA categories">
        @foreach($tabs as $ti => $tab)
        <button
          type="button"
          class="mlp-mba__tab{{ $ti === 0 ? ' is-active' : '' }}"
          role="tab"
          id="mlp-mba-tab-{{ $ti }}"
          aria-selected="{{ $ti === 0 ? 'true' : 'false' }}"
          aria-controls="mlp-mba-panel-{{ $ti }}"
          data-mlp-mba-tab="{{ $ti }}"
        >{{ $tab['label'] }}</button>
        @endforeach
      </div>

      @foreach($tabs as $ti => $tab)
      @php
        $unis = collect($tab['universities'] ?? [])->filter(fn ($u) => filled($u['name'] ?? null))->values();
      @endphp
      <div
        class="mlp-mba__panel{{ $ti === 0 ? ' is-active' : '' }}"
        role="tabpanel"
        id="mlp-mba-panel-{{ $ti }}"
        aria-labelledby="mlp-mba-tab-{{ $ti }}"
        @if($ti !== 0) hidden @endif
        data-mlp-mba-panel="{{ $ti }}"
      >
        @forelse($unis as $ui => $uni)
        @php
          $logo = media_url($uni['logo'] ?? null, null);
          $tabKey = strtolower(trim((string) ($tab['key'] ?? '')));
          $generatedPhoto = $generatedImagesByTab[$tabKey][$ui] ?? $fallbackCampus;
          $photo = mlp_image_url($generatedPhoto, ['w' => 1200, 'fallback' => $fallbackCampus]);
          $programs = collect($uni['programs'] ?? [])->filter(fn ($p) => filled($p['title'] ?? null))->values();
          $initials = collect(preg_split('/\s+/', (string) $uni['name']))
              ->filter()
              ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
              ->take(3)
              ->implode('');
          $flip = $ui % 2 === 1;
        @endphp
        <article class="mlp-mba__showcase{{ $flip ? ' mlp-mba__showcase--flip' : '' }}" data-mlp-mba-showcase>
          <figure class="mlp-mba__plate">
            <img class="mlp-mba__uni-photo" src="{{ $photo }}" alt="{{ $uni['name'] }} campus" width="1200" height="800" loading="lazy" decoding="async">
            <figcaption class="mlp-mba__plate-bar">
              <span class="mlp-mba__uni-logo">
                @if($logo)
                <img src="{{ $logo }}" alt="{{ $uni['name'] }} logo" loading="lazy" decoding="async">
                @else
                <span class="mlp-mba__uni-initials" aria-hidden="true">{{ $initials }}</span>
                @endif
              </span>
              <span class="mlp-mba__plate-copy">
                <span class="mlp-mba__uni-name">{{ $uni['name'] }}</span>
                <span class="mlp-mba__uni-count">{{ $programs->count() }} {{ $programs->count() === 1 ? 'program' : 'programs' }}</span>
              </span>
            </figcaption>
          </figure>

          <div class="mlp-mba__catalog">
            <p class="mlp-mba__catalog-label mlp-meta">Programs</p>
            @if($programs->isNotEmpty())
            <ol class="mlp-mba__programs">
              @foreach($programs as $pi => $program)
              <li class="mlp-mba__program">
                <span class="mlp-mba__program-index" aria-hidden="true">{{ str_pad((string) ($pi + 1), 2, '0', STR_PAD_LEFT) }}</span>
                <span class="mlp-mba__program-title">{{ $program['title'] }}</span>
                <span class="mlp-mba__program-mark" aria-hidden="true"></span>
              </li>
              @endforeach
            </ol>
            @else
            <p class="mlp-mba__empty">Programs for this university will appear here.</p>
            @endif
          </div>
        </article>
        @empty
        <p class="mlp-mba__empty">Programs for this category will appear here.</p>
        @endforelse
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
