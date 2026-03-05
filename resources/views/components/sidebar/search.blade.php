<div class="widget-search">
    <form action="{{ route('frontend.search') }}" method="GET">
        <input type="search"
               name="q"
               placeholder="Buscar..."
               value="{{ request('q') }}"
               autocomplete="off">
        <button type="submit" class="btn-submit" aria-label="Buscar">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>