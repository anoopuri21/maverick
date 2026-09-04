@php
    $partnerCards = collect($partners->cards ?? [])->filter(fn ($c) => filled($c['name'] ?? null) || filled($c['code'] ?? null));
    $showPartners = filled($partners->label ?? null)
        || filled($partners->heading ?? null)
        || filled($partners->heading_italic ?? null)
        || html_filled($partners->sub ?? null)
        || $partnerCards->isNotEmpty();
@endphp
@if($showPartners)
<section class="gbp-partners section-wrapper" aria-label="Partner University Progression" data-testid="gbp-partners">
    <div class="container">
        <div class="gbp-partners__header">
            @if(filled($partners->label))
            <span class="section-label"><span>{{ $partners->label }}</span></span>
            @endif
            @if(filled($partners->heading) || filled($partners->heading_italic))
            <h2 class="gbp-partners__heading section-title">
                @if(filled($partners->heading))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $partners->heading }}</span></span>
                @endif
                @if(filled($partners->heading_italic))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $partners->heading_italic }}</em></span></span>
                @endif
            </h2>
            @endif
            @if(html_filled($partners->sub ?? null))
            <div class="gbp-partners__sub body-text fade-up gbp-richtext">{!! $partners->sub !!}</div>
            @endif
        </div>

        @if($partnerCards->isNotEmpty())
        <div class="gbp-partners__grid">
            @foreach($partnerCards as $partner)
            <article class="gbp-partner-card" data-testid="gbp-partner-{{ strtolower($partner['code'] ?? $loop->iteration) }}">
                @if(filled($partner['code'] ?? null))
                <span class="gbp-partner-card__code" aria-hidden="true">{{ $partner['code'] }}</span>
                @endif
                @if(filled($partner['name'] ?? null))
                <h3 class="gbp-partner-card__title card-title">{{ $partner['name'] }}</h3>
                @endif
                @if(filled($partner['description'] ?? null))
                <div class="gbp-partner-card__description">{!! rich_html($partner['description'] ?? null) !!}</div>
                @endif
                @php $tags = collect($partner['best_for'] ?? [])->filter(fn ($t) => filled($t)); @endphp
                @if($tags->isNotEmpty())
                <div class="gbp-partner-card__suited">
                    <span class="gbp-partner-card__suited-label">Best Suited For</span>
                    <div class="gbp-partner-card__tags">
                        @foreach($tags as $tag)
                        <span class="gbp-partner-card__tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </article>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
