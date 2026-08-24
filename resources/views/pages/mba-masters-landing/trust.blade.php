{{-- §2 Trust — The Living Prospectus / Evidence Margin --}}
@php
  $stats = collect($trust->stats ?? [])
      ->filter(fn ($s) => filled($s['value'] ?? null) || filled($s['label'] ?? null))
      ->values();
@endphp
@if($stats->isNotEmpty())
<section class="mlp-trust prospectus-evidence" id="mlp-trust" aria-label="Trust statistics">
  <div class="prospectus-evidence__background" aria-hidden="true">
    <span class="prospectus-evidence__wash"></span>
    <span class="prospectus-evidence__rule prospectus-evidence__rule--one"></span>
    <span class="prospectus-evidence__rule prospectus-evidence__rule--two"></span>
  </div>

  <div class="prospectus-evidence__frame container">
    <header class="prospectus-evidence__masthead">
      <div class="prospectus-evidence__heading-group">
        <p class="prospectus-evidence__folio">02 / Evidence margin</p>
        @if(filled($trust->label))
        <h2 class="prospectus-evidence__title">{{ $trust->label }}</h2>
        @endif
      </div>
      <p class="prospectus-evidence__aside">The numbers behind the conversation.</p>
    </header>

    <div class="prospectus-evidence__body">
      <div class="prospectus-evidence__statement">
        <p class="prospectus-evidence__eyebrow">A growing learning community</p>
        <p class="prospectus-evidence__lead">Proof is not a promise. It is the record of people who chose to begin.</p>
        <a class="prospectus-evidence__link" href="#mlp-enquire" data-prospectus-open>Ask admissions what it means for you <span aria-hidden="true">↗</span></a>
      </div>

      <ol class="prospectus-evidence__records" aria-label="Trust statistics">
        @foreach($stats as $i => $stat)
        @php
          $rawValue = (string) ($stat['value'] ?? '');
          $numericValue = preg_replace('/[^0-9.]/', '', $rawValue);
          $suffix = preg_replace('/[0-9.,\s]/', '', $rawValue);
        @endphp
        <li class="prospectus-evidence__record{{ $i === 0 ? ' prospectus-evidence__record--lead' : '' }}" data-prospectus-evidence-record style="--prospectus-index: {{ $i }}">
          <span class="prospectus-evidence__record-index" aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
          <span
            class="prospectus-evidence__value"
            @if($numericValue !== '')
            data-mlp-count="{{ $numericValue }}"
            data-mlp-suffix="{{ $suffix }}"
            @endif
          >{{ $rawValue }}</span>
          <span class="prospectus-evidence__label">{{ $stat['label'] ?? '' }}</span>
          <span class="prospectus-evidence__record-line" aria-hidden="true"></span>
        </li>
        @endforeach
      </ol>
    </div>
  </div>
</section>
@endif
