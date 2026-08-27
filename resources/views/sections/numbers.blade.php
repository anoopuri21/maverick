@php
    $numberStats = collect([
        ['index' => '01', 'value' => $numbers->stat1_value ?? null, 'label' => $numbers->stat1_label ?? null],
        ['index' => '02', 'value' => $numbers->stat2_value ?? null, 'label' => $numbers->stat2_label ?? null],
        ['index' => '03', 'value' => $numbers->stat3_value ?? null, 'label' => $numbers->stat3_label ?? null],
        ['index' => '04', 'value' => $numbers->stat4_value ?? null, 'label' => $numbers->stat4_label ?? null],
        ['index' => '05', 'value' => $numbers->stat5_value ?? null, 'label' => $numbers->stat5_label ?? null],
        ['index' => '06', 'value' => $numbers->stat6_value ?? null, 'label' => $numbers->stat6_label ?? null],
    ])->filter(fn ($stat) => filled($stat['value']) || filled($stat['label']));
    $numbersLink = edu_href($numbers->context_link_url ?? null);
@endphp
<section id="numbers" class="numbers section--light section-wrapper">
    <div class="container">
        <div class="numbers__header">
            <div class="numbers__heading-col">
                <div class="section-label"><span>{{ $numbers->label ?? '' }}</span></div>
                @if(filled($numbers->heading_line1 ?? null) || filled($numbers->heading_line2 ?? null) || filled($numbers->heading_line3 ?? null))
                <h2 class="numbers__heading section-title">
                    @if(filled($numbers->heading_line1 ?? null))
                    <span class="numbers__heading-line">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner">{{ $numbers->heading_line1 }}</span>
                        </span>
                    </span>
                    @endif
                    @if(filled($numbers->heading_line2 ?? null))
                    <span class="numbers__heading-line">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner">{{ $numbers->heading_line2 }}</span>
                        </span>
                    </span>
                    @endif
                    @if(filled($numbers->heading_line3 ?? null))
                    <span class="numbers__heading-line hwdi__heading-line--red">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner">{{ $numbers->heading_line3 }}</span>
                        </span>
                    </span>
                    @endif
                </h2>
                @endif
            </div>
            <div class="numbers__header-divider" aria-hidden="true"></div>
            @if(filled($numbers->context_text ?? null) || ($numbersLink && filled($numbers->context_link_text ?? null)))
            <div class="numbers__context fade-up">
                @if(filled($numbers->context_text ?? null))
                <p class="numbers__context-text body-text">{{ $numbers->context_text }}</p>
                @endif
                @if($numbersLink && filled($numbers->context_link_text ?? null))
                <a href="{{ $numbersLink }}" class="numbers__context-link">
                    {{ $numbers->context_link_text }}
                    <span class="numbers__context-arrow">→</span>
                </a>
                @endif
            </div>
            @endif
        </div>

        <div class="numbers__section-divider">
            <div class="numbers__section-divider-line"></div>
        </div>

        @if($numberStats->isNotEmpty())
        <div class="numbers__grid">
            @foreach($numberStats as $stat)
            @php $numericValue = is_numeric($stat['value']) ? $stat['value'] : null; @endphp
            <div class="numbers__card fade-up"
                @if($numericValue !== null) data-counter-target="{{ $numericValue }}" data-counter-suffix="+" @endif
                data-counter-label="{{ $stat['label'] }}">
                <div class="numbers__card-inner">
                    <span class="numbers__index">{{ $stat['index'] }}</span>
                    <div class="numbers__value-wrapper">
                        <span class="numbers__value" @if($numericValue !== null) data-counter @endif>{{ $numericValue !== null ? '0' : ($stat['value'] ?? '') }}</span>
                        @if($numericValue !== null)
                        <span class="numbers__suffix">+</span>
                        @endif
                    </div>
                    @if(filled($stat['label']))
                    <span class="numbers__label">{{ $stat['label'] }}</span>
                    @endif
                    <div class="numbers__card-line"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
