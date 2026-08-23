@php
    $showNotice = filled($notice->label ?? null) || html_filled($notice->body ?? null);
@endphp
@if($showNotice)
<section class="mp-notice section-wrapper section--light" aria-label="Important Academic Notice" data-testid="mp-notice">
    <div class="container">
        <div class="mp-notice__panel">
            @if(filled($notice->label))
            <div class="mp-notice__label">{{ $notice->label }}</div>
            @endif
            @if(html_filled($notice->body ?? null))
            <div class="mp-notice__content mp-richtext">{!! rich_html($notice->body ?? null) !!}</div>
            @endif
        </div>
    </div>
</section>
@endif
