{{-- §6 MBA specializations — PDF catalogue with separate specialization and specification records --}}
@php
  $toSpecialization = static function ($program): ?array {
    $title = trim((string) ($program['title'] ?? ''));

    if ($title === '' || strcasecmp($title, 'Global MBA') === 0 || strcasecmp($title, 'Master of Business Administration (MBA)') === 0) {
      return null;
    }

    $title = preg_replace('/^(?:Executive\s+)?MBA\s+in\s+/i', '', $title) ?: $title;

    return ['title' => trim($title)];
  };
  $tabs = collect($mba->tabs ?? [])
    ->filter(fn ($tab) => filled($tab['label'] ?? null))
    ->map(function (array $tab) use ($toSpecialization): array {
      $tab['universities'] = collect($tab['universities'] ?? [])
        ->map(function (array $university) use ($toSpecialization): array {
          $university['specializations'] = collect($university['programs'] ?? [])
            ->map($toSpecialization)
            ->filter()
            ->values()
            ->all();

          return $university;
        })
        ->filter(fn (array $university) => ! empty($university['specializations']))
        ->values()
        ->all();

      return $tab;
    })
    ->filter(fn (array $tab) => ! empty($tab['universities']))
    ->values();
  $generatedImageBase = 'assets/images/mba-masters-landing/mba/';
  $generatedStage = $generatedImageBase.'mba-stage.jpg';
  $generatedImagesByTab = [
    'rbs-mba' => [$generatedImageBase.'specialized-mba.jpg'],
    'gau-mba' => [$generatedImageBase.'business-management-mba.jpg'],
    'gau-emba' => [$generatedImageBase.'executive-mba.jpg'],
    'uca-global-mba' => [$generatedImageBase.'global-mba.jpg'],
  ];
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
        <p class="mlp-mba__label mlp-meta mlp-eyebrow">{{ $mba->label }}</p>
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
      <div class="mlp-mba__tablist" role="tablist" aria-label="MBA specialization categories from programme listing">
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
        @foreach($unis as $ui => $uni)
        @php
          $logo = settings_media_url($uni, 'logo');
          $tabKey = strtolower(trim((string) ($tab['key'] ?? '')));
          $generatedPhoto = $generatedImagesByTab[$tabKey][$ui] ?? $fallbackCampus;
          $photo = mlp_image_url($generatedPhoto, ['w' => 1200, 'fallback' => $fallbackCampus]);
          $specializations = collect($uni['specializations'] ?? [])->filter(fn ($specialization) => filled($specialization['title'] ?? null))->values();
          $specializationColumns = $specializations->count() > 8
            ? $specializations->chunk((int) ceil($specializations->count() / 2))->values()
            : collect([$specializations]);
          $initials = collect(preg_split('/\s+/', (string) $uni['name']))
              ->filter()
              ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
              ->take(3)
              ->implode('');
          $flip = $ui % 2 === 1;
        @endphp
        <article class="mlp-mba__showcase{{ $flip ? ' mlp-mba__showcase--flip' : '' }}" data-mlp-mba-showcase>
          <figure class="mlp-mba__plate">
            <img class="mlp-mba__uni-photo" src="{{ $photo }}" alt="{{ $uni['name'] }}" width="1200" height="800" loading="lazy" decoding="async">
            <figcaption class="mlp-mba__plate-bar">
              <!-- <span class="mlp-mba__uni-logo">
                @if($logo)
                <img src="{{ $logo }}" alt="{{ $uni['name'] }} logo" loading="lazy" decoding="async">
                @else
                <span class="mlp-mba__uni-initials" aria-hidden="true">{{ $initials }}</span>
                @endif
              </span> -->
              <span class="mlp-mba__plate-copy">
                <!-- <span class="mlp-mba__uni-name">{{ $uni['name'] }}</span> -->
                <span class="mlp-mba__uni-count">{{ $specializations->count() }} {{ $specializations->count() === 1 ? 'specialization' : 'specializations' }}</span>
              </span>
            </figcaption>
          </figure>

          <div class="mlp-mba__catalog-layout">
            <div class="mlp-mba__catalog">
              <p class="mlp-mba__catalog-label mlp-meta">Specializations</p>
              <ol
                class="mlp-mba__programs{{ $specializationColumns->count() > 1 ? ' mlp-mba__programs--columns' : '' }}"
                aria-label="{{ $uni['name'] }} specializations"
                @if($specializationColumns->count() > 1) style="--mlp-specialization-rows: {{ $specializationColumns->first()->count() }}" @endif
              >
                @php $specializationOffset = 0; @endphp
                @foreach($specializationColumns as $column)
                @foreach($column as $si => $specialization)
                <li class="mlp-mba__program">
                  <span class="mlp-mba__program-index" aria-hidden="true">{{ str_pad((string) ($specializationOffset + $si + 1), 2, '0', STR_PAD_LEFT) }}</span>
                  <span class="mlp-mba__program-title">{{ $specialization['title'] }}</span>
                </li>
                @endforeach
                @php $specializationOffset += $column->count(); @endphp
                @endforeach
              </ol>
            </div>
          </div>
        </article>
        @endforeach
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
