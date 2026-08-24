{{-- §8 Fees + payment — The Closing Archive / Investment Index --}}
@php
  $rows = collect($fees->rows ?? [])
      ->filter(fn ($row) => filled($row['program'] ?? null))
      ->values();
@endphp

@if($rows->isNotEmpty() || filled($fees->heading))
<section class="mlp-fees archive-investment" id="mlp-fees" aria-labelledby="archive-investment-title">
  <div class="archive-investment__frame container">
    <header class="archive-investment__intro">
      <div>
        <p class="archive-investment__label">
          @if(filled($fees->index))<span>{{ $fees->index }}</span>@endif
          @if(filled($fees->label))<span>{{ $fees->label }}</span>@endif
        </p>
        @if(filled($fees->heading))
        <h2 class="archive-investment__heading" id="archive-investment-title">{{ $fees->heading }}</h2>
        @endif
      </div>
      @if(filled($fees->intro))
      <p class="archive-investment__intro-copy">{{ $fees->intro }}</p>
      @endif
    </header>

    @if($rows->isNotEmpty())
    <ol class="archive-investment__records" aria-label="Programme fees and structure">
      @foreach($rows as $i => $row)
      @php
        $payment = trim((string) ($row['payment'] ?? ''));
        if (str_contains(strtolower($payment), 'advisor')) {
            $payment = 'Details on request';
        }
      @endphp
      <li class="archive-investment__record" data-closing-element>
        <span class="archive-investment__record-mark" aria-hidden="true"><i data-lucide="receipt-text"></i></span>
        <div class="archive-investment__record-body">
          <div class="archive-investment__record-topline">
            <h3>{{ $row['program'] }}</h3>
            <strong>{{ $row['fee'] ?? '—' }}</strong>
          </div>
          <dl class="archive-investment__details">
            <div><dt>Duration</dt><dd>{{ $row['duration'] ?? '—' }}</dd></div>
            <div><dt>Mode</dt><dd>{{ $row['mode'] ?? '—' }}</dd></div>
            <div><dt>Payment</dt><dd>{{ $payment !== '' ? $payment : '—' }}</dd></div>
          </dl>
        </div>
      </li>
      @endforeach
    </ol>
    @endif

    @if(filled($fees->note))
    <p class="archive-investment__note">{{ $fees->note }}</p>
    @endif

    @if(filled($fees->cta_primary_label) || filled($fees->cta_secondary_label))
    <div class="archive-investment__actions">
      @if(filled($fees->cta_primary_label))
      <a href="{{ edu_href($fees->cta_primary_url) ?? '#mlp-enquire' }}" class="archive-investment__primary">{{ $fees->cta_primary_label }} <span aria-hidden="true">↗</span></a>
      @endif
      @if(filled($fees->cta_secondary_label))
      <a href="{{ edu_href($fees->cta_secondary_url) ?? '#mlp-enquire' }}" class="archive-investment__secondary">{{ $fees->cta_secondary_label }}</a>
      @endif
    </div>
    @endif
  </div>
</section>
@endif
