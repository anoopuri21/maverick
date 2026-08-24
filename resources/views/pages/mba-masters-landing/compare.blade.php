{{-- §15 Comparison — The Closing Archive / Parallel Brief --}}
@php
  $rows = collect($compare->rows ?? [])
      ->filter(fn ($row) => filled($row['criterion'] ?? null))
      ->values();
  $icons = [
    'schedule' => 'calendar',
    'location' => 'map-pin',
    'duration' => 'clock',
    'cost of living' => 'wallet',
    'career continuity' => 'trending-up',
    'awarding body' => 'building-2',
  ];
@endphp

@if(filled($compare->heading) || $rows->isNotEmpty())
<section class="mlp-compare archive-parallel" id="mlp-compare" aria-labelledby="archive-parallel-title">
  <div class="archive-parallel__frame container">
    <header class="archive-parallel__intro">
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
    <div class="archive-parallel__brief">
      <div class="archive-parallel__columns" aria-hidden="true">
        <span></span>
        <span><i data-lucide="monitor"></i>{{ $compare->col_online ?? 'Online with Maverick' }}</span>
        <span><i data-lucide="building-2"></i>{{ $compare->col_traditional ?? 'Traditional campus' }}</span>
      </div>
      <div class="archive-parallel__rows">
        @foreach($rows as $row)
        @php
          $criterion = strtolower(trim((string) $row['criterion']));
          $icon = collect($icons)->first(fn ($value, $key) => str_contains($criterion, $key)) ?? 'circle-dot';
        @endphp
        <article class="archive-parallel__row" data-closing-element>
          <h3 class="archive-parallel__criterion">
            <span aria-hidden="true"><i data-lucide="{{ $icon }}"></i></span>
            {{ $row['criterion'] }}
          </h3>
          <div class="archive-parallel__side archive-parallel__side--online">
            <span class="archive-parallel__side-label">{{ $compare->col_online ?? 'Online with Maverick' }}</span>
            <div class="archive-parallel__prose mlp-prose">{!! \App\Support\MlpProse::html($row['online'] ?? '') !!}</div>
          </div>
          <div class="archive-parallel__side archive-parallel__side--traditional">
            <span class="archive-parallel__side-label">{{ $compare->col_traditional ?? 'Traditional campus' }}</span>
            <div class="archive-parallel__prose mlp-prose">{!! \App\Support\MlpProse::html($row['traditional'] ?? '') !!}</div>
          </div>
        </article>
        @endforeach
      </div>
    </div>
    @endif

    @if(filled($compare->cta_label))
    <div class="archive-parallel__actions">
      <a href="{{ edu_href($compare->cta_url) ?? '#mlp-enquire' }}" class="archive-parallel__primary">{{ $compare->cta_label }} <span aria-hidden="true">↗</span></a>
    </div>
    @endif
  </div>
</section>
@endif
