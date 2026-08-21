@php
    $whyItems = collect($why->items ?? [])->filter(fn ($i) => filled($i['title'] ?? null) || filled($i['desc'] ?? null));
    $showWhy = filled($why->label ?? null)
        || filled($why->heading ?? null)
        || filled($why->heading_highlight ?? null)
        || filled($why->statement ?? null)
        || $whyItems->isNotEmpty();
@endphp
@if($showWhy)
<section class="mp-why section-wrapper section--light" aria-label="Why Choose Maverick's Master's Pathway" data-testid="mp-why">
    <div class="container">
        <div class="mp-why__grid">
            <div class="mp-why__left">
                @if(filled($why->label))
                <span class="section-label"><span>{{ $why->label }}</span></span>
                @endif
                @if(filled($why->heading) || filled($why->heading_highlight))
                <h2 class="mp-why__heading section-title">
                    @if(filled($why->heading))
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $why->heading }}</span></span>
                    @endif
                    @if(filled($why->heading_highlight))
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><span class="color-red">{{ $why->heading_highlight }}</span></span></span>
                    @endif
                </h2>
                @endif
                @if(filled($why->statement))
                <p class="mp-why__statement">{{ $why->statement }}</p>
                @endif
            </div>
            @if($whyItems->isNotEmpty())
            <div class="mp-why__right">
                @foreach($whyItems as $benefit)
                <div class="mp-benefit fade-up">
                    <span class="mp-benefit__num">{{ sprintf('%02d', $loop->iteration) }}</span>
                    <div class="mp-benefit__body">
                        @if(filled($benefit['title'] ?? null))
                        <h3 class="mp-benefit__title">{{ $benefit['title'] }}</h3>
                        @endif
                        @if(filled($benefit['desc'] ?? null))
                        <p class="mp-benefit__desc">{{ $benefit['desc'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>
@endif
