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

        {{-- Sino de notificações --}}
        <div class="user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow"
                   href="#"
                   role="button"
                   data-toggle="dropdown"
                   aria-label="Notifications"
                   title="Notifications">
                    <i class="icon-copy dw dw-notification"></i>
                    <span class="badge notification-active"></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <div class="notification-list mx-h-350 customscroll">
                        <ul>
                            <li>
                                <a href="#">
                                    <img src="/dashboard/vendors/images/img.jpg" alt="John Doe" />
                                    <h3>John Doe</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="/dashboard/vendors/images/photo1.jpg" alt="Lea R. Frith" />
                                    <h3>Lea R. Frith</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="/dashboard/vendors/images/photo2.jpg" alt="Erik L. Richards" />
                                    <h3>Erik L. Richards</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="/dashboard/vendors/images/photo3.jpg" alt="John Doe" />
                                    <h3>John Doe</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="/dashboard/vendors/images/photo4.jpg" alt="Renee I. Hansen" />
                                    <h3>Renee I. Hansen</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="/dashboard/vendors/images/img.jpg" alt="Vicki M. Coleman" />
                                    <h3>Vicki M. Coleman</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

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