{{-- §16 Comparison — Versus duel + mobile Online/Campus tabs --}}
@php
  $rows = collect($compare->rows ?? [])
      ->filter(fn ($row) => filled($row['criterion'] ?? null))
      ->values();
  $blocks = collect($compare->blocks ?? [])
      ->filter(fn ($b) => filled($b['title'] ?? null))
      ->values();
  $colOnline = $compare->col_online ?? 'Online with Maverick';
  $colTraditional = $compare->col_traditional ?? 'Traditional campus';
  $icons = [
    'fees' => 'wallet',
    'commute' => 'map-pin',
    'timing' => 'clock',
    'schedule' => 'calendar',
    'visa' => 'id-card',
    'working' => 'briefcase',
    'award' => 'award',
    'networking' => 'users',
    'location' => 'map-pin',
    'duration' => 'clock',
    'career continuity' => 'trending-up',
    'awarding body' => 'building-2',
  ];
@endphp

@if(filled($compare->heading) || $rows->isNotEmpty())
<section class="mlp-compare archive-parallel" id="mlp-compare" aria-labelledby="archive-parallel-title">
  <div class="archive-parallel__frame container">
    <header class="archive-parallel__intro" data-mlp-reveal="compare-head">
      <div>
        @if(filled($compare->label))
        <p class="archive-parallel__label">{{ $compare->label }}</p>
        @endif
        @if(filled($compare->heading))
        <h2 class="archive-parallel__heading" id="archive-parallel-title">{{ $compare->heading }}</h2>
        @endif
      </div>
      @if(filled($compare->intro))
      <p class="archive-parallel__intro-copy">{{ $compare->intro }}</p>
      @endif
    </header>

    @if($rows->isNotEmpty())
    <div class="archive-parallel__brief" aria-label="Online and traditional study comparison" data-mlp-reveal="compare-matrix">
      <div class="archive-parallel__tabs" role="tablist" aria-label="Compare format">
        <button
          type="button"
          class="archive-parallel__tab is-active"
          role="tab"
          id="compare-tab-online"
          aria-selected="true"
          aria-controls="compare-pane-online"
          data-compare-tab="online"
        >
          <i data-lucide="monitor" aria-hidden="true"></i>
          <span>{{ $colOnline }}</span>
        </button>
        <button
          type="button"
          class="archive-parallel__tab"
          role="tab"
          id="compare-tab-traditional"
          aria-selected="false"
          aria-controls="compare-pane-traditional"
          data-compare-tab="traditional"
        >
          <i data-lucide="building-2" aria-hidden="true"></i>
          <span>{{ $colTraditional }}</span>
        </button>
      </div>

      <div class="archive-parallel__columns" role="row">
        <span class="archive-parallel__col-spacer" aria-hidden="true"></span>
        <span class="archive-parallel__col archive-parallel__col--online" role="columnheader">
          <i data-lucide="monitor" aria-hidden="true"></i>
          {{ $colOnline }}
        </span>
        <span class="archive-parallel__col archive-parallel__col--traditional" role="columnheader">
          <i data-lucide="building-2" aria-hidden="true"></i>
          {{ $colTraditional }}
        </span>
      </div>

      <div class="archive-parallel__rows" role="rowgroup">
        @foreach($rows as $i => $row)
        @php
          $criterion = strtolower(trim((string) $row['criterion']));
          $icon = collect($icons)->first(fn ($value, $key) => str_contains($criterion, $key)) ?? 'circle-dot';
        @endphp
        <article class="archive-parallel__row" role="row" data-closing-element style="--mlp-i: {{ $i }}">
          <h3 class="archive-parallel__criterion" role="rowheader">
            <span class="archive-parallel__criterion-icon" aria-hidden="true"><i data-lucide="{{ $icon }}"></i></span>
            <span class="archive-parallel__criterion-text">{{ $row['criterion'] }}</span>
          </h3>
          <div
            class="archive-parallel__side archive-parallel__side--online is-active"
            role="cell"
            data-compare-pane="online"
            @if($i === 0) id="compare-pane-online" @endif
          >
            <span class="archive-parallel__side-label">{{ $colOnline }}</span>
            <div class="archive-parallel__prose mlp-prose">{!! \App\Support\MlpProse::html($row['online'] ?? '') !!}</div>
          </div>
          <div
            class="archive-parallel__side archive-parallel__side--traditional"
            role="cell"
            data-compare-pane="traditional"
            @if($i === 0) id="compare-pane-traditional" @endif
          >
            <span class="archive-parallel__side-label">{{ $colTraditional }}</span>
            <div class="archive-parallel__prose mlp-prose">{!! \App\Support\MlpProse::html($row['traditional'] ?? '') !!}</div>
          </div>
        </article>
        @endforeach
      </div>
    </div>
    @endif

    @if($blocks->isNotEmpty())
    <ul class="archive-parallel__blocks" aria-label="Format notes">
      @foreach($blocks as $block)
      <li class="archive-parallel__block">
        <h3>{{ $block['title'] }}</h3>
        @if(filled($block['text'] ?? null))
        <p>{{ $block['text'] }}</p>
        @endif
      </li>
      @endforeach
    </ul>
    @endif
  </div>
</section>
@endif
