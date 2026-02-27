<div class="left-side-bar">

    {{-- ───────────────────────────────────────────────
         LOGO / BRAND
    ─────────────────────────────────────────────── --}}
    <div class="brand-logo">
        <a href="{{ route('admin.dashboard') }}" title="Dashboard">
            <img src="/dashboard/vendors/images/deskapp-logo.svg"
                 alt="DeskApp"
                 class="dark-logo" />
            <img src="/dashboard/vendors/images/deskapp-logo-white.svg"
                 alt="DeskApp"
                 class="light-logo" />
        </a>
        <div class="close-sidebar db-focus-ring" data-toggle="left-sidebar-close" title="Close Menu">
            <i class="ion-close-round"></i>
        </div>
    </div>

    {{-- ───────────────────────────────────────────────
         MENU DE NAVEGAÇÃO
    ─────────────────────────────────────────────── --}}
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">

                {{-- ── Home ──────────────────── --}}
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="dropdown-toggle no-arrow db-focus-ring {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <span class="micon fa fa-home"></span>
                        <span class="mtext">Home</span>
                    </a>
                </li>

                {{-- ── Media ─────────────────── --}}
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="dropdown-toggle no-arrow db-focus-ring">
                        <span class="micon fa fa-file"></span>
                        <span class="mtext">Media</span>
                    </a>
                </li>

                {{-- ── Posts ─────────────────── --}}
                <li class="dropdown {{ Route::is('admin.posts.index') || Route::is('admin.posts.create') ? 'active' : '' }}">
                    <a href="javascript:;" class="dropdown-toggle db-focus-ring">
                        <span class="micon fa fa-newspaper-o"></span>
                        <span class="mtext">Posts</span>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ route('admin.posts.index') }}"
                               class="db-focus-ring {{ Route::is('admin.posts.index') ? 'active' : '' }}">
                                Posts
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.posts.create') }}"
                               class="db-focus-ring {{ Route::is('admin.posts.create') ? 'active' : '' }}">
                                Create Post
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ── Comments ──────────────── --}}
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle db-focus-ring">
                        <span class="micon fa fa-comments"></span>
                        <span class="mtext">Comments</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="" class="db-focus-ring">New</a></li>
                        <li><a href="" class="db-focus-ring">All Comments</a></li>
                        <li><a href="" class="db-focus-ring">Trash</a></li>
                    </ul>
                </li>

                {{-- ── Categories ────────────── --}}
                <li class="dropdown {{ Route::is('admin.categories.*') ? 'active' : '' }}">
                    <a href="javascript:;" class="dropdown-toggle db-focus-ring">
                        <span class="micon fa fa-th-list"></span>
                        <span class="mtext">Categories</span>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ route('admin.categories.index') }}" class="db-focus-ring">
                                All Categories
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.categories.trash') }}" class="db-focus-ring">
                                Trash
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ── Tags ──────────────────── --}}
                <li class="dropdown {{ Route::is('admin.tags.*') ? 'active' : '' }}">
                    <a href="javascript:;" class="dropdown-toggle db-focus-ring">
                        <span class="micon fa fa-tags"></span>
                        <span class="mtext">Tags</span>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ route('admin.tags.index') }}"
                               class="db-focus-ring {{ Route::is('admin.tags.index') ? 'active' : '' }}">
                                All Tags
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ── Invoice ───────────────── --}}
                <li>
                    <a href="invoice.html" class="dropdown-toggle no-arrow db-focus-ring">
                        <span class="micon bi bi-receipt-cutoff"></span>
                        <span class="mtext">Invoice</span>
                    </a>
                </li>

                {{-- ── Divider ───────────────── --}}
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <div class="sidebar-small-cap">Settings</div>
                </li>

                {{-- ── Profile ───────────────── --}}
                <li>
                    <a href="{{ route('admin.profile') }}"
                       class="dropdown-toggle no-arrow db-focus-ring {{ Route::is('admin.profile') ? 'active' : '' }}">
                        <span class="micon fa fa-user-circle"></span>
                        <span class="mtext">Profile</span>
                    </a>
                </li>

                @if(auth()->user()->isOwner())

                    {{-- ── Users ─────────────── --}}
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                           class="dropdown-toggle no-arrow db-focus-ring {{ Route::is('admin.users.index') ? 'active' : '' }}">
                            <span class="micon fa fa-users"></span>
                            <span class="mtext">Users</span>
                        </a>
                    </li>

                    {{-- ── Sidebars ──────────── --}}
                    <li>
                        <a href="" class="dropdown-toggle no-arrow db-focus-ring">
                            <span class="micon fa fa-columns"></span>
                            <span class="mtext">Sidebars</span>
                        </a>
                    </li>

                    {{-- ── General (Settings) ── --}}
                    <li>
                        <a href="javascript:;" class="dropdown-toggle db-focus-ring">
                            <span class="micon fa fa-cogs"></span>
                            <span class="mtext">General</span>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.settings') }}" class="db-focus-ring">
                                    Settings
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="javascript:;"
                                   class="dropdown-toggle db-focus-ring"
                                   data-option="off">
                                    <span class="micon fa fa-plug"></span>
                                    <span class="mtext">Menus</span>
                                </a>
                                <ul class="submenu child">
                                    <li>
                                        <a href="{{ route('admin.header') }}" class="db-focus-ring">
                                            Header
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="db-focus-ring">
                                            Footer
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                @endif

            </ul>
        </div>
    </div>

</div>