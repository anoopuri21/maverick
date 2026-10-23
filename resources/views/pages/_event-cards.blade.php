@forelse($events as $e)
<div class="ep-card">
    <a href="{{ route('events.show', $e->slug ?: $e->id) }}" class="ep-card__link" aria-label="{{ $e->title }}">
        <div class="ep-card__media">
            @if($e->display_image_url)
                <img src="{{ $e->display_image_url }}" alt="{{ $e->title }}" loading="lazy">
            @else
                <div class="ep-card__fallback">
                    <span data-lucide="calendar"></span>
                </div>
            @endif
            <div class="ep-card__badge-date">
                <span class="d">{{ $e->event_date ? $e->event_date->format('d') : '00' }}</span>
                <span class="m">{{ $e->event_date ? $e->event_date->format('M') : '---' }}</span>
            </div>
            @if($e->is_past)
                <span class="ep-card__badge-status ep-card__badge-status--past">Past Event</span>
            @else
                <span class="ep-card__badge-status ep-card__badge-status--upcoming">Upcoming</span>
            @endif
        </div>
        <div class="ep-card__content">
            <div class="ep-card__header">
                @if(filled($e->event_time))
                    <span class="ep-card__tag"><i data-lucide="clock" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;margin-right:3px;"></i> {{ $e->event_time }}</span>
                @endif
                @if(filled($e->location))
                    <span class="ep-card__location"><i data-lucide="map-pin"></i> {{ $e->location }}</span>
                @endif
            </div>
            <h3 class="ep-card__title">{{ $e->title }}</h3>
            @if(filled($e->description))
                <div class="ep-card__desc">{!! Str::limit(strip_tags($e->description), 130) !!}</div>
            @endif
            <div class="ep-card__footer">
                <span class="ep-card__btn">View Event Details <i data-lucide="arrow-right"></i></span>
            </div>
        </div>
    </a>
</div>
@empty
@endforelse
