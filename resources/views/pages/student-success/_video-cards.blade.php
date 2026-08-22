@foreach($videos as $v)
<article class="ep-success-card ep-success-card--video">
    <button type="button" class="ep-success-card__play" data-video-open data-video-embed="{{ $v['embed'] }}" aria-label="Play video{{ filled($v['name']) ? ' by '.$v['name'] : '' }}">
        <div class="ep-success-card__media">
            @if($v['thumb'])
                <img src="{{ $v['thumb'] }}" alt="{{ $v['name'] !== '' ? $v['name'] : 'Video story' }}" loading="lazy" width="400" height="280" @if($v['thumb_fallback']) data-retry="{{ $v['thumb_fallback'] }}" onerror="if(this.dataset.retry){this.src=this.dataset.retry;delete this.dataset.retry;}" @endif>
            @else
                <div class="ep-success-card__fallback" aria-hidden="true"></div>
            @endif
        </div>
        <span class="ep-success-card__play-btn" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
        </span>
    </button>
    @if(filled($v['name']) || filled($v['role']))
    <div class="ep-success-card__body">
        <p class="ep-success-card__attr ep-success-card__attr--left">@if(filled($v['name'])){{ $v['name'] }}@endif@if(filled($v['name']) && filled($v['role'])), @endif{{ $v['role'] }}</p>
    </div>
    @endif
</article>
@endforeach
