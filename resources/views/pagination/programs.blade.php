@if (is_object($paginator) && method_exists($paginator, 'hasPages') && $paginator->hasPages())
    <nav class="pl-pagination pl-pagination--desktop" aria-label="Programme pagination">
        <ul class="pagination">
            {{-- Previous --}}
            @if (method_exists($paginator, 'onFirstPage') && $paginator->onFirstPage())
                <li class="disabled"><span>Previous</span></li>
            @elseif (method_exists($paginator, 'previousPageUrl'))
                <li><a href="{{ $paginator->previousPageUrl() }}">Previous</a></li>
            @endif

            {{-- Pages --}}
            @if (method_exists($paginator, 'getUrlRange') && method_exists($paginator, 'lastPage') && method_exists($paginator, 'currentPage'))
                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><a href="{{ $url }}">{{ $page }}</a></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif

            {{-- Next --}}
            @if (method_exists($paginator, 'hasMorePages') && $paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}">Next</a></li>
            @elseif (method_exists($paginator, 'nextPageUrl'))
                <li class="disabled"><span>Next</span></li>
            @endif
        </ul>
    </nav>
@endif
