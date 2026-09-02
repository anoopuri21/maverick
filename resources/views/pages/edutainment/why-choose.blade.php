{{-- ===== S8: WHY CHOOSE MAVERICK EDUTAINMENT? ===== --}}
@php
    $whyCards = collect($whyChoose->cards ?? [])->filter(fn ($card) => filled($card['title'] ?? null));
    $showWhyChoose = filled($whyChoose->label ?? null)
        || filled($whyChoose->title ?? null)
        || filled($whyChoose->title_italic ?? null)
        || $whyCards->isNotEmpty();
@endphp
@if($showWhyChoose)
<section id="edu-why-choose" class="edu-why-choose section--light section--warm section-wrapper" aria-label="Why Choose Maverick Edutainment">
  <div class="container">
    <div class="edu-why-choose__header">
      @include('pages.edutainment._section-heading', [
        'label' => $whyChoose->label,
        'title' => $whyChoose->title,
        'titleLine2' => $whyChoose->title_line2,
        'titleItalic' => $whyChoose->title_italic,
        'titleBreak' => $whyChoose->title_break,
      ])
    </div>

    @if($whyCards->isNotEmpty())
    <div class="edu-why-choose__grid">
      @foreach($whyCards as $card)
      <div class="edu-why-choose__card fade-up">
        @if(filled($card['icon_key'] ?? null))
        <div class="edu-why-choose__icon">
          <x-edu.icon :name="$card['icon_key']" :size="24" />
        </div>
        @endif
        <h4 class="edu-why-choose__title">{{ $card['title'] }}</h4>
        @if(filled($card['description'] ?? null))
        <div class="edu-why-choose__desc">{!! rich_html($card['description'] ?? null) !!}</div>
        @endif
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
