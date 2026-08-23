{{-- §16 FAQ — full-bleed dark accordion rows --}}
@php
  $items = collect($faq->items ?? [])->filter(fn ($i) => filled($i['question'] ?? null))->values();
@endphp
@if(filled($faq->heading) || $items->isNotEmpty())
<section class="mlp-faq" id="mlp-faq" aria-label="Frequently asked questions">
  <div class="mlp-faq__deco" aria-hidden="true">
    <span class="mlp-faq__orb mlp-faq__orb--a"></span>
    <span class="mlp-faq__orb mlp-faq__orb--b"></span>
  </div>

  <div class="container mlp-faq__inner">
    <header class="mlp-faq__head" data-mlp-reveal="faq-head">
      <div class="mlp-faq__meta">
        @if(filled($faq->label))
        <p class="mlp-faq__label mlp-meta">{{ $faq->label }}</p>
        @endif
      </div>
      @if(filled($faq->heading))
      <h2 class="mlp-faq__heading mlp-headline">{{ $faq->heading }}</h2>
      @endif
    </header>
  </div>

  @if($items->isNotEmpty())
  <div class="mlp-faq__list" data-mlp-faq-list>
    @foreach($items as $fi => $item)
    @php $panelId = 'mlp-faq-panel-'.($fi + 1); @endphp
    <div class="mlp-faq__row" data-mlp-faq-row>
      <h3 class="mlp-faq__q-wrap">
        <button
          type="button"
          class="mlp-faq__q"
          aria-expanded="false"
          aria-controls="{{ $panelId }}"
          data-mlp-faq-toggle
        >
          <span class="mlp-faq__q-index" aria-hidden="true">{{ str_pad((string) ($fi + 1), 2, '0', STR_PAD_LEFT) }}</span>
          <span class="mlp-faq__q-text">{{ $item['question'] }}</span>
          <span class="mlp-faq__q-icon" aria-hidden="true"></span>
        </button>
      </h3>
      <div class="mlp-faq__a" id="{{ $panelId }}" role="region" hidden data-mlp-faq-panel>
        <div class="mlp-faq__a-inner mlp-prose mlp-prose--on-dark">
          {!! \App\Support\MlpProse::html($item['answer'] ?? '') !!}
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif
</section>
@endif
