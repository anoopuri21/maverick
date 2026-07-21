<section id="numbers" class="numbers section--light section-wrapper">
    <div class="container">
        <div class="numbers__header">
            <div class="numbers__heading-col">
                <div class="section-label"><span>Maverick</span></div>
                <h2 class="numbers__heading section-title">
                    <span class="numbers__heading-line">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner">{{ $numbers->heading_line1 }}</span>
                        </span>
                    </span>
                    <span class="numbers__heading-line">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner">{{ $numbers->heading_line2 }}</span>
                        </span>
                    </span>
                    <span class="numbers__heading-line hwdi__heading-line--red">
                        <span class="text-reveal-wrapper">
                            <span class="text-reveal-inner">{{ $numbers->heading_line3 }}</span>
                        </span>
                    </span>
                </h2>
            </div>
            <div class="numbers__header-divider" aria-hidden="true"></div>
            <div class="numbers__context fade-up">
                <p class="numbers__context-text body-text">{{ $numbers->context_text }}</p>
                <a href="{{ $numbers->context_link_url }}" class="numbers__context-link">
                    {{ $numbers->context_link_text }}
                    <span class="numbers__context-arrow">→</span>
                </a>
            </div>
        </div>

        <div class="numbers__section-divider">
            <div class="numbers__section-divider-line"></div>
        </div>

        <div class="numbers__grid">
            @foreach([
                ['index' => '01', 'value' => $numbers->stat1_value, 'label' => $numbers->stat1_label],
                ['index' => '02', 'value' => $numbers->stat2_value, 'label' => $numbers->stat2_label],
                ['index' => '03', 'value' => $numbers->stat3_value, 'label' => $numbers->stat3_label],
                ['index' => '04', 'value' => $numbers->stat4_value, 'label' => $numbers->stat4_label],
                ['index' => '05', 'value' => $numbers->stat5_value, 'label' => $numbers->stat5_label],
                ['index' => '06', 'value' => $numbers->stat6_value, 'label' => $numbers->stat6_label],
            ] as $stat)
            <div class="numbers__card fade-up"
                data-counter-target="{{ $stat['value'] }}"
                data-counter-suffix="+"
                data-counter-label="{{ $stat['label'] }}">
                <div class="numbers__card-inner">
                    <span class="numbers__index">{{ $stat['index'] }}</span>
                    <div class="numbers__value-wrapper">
                        <span class="numbers__value" data-counter>0</span>
                        <span class="numbers__suffix">+</span>
                    </div>
                    <span class="numbers__label">{{ $stat['label'] }}</span>
                    <div class="numbers__card-line"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>