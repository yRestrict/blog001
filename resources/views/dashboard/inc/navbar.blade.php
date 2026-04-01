<div class="header">

    {{-- ───────────────────────────────────────────────
         ESQUERDA: Hamburguer + Busca
    ─────────────────────────────────────────────── --}}
    <div class="header-left">

        <div class="menu-icon bi bi-list db-focus-ring" title="Toggle Menu"></div>

        @livewire('admin.global-search')

    </div>

    {{-- ───────────────────────────────────────────────
         DIREITA: Configuração · Notificações · Perfil
    ─────────────────────────────────────────────── --}}
    <div class="header-right">


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