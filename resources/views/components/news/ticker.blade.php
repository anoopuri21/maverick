@props(['items'])

@if($items->count() > 0)
    <div class="news-ticker-container" aria-label="Latest headlines ticker">
        <div class="news-ticker__label">LATEST</div>
        <div class="news-ticker__wrapper">
            <div class="news-ticker__track">
                <!-- Double items for seamless marquee scrolling loop -->
                @foreach($items->concat($items) as $item)
                    <a href="{{ route('insights.show', $item->slug) }}" class="news-ticker__item">
                        {{ $item->title }}
                    </a>
                    <span class="news-ticker__divider" aria-hidden="true">•</span>
                @endforeach
            </div>
        </div>
    </div>
@endif
