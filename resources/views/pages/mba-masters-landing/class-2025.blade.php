{{-- §8 Class of 2025 - Built for the GCC - PDF Exact --}}
@php
  // PDF exact metrics, fallback to settings if available
  $metrics = collect($class->metrics ?? [])
      ->filter(fn ($m) => filled($m['value'] ?? null))
      ->values();
  if ($metrics->isEmpty()) {
      $metrics = collect([
          ['value' => '35', 'label' => 'median age in the current cohort'],
          ['value' => '9 years', 'label' => 'of average work experience'],
          ['value' => '68%', 'label' => 'hold mid-level or senior roles'],
          ['value' => '100%', 'label' => 'employed full time while studying'],
          ['value' => '80%+', 'label' => 'sponsored or supported by their employer'],
      ]);
  }
  $bottomText = $class->intro ?? 'The most diverse cohort we have run in the Gulf: founders, government specialists, bankers, and senior operators from the UAE, Saudi Arabia, Oman, and Qatar. You learn from each other as much as from the faculty. Group work brings four markets into one discussion, which is exactly how regional business runs.';
@endphp

<section class="mlp-class-2025 gcc-circle" id="mlp-class-2025" aria-labelledby="gcc-circle-title">
  <div class="gcc-circle__background" aria-hidden="true">
    <span class="gcc-circle__wash mlp-wash"></span>
    <span class="gcc-circle__contour gcc-circle__contour--one mlp-contour"></span>
    <span class="gcc-circle__contour gcc-circle__contour--two mlp-contour"></span>
  </div>

  <div class="gcc-circle__frame container">
    <header class="gcc-circle__intro" data-mlp-reveal="class2025-head">
      <p class="gcc-circle__label mlp-eyebrow mlp-meta">Class of 2025</p>
      <h2 class="gcc-circle__heading mlp-h2" id="gcc-circle-title">Class of 2025</h2>
    </header>

    <div class="gcc-circle__stage" data-mlp-reveal="class2025-stage">
      <div class="gcc-circle__center" aria-label="MBA - 2025, Built for the GCC">
        <svg class="gcc-circle__svg" viewBox="0 0 300 300" aria-hidden="true">
          <defs>
            <path id="gccCirclePath" d="M150,150 m-110,0 a110,110 0 1,1 220,0 a110,110 0 1,1 -220,0"/>
          </defs>
          <circle cx="150" cy="150" r="110" class="gcc-circle__ring" fill="none"/>
          <text class="gcc-circle__ring-text">
            <textPath href="#gccCirclePath" startOffset="0%">Built for the GCC • Built for the GCC • Built for the GCC •</textPath>
          </text>
        </svg>
        <div class="gcc-circle__badge">
          <strong>MBA - 2025</strong>
          <span>Built for the GCC</span>
        </div>
      </div>

      <ol class="gcc-circle__metrics" aria-label="Class of 2025 metrics">
        @foreach($metrics as $mi => $metric)
        <li class="gcc-circle__metric" style="--gcc-i: {{ $mi }}" data-archive-element>
          <span class="gcc-circle__metric-node" aria-hidden="true">
            <span class="gcc-circle__metric-dot"></span>
            <span class="gcc-circle__metric-pulse"></span>
          </span>
          <div class="gcc-circle__metric-copy">
            <strong class="gcc-circle__metric-value" @if(is_numeric(preg_replace('/[^0-9.]/','',$metric['value']))) data-mlp-count="{{ preg_replace('/[^0-9.]/','',$metric['value']) }}" @endif>{{ $metric['value'] }}</strong>
            <span class="gcc-circle__metric-label">{{ $metric['label'] }}</span>
          </div>
        </li>
        @endforeach
      </ol>
    </div>

    <div class="gcc-circle__bottom" data-mlp-reveal="class2025-bottom">
      <p class="gcc-circle__bottom-text mlp-lede">{{ $bottomText }}</p>
    </div>
  </div>
</section>
