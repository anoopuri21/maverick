@if ($paginator->hasPages())
    <nav class="pl-pagination pl-pagination--desktop" aria-label="Programme pagination">
        <ul class="pagination">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span>{{ $paginator->getPreviousPageUrl() ? '' : 'Previous' }}</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}">Previous</a></li>
            @endif

            {{-- Pages --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="active"><a href="{{ $url }}">{{ $page }}</a></li>
                @else
                    <li><a href="{{ $url }}">{{ $page }}</a></li>
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
@endif
