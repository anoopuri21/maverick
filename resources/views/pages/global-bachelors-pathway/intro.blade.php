@php
    $introParagraphs = collect($intro->paragraphs ?? [])->filter(fn ($p) => html_filled(is_string($p) ? $p : null));
    $introHighlights = collect($intro->highlights ?? [])->filter(fn ($h) => filled($h['label'] ?? null) || filled($h['value'] ?? null));
    $showIntro = filled($intro->tag ?? null)
        || filled($intro->heading_line1 ?? null)
        || filled($intro->heading_line2 ?? null)
        || filled($intro->heading_italic ?? null)
        || $introParagraphs->isNotEmpty()
        || $introHighlights->isNotEmpty();
@endphp
@if($showIntro)
<section class="gbp-intro" aria-label="Your Structured Route" data-testid="gbp-intro">
    <div class="container">
        @if(filled($intro->tag) || filled($intro->heading_line1) || filled($intro->heading_line2) || filled($intro->heading_italic) || $introParagraphs->isNotEmpty())
        <div class="gbp-intro__content">
            @if(filled($intro->tag))
            <span class="gbp-intro__label">
                <span class="gbp-intro__label-line"></span>
                {{ $intro->tag }}
            </span>
            @endif
            @if(filled($intro->heading_line1) || filled($intro->heading_line2) || filled($intro->heading_italic))
            <h2 class="gbp-intro__heading">
                @if(filled($intro->heading_line1))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $intro->heading_line1 }}</span></span>
                @endif
                @if(filled($intro->heading_line2))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $intro->heading_line2 }}</span></span>
                @endif
                @if(filled($intro->heading_italic))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $intro->heading_italic }}</em></span></span>
                @endif
            </h2>
            @endif
            @foreach($introParagraphs as $paragraph)
            <div class="gbp-intro__paragraph fade-up gbp-richtext">{!! $paragraph !!}</div>
            @endforeach
        </div>
        @endif

        @if($introHighlights->isNotEmpty())
        <div class="gbp-intro__highlights">
            @foreach($introHighlights as $highlight)
            <div class="gbp-intro-card">
                @if(filled($highlight['icon_key'] ?? null))
                <div class="gbp-intro-card__icon">
                    <x-gbp.icon :name="$highlight['icon_key']" :size="24" />
                </div>
                @endif
                <div class="gbp-intro-card__content">
                    @if(filled($highlight['label'] ?? null))
                    <span class="gbp-intro-card__label">{{ $highlight['label'] }}</span>
                    @endif
                    @if(filled($highlight['value'] ?? null))
                    <span class="gbp-intro-card__value">{{ $highlight['value'] }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
