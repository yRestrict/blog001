<div class="left-side-bar">
    <div class="brand-logo">
        <a href="index.html">
            <img src="/dashboard/vendors/images/deskapp-logo.svg" alt="" class="dark-logo" />
            <img src="/dashboard/vendors/images/deskapp-logo-white.svg" alt="" class="light-logo" />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <span class="micon fa fa-home"></span><span class="mtext">Home</span>
                    </a>
                </li>

                {{-- Media --}}
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow">
                        <span class="micon fa fa-file"></span><span class="mtext">Media</span>
                    </a>
                </li>

                {{-- Posts --}}
                <li class="dropdown {{ Route::is('admin.posts.index') || Route::is('admin.posts.create')? 'active' : '' }}">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon fa fa-newspaper-o"></span>
                        <span class="mtext">Posts</span>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="{{ route('admin.posts.index') }}"
                            class="{{ Route::is('admin.posts.index') ? 'active' : '' }}">
                                Posts
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.posts.create') }}"
                            class="{{ Route::is('admin.posts.create') ? 'active' : '' }}">
                                Create Posts
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Comentarios --}}
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon fa fa-comments"></span><span class="mtext">Comentaries</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="">New</a></li>
                        <li><a href="">Comentarios</a></li>
                        <li><a href="">Excluded</a></li>
                    </ul>
                </li>

                {{-- Categorias --}}
                <li class="dropdown {{ Route::is('admin.categories.*') ? 'active' : '' }}">
                    <a href="javascript:;" class="dropdown-toggle ">
                        <span class="micon fa fa-th-list"></span><span class="mtext">Categories</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('admin.categories.index') }}">New</a></li>
                        <li><a href="{{ route('admin.categories.trash') }}">Lixeira</a></li>
                    </ul>
                </li>

                {{-- Tags --}}
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle {{ Route::is('admin.tags.*') ? 'active' : '' }}">
                        <span class="micon fa fa-tags"></span><span class="mtext">Tags</span>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ route('admin.tags.index') }}"
                            class="{{ Route::is('admin.tags.index') ? 'active' : '' }}">
                                Tags
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="invoice.html" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-receipt-cutoff"></span><span class="mtext">Invoice</span>
                    </a>
                </li>

                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <div class="sidebar-small-cap">Setting</div>
                </li>
                 <li>
                    <a href="{{ route('admin.profile') }}" class="dropdown-toggle no-arrow {{ Route::is('admin.profile') }}">
                        <span class="micon fa fa-user-circle"></span>
                        <span class="mtext">Profile
                    </a>
                </li>
                @if(auth()->user()->isOwner())
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="dropdown-toggle no-arrow {{ Route::is('admin.users.index') }}">
                            <span class="micon fa fa-users"></span>
                            <span class="mtext">Users</span>
                        </a>
                    </li>

                    <li>
                        <a href="" class="dropdown-toggle no-arrow">
                            <span class="micon fa fa-columns"></span>
                            <span class="mtext">Sidebars</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon fa fa-cogs"></span>
                            <span class="mtext">General</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('admin.settings') }}">Setting</a></li>
                            <li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle" data-option="off">
                                    <span class="micon fa fa-plug"></span>
                                    <span class="mtext">Menus</span>
                                </a>
                                <ul class="submenu child">
                                    <li><a href="{{ route('admin.header') }}">Header</a></li>
                                    <li><a href="javascript:;">Footer</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>