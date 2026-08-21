@php
    $finalCtas = collect($finalCta->ctas ?? [])->filter(fn ($c) => filled($c['label'] ?? null) && filled($c['url'] ?? null));
    $contacts = collect($finalCta->contacts ?? [])->filter(fn ($c) => filled($c['label'] ?? null));
    $showFinal = filled($finalCta->eyebrow ?? null)
        || filled($finalCta->heading ?? null)
        || filled($finalCta->heading_highlight ?? null)
        || html_filled($finalCta->sub ?? null)
        || html_filled($finalCta->description ?? null)
        || $finalCtas->isNotEmpty()
        || $contacts->isNotEmpty();
@endphp
@if($showFinal)
<section class="mp-final" id="enquire" aria-label="Start Your Master's Journey" data-testid="mp-final">
    <div class="container">
        <div class="mp-final__inner">
            @if(filled($finalCta->eyebrow))
            <span class="mp-final__eyebrow">{{ $finalCta->eyebrow }}</span>
            @endif
            @if(filled($finalCta->heading) || filled($finalCta->heading_highlight))
            <h2 class="mp-final__heading section-title">
                {{ $finalCta->heading }}
                @if(filled($finalCta->heading_highlight))
                <span>{{ $finalCta->heading_highlight }}</span>
                @endif
            </h2>
            @endif
            @if(html_filled($finalCta->sub ?? null))
            <div class="mp-final__sub mp-richtext">{!! $finalCta->sub !!}</div>
            @endif
            @if(html_filled($finalCta->description ?? null))
            <div class="mp-final__description body-text mp-richtext">{!! $finalCta->description !!}</div>
            @endif
            @if($finalCtas->isNotEmpty())
            <div class="mp-final__ctas">
                @foreach($finalCtas as $cta)
                @php
                    $class = ($cta['style'] ?? 'solid') === 'outline'
                        ? 'btn mp-final__btn mp-final__btn--outline'
                        : 'btn mp-final__btn mp-final__btn--solid';
                @endphp
                <a href="{{ edu_href($cta['url']) }}" class="{{ $class }}">{{ $cta['label'] }}</a>
                @endforeach
            </div>
            @endif
            @if($contacts->isNotEmpty())
            <div class="mp-final__contact">
                @foreach($contacts as $contact)
                    @if(filled($contact['url'] ?? null))
                    <a href="{{ edu_href($contact['url']) }}"><span>{{ $contact['label'] }}</span></a>
                    @else
                    <span>{{ $contact['label'] }}</span>
                    @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>
@endif
