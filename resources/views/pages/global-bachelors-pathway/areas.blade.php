@php
    $areaCards = collect($areas->cards ?? [])->filter(fn ($c) => filled($c['title'] ?? null) || filled($c['description'] ?? null));
    $showAreas = filled($areas->label ?? null)
        || filled($areas->heading ?? null)
        || filled($areas->heading_italic ?? null)
        || html_filled($areas->sub ?? null)
        || $areaCards->isNotEmpty();
@endphp
@if($showAreas)
<section class="gbp-areas section-wrapper section--light" aria-label="Programs Offered" data-testid="gbp-areas">
    <div class="container">
        <div class="gbp-areas__header">
            @if(filled($areas->label))
            <span class="section-label"><span>{{ $areas->label }}</span></span>
            @endif
            @if(filled($areas->heading) || filled($areas->heading_italic))
            <h2 class="gbp-areas__heading section-title">
                @if(filled($areas->heading))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $areas->heading }}</span></span>
                @endif
                @if(filled($areas->heading_italic))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $areas->heading_italic }}</em></span></span>
                @endif
            </h2>
            @endif
            @if(html_filled($areas->sub ?? null))
            <div class="gbp-areas__sub body-text fade-up gbp-richtext">{!! $areas->sub !!}</div>
            @endif
        </div>

        @if($areaCards->isNotEmpty())
        <div class="gbp-areas__grid">
            @foreach($areaCards as $area)
            <article class="gbp-area-card" data-testid="gbp-area-{{ $loop->iteration }}">
                @if(filled($area['icon_key'] ?? null))
                <div class="gbp-area-card__icon" aria-hidden="true">
                    <x-gbp.icon :name="$area['icon_key']" :size="24" />
                </div>
                @endif
                @if(filled($area['title'] ?? null))
                <h3 class="gbp-area-card__title card-title">{{ $area['title'] }}</h3>
                @endif
                @if(filled($area['description'] ?? null))
                <p class="gbp-area-card__description">{!! rich_html($area['description'] ?? null) !!}</p>
                @endif
                @php $items = collect($area['items'] ?? [])->filter(fn ($i) => filled($i)); @endphp
                @if($items->isNotEmpty())
                <ul class="gbp-area-card__list">
                    @foreach($items as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                @endif
            </article>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
