@php
    $howPhases = collect($how->phases ?? [])->filter(fn ($p) => filled($p['title'] ?? null) || filled($p['num'] ?? null));
    $showHow = filled($how->label ?? null)
        || filled($how->heading ?? null)
        || filled($how->heading_highlight ?? null)
        || $howPhases->isNotEmpty()
        || html_filled($how->notice ?? null);
@endphp
@if($showHow)
<section class="mp-how section-wrapper section--light" aria-label="How the Master's Pathway Works" data-testid="mp-how">
    <div class="container">
        <div class="mp-how__header">
            @if(filled($how->label))
            <span class="section-label"><span>{{ $how->label }}</span></span>
            @endif
            @if(filled($how->heading) || filled($how->heading_highlight))
            <h2 class="mp-how__heading section-title">
                @if(filled($how->heading))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $how->heading }}</span></span>
                @endif
                @if(filled($how->heading_highlight))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><span class="color-red">{{ $how->heading_highlight }}</span></span></span>
                @endif
            </h2>
            @endif
        </div>

        @if($howPhases->isNotEmpty())
        <div class="mp-how__phases">
            @foreach($howPhases as $phase)
            @php $facts = collect($phase['facts'] ?? [])->filter(fn ($f) => filled($f['label'] ?? null) || filled($f['value'] ?? null)); @endphp
            <div class="mp-how__phase">
                @if(filled($phase['num'] ?? null))
                <span class="mp-how__phase-num">{{ $phase['num'] }}</span>
                @endif
                @if(filled($phase['title'] ?? null))
                <h3 class="mp-how__phase-title">{{ $phase['title'] }}</h3>
                @endif
                @if(filled($phase['sub'] ?? null))
                <p class="mp-how__phase-sub">{{ $phase['sub'] }}</p>
                @endif
                @if($facts->isNotEmpty())
                <dl class="mp-how__phase-facts">
                    @foreach($facts as $fact)
                    <div>
                        @if(filled($fact['label'] ?? null))<dt>{{ $fact['label'] }}</dt>@endif
                        @if(filled($fact['value'] ?? null))<dd>{{ $fact['value'] }}</dd>@endif
                    </div>
                    @endforeach
                </dl>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        @if(html_filled($how->notice ?? null))
        <div class="mp-how__notice mp-richtext">{!! $how->notice !!}</div>
        @endif
    </div>
</section>
@endif
