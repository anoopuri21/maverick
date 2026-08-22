@foreach($stories as $s)
<article class="ep-success-card">
    <div class="ep-success-card__media">
        @if(filled($s['photo']))
            <img src="{{ $s['photo'] }}" alt="{{ $s['name'] !== '' ? $s['name'] : 'Student story' }}" loading="lazy" width="400" height="280">
        @else
            <div class="ep-success-card__fallback" aria-hidden="true">{{ $s['initials'] }}</div>
        @endif
        <span class="ep-success-card__fade" aria-hidden="true"></span>
    </div>
    <div class="ep-success-card__body">
        <svg class="ep-success-card__quote-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M7.17 6C4.87 6 3 7.87 3 10.17c0 1.8 1.13 3.33 2.7 3.9-.22 1.66-1.2 3.13-2.7 4.1.9.55 1.96.83 3.05.83 3.31 0 6-2.69 6-6V6H7.17zm11 0C15.87 6 14 7.87 14 10.17c0 1.8 1.13 3.33 2.7 3.9-.22 1.66-1.2 3.13-2.7 4.1.9.55 1.96.83 3.05.83 3.31 0 6-2.69 6-6V6h-4.88z"/>
        </svg>
        @if(filled($s['quote']))
        <p class="ep-success-card__quote">{!! rich_html($s['quote'] ?? null) !!}</p>
        @endif
        @if(filled($s['name']) || filled($s['role']))
        <p class="ep-success-card__attr">— {{ $s['name'] }}@if(filled($s['name']) && filled($s['role'])), @endif{{ $s['role'] }}</p>
        @endif
    </div>
</article>
@endforeach
