{{-- §6 MBA specializations - MBA specializations in UAE: Pick Your MBA - PDF Exact --}}
@php
  // Support both old structure (tabs->universities->programs) and new PDF exact (tabs->programs directly)
  $rawTabs = collect($mba->tabs ?? [])->filter(fn ($tab) => filled($tab['label'] ?? null))->values();
  $tabs = $rawTabs->map(function ($tab) {
      // If tab has universities, keep as is for backward compat
      if (!empty($tab['universities'])) {
          $tab['programs'] = collect($tab['universities'])->flatMap(fn ($uni) => $uni['programs'] ?? [])->values()->all();
          return $tab;
      }
      // New structure: tab has programs directly
      return $tab;
  })->values();
  
  // Fallback to PDF exact if empty
  if ($tabs->isEmpty()) {
      $tabs = collect([
          [
              'key' => 'rbs-mba',
              'label' => 'MBA, Rushford Business School (Switzerland)',
              'description' => 'Twelve specializations in one online format. Works in any industry: the curriculum is built around leadership, strategy, and decision-making. Ideal for team leads, operations managers, and consultants moving into senior roles.',
              'programs' => [
                  ['title' => 'MBA in Sustainability, Energy and Environment'],
                  ['title' => 'MBA in Strategic Management'],
                  ['title' => 'MBA in Real Estate Management'],
                  ['title' => 'MBA in Human Resource Management'],
                  ['title' => 'MBA in Marketing'],
                  ['title' => 'MBA in Logistics & Supply Chain Management'],
                  ['title' => 'MBA in Healthcare Leadership'],
                  ['title' => 'MBA in Hospitality & Tourism Management'],
                  ['title' => 'MBA in Health Economics'],
                  ['title' => 'MBA in Entrepreneurship and Innovation'],
                  ['title' => 'MBA in Finance'],
                  ['title' => 'Master of Business Administration (MBA)'],
              ],
          ],
          [
              'key' => 'gau-mba',
              'label' => 'MBA, Girne American University (North Cyprus)',
              'description' => 'Six MBAs for managers who want a specialization with an international cohort.',
              'programs' => [
                  ['title' => 'MBA in Business Management'],
                  ['title' => 'MBA in Financial Management'],
                  ['title' => 'MBA in International Business Management'],
                  ['title' => 'MBA in Management Information Systems'],
                  ['title' => 'MBA in Marketing'],
                  ['title' => 'MBA Data Science/Analytics Management'],
              ],
          ],
          [
              'key' => 'gau-emba',
              'label' => 'Executive MBA, Girne American University (North Cyprus)',
              'description' => 'Sixteen Executive MBAs for senior leaders. Cohort size is limited and classmates bring seniority, so discussions draw on real leadership experience.',
              'programs' => [
                  ['title' => 'Executive MBA in Educational Leadership'],
                  ['title' => 'Executive MBA in Media & Entertainment'],
                  ['title' => 'Executive MBA in Global Banking & Finance'],
                  ['title' => 'Executive MBA in Health & Safety Leadership'],
                  ['title' => 'Executive MBA in Renewable Energy & Sustainability'],
                  ['title' => 'Executive MBA in Tourism & Hospitality Management'],
                  ['title' => 'Executive MBA in Innovation & Entrepreneurship'],
                  ['title' => 'Executive MBA in Project Management'],
                  ['title' => 'Executive MBA in Human Resources Management'],
                  ['title' => 'Executive MBA in Supply Chain Management'],
                  ['title' => 'Executive MBA in Health Care Management'],
                  ['title' => 'Executive MBA in Engineering Management'],
                  ['title' => 'Executive MBA in Public Administration'],
                  ['title' => 'Executive MBA in Public Health'],
                  ['title' => 'Executive MBA in Digital Marketing'],
                  ['title' => 'Executive MBA in Sport Management'],
              ],
          ],
          [
              'key' => 'global-mba',
              'label' => 'Global MBA, University for the Creative Arts (UK) with Rushford',
              'description' => 'One degree with an international perspective, for careers across borders. Popular in logistics, trade, and regional HQ roles across Dubai and Abu Dhabi.',
              'programs' => [['title' => 'Global MBA']],
          ],
          [
              'key' => 'uws-mba',
              'label' => 'MBA in International Business, University of the West of Scotland (UK)',
              'description' => 'A UK-awarded MBA focused on cross-border trade, global strategy, and international teams. A strong fit for professionals in logistics, import-export, and regional HQ roles.',
              'programs' => [['title' => 'MBA in International Business']],
          ],
      ]);
  }
  
  $stage = mlp_image_url(settings_media_url($mba, 'stage_image'), ['w' => 1920, 'fallback' => 'assets/images/mba-masters-landing/mba/mba-stage.jpg']);
