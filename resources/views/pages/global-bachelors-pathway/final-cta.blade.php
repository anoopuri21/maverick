@php
    $finalCtas = collect($finalCta->ctas ?? [])->filter(fn ($cta) => filled($cta['label'] ?? null) && filled($cta['url'] ?? null));
    $showFinal = filled($finalCta->eyebrow ?? null)
        || filled($finalCta->heading ?? null)
        || filled($finalCta->heading_italic ?? null)
        || html_filled($finalCta->sub ?? null)
        || html_filled($finalCta->description ?? null)
        || $finalCtas->isNotEmpty();
@endphp
@if($showFinal)
<section class="gbp-final section-wrapper" id="enquire" aria-label="Start Your Journey" data-testid="gbp-final">
    <div class="container">
        <div class="gbp-final__inner fade-up">
            @if(filled($finalCta->eyebrow))
            <span class="gbp-final__eyebrow">{{ $finalCta->eyebrow }}</span>
            @endif
            @if(filled($finalCta->heading) || filled($finalCta->heading_italic))
            <h2 class="gbp-final__heading section-title">
                {{ $finalCta->heading }}
                @if(filled($finalCta->heading_italic))
                <em>{{ $finalCta->heading_italic }}</em>
                @endif
            </h2>
            @endif
            @if(html_filled($finalCta->sub ?? null))
            <div class="gbp-final__sub gbp-richtext">{!! $finalCta->sub !!}</div>
            @endif
            @if(html_filled($finalCta->description ?? null))
            <div class="gbp-final__description body-text gbp-richtext">{!! rich_html($finalCta->description ?? null) !!}</div>
            @endif
            @if($finalCtas->isNotEmpty())
            <div class="gbp-final__ctas">
                @foreach($finalCtas as $cta)
                @php
                    $class = ($cta['style'] ?? 'outline') === 'solid'
                        ? 'btn gbp-final__btn gbp-final__btn--solid'
                        : 'btn gbp-final__btn gbp-final__btn--outline';
                @endphp
                <a href="{{ edu_href($cta['url']) }}" class="{{ $class }}" @if(filled($cta['anchor_id'] ?? null)) id="{{ $cta['anchor_id'] }}" @endif>{{ $cta['label'] }}</a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>
@endif
