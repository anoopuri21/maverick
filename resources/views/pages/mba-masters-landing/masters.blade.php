{{-- §7 Master's programmes - Master's programs in UAE: Find your specialization - PDF Exact --}}
@php
  $rawPrograms = collect($masters->universities ?? [])->flatMap(fn ($uni) => $uni['programs'] ?? [])->values();
  $programs = $rawPrograms->filter(fn ($p) => filled($p['title'] ?? null))->values();
  
  // Fallback to PDF exact if empty
  if ($programs->isEmpty()) {
      $programs = collect([
          ['title' => 'MSc in Logistics and Supply Chain Management', 'university' => 'Rushford'],
          ['title' => 'MSc in International Human Resource Management', 'university' => 'Rushford'],
          ['title' => 'MSc in Sustainability, Energy and Environment', 'university' => 'Rushford'],
          ['title' => 'MSc in Business Analytics', 'university' => 'Rushford'],
          ['title' => 'MSc in International Hospitality and Tourism Management', 'university' => 'Rushford'],
          ['title' => 'MSc in Strategic Leadership', 'university' => 'Rushford'],
          ['title' => 'MSc in Digital Marketing', 'university' => 'Rushford'],
          ['title' => 'MSc in Finance', 'university' => 'Rushford'],
          ['title' => 'MSc in Project Management', 'university' => 'Rushford'],
          ['title' => 'MSc in Renewable Energy', 'university' => 'GAU'],
          ['title' => 'MSc in Software Engineering', 'university' => 'GAU'],
          ['title' => 'MSc in Hospitality and Tourism Management', 'university' => 'GAU'],
          ['title' => 'MSc in Computer Information Systems', 'university' => 'GAU'],
          ['title' => 'LLM International Commercial Law & ADR', 'university' => 'Wolverhampton'],
      ]);
  }
  
  $trendingRows = collect($masters->trending ?? [])->filter(fn ($row) => filled($row['label'] ?? null))->values();
  if ($trendingRows->isEmpty()) {
      $trendingRows = collect([
          ['label' => 'Sustainability, Energy and Environment', 'percent' => 92],
          ['label' => 'Business Analytics', 'percent' => 88],
          ['label' => 'Logistics and Supply Chain Management', 'percent' => 85],
      ]);
  }
  
  $trendingTitle = filled($masters->trending_title ?? null) ? (string) $masters->trending_title : 'Trending|Specialisations';
  $trendingParts = explode('|', $trendingTitle, 2);
  $heading = filled($masters->heading) ? $masters->heading : "Master's programs in UAE: Find your specialization";
  $label = filled($masters->label) ? $masters->label : 'Programme directory';
@endphp

@if($programs->isNotEmpty() || filled($masters->heading))
<section class="mlp-masters mlp-masters--prospectus" id="mlp-masters" aria-label="Master's programmes">
  <div class="container mlp-masters__inner">
    <header class="mlp-masters__head mlp-intro-grid" data-mlp-reveal="masters-head">
      <div>
        <p class="mlp-masters__label mlp-eyebrow">{{ $label }}</p>
        <h2 class="mlp-masters__heading mlp-h2">{{ $heading }}</h2>
      </div>
      @if(filled($masters->intro))
      <p class="mlp-masters__intro">{{ $masters->intro }}</p>
      @else
      <p class="mlp-masters__intro">Go beyond the MBA. Choose an MSc or LLM to deepen one area and lead in that domain. Popular among HR managers, logistics managers, and finance professionals aiming for regional leadership.</p>
      @endif
    </header>

    <div class="mlp-masters__split{{ $trendingRows->isNotEmpty() ? '' : ' mlp-masters__split--full' }}" data-mlp-reveal="masters-split">
      @if($programs->isNotEmpty())
      <ol class="mlp-masters__ledger" data-mlp-reveal="masters-list" aria-label="All Master's programmes">
        @foreach($programs as $program)
        <li class="mlp-masters__item">
          <span class="mlp-masters__item-mark" aria-hidden="true"></span>
          <span class="mlp-masters__item-title">{{ $program['title'] }}</span>
          @if(filled($program['university'] ?? null))
          <span class="mlp-masters__item-uni">{{ $program['university'] }}</span>
          @endif
        </li>
        @endforeach
      </ol>
      @endif

      @if($trendingRows->isNotEmpty())
      <aside class="mlp-trending" aria-label="Trending specialisations">
        <h3 class="mlp-trending__title">
          @php $trendingDark = trim($trendingParts[0] ?? ''); @endphp
          <span class="mlp-trending__title-dark">{{ $trendingDark !== '' ? $trendingDark : 'Trending' }}</span>
          @if(isset($trendingParts[1]) && trim($trendingParts[1]) !== '')
          <span class="mlp-trending__title-gold">{{ trim($trendingParts[1]) }}</span>
          @endif
        </h3>
        <ul class="mlp-trending__list">
          @foreach($trendingRows as $row)
          @php
            $percent = (int) ($row['percent'] ?? 0);
            $percent = max(0, min(100, $percent));
          @endphp
          <li class="mlp-trending__row" style="--trend: {{ $percent }}%">
            <span class="mlp-trending__label">{{ $row['label'] }}</span>
            <span class="mlp-trending__track" aria-hidden="true">
              <span class="mlp-trending__fill"><span class="mlp-trending__value">{{ $percent }}%</span></span>
            </span>
          </li>
          @endforeach
        </ul>
        <p class="mlp-trending__note">Highest demand this quarter among GCC working professionals.</p>
      </aside>
      @endif
    </div>

    <div class="mlp-masters__cta-row">
      <a href="#mlp-enquire" class="mlp-masters__cta mlp-cta mlp-cta--primary">Check eligibility <span aria-hidden="true">↗</span></a>
      <p class="mlp-masters__cta-note">Every programme above is open to enquiry — admissions team will confirm eligibility and next steps. Response within 24 hours.</p>
    </div>
  </div>
</section>
@endif
