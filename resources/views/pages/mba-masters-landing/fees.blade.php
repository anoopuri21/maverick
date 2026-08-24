{{-- §8 Fees + payment — The Fee Blueprint --}}
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
<section class="fee-blueprint" id="mlp-fees" aria-labelledby="fee-blueprint-title">
  <div class="fee-blueprint__background" aria-hidden="true">
    @if($stage)
    <img class="fee-blueprint__plate" src="{{ $stage }}" alt="" width="1200" height="800" loading="lazy" decoding="async">
    @endif
    <span class="fee-blueprint__wash"></span>
    <span class="fee-blueprint__rule fee-blueprint__rule--one"></span>
    <span class="fee-blueprint__rule fee-blueprint__rule--two"></span>
  </div>

  <div class="fee-blueprint__frame container">
    <header class="fee-blueprint__intro">
      <div>
        <p class="fee-blueprint__folio">
          @if(filled($fees->index))<span>{{ $fees->index }}</span>@endif
          @if(filled($fees->label))<span>{{ $fees->label }}</span>@endif
        </p>
        @if(filled($fees->heading))
        <h2 class="fee-blueprint__heading" id="fee-blueprint-title">{{ $fees->heading }}</h2>
        @endif
      </div>
      @if(filled($fees->intro))
      <p class="fee-blueprint__intro-copy">{{ $fees->intro }}</p>
      @endif
    </header>

    @if($rows->isNotEmpty())
    <div class="fee-blueprint__board" data-fee-blueprint>
      <div class="fee-blueprint__legend" aria-hidden="true">
        <span>Programme</span>
        <span>Duration</span>
        <span>Mode</span>
        <span>Fee / payment</span>
        <span>Next step</span>
      </div>

      <table class="fee-blueprint__table">
        <caption class="fee-blueprint__caption">Programme fees, duration, study mode and payment options</caption>
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Programme</th>
            <th scope="col">Duration</th>
            <th scope="col">Study mode</th>
            <th scope="col">Fee / payment</th>
            <th scope="col"><span class="fee-blueprint__visually-hidden">Advisor</span></th>
          </tr>
        </thead>
        <tbody>
          @foreach($rows as $i => $row)
          <tr data-fee-row style="--fee-index: {{ $i }}">
            <td class="fee-blueprint__index" data-label="#" aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</td>
            <th class="fee-blueprint__program" scope="row" data-label="Programme">{{ $row['program'] }}</th>
            <td data-label="Duration">{{ $row['duration'] ?? '—' }}</td>
            <td data-label="Study mode">{{ $row['mode'] ?? '—' }}</td>
            <td class="fee-blueprint__fee" data-label="Fee / payment">
              <strong>{{ $row['fee'] ?? '—' }}</strong>
              @if(filled($row['payment'] ?? null))
              <small>{{ $row['payment'] }}</small>
              @endif
            </td>
            <td class="fee-blueprint__action" data-label="Next step">
              <a href="#mlp-enquire">Speak to advisor <span aria-hidden="true">↗</span></a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif

    @if(filled($fees->note))
    <p class="fee-blueprint__note">{{ $fees->note }}</p>
    @endif

    @if(filled($fees->cta_primary_label) || filled($fees->cta_secondary_label))
    <div class="fee-blueprint__actions">
      @if(filled($fees->cta_primary_label))
      <a href="{{ edu_href($fees->cta_primary_url) ?? '#mlp-enquire' }}" class="fee-blueprint__primary">{{ $fees->cta_primary_label }} <span aria-hidden="true">↗</span></a>
      @endif
      @if(filled($fees->cta_secondary_label))
      <a href="{{ edu_href($fees->cta_secondary_url) ?? '#mlp-enquire' }}" class="fee-blueprint__secondary">{{ $fees->cta_secondary_label }}</a>
      @endif
    </div>
    @endif
  </div>
</section>
@endif
