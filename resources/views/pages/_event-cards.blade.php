@forelse($events as $e)
<div class="ep-card">
    <a href="{{ route('events.show', $e->slug ?: $e->id) }}" class="ep-card__link" aria-label="{{ $e->title }}">
        <div class="ep-card__media">
            @if(!empty($e->display_image_url))
                <img src="{{ $e->display_image_url }}" alt="{{ $e->title }}" loading="lazy">
            @else
                <div class="ep-card__fallback">
                    <span data-lucide="calendar"></span>
                </div>
            @endif
            @if(!empty($e->event_date))
                <div class="ep-card__badge-date">
                    <span class="d">{{ $e->event_date->format('d') }}</span>
                    <span class="m">{{ $e->event_date->format('M') }}</span>
                </div>
            @endif
            @if($e->is_past)
                <span class="ep-card__badge-status ep-card__badge-status--past">Past Event</span>
            @else
                <span class="ep-card__badge-status ep-card__badge-status--upcoming">Upcoming</span>
            @endif
        </div>
        <div class="ep-card__content">
            <div class="ep-card__meta">
                @if(!empty($e->event_type))
                    <div class="ep-card__meta-row">
                        <span class="ep-card__tag" style="background: rgba(185,28,28,0.1); color: var(--color-brand-red, #B91C1C); border-color: rgba(185,28,28,0.2);">{{ $e->event_type }}</span>
                    </div>
                @endif
                @if(!empty($e->formatted_time))
                    <div class="ep-card__meta-row ep-card__time">
                        <i data-lucide="clock"></i> <span>{{ $e->formatted_time }}</span>
                    </div>
                @endif
                @if(!empty($e->location))
                    <div class="ep-card__meta-row ep-card__location">
                        <i data-lucide="map-pin"></i> <span>{{ $e->location }}</span>
                    </div>
                @endif
            </div>
            <h3 class="ep-card__title">{{ $e->title }}</h3>
            @if(!empty($e->description))
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
