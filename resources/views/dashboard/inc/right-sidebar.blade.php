<div class="left-side-bar">

    {{-- ── LOGO ─────────────────────────────────────────── --}}
    <div class="brand-logo">
        <a href="{{ route('admin.dashboard') }}" title="Dashboard">
            <img src="/dashboard/vendors/images/deskapp-logo.svg" alt="" class="dark-logo" />
            <img src="/dashboard/vendors/images/deskapp-logo-white.svg" alt="" class="light-logo" />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>

    {{-- ── MENU ─────────────────────────────────────────── --}}
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">

                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="dropdown-toggle no-arrow {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <span class="micon fa fa-home"></span>
                        <span class="mtext">Home</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.media') }}"
                       class="dropdown-toggle no-arrow {{ Route::is('admin.media') ? 'active' : '' }}">
                        <span class="micon fa fa-file"></span>
                        <span class="mtext">Media</span>
                    </a>
                </li>

                <li class="dropdown {{ Route::is('admin.posts.*') ? 'active' : '' }}">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon fa fa-newspaper-o"></span>
                        <span class="mtext">Posts</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('admin.posts.create') }}" class="{{ Route::is('admin.posts.create') ? 'active' : '' }}"><i class="fa fa-plus sub-icon"></i> Novo Post</a></li>
                        <li><a href="{{ route('admin.posts.index') }}" class="{{ Route::is('admin.posts.index') ? 'active' : '' }}"><i class="fa fa-list sub-icon"></i> Todos os Posts</a></li>
                        @if(auth()->user()->isOwner())
                            <li><a href="{{ route('admin.posts.trash') }}" class="{{ Route::is('admin.posts.trash') ? 'active' : '' }}"><i class="fa fa-trash sub-icon"></i> Lixeira</a></li>
                            <li><a href="{{ route('admin.posts.pending') }}" class="{{ Route::is('admin.posts.pending') ? 'active' : '' }}"><i class="fa fa-trash sub-icon"></i> Pendente</a></li>
                        @endif
                    </ul>
                </li>



                <li>
                    <a href="{{ route('admin.comments.index') }}"
                    class="dropdown-toggle no-arrow {{ Route::is('admin.comments.index') ? 'active' : '' }}">
                        <span class="micon fa fa-tags"></span>
                        <span class="mtext">Comentarios</span>
                    </a>
                </li>

                <li class="dropdown {{ Route::is('admin.categories.*') ? 'active' : '' }}">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon fa fa-th-list"></span>
                        <span class="mtext">Categorias</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('admin.categories.index') }}" class="{{ Route::is('admin.categories.index') ? 'active' : '' }}"><i class="fa fa-folder sub-icon"></i> Categorias</a></li>
                        @if(auth()->user()->isOwner())
                        <li><a href="{{ route('admin.categories.trash') }}" class="{{ Route::is('admin.categories.trash') ? 'active' : '' }}"><i class="fa fa-trash sub-icon"></i> Lixeira</a></li>@endif
                    </ul>
                </li>

                 <li>
                    <a href="{{ route('admin.tags.index') }}"
                       class="dropdown-toggle no-arrow {{ Route::is('admin.tags.index') ? 'active' : '' }}">
                        <span class="micon fa fa-tags"></span>
                        <span class="mtext">Tag</span>
                    </a>
                </li>

                <li><div class="dropdown-divider"></div></li>
                <li><div class="sidebar-small-cap">Config</div></li>

                <li>
                    <a href="{{ route('admin.profile') }}"
                       class="dropdown-toggle no-arrow {{ Route::is('admin.profile') ? 'active' : '' }}">
                        <span class="micon fa fa-user-circle"></span>
                        <span class="mtext">Perfil</span>
                    </a>
                </li>

                @if(auth()->user()->isOwner())
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                           class="dropdown-toggle no-arrow {{ Route::is('admin.users.*') ? 'active' : '' }}">
                            <span class="micon fa fa-users"></span>
                            <span class="mtext">Usuários</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.sidebars') }}"
                           class="dropdown-toggle no-arrow {{ Route::is('admin.sidebars') ? 'active' : '' }}">
                            <span class="micon fa fa-columns"></span>
                            <span class="mtext">Sidebars</span>
                        </a>
                    </li>

                    <li class="dropdown {{ Route::is('admin.settings') || Route::is('admin.header') || Route::is('admin.footer') ? 'active' : '' }}">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon fa fa-cogs"></span>
                            <span class="mtext">Geral</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('admin.settings') }}" class="{{ Route::is('admin.settings') ? 'active' : '' }}"><i class="fa fa-sliders sub-icon"></i> Configurações</a></li>
                            <li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle"><i class="fa fa-bars sub-icon"></i> Menus</a>
                                <ul class="submenu child">
                                    <li><a href="{{ route('admin.header') }}" class="{{ Route::is('admin.header') ? 'active' : '' }}"><i class="fa fa-arrow-up sub-icon"></i> Header</a></li>
                                    <li><a href="{{ route('admin.footer') }}" class="{{ Route::is('admin.footer') ? 'active' : '' }}"><i class="fa fa-arrow-down sub-icon"></i> Footer</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>

