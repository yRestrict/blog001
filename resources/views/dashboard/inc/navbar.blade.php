<div class="header">

    {{-- ───────────────────────────────────────────────
         ESQUERDA: Hamburguer + Busca
    ─────────────────────────────────────────────── --}}
    <div class="header-left">

        {{-- Botão de abrir/fechar a sidebar --}}
        <div class="menu-icon bi bi-list db-focus-ring" title="Toggle Menu"></div>

        {{-- Botão de busca (visível apenas em mobile) --}}
        <div class="search-toggle-icon bi bi-search db-focus-ring"
             data-toggle="header_search"
             title="Search"></div>

        {{-- Campo de busca avançada --}}
        <div class="header-search">
            <form>
                <div class="form-group mb-0">
                    <i class="dw dw-search2 search-icon"></i>
                    <input type="text"
                           class="form-control search-input"
                           placeholder="Search Here" />
                    <div class="dropdown">
                        <a class="dropdown-toggle no-arrow db-icon-btn"
                           href="#"
                           role="button"
                           data-toggle="dropdown"
                           title="Advanced Search">
                            <i class="ion-arrow-down-c"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">From</label>
                                <div class="col-sm-12 col-md-10">
                                    <input class="form-control form-control-sm form-control-line" type="text" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">To</label>
                                <div class="col-sm-12 col-md-10">
                                    <input class="form-control form-control-sm form-control-line" type="text" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">Subject</label>
                                <div class="col-sm-12 col-md-10">
                                    <input class="form-control form-control-sm form-control-line" type="text" />
                                </div>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>

    {{-- ───────────────────────────────────────────────
         DIREITA: Configuração · Notificações · Perfil
    ─────────────────────────────────────────────── --}}
    <div class="header-right">

        {{-- Botão de configurações (abre right-sidebar de layout) --}}
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

        {{-- Dropdown de perfil do usuário (Livewire) --}}
        @livewire('admin.top-user-info')

        {{-- Link do GitHub --}}
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