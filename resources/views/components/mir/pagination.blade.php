{{--
    Paginação 3 zonas — seletor por página, botões, contagem
    @props: paginator (LengthAwarePaginator)
--}}
@props(['paginator'])

<div class="mir-pagination">
    {{-- Esquerda: por página --}}
    <div class="mir-pagination-left">
        <span class="mir-pagination-left-label">Por página</span>
        <select wire:model.live="perPage" class="mir-per-page">
            <option value="6">6</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </select>
    </div>

    {{-- Centro: botões de página --}}
    <div class="mir-pagination-center">
        @if($paginator->lastPage() > 1)
            <button wire:click="previousPage" class="mir-page-btn {{ $paginator->onFirstPage() ? 'disabled' : '' }}" {{ $paginator->onFirstPage() ? 'disabled' : '' }}>
                <i class="fa-solid fa-chevron-left" style="font-size:.65rem"></i>
            </button>
            @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                <button wire:click="gotoPage({{ $page }})" class="mir-page-btn {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                    {{ $page }}
                </button>
            @endforeach
            <button wire:click="nextPage" class="mir-page-btn {{ !$paginator->hasMorePages() ? 'disabled' : '' }}" {{ !$paginator->hasMorePages() ? 'disabled' : '' }}>
                <i class="fa-solid fa-chevron-right" style="font-size:.65rem"></i>
            </button>
        @else
            <button class="mir-page-btn active">1</button>
        @endif
    </div>

    {{-- Direita: contagem --}}
    <div class="mir-pagination-right">
        @if($paginator->total() > 0)
            Mostrando {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} de {{ $paginator->total() }}
        @else
            0 resultados
        @endif
    </div>
</div>
