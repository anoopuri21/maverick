{{-- §10 Fees - MBA Fees in UAE: What You Actually Pay - PDF Exact --}}
@php
  $rows = collect($fees->rows ?? [])
      ->filter(fn ($row) => filled($row['programme'] ?? $row['program'] ?? null))
      ->values();
@endphp

@if($rows->isNotEmpty() || filled($fees->heading))
<section class="mlp-fees pricing-cards" id="mlp-fees" aria-labelledby="pricing-cards-title">
  <div class="pricing-cards__frame container">
    <header class="pricing-cards__intro mlp-intro-grid" data-mlp-reveal="fees-head">
      <div>
        @if(filled($fees->label))
        <p class="pricing-cards__label mlp-eyebrow mlp-meta">{{ $fees->label }}</p>
        @endif
        @if(filled($fees->heading))
        <h2 class="pricing-cards__heading mlp-h2" id="pricing-cards-title">{{ $fees->heading ?? 'MBA Fees in UAE: What You Actually Pay' }}</h2>
        @endif
      </div>
      @if(filled($fees->intro))
      <p class="pricing-cards__intro-copy mlp-lede">{{ $fees->intro }}</p>
      @endif
    </header>

    @if($rows->isNotEmpty())
    <div class="pricing-cards__table-wrap" data-mlp-reveal="fees-table">
      <div class="pricing-cards__table-scroll">
        <table class="pricing-cards__table" aria-label="Programme fees and structure">
          <thead>
            <tr>
              <th>Programme</th>
              <th>Mode</th>
              <th>Fees</th>
              <th>Duration</th>
              <th>Payment</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rows as $row)
            <tr>
              <td>{{ $row['programme'] ?? $row['program'] ?? '—' }}</td>
              <td>{{ $row['mode'] ?? '—' }}</td>
              <td>{{ $row['fees'] ?? '—' }}</td>
              <td>{{ $row['duration'] ?? '—' }}</td>
              <td>{{ $row['payment'] ?? '—' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if(filled($fees->note))
      <p class="pricing-cards__note">{{ $fees->note }}</p>
      @endif
    </div>
    @endif

    {{-- PDF Exact Blocks - New Component --}}
    <div class="pricing-cards__blocks" data-mlp-reveal="fees-blocks">
      <article class="pricing-cards__block mlp-hairline">
        <h3><i data-lucide="wallet" aria-hidden="true"></i> A flexible payment plan, in AED, with no interest</h3>
        <p>No upfront full payment. Split your fees into monthly instalments in dirhams, on a schedule that matches your salary cycle. The degree fits your cash flow from day one. Many students also have part of their fee supported by their employer.</p>
      </article>
      <article class="pricing-cards__block mlp-hairline">
        <h3><i data-lucide="check-circle-2" aria-hidden="true"></i> What your fee covers</h3>
        <ul>
          <li>Full tuition and the UK university award</li>
          <li>All study materials and portal access</li>
          <li>Session recordings for revision</li>
          <li>Advisor support in Sharjah, on Gulf time</li>
          <li>Induction, enrolment, and student services</li>
        </ul>
      </article>
      <article class="pricing-cards__block mlp-hairline">
        <h3><i data-lucide="gift" aria-hidden="true"></i> Scholarships and early-bird discounts</h3>
        <p>MBA scholarship support for strong candidates, plus early-bird discounts when you reserve your seat ahead of the intake. Ask your advisor what applies to your profile.</p>
      </article>
    </div>

    <p class="pricing-cards__base" data-mlp-reveal="fees-base">
      <span class="pricing-cards__base-label">Fee structure starts from</span>
      <strong class="pricing-cards__base-price">AED 16,000–40,000*</strong>
    </p>

    @if(filled($fees->cta_primary_label) || filled($fees->cta_secondary_label))
    <div class="pricing-cards__actions">
      @if(filled($fees->cta_primary_label))
      <a href="{{ edu_href($fees->cta_primary_url) ?? '#mlp-enquire' }}" class="pricing-cards__primary mlp-cta mlp-cta--primary">{{ $fees->cta_primary_label }} <span aria-hidden="true">↗</span></a>
      @endif
      @if(filled($fees->cta_secondary_label))
      <a href="{{ edu_href($fees->cta_secondary_url) ?? '#mlp-enquire' }}" class="pricing-cards__secondary mlp-cta mlp-cta--ghost">{{ $fees->cta_secondary_label }}</a>
      @endif
    </div>
    @endif
  </div>
</section>
@endif
