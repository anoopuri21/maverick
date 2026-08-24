{{-- §8 Fees + payment — Programme Index --}}
@php
  $rows = collect($fees->rows ?? [])
      ->filter(fn ($row) => filled($row['program'] ?? null))
      ->values();
@endphp

@if($rows->isNotEmpty() || filled($fees->heading))
<section class="programme-index" id="mlp-fees" aria-labelledby="programme-index-title">
  <div class="programme-index__frame container">
    <header class="programme-index__intro">
      <div>
        <p class="programme-index__folio">
          @if(filled($fees->index))<span>{{ $fees->index }}</span>@endif
          @if(filled($fees->label))<span>{{ $fees->label }}</span>@endif
        </p>
        @if(filled($fees->heading))
        <h2 class="programme-index__heading" id="programme-index-title">{{ $fees->heading }}</h2>
        @endif
      </div>
      @if(filled($fees->intro))
      <p class="programme-index__intro-copy">{{ $fees->intro }}</p>
      @endif
    </header>

    @if($rows->isNotEmpty())
    <ol class="programme-index__list" aria-label="Programme fees and structure">
      @foreach($rows as $i => $row)
      @php
        $payment = trim((string) ($row['payment'] ?? ''));
        // Keep legacy admin copy neutral in the public fee record.
        if (str_contains(strtolower($payment), 'advisor')) {
            $payment = 'Details on request';
        }
      @endphp
      <li class="programme-index__item">
        <span class="programme-index__number" aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
        <div class="programme-index__body">
          <div class="programme-index__topline">
            <h3 class="programme-index__program">{{ $row['program'] }}</h3>
            <strong class="programme-index__fee">{{ $row['fee'] ?? '—' }}</strong>
          </div>
          <dl class="programme-index__details">
            <div>
              <dt>Duration</dt>
              <dd>{{ $row['duration'] ?? '—' }}</dd>
            </div>
            <div>
              <dt>Mode</dt>
              <dd>{{ $row['mode'] ?? '—' }}</dd>
            </div>
            <div>
              <dt>Payment</dt>
              <dd>{{ $payment !== '' ? $payment : '—' }}</dd>
            </div>
          </dl>
        </div>
      </li>
      @endforeach
    </ol>
    @endif

    @if(filled($fees->note))
    <p class="programme-index__note">{{ $fees->note }}</p>
    @endif

    @if(filled($fees->cta_primary_label) || filled($fees->cta_secondary_label))
    <div class="programme-index__actions">
      @if(filled($fees->cta_primary_label))
      <a href="{{ edu_href($fees->cta_primary_url) ?? '#mlp-enquire' }}" class="programme-index__primary">{{ $fees->cta_primary_label }} <span aria-hidden="true">↗</span></a>
      @endif
      @if(filled($fees->cta_secondary_label))
      <a href="{{ edu_href($fees->cta_secondary_url) ?? '#mlp-enquire' }}" class="programme-index__secondary">{{ $fees->cta_secondary_label }}</a>
      @endif
    </div>
    @endif
  </div>
</section>
@endif
