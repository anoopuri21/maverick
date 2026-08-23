@php
    $overviewParagraphs = collect($overview->paragraphs ?? [])->filter(fn ($p) => html_filled(is_string($p) ? $p : null));
    $stages = collect($overview->stages ?? [])->filter(fn ($s) => filled($s['title'] ?? null) || filled($s['year'] ?? null) || filled($s['description'] ?? null));
    $panelStats = collect($overview->panel_stats ?? [])->filter(fn ($s) => filled($s['number'] ?? null) || filled($s['label'] ?? null));
    $showOverview = filled($overview->tag ?? null)
        || filled($overview->heading ?? null)
        || filled($overview->heading_italic ?? null)
        || $overviewParagraphs->isNotEmpty()
        || html_filled($overview->quote ?? null)
        || $stages->isNotEmpty();
    $showRoadmapSvg = $stages->count() === 4;
@endphp
@if($showOverview)
<section class="gbp-overview section-wrapper section--light" aria-label="Programme Overview" data-testid="gbp-overview">
    <div class="container">
        @if(filled($overview->tag) || filled($overview->heading) || filled($overview->heading_italic) || $overviewParagraphs->isNotEmpty())
        <div class="gbp-overview__header">
            @if(filled($overview->tag))
            <span class="section-label"><span>{{ $overview->tag }}</span></span>
            @endif
            @if(filled($overview->heading) || filled($overview->heading_italic))
            <h2 class="gbp-overview__heading section-title">
                @if(filled($overview->heading))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $overview->heading }}</span></span>
                @endif
                @if(filled($overview->heading_italic))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $overview->heading_italic }}</em></span></span>
                @endif
            </h2>
            @endif
            @foreach($overviewParagraphs as $paragraph)
            <div class="gbp-overview__paragraph body-text fade-up gbp-richtext">{!! $paragraph !!}</div>
            @endforeach
        </div>
        @endif

        @if($stages->isNotEmpty())
        <div class="gbp-roadmap-layout">
            <div class="gbp-roadmap-col">
                @if($showRoadmapSvg)
                <div class="gbp-roadmap-svg-wrap" aria-hidden="true">
                    <svg class="gbp-roadmap-svg" viewBox="0 0 100 320" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="gbpRoadmapGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#b20202"/>
                                <stop offset="33%" stop-color="#0f2983"/>
                                <stop offset="66%" stop-color="#9333ea"/>
                                <stop offset="100%" stop-color="#059669"/>
                            </linearGradient>
                            <filter id="gbpRoadmapGlow">
                                <feGaussianBlur stdDeviation="2.5" result="blur"/>
                                <feMerge>
                                    <feMergeNode in="blur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                </feMerge>
                            </filter>
                        </defs>
                        <path class="gbp-roadmap-path-base"
                              d="M50 0 C30 0, 10 14, 10 44 C10 74, 10 86, 10 100 C10 114, 30 120, 50 125 C70 120, 90 126, 90 146 C90 166, 90 178, 90 192 C90 206, 70 212, 50 217 C30 212, 10 218, 10 238 C10 252, 10 264, 10 276 L50 276"
                              fill="none" stroke="rgba(15,41,131,0.12)" stroke-width="2" stroke-dasharray="4 6" stroke-linecap="round"/>
                        <path class="gbp-roadmap-path"
                              d="M50 0 C30 0, 10 14, 10 44 C10 74, 10 86, 10 100 C10 114, 30 120, 50 125 C70 120, 90 126, 90 146 C90 166, 90 178, 90 192 C90 206, 70 212, 50 217 C30 212, 10 218, 10 238 C10 252, 10 264, 10 276 L50 276"
                              fill="none" stroke="url(#gbpRoadmapGrad)" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="1200" stroke-dashoffset="1200" filter="url(#gbpRoadmapGlow)"/>
                    </svg>
                </div>
                @endif

                <div class="gbp-roadmap">
                    @foreach($stages as $index => $stage)
                    <div class="gbp-roadmap-stage gbp-roadmap-stage--{{ $index % 2 === 0 ? 'left' : 'right' }}"
                         data-testid="gbp-roadmap-{{ $index + 1 }}"
                         data-stage-index="{{ $index }}">
                        <div class="gbp-roadmap-stage__marker-col">
                            <div class="gbp-roadmap-stage__marker">
                                @if(filled($stage['year'] ?? null))
                                <span class="gbp-roadmap-stage__number">{{ $stage['year'] }}</span>
                                @endif
                                <div class="gbp-roadmap-stage__marker-ring"></div>
                                <div class="gbp-roadmap-stage__marker-glow"></div>
                            </div>
                        </div>
                        <div class="gbp-roadmap-stage__card">
                            @if(filled($stage['duration'] ?? null))
                            <div class="gbp-roadmap-stage__card-top">
                                <div class="gbp-roadmap-stage__accent-bar"></div>
                                <span class="gbp-roadmap-stage__duration">{{ $stage['duration'] }}</span>
                            </div>
                            @endif
                            @if(filled($stage['title'] ?? null))
                            <h3 class="gbp-roadmap-stage__title card-title">{{ $stage['title'] }}</h3>
                            @endif
                            @if(filled($stage['description'] ?? null))
                            <p class="gbp-roadmap-stage__description">{!! rich_html($stage['description'] ?? null) !!}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <aside class="gbp-roadmap-panel" aria-label="Your journey stages">
                @if(filled($overview->panel_label) || filled($overview->panel_title))
                <div class="gbp-roadmap-panel__header">
                    @if(filled($overview->panel_label))
                    <span class="gbp-roadmap-panel__label">{{ $overview->panel_label }}</span>
                    @endif
                    @if(filled($overview->panel_title))
                    <h3 class="gbp-roadmap-panel__title">{{ $overview->panel_title }}</h3>
                    @endif
                </div>
                @endif
                <div class="gbp-roadmap-panel__list">
                    @foreach($stages as $index => $stage)
                    <div class="gbp-roadmap-panel__item gbp-roadmap-panel__item--{{ $index + 1 }}" data-stage-index="{{ $index }}">
                        <div class="gbp-roadmap-panel__step-num">
                            @if(filled($stage['year'] ?? null))
                            <span>{{ $stage['year'] }}</span>
                            @endif
                            <div class="gbp-roadmap-panel__step-ring"></div>
                        </div>
                        <div class="gbp-roadmap-panel__step-body">
                            @if(filled($stage['duration'] ?? null))
                            <span class="gbp-roadmap-panel__step-duration">{{ $stage['duration'] }}</span>
                            @endif
                            @if(filled($stage['title'] ?? null))
                            <h4 class="gbp-roadmap-panel__step-title">{{ $stage['title'] }}</h4>
                            @endif
                        </div>
                        <div class="gbp-roadmap-panel__step-connector">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($panelStats->isNotEmpty())
                <div class="gbp-roadmap-panel__footer">
                    @foreach($panelStats as $stat)
                    <div class="gbp-roadmap-panel__stat">
                        @if(filled($stat['number'] ?? null))
                        <span class="gbp-roadmap-panel__stat-num">{{ $stat['number'] }}</span>
                        @endif
                        @if(filled($stat['label'] ?? null))
                        <span class="gbp-roadmap-panel__stat-label">{{ $stat['label'] }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </aside>
        </div>
        @endif

        @if(html_filled($overview->quote ?? null))
        <div class="gbp-overview__quote body-text fade-up gbp-richtext">{!! rich_html($overview->quote ?? null) !!}</div>
        @endif
    </div>
</section>
@endif
