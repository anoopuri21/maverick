@props(['insight', 'heading' => 'h2'])

<a href="{{ $insight->permalink() }}" class="fv-card">
    @if($insight->featuredImageUrl())
        <div class="fv-card__media">
            <img src="{{ $insight->featuredImageUrl() }}" alt="{{ $insight->title }}" loading="lazy" width="800" height="540">
        </div>
    @else
        <div class="fv-card__media fv-card__media--empty" aria-hidden="true"></div>
    @endif
    <div class="fv-card__body">
        @if($insight->badge)
            <span class="fv-card__badge">{{ $insight->badge }}</span>
        @endif
        <{{ $heading }} class="fv-card__title">{{ $insight->title }}</{{ $heading }}>
        @if($insight->faculty_name)
            <span class="fv-card__faculty">
                {{ $insight->faculty_name }}
                @if($insight->faculty_role)
                    <span class="fv-card__faculty-role">{{ $insight->faculty_role }}</span>
                @endif
            </span>
        @endif
        @if($insight->excerpt)
            <p class="fv-card__excerpt">{{ $insight->excerpt }}</p>
        @endif
        <span class="fv-card__link">Read More <span aria-hidden="true">→</span></span>
    </div>
</a>
