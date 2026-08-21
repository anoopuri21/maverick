@php
    $eligibility = collect($admission->eligibility ?? [])->filter(fn ($i) => filled($i));
    $entry = collect($admission->entry_requirements ?? [])->filter(fn ($i) => filled($i));
    $showAdmission = filled($admission->label ?? null)
        || filled($admission->heading ?? null)
        || filled($admission->heading_italic ?? null)
        || $eligibility->isNotEmpty()
        || $entry->isNotEmpty()
        || html_filled($admission->note ?? null);
@endphp
@if($showAdmission)
<section class="gbp-admission section-wrapper section--light" aria-label="Admission Requirements" data-testid="gbp-admission">
    <div class="container">
        <div class="gbp-admission__header">
            @if(filled($admission->label))
            <span class="section-label"><span>{{ $admission->label }}</span></span>
            @endif
            @if(filled($admission->heading) || filled($admission->heading_italic))
            <h2 class="gbp-admission__heading section-title">
                @if(filled($admission->heading))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $admission->heading }}</span></span>
                @endif
                @if(filled($admission->heading_italic))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $admission->heading_italic }}</em></span></span>
                @endif
            </h2>
            @endif
        </div>

        @if($eligibility->isNotEmpty() || $entry->isNotEmpty() || html_filled($admission->note ?? null))
        <div class="gbp-admission__grid">
            @if($eligibility->isNotEmpty())
            <div class="gbp-admission__card fade-up">
                @if(filled($admission->eligibility_title))
                <h3 class="gbp-admission__card-title card-title">{{ $admission->eligibility_title }}</h3>
                @endif
                <ul class="gbp-admission__list">
                    @foreach($eligibility as $item)
                    <li>
                        <span aria-hidden="true"><x-gbp.icon name="check" :size="18" /></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if($entry->isNotEmpty() || html_filled($admission->note ?? null))
            <div class="gbp-admission__card fade-up">
                @if(filled($admission->entry_title))
                <h3 class="gbp-admission__card-title card-title">{{ $admission->entry_title }}</h3>
                @endif
                @if($entry->isNotEmpty())
                <ul class="gbp-admission__list">
                    @foreach($entry as $item)
                    <li>
                        <span aria-hidden="true"><x-gbp.icon name="check" :size="18" /></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                @endif
                @if(html_filled($admission->note ?? null))
                <div class="gbp-admission__note fade-up gbp-richtext">{!! $admission->note !!}</div>
                @endif
            </div>
            @endif
        </div>
        @endif
    </div>
</section>
@endif
