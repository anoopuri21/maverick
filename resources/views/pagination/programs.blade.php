@php
    // Defensive: ensure $paginator is a real LengthAwarePaginator with methods
    $isPaginator = is_object($paginator)
        && method_exists($paginator, 'hasPages')
        && method_exists($paginator, 'currentPage')
        && method_exists($paginator, 'lastPage')
        && method_exists($paginator, 'onFirstPage')
        && method_exists($paginator, 'hasMorePages')
        && method_exists($paginator, 'previousPageUrl')
        && method_exists($paginator, 'nextPageUrl')
        && method_exists($paginator, 'url');

    if (!$isPaginator || !$paginator->hasPages()) {
        @php return; @endphp
    }

    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();

    // Build pages array with ellipsis — show max 4 page buttons at a time
    $pages = [];

    if ($lastPage <= 4) {
        // Show all pages when there are 4 or fewer
        for ($i = 1; $i <= $lastPage; $i++) {
            $pages[] = ['type' => 'page', 'value' => $i];
        }
    } elseif ($currentPage <= 2) {
        // Near start: 1 2 3 4 ... last
        for ($i = 1; $i <= 4; $i++) {
            $pages[] = ['type' => 'page', 'value' => $i];
        }
        $pages[] = ['type' => 'ellipsis'];
        $pages[] = ['type' => 'page', 'value' => $lastPage];
    } elseif ($currentPage >= $lastPage - 1) {
        // Near end: 1 ... last-3 last-2 last-1 last
        $pages[] = ['type' => 'page', 'value' => 1];
        $pages[] = ['type' => 'ellipsis'];
        for ($i = $lastPage - 3; $i <= $lastPage; $i++) {
            $pages[] = ['type' => 'page', 'value' => $i];
        }
    } else {
        // Middle: 1 ... current-1 current current+1 ... last
        $pages[] = ['type' => 'page', 'value' => 1];
        $pages[] = ['type' => 'ellipsis'];
        $pages[] = ['type' => 'page', 'value' => $currentPage - 1];
        $pages[] = ['type' => 'page', 'value' => $currentPage];
        $pages[] = ['type' => 'page', 'value' => $currentPage + 1];
        $pages[] = ['type' => 'ellipsis'];
        $pages[] = ['type' => 'page', 'value' => $lastPage];
    }
@endphp

<nav class="pl-pagination pl-pagination--desktop" aria-label="Programme pagination">
    <ul class="pagination">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>Previous</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}">Previous</a></li>
        @endif

        {{-- Pages with ellipsis --}}
        @foreach ($pages as $item)
            @if ($item['type'] === 'ellipsis')
                <li class="disabled"><span>...</span></li>
            @else
                @if ($item['value'] == $currentPage)
                    <li class="active"><a href="{{ $paginator->url($item['value']) }}">{{ $item['value'] }}</a></li>
                @else
                    <li><a href="{{ $paginator->url($item['value']) }}">{{ $item['value'] }}</a></li>
                @endif
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}">Next</a></li>
        @else
            <li class="disabled"><span>Next</span></li>
        @endif

    </ul>
</nav>
