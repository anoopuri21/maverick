@php
    $exploreCards = collect($explore->cards ?? [])->filter(fn ($c) => filled($c['country'] ?? null) || filled($c['university'] ?? null));
    $showExplore = filled($explore->label ?? null)
        || filled($explore->heading ?? null)
        || filled($explore->heading_italic ?? null)
        || html_filled($explore->sub ?? null)
        || $exploreCards->isNotEmpty();
@endphp
@if($showExplore)
<section class="gbp-explore section-wrapper" aria-label="Explore Europe with Your Choices" data-testid="gbp-explore">
    <div class="container">
        <div class="gbp-explore__header">
            @if(filled($explore->label))
            <span class="section-label"><span>{{ $explore->label }}</span></span>
            @endif
            @if(filled($explore->heading) || filled($explore->heading_italic))
            <h2 class="gbp-explore__heading section-title">
                @if(filled($explore->heading))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $explore->heading }}</span></span>
                @endif
                @if(filled($explore->heading_italic))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $explore->heading_italic }}</em></span></span>
                @endif
            </h2>
            @endif
            @if(html_filled($explore->sub ?? null))
            <div class="gbp-explore__sub body-text fade-up gbp-richtext">{!! $explore->sub !!}</div>
            @endif
        </div>

        @if($exploreCards->isNotEmpty())
        <div class="gbp-explore__grid">
            @foreach($exploreCards as $country)
            <article class="gbp-explore-card" data-testid="gbp-explore-{{ \Illuminate\Support\Str::slug($country['country'] ?? $loop->iteration) }}">
                @if(filled($country['flag'] ?? null))
                <div class="gbp-explore-card__flag" aria-hidden="true">{{ $country['flag'] }}</div>
                @endif
                @if(filled($country['country'] ?? null))
                <h3 class="gbp-explore-card__country card-title">{{ $country['country'] }}</h3>
                @endif
                @if(filled($country['type'] ?? null))
                <span class="gbp-explore-card__type">{{ $country['type'] }}</span>
                @endif
                @if(filled($country['university'] ?? null))
                <p class="gbp-explore-card__university">{{ $country['university'] }}</p>
                @endif
                @php $highlights = collect($country['highlights'] ?? [])->filter(fn ($h) => filled($h)); @endphp
                @if($highlights->isNotEmpty())
                <ul class="gbp-explore-card__highlights">
                    @foreach($highlights as $highlight)
                    <li>
                        <span aria-hidden="true"><x-gbp.icon name="check" :size="18" /></span>
                        {{ $highlight }}
                    </li>
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
