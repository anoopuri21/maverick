@php
    $steps = collect($process->steps ?? [])->filter(fn ($s) => filled($s['title'] ?? null) || filled($s['desc'] ?? null));
    $showProcess = filled($process->label ?? null)
        || filled($process->heading ?? null)
        || filled($process->heading_highlight ?? null)
        || $steps->isNotEmpty();
@endphp
@if($showProcess)
<section class="mp-process section-wrapper section--light" aria-label="Application Process" data-testid="mp-process">
    <div class="container">
        <div class="mp-process__header">
            @if(filled($process->label))
            <span class="section-label"><span>{{ $process->label }}</span></span>
            @endif
            @if(filled($process->heading) || filled($process->heading_highlight))
            <h2 class="mp-process__heading section-title">
                @if(filled($process->heading))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $process->heading }}</span></span>
                @endif
                @if(filled($process->heading_highlight))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><span class="color-red">{{ $process->heading_highlight }}</span></span></span>
                @endif
            </h2>
            @endif
        </div>

        @if($steps->isNotEmpty())
        <div class="mp-timeline" data-testid="mp-timeline">
            <span class="mp-timeline__progress" aria-hidden="true"></span>
            @foreach($steps as $step)
            @php $num = $step['num'] ?? sprintf('%02d', $loop->iteration); @endphp
            <div class="mp-timeline__item {{ $loop->index % 2 === 0 ? 'is-left' : 'is-right' }}" data-testid="mp-step-{{ $num }}">
                <div class="mp-timeline__marker">
                    <span class="mp-timeline__marker-num">{{ $num }}</span>
                    @if(filled($step['icon_key'] ?? null))
                    <span class="mp-timeline__marker-icon">
                        <x-mp.icon :name="$step['icon_key']" :size="24" />
                    </span>
                    @endif
                </div>
                <div class="mp-timeline__card fade-up">
                    <span class="mp-timeline__step">STEP {{ $num }}</span>
                    @if(filled($step['title'] ?? null))
                    <h3 class="mp-timeline__title">{{ $step['title'] }}</h3>
                    @endif
                    @if(filled($step['desc'] ?? null))
                    <div class="mp-timeline__desc">{!! rich_html($step['desc'] ?? null) !!}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
