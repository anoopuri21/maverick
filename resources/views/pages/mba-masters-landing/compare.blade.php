{{-- §15 Comparison — editorial Online vs traditional matrix --}}
@php
  $rows = collect($compare->rows ?? [])->filter(fn ($r) => filled($r['criterion'] ?? null))->values();
@endphp
@if(filled($compare->heading) || $rows->isNotEmpty())
<section class="mlp-compare" id="mlp-compare" aria-label="Online versus traditional comparison">
  <div class="mlp-compare__deco" aria-hidden="true">
    <span class="mlp-compare__deco-rule"></span>
  </div>

  <div class="container mlp-compare__inner">
    <header class="mlp-compare__head" data-mlp-reveal="compare-head">
      <div class="mlp-compare__meta">
        @if(filled($compare->label))
        <p class="mlp-compare__label mlp-meta">{{ $compare->label }}</p>
        @endif
      </div>
      @if(filled($compare->heading))
      <h2 class="mlp-compare__heading mlp-headline">{{ $compare->heading }}</h2>
      @endif
      @if(filled($compare->intro))
      <p class="mlp-compare__intro mlp-lede">{{ $compare->intro }}</p>
      @endif
    </header>

    @if($rows->isNotEmpty())
    <div class="mlp-compare__matrix" data-mlp-reveal="compare-matrix" role="table" aria-label="Comparison matrix">
      <div class="mlp-compare__row mlp-compare__row--head" role="row">
        <div class="mlp-compare__cell mlp-compare__cell--criterion" role="columnheader">&nbsp;</div>
        <div class="mlp-compare__cell mlp-compare__cell--online" role="columnheader">{{ $compare->col_online ?? 'Online' }}</div>
        <div class="mlp-compare__cell mlp-compare__cell--traditional" role="columnheader">{{ $compare->col_traditional ?? 'Traditional' }}</div>
      </div>
      @foreach($rows as $row)
      <div class="mlp-compare__row" role="row">
        <div class="mlp-compare__cell mlp-compare__cell--criterion" role="rowheader">{{ $row['criterion'] }}</div>
        <div class="mlp-compare__cell mlp-compare__cell--online mlp-prose" role="cell" data-label="{{ $compare->col_online ?? 'Online' }}">{!! \App\Support\MlpProse::html($row['online'] ?? '') !!}</div>
        <div class="mlp-compare__cell mlp-compare__cell--traditional mlp-prose" role="cell" data-label="{{ $compare->col_traditional ?? 'Traditional' }}">{!! \App\Support\MlpProse::html($row['traditional'] ?? '') !!}</div>
      </div>
      @endforeach
    </div>
    @endif

    @if(filled($compare->cta_label))
    <div class="mlp-compare__ctas" data-mlp-reveal="compare-cta">
      <a href="{{ edu_href($compare->cta_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--primary">{{ $compare->cta_label }}</a>
    </div>
    @endif
  </div>
</section>
@endif
