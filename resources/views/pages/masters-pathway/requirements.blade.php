@php
    $reqItems = collect($requirements->items ?? [])->filter(fn ($i) => filled($i));
    $showReq = filled($requirements->label ?? null)
        || filled($requirements->heading ?? null)
        || filled($requirements->heading_highlight ?? null)
        || html_filled($requirements->intro ?? null)
        || $reqItems->isNotEmpty();
@endphp
@if($showReq)
<section class="mp-requirements section-wrapper section--light" aria-label="Entry Requirements" data-testid="mp-requirements">
    <div class="container">
        <div class="mp-requirements__grid">
            <div class="mp-requirements__intro">
                @if(filled($requirements->label))
                <span class="section-label"><span>{{ $requirements->label }}</span></span>
                @endif
                @if(filled($requirements->heading) || filled($requirements->heading_highlight))
                <h2 class="mp-requirements__heading section-title">
                    @if(filled($requirements->heading))
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $requirements->heading }}</span></span>
                    @endif
                    @if(filled($requirements->heading_highlight))
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><span class="color-red">{{ $requirements->heading_highlight }}</span></span></span>
                    @endif
                </h2>
                @endif
                @if(html_filled($requirements->intro ?? null))
                <div class="body-text fade-up mp-richtext">{!! $requirements->intro !!}</div>
                @endif
            </div>
            @if($reqItems->isNotEmpty())
            <ul class="mp-requirements__list">
                @foreach($reqItems as $req)
                <li class="mp-requirements__item fade-up">
                    <span class="mp-requirements__index">{{ sprintf('%02d', $loop->iteration) }}</span>
                    <span class="mp-requirements__text">{{ $req }}</span>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</section>
@endif
