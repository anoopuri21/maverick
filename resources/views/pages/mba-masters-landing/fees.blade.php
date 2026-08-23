{{-- §8 Fees & payment — elegant matrix + plate --}}
@php
  $rows = collect($fees->rows ?? [])->filter(fn ($r) => filled($r['program'] ?? null))->values();
  $stage = mlp_image_url($fees->stage_image ?? null, ['w' => 1920, 'fallback' => 'assets/images/programs/enquire-seminar.jpg']);
@endphp
@if($rows->isNotEmpty() || filled($fees->heading))
<section class="mlp-fees" id="mlp-fees" aria-label="Fees and payment options">
  <div class="mlp-fees__stage" aria-hidden="true">
    <img class="mlp-fees__stage-img" src="{{ $stage }}" alt="" width="1920" height="1080" loading="lazy" decoding="async">
    <div class="mlp-fees__stage-veil"></div>
  </div>

  <div class="container mlp-fees__inner">
    <header class="mlp-fees__head" data-mlp-reveal="fees-head">
      <div class="mlp-fees__meta">
        @if(filled($fees->label))
        <p class="mlp-fees__label mlp-meta">{{ $fees->label }}</p>
        @endif
      </div>
      @if(filled($fees->heading))
      <h2 class="mlp-fees__heading mlp-headline">{{ $fees->heading }}</h2>
      @endif
      @if(filled($fees->intro))
      <p class="mlp-fees__intro mlp-lede">{{ $fees->intro }}</p>
      @endif
    </header>

    @if($rows->isNotEmpty())
    <div class="mlp-fees__matrix" data-mlp-reveal="fees-matrix" role="table" aria-label="Fee overview">
      <div class="mlp-fees__row mlp-fees__row--head" role="row">
        <span role="columnheader">Program</span>
        <span role="columnheader">Duration</span>
        <span role="columnheader">Study mode</span>
        <span role="columnheader">Fee / payment</span>
        <span role="columnheader"><span class="mlp-vh">Advisor</span></span>
      </div>
      @foreach($rows as $i => $row)
      <div class="mlp-fees__row" role="row" style="--mlp-i: {{ $i }}">
        <span class="mlp-fees__cell mlp-fees__cell--program" role="cell" data-label="Program">{{ $row['program'] }}</span>
        <span class="mlp-fees__cell" role="cell" data-label="Duration">{{ $row['duration'] ?? '—' }}</span>
        <span class="mlp-fees__cell" role="cell" data-label="Study mode">{{ $row['mode'] ?? '—' }}</span>
        <span class="mlp-fees__cell mlp-fees__cell--fee" role="cell" data-label="Fee / payment">
          <strong>{{ $row['fee'] ?? '—' }}</strong>
          @if(filled($row['payment'] ?? null))
          <em>{{ $row['payment'] }}</em>
          @endif
        </span>
        <span class="mlp-fees__cell mlp-fees__cell--cta" role="cell">
          <a href="#mlp-enquire" class="mlp-fees__link">Speak to advisor</a>
        </span>
      </div>
      @endforeach
    </div>
    @endif

    @if(filled($fees->note))
    <p class="mlp-fees__note" data-mlp-reveal="fees-note">{{ $fees->note }}</p>
    @endif

    @if(filled($fees->cta_primary_label) || filled($fees->cta_secondary_label))
    <div class="mlp-fees__ctas" data-mlp-reveal="fees-ctas">
      @if(filled($fees->cta_primary_label))
      <a href="{{ edu_href($fees->cta_primary_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--primary">{{ $fees->cta_primary_label }}</a>
      @endif
      @if(filled($fees->cta_secondary_label))
      <a href="{{ edu_href($fees->cta_secondary_url) ?? '#mlp-enquire' }}" class="mlp-btn mlp-btn--ghost">{{ $fees->cta_secondary_label }}</a>
      @endif
    </div>
    @endif
  </div>
</section>
@endif
