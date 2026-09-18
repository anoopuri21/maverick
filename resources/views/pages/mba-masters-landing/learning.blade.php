{{-- §14 Learning - How Online Learning Actually Works - The Learning Atelier --}}
@php
  use App\Support\MlpProse;
  // PDF exact content, with fallback to settings
  $learningHeading = $learning->heading ?? 'How Online Learning Actually Works';
  $learningIntro = $learning->intro ?? 'Not recorded videos that gather dust. Structured learning with real people around you. The platform is built for busy schedules: focused modules, clear weekly goals, and support that replies within one working day.';
  $points = collect($learning->points ?? [])
      ->filter(fn ($p) => filled($p['title'] ?? null))
      ->values();
  if ($points->isEmpty()) {
      $points = collect([
          ['title' => 'Live evening classes', 'text' => 'Two live sessions per week, recorded if you miss one'],
          ['title' => 'Dedicated success coach', 'text' => 'One named coach for your whole degree, from induction to graduation'],
          ['title' => 'Online exams, from home', 'text' => 'No travel for assessments. Clear rubrics, timely feedback'],
          ['title' => 'Project on your own business', 'text' => 'Apply each module to a live challenge from your workplace'],
          ['title' => 'Career-relevant assessment', 'text' => 'Projects, presentations, and portfolios you can show your employer'],
      ]);
  }
  $pointIcons = [
      'Live evening classes' => 'video',
      'Dedicated success coach' => 'user-check',
      'Online exams, from home' => 'monitor-check',
      'Project on your own business' => 'briefcase',
      'Career-relevant assessment' => 'award',
  ];
  $plate = mlp_image_url(settings_media_url($learning, 'plate_image'), ['w' => 1200, 'fallback' => 'assets/images/edutainment/learning-beyond.png']);
@endphp

@if(filled($learningHeading) || $points->isNotEmpty())
<section class="mlp-learning atelier-learning" id="mlp-learning" aria-labelledby="atelier-learning-title">
  <div class="atelier-learning__background" aria-hidden="true">
    <span class="atelier-learning__wash mlp-wash"></span>
    <span class="atelier-learning__grid-line atelier-learning__grid-line--one"></span>
    <span class="atelier-learning__grid-line atelier-learning__grid-line--two"></span>
  </div>

  <div class="atelier-learning__frame container">
    <div class="atelier-learning__layout">
      <div class="atelier-learning__media" data-mlp-reveal="learning-media">
        <div class="atelier-learning__plate-wrap">
          @if($plate)
          <img class="atelier-learning__plate" src="{{ $plate }}" alt="Online learning - evening classes with success coach" width="1200" height="800" loading="lazy" decoding="async">
          @endif
          <span class="atelier-learning__veil" aria-hidden="true"></span>
          <span class="atelier-learning__diagonal" aria-hidden="true"></span>
          <span class="atelier-learning__grain" aria-hidden="true"></span>
        </div>
        <div class="atelier-learning__media-meta">
          <span class="atelier-learning__meta-line" aria-hidden="true"></span>
          <span>Live from UAE · Recorded for revision</span>
        </div>
      </div>

      <div class="atelier-learning__content">
        <header class="atelier-learning__intro" data-mlp-reveal="learning-head">
          @if(filled($learning->label ?? null))
          <p class="atelier-learning__label mlp-eyebrow mlp-meta">{{ $learning->label }}</p>
          @endif
          <h2 class="atelier-learning__heading mlp-h2" id="atelier-learning-title">{{ $learningHeading }}</h2>
          @if(filled($learningIntro))
          <p class="atelier-learning__intro-copy mlp-lede">{{ $learningIntro }}</p>
          @endif
        </header>

        @if($points->isNotEmpty())
        <ol class="atelier-learning__points" aria-label="How online learning works" data-mlp-reveal="learning-points">
          @foreach($points as $pi => $point)
          @php
            $title = $point['title'] ?? '';
            $icon = $pointIcons[$title] ?? 'check-circle-2';
          @endphp
          <li class="atelier-learning__point mlp-hairline" style="--atelier-i: {{ $pi }}">
            <span class="atelier-learning__num" aria-hidden="true">{{ str_pad((string)($pi+1), 2, '0', STR_PAD_LEFT) }}</span>
            <span class="atelier-learning__point-icon" aria-hidden="true"><i data-lucide="{{ $icon }}"></i></span>
            <div class="atelier-learning__point-copy">
              <h3 class="atelier-learning__point-title">{{ $title }}</h3>
              @if(filled($point['text'] ?? null))
              <div class="atelier-learning__point-text mlp-prose">{!! MlpProse::html($point['text']) !!}</div>
              @endif
            </div>
          </li>
          @endforeach
        </ol>
        @endif

        @if(filled($learning->cta_primary_label ?? null) || filled($learning->cta_secondary_label ?? null))
        <div class="atelier-learning__actions">
          @if(filled($learning->cta_primary_label ?? null))
          <a href="{{ edu_href($learning->cta_primary_url ?? '#mlp-enquire') }}" class="atelier-learning__primary mlp-cta mlp-cta--primary">{{ $learning->cta_primary_label }} <span aria-hidden="true">↗</span></a>
          @endif
          @if(filled($learning->cta_secondary_label ?? null))
          <a href="{{ edu_href($learning->cta_secondary_url ?? '#mlp-enquire') }}" class="atelier-learning__secondary mlp-cta mlp-cta--ghost">{{ $learning->cta_secondary_label }}</a>
          @endif
        </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endif
