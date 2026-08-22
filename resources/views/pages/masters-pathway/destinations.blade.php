@php
    $destItems = collect($destinations->items ?? [])->filter(fn ($d) => filled($d['name'] ?? null) || filled($d['university'] ?? null) || html_filled($d['description'] ?? null));
    $showDest = filled($destinations->label ?? null)
        || filled($destinations->heading ?? null)
        || filled($destinations->heading_highlight ?? null)
        || html_filled($destinations->sub ?? null)
        || $destItems->isNotEmpty();
@endphp
@if($showDest)
<section class="mp-destinations section-wrapper section--light" aria-label="Study Destinations" data-testid="mp-destinations">
    <div class="container">
        <div class="mp-destinations__header">
            @if(filled($destinations->label))
            <span class="section-label"><span>{{ $destinations->label }}</span></span>
            @endif
            @if(filled($destinations->heading) || filled($destinations->heading_highlight))
            <h2 class="mp-destinations__heading section-title">
                @if(filled($destinations->heading))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $destinations->heading }}</span></span>
                @endif
                @if(filled($destinations->heading_highlight))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><span class="color-red">{{ $destinations->heading_highlight }}</span></span></span>
                @endif
            </h2>
            @endif
            @if(html_filled($destinations->sub ?? null))
            <div class="body-text fade-up mp-richtext">{!! $destinations->sub !!}</div>
            @endif
        </div>

        @if($destItems->isNotEmpty())
        <div class="mp-destinations__list">
            @foreach($destItems as $dest)
            @php
                $slug = \Illuminate\Support\Str::slug($dest['slug'] ?? $dest['name'] ?? $loop->iteration);
                $position = in_array($dest['position'] ?? 'left', ['left', 'right'], true) ? $dest['position'] : 'left';
                $points = collect($dest['points'] ?? [])->filter(fn ($p) => filled($p));
            @endphp
            <article class="mp-dest mp-dest--{{ $position }}" data-testid="mp-dest-{{ $slug }}">
                @if(filled($dest['image'] ?? null))
                <div class="mp-dest__media">
                    <img class="mp-dest__image" src="{{ media_url($dest['image']) }}" alt="{{ filled($dest['name'] ?? null) ? 'Study in '.$dest['name'] : '' }}" loading="lazy" width="760" height="950">
                    <div class="mp-dest__overlay" aria-hidden="true"></div>
                    @if(filled($dest['name'] ?? null))
                    <span class="mp-dest__country">{{ $dest['name'] }}</span>
                    @endif
                </div>
                @endif
                <div class="mp-dest__content fade-up">
                    @if(filled($dest['label'] ?? null))
                    <span class="mp-dest__label">{{ $dest['label'] }}</span>
                    @endif
                    @if(filled($dest['name'] ?? null))
                    <h3 class="mp-dest__title card-title">Study in <span class="color-red">{{ $dest['name'] }}</span></h3>
                    @endif
                    @if(filled($dest['university'] ?? null))
                    <p class="mp-dest__partner">{{ $dest['university'] }}</p>
                    @endif
                    @if(html_filled($dest['description'] ?? null))
                    <div class="mp-dest__description body-text mp-richtext">{!! rich_html($dest['description'] ?? null) !!}</div>
                    @endif
                    @if($points->isNotEmpty())
                    <ul class="mp-dest__points">
                        @foreach($points as $point)
                        <li><span class="mp-dest__point-dot" aria-hidden="true"></span>{{ $point }}</li>
                        @endforeach
                    </ul>
                    @endif
                    @if(filled($dest['best_for'] ?? null))
                    <p class="mp-dest__best"><strong>Best Suited For:</strong> {{ $dest['best_for'] }}</p>
                    @endif
                    @if(filled($dest['qualification'] ?? null))
                    <p class="mp-dest__qualification">{{ $dest['qualification'] }}</p>
                    @endif
                </div>
            </article>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
