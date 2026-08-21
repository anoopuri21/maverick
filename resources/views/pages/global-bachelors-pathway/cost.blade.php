@php
    $costRows = collect($cost->comparisons ?? [])->filter(fn ($row) => filled($row['label'] ?? null) || filled($row['value'] ?? null));
    $showCost = filled($cost->tag ?? null)
        || filled($cost->heading ?? null)
        || filled($cost->heading_italic ?? null)
        || html_filled($cost->description ?? null)
        || html_filled($cost->closing ?? null)
        || $costRows->isNotEmpty();
@endphp
@if($showCost)
<section class="gbp-cost section-wrapper" aria-label="Cost and Time Advantage" data-testid="gbp-cost">
    <div class="container">
        <div class="gbp-cost__grid">
            <div class="gbp-cost__main">
                @if(filled($cost->tag))
                <span class="section-label"><span>{{ $cost->tag }}</span></span>
                @endif
                @if(filled($cost->heading) || filled($cost->heading_italic))
                <h2 class="gbp-cost__heading section-title">
                    @if(filled($cost->heading))
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $cost->heading }}</span></span>
                    @endif
                    @if(filled($cost->heading_italic))
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $cost->heading_italic }}</em></span></span>
                    @endif
                </h2>
                @endif
                @if(html_filled($cost->description ?? null))
                <div class="gbp-cost__description body-text fade-up gbp-richtext">{!! $cost->description !!}</div>
                @endif
                @if(html_filled($cost->closing ?? null))
                <div class="gbp-cost__closing body-text fade-up gbp-richtext">{!! $cost->closing !!}</div>
                @endif
            </div>
            @if($costRows->isNotEmpty())
            <div class="gbp-cost__rows">
                @foreach($costRows as $row)
                @php $variant = in_array($row['variant'] ?? 'muted', ['muted', 'accent'], true) ? $row['variant'] : 'muted'; @endphp
                <div class="gbp-cost-row gbp-cost-row--{{ $variant }} fade-up">
                    @if(filled($row['label'] ?? null))
                    <span class="gbp-cost-row__label">{{ $row['label'] }}</span>
                    @endif
                    @if(filled($row['value'] ?? null))
                    <span class="gbp-cost-row__value">{{ $row['value'] }}</span>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>
@endif
