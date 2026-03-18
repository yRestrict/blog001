<div class="header">

    {{-- ───────────────────────────────────────────────
         ESQUERDA: Hamburguer + Busca
    ─────────────────────────────────────────────── --}}
    <div class="header-left">

        <div class="menu-icon bi bi-list db-focus-ring" title="Toggle Menu"></div>

        <div class="search-toggle-icon bi bi-search db-focus-ring"
             data-toggle="header_search"
             title="Search"></div>

        {{-- Busca do dashboard --}}
        <div class="header-search">
            <form action="{{ route('admin.search') }}" method="GET" autocomplete="off">
                <div class="form-group mb-0">
                    <i class="dw dw-search2 search-icon"></i>
                    <input type="text"
                           name="q"
                           class="form-control search-input"
                           placeholder="Search Here"
                           value="{{ request('q') }}"
                           minlength="2">
                </div>
            </form>
        </div>

    </div>

    {{-- ───────────────────────────────────────────────
         DIREITA: Configuração · Notificações · Perfil
    ─────────────────────────────────────────────── --}}
    <div class="header-right">

        <div class="dashboard-setting user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow"
                   href="javascript:;"
                   data-toggle="right-sidebar"
                   title="Layout Settings"
                   aria-label="Layout Settings">
                    <i class="dw dw-settings2"></i>
                </a>
            </div>
        </div>

        @livewire('admin.notification-bell')

        @livewire('admin.top-user-info')

        <div class="github-link">
            <a href="https://github.com/dropways/deskapp"
               target="_blank"
               rel="noopener noreferrer"
               title="DeskApp on GitHub"
               aria-label="DeskApp on GitHub">
                <img src="/dashboard/vendors/images/github.svg" alt="GitHub" />
            </a>
        </div>

    </div>

</div>