{{-- ===== S11: FREQUENTLY ASKED QUESTIONS ===== --}}
@php
    $faqItems = collect($faq->items ?? [])->filter(fn ($item) => filled($item['question'] ?? null));
    $showFaq = filled($faq->label ?? null)
        || filled($faq->title ?? null)
        || filled($faq->title_italic ?? null)
        || $faqItems->isNotEmpty();
@endphp
@if($showFaq)
<section id="edu-faq" class="edu-faq section-wrapper section--light" aria-label="Frequently Asked Questions">
  <div class="container">
    <div class="edu-faq__header">
      @include('pages.edutainment._section-heading', [
        'label' => $faq->label,
        'title' => $faq->title,
        'titleLine2' => $faq->title_line2,
        'titleItalic' => $faq->title_italic,
        'titleBreak' => $faq->title_break,
      ])
    </div>

    @if($faqItems->isNotEmpty())
    <div class="edu-faq__list">
      @foreach($faqItems as $index => $faqItem)
      <div class="edu-faq__item" data-testid="edu-faq-item-{{ $index + 1 }}">
        <button class="edu-faq__question" type="button" data-testid="edu-faq-q-{{ $index + 1 }}">
          {{ $faqItem['question'] }}
          <svg class="edu-faq__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        @if(html_filled($faqItem['answer'] ?? null))
        <div class="edu-faq__answer">
          <div class="edu-faq__answer-inner edu-richtext">
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
