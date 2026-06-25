@if($paginator->hasPages())
    <nav aria-label="Page navigation">
        <div class="flex justify-center">
            <div class="join">
                {{-- Previous Page Link --}}
                @if($paginator->onFirstPage())
                    <span class="join-item btn btn-disabled">&laquo;</span>
                @else
                    <a class="join-item btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if(is_string($element))
                        <span class="join-item btn btn-disabled">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if(is_array($element))
                        @foreach($element as $page => $url)
                            @if($page == $paginator->currentPage())
                                <span class="join-item btn btn-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="join-item btn" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if($paginator->hasMorePages())
                    <a class="join-item btn" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
                @else
                    <span class="join-item btn btn-disabled">&raquo;</span>
                @endif
            </div>
        </div>
        
        <div class="text-center text-base-content/60 text-sm mt-2">
            Menampilkan {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </div>
    </nav>
@endif
