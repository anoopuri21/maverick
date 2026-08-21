@php
    $overviewParagraphs = collect($overview->paragraphs ?? [])->filter(fn ($p) => html_filled(is_string($p) ? $p : null));
    $overviewPhases = collect($overview->phases ?? [])->filter(fn ($p) => filled($p['title'] ?? null) || filled($p['label'] ?? null) || filled($p['desc'] ?? null));
    $showOverview = filled($overview->label ?? null)
        || filled($overview->heading ?? null)
        || filled($overview->heading_highlight ?? null)
        || $overviewParagraphs->isNotEmpty()
        || $overviewPhases->isNotEmpty();
@endphp
@if($showOverview)
<section class="mp-overview section-wrapper section--light" aria-label="What Is the Master's Pathway Program" data-testid="mp-overview">
    <div class="container">
        @if(filled($overview->label) || filled($overview->heading) || filled($overview->heading_highlight) || $overviewParagraphs->isNotEmpty())
        <div class="mp-overview__grid">
            <div class="mp-overview__left">
                @if(filled($overview->label))
                <span class="section-label"><span>{{ $overview->label }}</span></span>
                @endif
                @if(filled($overview->heading) || filled($overview->heading_highlight))
                <h2 class="mp-overview__heading section-title">
                    @if(filled($overview->heading))
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $overview->heading }}</span></span>
                    @endif
                    @if(filled($overview->heading_highlight))
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><span class="color-red">{{ $overview->heading_highlight }}</span></span></span>
                    @endif
                </h2>
                @endif
            </div>
            @if($overviewParagraphs->isNotEmpty())
            <div class="mp-overview__right">
                @foreach($overviewParagraphs as $paragraph)
                <div class="body-text fade-up mp-richtext">{!! $paragraph !!}</div>
                @endforeach
            </div>
            @endif
        </div>
        @endif

        @if($overviewPhases->isNotEmpty())
        <div class="mp-pathway" data-testid="mp-pathway">
            @foreach($overviewPhases as $phase)
            <div class="mp-pathway__phase mp-pathway__phase--{{ $loop->iteration }}">
                @if(filled($phase['label'] ?? null))
                <span class="mp-pathway__phase-label">{{ $phase['label'] }}</span>
                @endif
                @if(filled($phase['title'] ?? null))
                <h3 class="mp-pathway__phase-title">{{ $phase['title'] }}</h3>
                @endif
                @if(filled($phase['meta'] ?? null))
                <p class="mp-pathway__phase-meta">{{ $phase['meta'] }}</p>
                @endif
                @if(filled($phase['desc'] ?? null))
                <p class="mp-pathway__phase-desc">{{ $phase['desc'] }}</p>
                @endif
            </div>
            @if(! $loop->last)
            <div class="mp-pathway__connector" aria-hidden="true">
                <span class="mp-pathway__connector-line"></span>
                <span class="mp-pathway__connector-dot"></span>
            </div>
            @endif
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
