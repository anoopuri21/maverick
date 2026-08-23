{{-- §9 Class profile — cinematic chapter: split header, ghost numeral, stat count-ups --}}
@php
  $metrics = collect($class->metrics ?? [])->filter(fn ($m) => filled($m['value'] ?? null) || filled($m['label'] ?? null))->values();
  $regions = collect($class->regions ?? [])->filter(fn ($r) => filled($r['name'] ?? null))->values();
  $industries = collect($class->industries ?? [])->filter(fn ($i) => filled($i['name'] ?? null))->values();
  $fallbackIndustry = 'assets/images/homepage/business.jpg';
@endphp
@if(filled($class->heading) || $industries->isNotEmpty() || $regions->isNotEmpty())
<section class="mlp-class" id="mlp-class" aria-label="Class profile">
  <div class="mlp-class__deco" aria-hidden="true">
    <span class="mlp-class__orb mlp-class__orb--a"></span>
    <span class="mlp-class__orb mlp-class__orb--b"></span>
    <span class="mlp-class__deco-rule"></span>
  </div>

  <div class="container mlp-class__inner">
    <header class="mlp-class__head">
      <div class="mlp-class__head-main" data-mlp-reveal="class-head">
        <div class="mlp-class__meta">
          @if(filled($class->label))
          <p class="mlp-class__label mlp-meta">{{ $class->label }}</p>
          @endif
        </div>
        <span class="mlp-class__kicker" aria-hidden="true"></span>
        @if(filled($class->heading))
        <h2 class="mlp-class__heading mlp-headline">{{ $class->heading }}</h2>
        @endif
      </div>
      <div class="mlp-class__head-aside" data-mlp-reveal="class-head-aside">
        @if(filled($class->intro))
        <p class="mlp-class__intro mlp-lede">{{ $class->intro }}</p>
        @endif
        @if(filled($class->audience))
        <p class="mlp-class__audience">{{ $class->audience }}</p>
        @endif
      </div>
    </header>

    <span class="mlp-class__drop-rule" aria-hidden="true" data-mlp-class-droprule></span>

    @if($metrics->isNotEmpty())
    <ul class="mlp-class__metrics" data-mlp-class-metrics>
      @foreach($metrics as $mi => $metric)
      @php $numeric = is_numeric(str_replace([',', '+', '%'], '', (string) $metric['value'])); @endphp
      <li class="mlp-class__metric{{ $mi === 0 ? ' mlp-class__metric--lead' : '' }}">
        <span
          class="mlp-class__metric-value"
          @if($numeric)
          data-mlp-count="{{ preg_replace('/[^0-9.]/', '', (string) $metric['value']) }}"
          data-mlp-suffix="{{ preg_replace('/[0-9.,\s]/', '', (string) $metric['value']) }}"
          @endif
        >{{ $metric['value'] }}</span>
        @if(filled($metric['label'] ?? null))
        <span class="mlp-class__metric-label">{{ $metric['label'] }}</span>
        @endif
      </li>
      @endforeach
    </ul>
    @endif

    <div class="mlp-class__panels">
      @if($regions->isNotEmpty())
      <div class="mlp-class__panel mlp-class__panel--regions" data-mlp-reveal="class-regions">
        <p class="mlp-class__panel-label mlp-meta">Countries &amp; regions</p>
        <ul class="mlp-class__region-strip">
          @foreach($regions as $region)
          <li class="mlp-class__region">
            <span class="mlp-class__region-name">{{ $region['name'] }}</span>
            @if(filled($region['note'] ?? null))
            <span class="mlp-class__region-note">{{ $region['note'] }}</span>
            @endif
          </li>
          @endforeach
        </ul>
      </div>
      @endif

      @if($industries->isNotEmpty())
      <div class="mlp-class__panel mlp-class__panel--industries" data-mlp-reveal="class-industries">
        <p class="mlp-class__panel-label mlp-meta">Industry background</p>
        <ul class="mlp-class__industry-list">
          @foreach($industries as $ii => $industry)
          @php
            $photo = media_url($industry['image'] ?? null, $fallbackIndustry);
            $share = max(0, min(100, (float) preg_replace('/[^0-9.]/', '', (string) ($industry['share'] ?? '0'))));
            $shareText = rtrim(rtrim(number_format($share, 1, '.', ''), '0'), '.');
          @endphp
          <li class="mlp-class__industry{{ $ii === 0 ? ' mlp-class__industry--lead' : '' }}" style="--mlp-share: {{ $share }}%;" data-mlp-class-industry>
            <span class="mlp-class__industry-frame" aria-hidden="true" data-mlp-class-frame>
              <img class="mlp-class__industry-photo" src="{{ $photo }}" alt="" width="72" height="72" loading="lazy" decoding="async">
            </span>
            <div class="mlp-class__industry-body">
              <div class="mlp-class__industry-top">
                <span class="mlp-class__industry-index" aria-hidden="true">{{ str_pad((string) ($ii + 1), 2, '0', STR_PAD_LEFT) }}</span>
                <h3 class="mlp-class__industry-name">{{ $industry['name'] }}</h3>
                <span class="mlp-class__industry-share" data-mlp-count="{{ $shareText }}" data-mlp-suffix="%">{{ $shareText }}%</span>
              </div>
              <div class="mlp-class__industry-track" aria-hidden="true">
                <span class="mlp-class__industry-fill"></span>
              </div>
            </div>
          </li>
          @endforeach
        </ul>
      </div>
      @endif
    </div>
  </div>
</section>
@endif
