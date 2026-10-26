@foreach($stories as $s)
<article class="ep-success-card ep-success-card--story">
    <div class="ep-success-card__media">
        @if(filled($s['photo']))
            <img src="{{ $s['photo'] }}" alt="{{ $s['name'] !== '' ? $s['name'] : 'Student story' }}" loading="lazy" width="800" height="640">
        @else
            <div class="ep-success-card__fallback" aria-hidden="true">{{ $s['initials'] }}</div>
        @endif
        <span class="ep-success-card__fade" aria-hidden="true"></span>
        <span class="ep-success-card__badge">Student Success</span>
    </div>

    <div class="ep-success-card__body">
        <div class="ep-success-card__heading">
            <span class="ep-success-card__eyebrow">Graduate story</span>
            <h3 class="ep-success-card__name">{{ filled($s['name']) ? $s['name'] : 'Student' }}</h3>
        </div>

        <dl class="ep-success-card__details">
            <div class="ep-success-card__detail">
                <dt>University</dt>
                <dd>{{ filled($s['university'] ?? null) ? $s['university'] : 'Not specified' }}</dd>
            </div>
            <div class="ep-success-card__detail ep-success-card__detail--course">
                <dt>Course</dt>
                <dd>{{ filled($s['course'] ?? null) ? $s['course'] : 'Not specified' }}</dd>
            </div>
            <div class="ep-success-card__detail ep-success-card__detail--nationality">
                <dt>Nationality</dt>
                <dd>{{ filled($s['nationality'] ?? null) ? $s['nationality'] : 'Not specified' }}</dd>
            </div>
        </dl>

        @if(filled($s['quote']))
            <div class="ep-success-card__quote">{!! rich_html($s['quote'] ?? null) !!}</div>
        @endif

        <div class="ep-success-card__footer">
            <button
                type="button"
                class="ep-success-card__view"
                data-story-open
                aria-haspopup="dialog"
                aria-controls="studentStoryModal"
                data-story-name="{{ $s['name'] }}"
                data-story-course="{{ $s['course'] ?? '' }}"
                data-story-university="{{ $s['university'] ?? '' }}"
                data-story-nationality="{{ $s['nationality'] ?? '' }}"
                data-story-photo="{{ $s['photo'] ?? '' }}"
                data-story-quote="{{ trim(html_entity_decode(strip_tags($s['quote'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8')) }}"
                aria-label="View full story for {{ $s['name'] }}"
            >
                <span>View</span>
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
</article>
@endforeach
