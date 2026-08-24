{{-- §8 Fees + payment — The Fee Cartography --}}
@php
  $rows = collect($fees->rows ?? [])
      ->filter(fn ($row) => filled($row['program'] ?? null))
      ->values();
  $stage = mlp_image_url($fees->stage_image ?? null, [
    'w' => 1200,
    'fallback' => 'assets/images/programs/enquire-seminar.jpg',
  ]);
@endphp

@if($rows->isNotEmpty() || filled($fees->heading))
<section class="fee-cartography" id="mlp-fees" aria-labelledby="fee-cartography-title">
  <div class="fee-cartography__background" aria-hidden="true">
    @if($stage)
    <img class="fee-cartography__plate" src="{{ $stage }}" alt="" width="1200" height="800" loading="lazy" decoding="async">
    @endif
    <span class="fee-cartography__wash"></span>
    <span class="fee-cartography__contour fee-cartography__contour--one"></span>
    <span class="fee-cartography__contour fee-cartography__contour--two"></span>
  </div>

  <div class="fee-cartography__frame container">
    <header class="fee-cartography__intro">
      <div>
        <p class="fee-cartography__folio">
          @if(filled($fees->index))<span>{{ $fees->index }}</span>@endif
          @if(filled($fees->label))<span>{{ $fees->label }}</span>@endif
        </p>
        @if(filled($fees->heading))
        <h2 class="fee-cartography__heading" id="fee-cartography-title">{{ $fees->heading }}</h2>
        @endif
      </div>
      @if(filled($fees->intro))
      <p class="fee-cartography__intro-copy">{{ $fees->intro }}</p>
      @endif
    </header>

    @if($rows->isNotEmpty())
    <div class="fee-cartography__map" data-fee-cartography>
      <div class="fee-cartography__legend" aria-hidden="true">
        <span>Choose a route</span>
        <span>Duration / mode / payment</span>
        <span>Fee destination</span>
      </div>

      <div class="fee-cartography__origin" aria-hidden="true">
        <span class="fee-cartography__origin-node"></span>
        <span class="fee-cartography__origin-index">Start here</span>
        <strong>Choose your route</strong>
      </div>

      <svg class="fee-cartography__routes" viewBox="0 0 1200 520" preserveAspectRatio="none" aria-hidden="true">
        <g class="fee-cartography__route-grid">
          <path d="M0 260H1200M600 0V520" />
          <path d="M160 80H1040M160 440H1040" />
        </g>
        <g class="fee-cartography__route-lines">
          <path data-fee-path d="M184 260 C330 214 330 86 550 92 S780 88 1055 78" />
          <path data-fee-path d="M184 260 C350 246 410 184 585 190 S800 174 1055 188" />
          <path data-fee-path d="M184 260 C350 276 410 336 585 330 S800 346 1055 342" />
          <path data-fee-path d="M184 260 C330 306 330 434 550 428 S780 432 1055 452" />
        </g>
        <g class="fee-cartography__route-points">
          <circle cx="184" cy="260" r="8" />
          <circle cx="1055" cy="78" r="6" />
          <circle cx="1055" cy="188" r="6" />
          <circle cx="1055" cy="342" r="6" />
          <circle cx="1055" cy="452" r="6" />
        </g>
      </svg>

      <ol class="fee-cartography__route-list" aria-label="Programme fee routes">
        @foreach($rows as $i => $row)
        @php
          $payment = trim((string) ($row['payment'] ?? ''));
          // Keep public fee copy neutral even if legacy admin content contains advisor language.
          if (str_contains(strtolower($payment), 'advisor')) {
              $payment = 'Details on request';
          }
        @endphp
        <li class="fee-cartography__route" data-fee-route style="--fee-route-index: {{ $i }}">
          <span class="fee-cartography__route-index" aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
          <div class="fee-cartography__route-main">
            <h3 class="fee-cartography__program">{{ $row['program'] }}</h3>
            <div class="fee-cartography__route-meta">
              <span><b>Duration</b>{{ $row['duration'] ?? '—' }}</span>
              <span><b>Mode</b>{{ $row['mode'] ?? '—' }}</span>
              <span><b>Payment</b>{{ $payment !== '' ? $payment : '—' }}</span>
            </div>
          </div>
          <div class="fee-cartography__destination">
            <span>Fee destination</span>
            <strong>{{ $row['fee'] ?? '—' }}</strong>
          </div>
        </li>
        @endforeach
      </ol>
    </div>
    @endif

    @if(filled($fees->note))
    <p class="fee-cartography__note">{{ $fees->note }}</p>
    @endif

    @if(filled($fees->cta_primary_label) || filled($fees->cta_secondary_label))
    <div class="fee-cartography__actions">
      @if(filled($fees->cta_primary_label))
      <a href="{{ edu_href($fees->cta_primary_url) ?? '#mlp-enquire' }}" class="fee-cartography__primary">{{ $fees->cta_primary_label }} <span aria-hidden="true">↗</span></a>
      @endif
      @if(filled($fees->cta_secondary_label))
      <a href="{{ edu_href($fees->cta_secondary_url) ?? '#mlp-enquire' }}" class="fee-cartography__secondary">{{ $fees->cta_secondary_label }}</a>
      @endif
    </div>
    @endif
  </div>
</section>
@endif
