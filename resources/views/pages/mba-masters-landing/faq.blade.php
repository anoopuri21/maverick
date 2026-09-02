{{-- §16 FAQ — The Closing Archive / Field Notes --}}
@php
  $items = collect($faq->items ?? [])
      ->filter(fn ($item) => filled($item['question'] ?? null))
      ->values();
@endphp

@if(filled($faq->heading) || $items->isNotEmpty())
<section class="mlp-faq archive-fieldnotes" id="mlp-faq" aria-labelledby="archive-fieldnotes-title">
  <div class="archive-fieldnotes__background" aria-hidden="true">
    <span class="archive-fieldnotes__wash mlp-wash"></span>
    <span class="archive-fieldnotes__rule"></span>
  </div>

  <div class="archive-fieldnotes__frame container">
    <header class="archive-fieldnotes__intro">
      <div>
        @if(filled($faq->label))
        <p class="archive-fieldnotes__label mlp-eyebrow">{{ $faq->label }}</p>
        @endif
        @if(filled($faq->heading))
        <h2 class="archive-fieldnotes__heading mlp-h2" id="archive-fieldnotes-title">{{ $faq->heading }}</h2>
        @endif
      </div>
      <p class="archive-fieldnotes__intro-copy">A few useful notes before you take the next step.</p>
    </header>

    @if($items->isNotEmpty())
    <div class="archive-fieldnotes__list" data-mlp-faq-list>
      @foreach($items as $fi => $item)
      @php $panelId = 'mlp-faq-panel-'.($fi + 1); @endphp
      <article class="archive-fieldnotes__note" data-mlp-faq-row data-closing-element>
        <h3>
          <button type="button" class="archive-fieldnotes__question" aria-expanded="false" aria-controls="{{ $panelId }}" data-mlp-faq-toggle>
            <span class="archive-fieldnotes__question-icon" aria-hidden="true"><i data-lucide="plus"></i></span>
            <span>{{ $item['question'] }}</span>
          </button>
        </h3>
        <div class="archive-fieldnotes__answer mlp-prose" id="{{ $panelId }}" role="region" hidden data-mlp-faq-panel>
          {!! \App\Support\MlpProse::html($item['answer'] ?? '') !!}
        </div>
      </article>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
