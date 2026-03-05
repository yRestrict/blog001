@if ($paginator->hasPages())
    <div class="pagination-list">
        <ul class="list-inline">
            {{-- Botão Anterior --}}
            @if ($paginator->onFirstPage())
                <li><span><i class="fas fa-chevron-left"></i></span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
            @endif

            {{-- Elementos da Paginação --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span>{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="active">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Botão Próximo --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
            @else
                <li><span><i class="fas fa-chevron-right"></i></span></li>
            @endif
        </ul>
    </div>
@endif