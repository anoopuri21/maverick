@php
    $whyItems = collect($why->items ?? [])->filter(fn ($item) => filled($item['title'] ?? null) || filled($item['description'] ?? null));
    $showWhy = filled($why->tag ?? null)
        || filled($why->heading ?? null)
        || filled($why->heading_italic ?? null)
        || html_filled($why->quote ?? null)
        || html_filled($why->paragraph ?? null)
        || $whyItems->isNotEmpty();
@endphp
@if($showWhy)
<section class="gbp-why section-wrapper section--light section--warm" aria-label="Why Choose This Pathway" data-testid="gbp-why">
    <div class="container">
        <div class="gbp-why__grid">
            <div class="gbp-why__sticky">
                @if(filled($why->tag))
                <span class="section-label"><span>{{ $why->tag }}</span></span>
                @endif
                @if(filled($why->heading) || filled($why->heading_italic))
                <h2 class="gbp-why__heading section-title">
                    @if(filled($why->heading))
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $why->heading }}</span></span>
                    @endif
                    @if(filled($why->heading_italic))
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $why->heading_italic }}</em></span></span>
                    @endif
                </h2>
                @endif
                @if(html_filled($why->quote ?? null))
                <blockquote class="gbp-why__quote fade-up gbp-richtext">{!! $why->quote !!}</blockquote>
                @endif
                @if(html_filled($why->paragraph ?? null))
                <div class="gbp-why__paragraph body-text fade-up gbp-richtext">{!! $why->paragraph !!}</div>
                @endif
            </div>
            @if($whyItems->isNotEmpty())
            <div class="gbp-why__cards">
                @foreach($whyItems as $item)
                <article class="gbp-why-card" data-testid="gbp-why-card-{{ $loop->iteration }}">
                    <span class="gbp-why-card__number">{{ sprintf('%02d', $loop->iteration) }}</span>
                    @if(filled($item['icon_key'] ?? null))
                    <div class="gbp-why-card__icon" aria-hidden="true">
                        <x-gbp.icon :name="$item['icon_key']" :size="24" />
                    </div>
                    @endif
                    <div class="gbp-why-card__body">
                        @if(filled($item['title'] ?? null))
                        <h3 class="gbp-why-card__title card-title">{{ $item['title'] }}</h3>
                        @endif
                        @if(filled($item['description'] ?? null))
                        <p class="gbp-why-card__description">{{ $item['description'] }}</p>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>
@endif