@endphp

@if($tabs->isNotEmpty() || filled($mba->heading))
<section class="mlp-mba" id="mlp-mba" aria-labelledby="mlp-mba-title">
  <div class="mlp-mba__wash" aria-hidden="true">
    <img class="mlp-mba__wash-img" src="{{ $stage }}" alt="" width="1920" height="1080" loading="lazy" decoding="async">
  </div>

  <div class="container mlp-mba__inner">
    <header class="mlp-mba__head" data-mlp-reveal="mba-head">
      <div class="mlp-mba__meta">
        @if(filled($mba->label))
        <p class="mlp-mba__label mlp-meta mlp-eyebrow">{{ $mba->label }}</p>
        @endif
      </div>
      @if(filled($mba->heading))
      <h2 class="mlp-mba__heading mlp-headline mlp-h2" id="mlp-mba-title">{{ $mba->heading ?? 'MBA specializations in UAE: Pick Your MBA' }}</h2>
      @endif
      @if(filled($mba->intro))
      <p class="mlp-mba__intro mlp-lede">{{ $mba->intro }}</p>
      @endif
    </header>

    @if($tabs->isNotEmpty())
    <div class="mlp-mba__chrome" data-mlp-mba-tabs data-mlp-reveal="mba-chrome">
      <div class="mlp-mba__tablist mlp-hairline" role="tablist" aria-label="MBA specialization categories">
        @foreach($tabs as $ti => $tab)
        <button
          type="button"
          class="mlp-mba__tab{{ $ti === 0 ? ' is-active' : '' }}"
          role="tab"
          id="mlp-mba-tab-{{ $ti }}"
          aria-selected="{{ $ti === 0 ? 'true' : 'false' }}"
          aria-controls="mlp-mba-panel-{{ $ti }}"
          data-mlp-mba-tab="{{ $ti }}"
        >{{ $tab['label'] }}</button>
        @endforeach
      </div>

      @foreach($tabs as $ti => $tab)
      @php
        $programs = collect($tab['programs'] ?? [])->filter(fn ($p) => filled($p['title'] ?? null))->values();
      @endphp
      <div
        class="mlp-mba__panel{{ $ti === 0 ? ' is-active' : '' }}"
        role="tabpanel"
        id="mlp-mba-panel-{{ $ti }}"
        aria-labelledby="mlp-mba-tab-{{ $ti }}"
        @if($ti !== 0) hidden @endif
        data-mlp-mba-panel="{{ $ti }}"
      >
        @if(filled($tab['description'] ?? null))
        <p class="mlp-mba__tab-description mlp-lede">{{ $tab['description'] }}</p>
        @endif
        
        @if($programs->isNotEmpty())
        <ol class="mlp-mba__programs{{ $programs->count() > 8 ? ' mlp-mba__programs--columns' : '' }}" aria-label="{{ $tab['label'] }} programmes" @if($programs->count() > 8) style="--mlp-specialization-rows: {{ (int) ceil($programs->count()/2) }}" @endif>
          @foreach($programs as $program)
          <li class="mlp-mba__program mlp-hairline">
            <span class="mlp-mba__program-index" aria-hidden="true"><i data-lucide="graduation-cap"></i></span>
            <span class="mlp-mba__program-title">{{ $program['title'] }}</span>
          </li>
          @endforeach
        </ol>
        @endif
      </div>
      @endforeach
    </div>
    
    <div class="mlp-mba__closing" data-mlp-reveal="mba-closing">
      <p>Not sure which route fits? Ask an advisor and get a recommendation within 24 hours. The right pick depends on your experience and your next role, and the call is free.</p>
      <a href="#mlp-enquire" class="mlp-mba__closing-cta mlp-cta mlp-cta--primary">Ask an advisor <span aria-hidden="true">↗</span></a>
    </div>
    @endif
  </div>
</section>
@endif
