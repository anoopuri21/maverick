{{-- ===== S10: FAQ ACCORDION ===== --}}
@php
    $faqItems = collect($faq->items ?? [])->filter(fn ($item) => filled($item['question'] ?? null));
    $showFaq = filled($faq->label ?? null) || filled($faq->title ?? null) || $faqItems->isNotEmpty();
@endphp
@if($showFaq)
<section class="dmba-faq section--light section-wrapper" aria-label="Frequently Asked Questions" data-testid="dmba-faq-section">
  <div class="container">
    <div class="dmba-faq__header">
      @if(filled($faq->label))
      <div class="section-label"><span>{{ $faq->label }}</span></div>
      @endif
      @if(filled($faq->title))
      <h2 class="section-title">{{ $faq->title }}</h2>
      @endif
    </div>

    @if($faqItems->isNotEmpty())
    <div class="dmba-faq__list" data-testid="dmba-faq-list">
      @foreach($faqItems as $faqItem)
      <div class="dmba-faq__item" data-testid="dmba-faq-item-{{ $loop->iteration }}">
        <button class="dmba-faq__question" type="button" data-testid="dmba-faq-q-{{ $loop->iteration }}">
          {{ $faqItem['question'] }}
          <svg class="dmba-faq__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        @if(html_filled($faqItem['answer'] ?? null))
        <div class="dmba-faq__answer">
          <div class="dmba-faq__answer-inner dmba-richtext">
            {!! rich_html($faqItem['answer'] ?? null) !!}
          </div>
        </div>
        @endif
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
