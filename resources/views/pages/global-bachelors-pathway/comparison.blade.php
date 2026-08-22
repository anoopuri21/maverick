@php
    $comparisonCards = collect($comparison->cards ?? [])->filter(fn ($c) => filled($c['title'] ?? null) || filled($c['duration'] ?? null));
    $showCallout = filled($comparison->callout_label ?? null) || filled($comparison->callout_value ?? null) || html_filled($comparison->callout_description ?? null);
    $showComparison = html_filled($comparison->heading ?? null) || $comparisonCards->isNotEmpty() || $showCallout;
@endphp
@if($showComparison)
<section class="gbp-comparison section-wrapper section--light" aria-label="Pathway Comparison" data-testid="gbp-comparison">
    <div class="container">
        @if(html_filled($comparison->heading ?? null))
        <div class="gbp-comparison__header">
            <h2 class="gbp-comparison__heading section-title gbp-richtext">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{!! $comparison->heading !!}</span></span>
            </h2>
        </div>
        @endif

        @if($comparisonCards->isNotEmpty())
        <div class="gbp-comparison__grid">
            @foreach($comparisonCards as $card)
            @php
                $bullets = collect($card['bullets'] ?? [])->filter(fn ($b) => filled($b));
                $prices = collect($card['prices'] ?? [])->filter(fn ($p) => filled($p['country'] ?? null) || filled($p['amount'] ?? null));
                $priceMode = ($card['price_mode'] ?? 'single') === 'rows' ? 'rows' : 'single';
            @endphp
            <article class="gbp-comparison-card{{ !empty($card['is_recommended']) ? ' gbp-comparison-card--recommended' : '' }}" data-testid="gbp-comparison-card-{{ $loop->iteration }}">
                <div class="gbp-comparison-card__badge-notch"></div>
                <div class="gbp-comparison-card__body">
                    @if(filled($card['title'] ?? null))
                    <h3 class="gbp-comparison-card__title">{{ $card['title'] }}</h3>
                    @endif
                    @if(filled($card['duration'] ?? null))
                    <span class="gbp-comparison-card__duration">{{ $card['duration'] }}</span>
                    @endif
                    <div class="gbp-comparison-card__divider"></div>
                    @if($bullets->isNotEmpty())
                    <ul class="gbp-comparison-card__bullets">
                        @foreach($bullets as $bullet)
                        <li>
                            <span class="bullet-dot"></span>
                            {{ $bullet }}
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @if(filled($card['tagline'] ?? null) || filled($card['price_value'] ?? null) || $prices->isNotEmpty())
                <div class="gbp-comparison-card__footer">
                    @if(filled($card['tagline'] ?? null))
                    <div class="gbp-comparison-card__tagline-wrapper">
                        <div class="gbp-comparison-card__tagline">{{ $card['tagline'] }}</div>
                    </div>
                    @endif
                    @if($priceMode === 'single' && filled($card['price_value'] ?? null))
                    <div class="gbp-comparison-card__price-panel">
                        @if(filled($card['price_label'] ?? null))
                        <span class="gbp-comparison-card__price-label">{{ $card['price_label'] }}</span>
                        @endif
                        <div class="gbp-comparison-card__price-value">{{ $card['price_value'] }}</div>
                    </div>
                    @elseif($priceMode === 'rows' && $prices->isNotEmpty())
                    <div class="gbp-comparison-card__price-panel">
                        @if(filled($card['price_label'] ?? null))
                        <span class="gbp-comparison-card__price-label">{{ $card['price_label'] }}</span>
                        @endif
                        <div class="gbp-comparison-card__price-list">
                            @foreach($prices as $price)
                            <div class="gbp-comparison-card__price-row">
                                @if(filled($price['country'] ?? null))
                                <span class="gbp-comparison-card__price-country">{{ $price['country'] }}</span>
                                @endif
                                @if(filled($price['amount'] ?? null))
                                <span class="gbp-comparison-card__price-amount">{{ $price['amount'] }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </article>
            @endforeach
        </div>
        @endif

        @if($showCallout)
        <div class="gbp-comparison-callout" data-testid="gbp-comparison-callout">
            @if(filled($comparison->callout_label) || filled($comparison->callout_value))
            <div class="gbp-comparison-callout__left">
                @if(filled($comparison->callout_label))
                <span class="gbp-comparison-callout__label">{{ $comparison->callout_label }}</span>
                @endif
                @if(filled($comparison->callout_value))
                <div class="gbp-comparison-callout__value">{{ $comparison->callout_value }}</div>
                @endif
            </div>
            @endif
            @if(html_filled($comparison->callout_description ?? null))
            <div class="gbp-comparison-callout__right">
                <div class="gbp-comparison-callout__description gbp-richtext">{!! $comparison->callout_description !!}</div>
            </div>
            @endif
        </div>
        @endif
    </div>
</section>
@endif