{{-- ── JS DA SIDEBAR (inline, roda após o DOM) ───────────── --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Aguarda o script.min.js rodar e depois desabilita o vmenuModule
    setTimeout(function () { initSidebarAccordion(); }, 50);
});

function initSidebarAccordion() {
    var menu = document.getElementById('accordion-menu');
    if (!menu) return;

    // Remover todos os handlers jQuery do vmenuModule nos links do accordion
    if (window.jQuery) {
        jQuery('#accordion-menu').find('a').off('click');
    }

    // Encontrar todos os <li> que contêm <ul> submenu direto
    var parents = menu.querySelectorAll('li:has(> ul)');

    // Fallback para browsers que não suportam :has() no querySelectorAll
    if (!parents.length) {
        parents = [];
        menu.querySelectorAll('li').forEach(function (li) {
            if (li.querySelector(':scope > ul')) parents.push(li);
        });
    }

    function getSubmenu(li) {
        return li.querySelector(':scope > ul');
    }

    function collapse(li) {
        var ul = getSubmenu(li);
        if (!ul || !li.classList.contains('show')) return;

        // Fechar filhos primeiro
        li.querySelectorAll('.show').forEach(function (child) {
            var childUl = getSubmenu(child);
            if (childUl) {
                childUl.style.height = '0';
                child.classList.remove('show');
            }
        });

        // Travar no height atual, depois colapsar
        ul.style.height = ul.scrollHeight + 'px';
        ul.offsetHeight; // force reflow
        ul.style.height = '0';
        li.classList.remove('show');
    }

    function expand(li) {
        var ul = getSubmenu(li);
        if (!ul) return;

        li.classList.add('show');
        ul.style.height = ul.scrollHeight + 'px';

        function done(e) {
            if (e.target !== ul) return;
            ul.removeEventListener('transitionend', done);
            if (li.classList.contains('show')) {
                ul.style.height = 'auto';
            }
        }
        ul.addEventListener('transitionend', done);
    }

    // Bind clicks
    parents.forEach(function (li) {
        var link = li.querySelector(':scope > a');
        if (!link) return;

        link.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var isOpen = li.classList.contains('show');

            // Fechar siblings
            var sibs = li.parentElement.querySelectorAll(':scope > li.show');
            sibs.forEach(function (sib) {
                if (sib !== li) collapse(sib);
            });

            // Toggle
            if (isOpen) {
                collapse(li);
            } else {
                expand(li);
            }
        });
    });

    // Abrir itens ativos no load (sem animação)
    menu.querySelectorAll('a.active').forEach(function (a) {
        var li = a.closest('li');
        while (li && li !== menu) {
            var ul = getSubmenu(li);
            if (ul) {
                li.classList.add('show');
                ul.style.height = 'auto';
            }
            li = li.parentElement ? li.parentElement.closest('li') : null;
        }
    });

    // Resetar qualquer style inline que o vmenuModule tenha setado
    menu.querySelectorAll('.submenu').forEach(function (ul) {
        var li = ul.parentElement;
        if (li.classList.contains('show')) {
            ul.style.display = '';
            ul.style.height = 'auto';
        } else {
            ul.style.display = '';
            ul.style.height = '0';
        }
    });
}
</script>
