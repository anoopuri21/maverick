@php
    $credentialItems = collect($items ?? [])
        ->filter(fn ($item) => filled($item['title'] ?? null) && filled($item['iso2'] ?? null))
        ->values();
    $showCredentials = ! empty($enabled) && $credentialItems->isNotEmpty();
    $variant = $variant ?? 'hero-strip';
    $flagWidth = $variant === 'overview-cards' ? 256 : 60;
    $flagHeight = $variant === 'overview-cards' ? 192 : 45;
@endphp
@if($showCredentials)
<div class="dmba-credentials dmba-credentials--{{ $variant }}" data-testid="dmba-credentials-{{ $variant }}" role="group" @if(filled($label ?? null)) aria-label="{{ $label }}" @else aria-label="Dual qualifications" @endif>
  @if(filled($label ?? null))
  <p class="dmba-credentials__label">{{ $label }}</p>
  @endif
  <div class="dmba-credentials__inner">
    @foreach($credentialItems as $item)
      @if(! $loop->first)
      <span class="dmba-credentials__plus" aria-hidden="true">+</span>
      @endif
      <div class="dmba-credentials__item">
        <img
          src="https://flagcdn.com/{{ $flagWidth }}x{{ $flagHeight }}/{{ strtolower($item['iso2']) }}.png"
          alt=""
          class="dmba-credentials__flag"
          width="{{ $flagWidth }}"
          height="{{ $flagHeight }}"
          loading="lazy"
          decoding="async"
        />
        <div class="dmba-credentials__text">
          <span class="dmba-credentials__title">{{ $item['title'] }}</span>
          @if(filled($item['subtitle'] ?? null))
          <span class="dmba-credentials__subtitle">{{ $item['subtitle'] }}</span>
          @endif
        </div>
      </div>
    @endforeach
  </div>
</div>
@endif
