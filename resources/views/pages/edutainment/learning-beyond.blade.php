{{-- ===== S3: LEARNING BEYOND THE CLASSROOM ===== --}}
@php
    $learningCards = collect($learning->cards ?? [])->filter(fn ($card) => filled($card['title'] ?? null));
    $showLearning = filled($learning->label ?? null)
        || filled($learning->title ?? null)
        || filled($learning->title_italic ?? null)
        || html_filled($learning->body ?? null)
        || filled($learning->image ?? null)
        || $learningCards->isNotEmpty();
@endphp
@if($showLearning)
<section id="edu-learning-beyond" class="edu-learning-beyond section-wrapper section--light" aria-label="Learning Beyond the Classroom">
  <div class="container">
    @if(filled($learning->label) || filled($learning->title) || filled($learning->title_italic) || html_filled($learning->body ?? null))
    <div class="edu-learning-beyond__header">
      @include('pages.edutainment._section-heading', [
        'label' => $learning->label,
        'title' => $learning->title,
        'titleLine2' => $learning->title_line2,
        'titleItalic' => $learning->title_italic,
        'titleBreak' => $learning->title_break,
      ])
      @if(html_filled($learning->body ?? null))
      <div class="edu-richtext fade-up">{!! $learning->body !!}</div>
      @endif
    </div>
    @endif

    @if(filled($learning->image))
    <div class="edu-learning-beyond__media fade-up">
      <img src="{{ media_url($learning->image) }}" alt="{{ $learning->title ?? 'Learning beyond the classroom' }}" loading="lazy" />
    </div>
    @endif

    @if($learningCards->isNotEmpty())
      @if(filled($learning->cards_heading))
      <h3 class="edu-learning-beyond__subheading fade-up">{{ $learning->cards_heading }}</h3>
      @endif

      <div class="edu-learning-beyond__grid">
        @foreach($learningCards as $card)
        <div class="edu-learning-beyond__card fade-up">
          @if(filled($card['icon'] ?? null))
          <div class="edu-learning-beyond__card-icon">{{ $card['icon'] }}</div>
          @endif
          <h4 class="edu-learning-beyond__card-title">{{ $card['title'] }}</h4>
        </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
@endif
