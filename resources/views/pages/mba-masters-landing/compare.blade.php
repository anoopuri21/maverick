{{-- §16 Comparison - Online MBA vs Classroom MBA: A Fair Comparison - PDF Exact --}}
@php
  $rows = collect($compare->rows ?? [])
      ->filter(fn ($row) => filled($row['criterion'] ?? null))
      ->values();
  if ($rows->isEmpty()) {
      $rows = collect([
          ['criterion' => 'Total fees', 'online' => 'AED 16,000 to 40,000', 'traditional' => 'Typically AED 80,000 to 200,000+'],
          ['criterion' => 'Commute', 'online' => 'Zero', 'traditional' => '3 to 5 hours a week in traffic'],
          ['criterion' => 'Class timing', 'online' => 'Evenings and weekends, from home', 'traditional' => 'Fixed campus timetable'],
          ['criterion' => 'Visa needed', 'online' => 'No', 'traditional' => 'Yes for international campuses'],
          ['criterion' => 'Study while working', 'online' => 'Yes, designed for it', 'traditional' => 'Often requires a break'],
          ['criterion' => 'Award on certificate', 'online' => 'UK university degree', 'traditional' => 'UK university degree'],
          ['criterion' => 'Networking', 'online' => 'Live cohort events and an active WhatsApp community', 'traditional' => 'Campus cohorts'],
      ]);
  }
  $icons = [
      'total fees' => 'wallet',
      'commute' => 'car',
      'class timing' => 'clock',
      'visa' => 'passport',
      'study while working' => 'briefcase',
      'award' => 'award',
      'networking' => 'users',
  ];
@endphp

@if(filled($compare->heading) || $rows->isNotEmpty())
<section class="mlp-compare archive-parallel" id="mlp-compare" aria-labelledby="archive-parallel-title">
  <div class="archive-parallel__frame container">
    <header class="archive-parallel__intro mlp-intro-grid" data-mlp-reveal="compare-head">
      <div>
        @if(filled($compare->label))
        <p class="archive-parallel__label mlp-eyebrow mlp-meta">{{ $compare->label }}</p>
        @endif
        @if(filled($compare->heading))
        <h2 class="archive-parallel__heading mlp-h2" id="archive-parallel-title">{{ $compare->heading ?? 'Online MBA vs Classroom MBA: A Fair Comparison' }}</h2>
        @endif
      </div>
      @if(filled($compare->intro))
      <p class="archive-parallel__intro-copy mlp-lede">{{ $compare->intro }}</p>
      @endif
    </header>

    @if($rows->isNotEmpty())
    <div class="archive-parallel__table-wrap" data-mlp-reveal="compare-table">
      <div class="archive-parallel__table-scroll">
        <table class="archive-parallel__table" aria-label="Online and traditional study comparison">
          <thead>
            <tr>
              <th>Aspect</th>
              <th><i data-lucide="monitor" aria-hidden="true"></i> {{ $compare->col_online ?? 'This online MBA' }}</th>
              <th><i data-lucide="building-2" aria-hidden="true"></i> {{ $compare->col_traditional ?? 'Classroom MBA' }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rows as $row)
            @php
              $criterion = strtolower(trim((string) $row['criterion']));
              $icon = collect($icons)->first(fn ($v, $k) => str_contains($criterion, $k)) ?? 'circle-dot';
            @endphp
            <tr>
              <td><span class="archive-parallel__criterion"><i data-lucide="{{ $icon }}" aria-hidden="true"></i> {{ $row['criterion'] }}</span></td>
              <td class="archive-parallel__online">{{ $row['online'] ?? '' }}</td>
              <td class="archive-parallel__traditional">{{ $row['traditional'] ?? '' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    {{-- PDF Exact Blocks --}}
    <div class="archive-parallel__blocks" data-mlp-reveal="compare-blocks">
      <article class="archive-parallel__block mlp-hairline">
        <h3><i data-lucide="zap" aria-hidden="true"></i> Fast-track or standard: your choice</h3>
        <p>A fast-track MBA completes the same modules in a shorter window for experienced applicants. Standard pace spreads the load for busier seasons. Your advisor helps you pick based on your workload.</p>
      </article>
      <article class="archive-parallel__block mlp-hairline">
        <h3><i data-lucide="award" aria-hidden="true"></i> The same award, either way</h3>
        <p>Whichever format you choose, the degree on your certificate is identical. The difference is how you study, not what you earn.</p>
      </article>
    </div>

    @if(filled($compare->cta_label))
    <div class="archive-parallel__actions">
      <a href="{{ edu_href($compare->cta_url) ?? '#mlp-enquire' }}" class="archive-parallel__primary mlp-cta mlp-cta--primary">{{ $compare->cta_label }} <span aria-hidden="true">↗</span></a>
    </div>
    @endif
  </div>
</section>
@endif
