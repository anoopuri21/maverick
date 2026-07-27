@props(['headings'])

@if(count($headings) > 0)
<nav class="blog-toc" aria-label="Table of contents">
    <div class="blog-toc__header">
        <h3 class="blog-toc__title">Table of Contents</h3>
        <button class="blog-toc__toggle-btn" aria-expanded="false" aria-controls="blog-toc-list">
            <span class="blog-toc__toggle-text">Show</span>
            <svg class="blog-toc__toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <path d="M19 9l-7 7-7-7" />
            </svg>
        </button>
    </div>
    <ul id="blog-toc-list" class="blog-toc__list">
        @foreach($headings as $heading)
            <li class="blog-toc__item blog-toc__item--level-{{ $heading->level }}">
                <a href="#{{ $heading->anchor }}" class="blog-toc__link">
                    {{ $heading->text }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
@endif
