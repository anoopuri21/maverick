@php
    $destItems = collect($destinations->items ?? [])->filter(fn ($d) => filled($d['name'] ?? null) || filled($d['university'] ?? null) || html_filled($d['description'] ?? null));
    $showDest = filled($destinations->label ?? null)
        || filled($destinations->heading ?? null)
        || filled($destinations->heading_italic ?? null)
        || $destItems->isNotEmpty();
@endphp
@if($showDest)
<section class="gbp-destinations section-wrapper" aria-label="Study Destinations" data-testid="gbp-destinations">
    <div class="container">
        <div class="gbp-destinations__header">
            @if(filled($destinations->label))
            <span class="section-label"><span>{{ $destinations->label }}</span></span>
            @endif
            @if(filled($destinations->heading) || filled($destinations->heading_italic))
            <h2 class="gbp-destinations__heading section-title">
                @if(filled($destinations->heading))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $destinations->heading }}</span></span>
                @endif
                @if(filled($destinations->heading_italic))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $destinations->heading_italic }}</em></span></span>
                @endif
            </h2>
            @endif
        </div>

        @if($destItems->isNotEmpty())
        <div class="gbp-destinations__list">
            @foreach($destItems as $dest)
            @php
                $slug = \Illuminate\Support\Str::slug($dest['slug'] ?? $dest['name'] ?? $loop->iteration);
                $position = in_array($dest['position'] ?? 'right', ['left', 'right'], true) ? $dest['position'] : 'right';
                $points = collect($dest['points'] ?? [])->filter(fn ($p) => filled($p));
            @endphp
            <article class="gbp-dest gbp-dest--{{ $position }} gbp-dest--{{ $slug }}" data-testid="gbp-dest-{{ $slug }}">
                @if(filled($dest['image'] ?? null))
                <div class="gbp-dest__media">
                    <img
                        class="gbp-dest__image"
                        src="{{ media_url($dest['image']) }}"
                        alt="{{ filled($dest['name'] ?? null) ? 'Study in '.$dest['name'] : '' }}"
                        loading="lazy"
                        width="760"
                        height="950"
                    >
                    <div class="gbp-dest__overlay" aria-hidden="true"></div>
                </div>
                @endif
                <div class="gbp-dest__content fade-up">
                    @if(filled($dest['label'] ?? null))
                    <span class="gbp-dest__label">{{ $dest['label'] }}</span>
                    @endif
                    @if(filled($dest['name'] ?? null))
                    <h3 class="gbp-dest__title card-title">Study in <em>{{ $dest['name'] }}</em></h3>
                    @endif
                    @if(filled($dest['university'] ?? null))
                    <p class="gbp-dest__partner">{{ $dest['university'] }}</p>
                    @endif
                    @if(html_filled($dest['description'] ?? null))
                    <div class="gbp-dest__description body-text gbp-richtext">{!! rich_html($dest['description'] ?? null) !!}</div>
                    @endif
                    @if($points->isNotEmpty())
                    <ul class="gbp-dest__points">
                        @foreach($points as $point)
                        <li>
                            <span aria-hidden="true"><x-gbp.icon name="check" :size="18" /></span>
                            {{ $point }}
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    @if(filled($dest['best_for'] ?? null))
                    <p class="gbp-dest__best"><strong>Best For:</strong> {{ $dest['best_for'] }}</p>
                    @endif
                </div>
            </article>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
