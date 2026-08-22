@php
    $docGroups = collect($documents->groups ?? [])->filter(fn ($g) => filled($g['title'] ?? null) || collect($g['items'] ?? [])->filter()->isNotEmpty());
    $showDocs = filled($documents->label ?? null)
        || filled($documents->heading ?? null)
        || filled($documents->heading_italic ?? null)
        || $docGroups->isNotEmpty();
@endphp
@if($showDocs)
<section class="gbp-docs section-wrapper" aria-label="Documents Required" data-testid="gbp-docs">
    <div class="container">
        <div class="gbp-docs__header">
            @if(filled($documents->label))
            <span class="section-label"><span>{{ $documents->label }}</span></span>
            @endif
            @if(filled($documents->heading) || filled($documents->heading_italic))
            <h2 class="gbp-docs__heading section-title">
                @if(filled($documents->heading))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $documents->heading }}</span></span>
                @endif
                @if(filled($documents->heading_italic))
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $documents->heading_italic }}</em></span></span>
                @endif
            </h2>
            @endif
        </div>

        @if($docGroups->isNotEmpty())
        <div class="gbp-docs__grid">
            @foreach($docGroups as $group)
            <article class="gbp-doc-card" data-testid="gbp-doc-{{ $loop->iteration }}">
                @if(filled($group['icon_key'] ?? null))
                <div class="gbp-doc-card__icon" aria-hidden="true">
                    <x-gbp.icon :name="$group['icon_key']" :size="24" />
                </div>
                @endif
                @if(filled($group['title'] ?? null))
                <h3 class="gbp-doc-card__title card-title">{{ $group['title'] }}</h3>
                @endif
                @php $items = collect($group['items'] ?? [])->filter(fn ($i) => filled($i)); @endphp
                @if($items->isNotEmpty())
                <ul class="gbp-doc-card__list">
                    @foreach($items as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                @endif
            </article>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
