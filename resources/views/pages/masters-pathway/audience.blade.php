@php
    $audienceItems = collect($audience->items ?? [])->filter(fn ($i) => filled($i));
    $showAudience = filled($audience->label ?? null)
        || filled($audience->heading ?? null)
        || filled($audience->heading_highlight ?? null)
        || filled($audience->statement ?? null)
        || $audienceItems->isNotEmpty();
@endphp
@if($showAudience)
<section class="mp-audience section-wrapper section--light" aria-label="Who Is This Program For" data-testid="mp-audience">
    <div class="container">
        <div class="mp-audience__header">
            @if(filled($audience->label))
            <span class="section-label"><span>{{ $audience->label }}</span></span>
            @endif
            @if(filled($audience->heading) || filled($audience->heading_highlight))
            <h2 class="mp-audience__heading section-title">
                @if(filled($audience->heading))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $audience->heading }}</span></span>
                @endif
                @if(filled($audience->heading_highlight))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><span class="color-red">{{ $audience->heading_highlight }}</span></span></span>
                @endif
            </h2>
            @endif
            @if(filled($audience->statement))
            <p class="mp-audience__statement">{{ $audience->statement }}</p>
            @endif
        </div>

        @if($audienceItems->isNotEmpty())
        <div class="mp-audience__grid">
            @foreach($audienceItems as $item)
            <div class="mp-audience__item fade-up">
                <span class="mp-audience__index">{{ sprintf('%02d', $loop->iteration) }}</span>
                <p class="mp-audience__text">{{ $item }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
