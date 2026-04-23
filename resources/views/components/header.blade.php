<header class="navbar navbar-expand-lg shadow fixed-top">
    <div class="container-fluid">

        {{-- Logo --}}
        <div class="logo">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="/">
                @if ($siteSetting && $siteSetting->site_logo_light)
                    @if ($siteSetting->site_logo_mobile)
                        <img src="{{ asset('uploads/logo/' . $siteSetting->site_logo_mobile) }}"
                            alt="{{ $siteSetting->site_title ?? config('app.name') }}"
                            class="logo-mobile d-lg-none" />
                    @endif
                    <img src="{{ asset('uploads/logo/' . $siteSetting->site_logo_light) }}"
                        alt="{{ $siteSetting->site_title ?? config('app.name') }}"
                        class="logo-dark {{ $siteSetting->site_logo_mobile ? 'd-none d-lg-block' : '' }}" />
                    <img src="{{ asset('uploads/logo/' . $siteSetting->site_logo_dark) }}"
                        alt="{{ $siteSetting->site_title ?? config('app.name') }}"
                        class="logo-white display-none {{ $siteSetting->site_logo_mobile ? 'd-none d-lg-block' : '' }}" />
                @else
                    <span class="fw-bold">{{ config('app.name') }}</span>
                @endif
            </a>
        </div>

        {{-- Ações mobile: search + hamburguer --}}
        <div class="d-flex align-items-center d-lg-none gap-2">
            <button class="btn btn-link p-0 border-0 search-toggle" type="button" title="Search">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                    class="bi bi-search text-muted" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
            </button>
            <button class="mob-drawer-toggle" id="mobDrawerToggle" aria-label="Abrir menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        {{-- Menu Desktop --}}
        <div class="collapse navbar-collapse" id="navbarDeskApp">
            <ul class="navbar-nav mx-auto">
                @foreach ($menu as $item)
                    @include('components.menu._nav-item', ['item' => $item, 'depth' => 0])
                @endforeach
            </ul>
            <div class="d-flex align-items-center ms-lg-3">
                <div class="theme-switch-wrapper">
                    <label class="theme-switch" for="checkbox">
                        <input type="checkbox" id="checkbox" aria-label="Alternar tema claro/escuro" />
                        <div class="slider round">
                            <svg class="icon-light text-warning" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                            </svg>
                            <svg class="icon-dark text-dark" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
                            </svg>
                        </div>
                    </label>
                </div>
                <button class="btn btn-link p-0 border-0 search-toggle ms-3" type="button" title="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-search text-muted" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button>
                @auth
                <a href="{{ route('admin.dashboard') }}" class="btn btn-link p-0 border-0"
                   style="margin-left: 6px;" title="Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-grid-fill text-muted" viewBox="0 0 16 16">
                        <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3A1.5 1.5 0 0 1 15 10.5v3A1.5 1.5 0 0 1 13.5 15h-3A1.5 1.5 0 0 1 9 13.5v-3z"/>
                    </svg>
                </a>
                @endauth
            </div>
        </div>

    </div>
</header>

{{-- ── Drawer Mobile ─────────────────────────────────────────────────────── --}}
<div class="mob-drawer-overlay" id="mobDrawerOverlay"></div>
<nav class="mob-drawer" id="mobDrawer">

    <div class="mob-drawer-header">
        <a href="/" class="mob-drawer-logo">
            @if ($siteSetting && $siteSetting->site_logo_light)
                <img src="{{ asset('uploads/logo/' . ($siteSetting->site_logo_mobile ?? $siteSetting->site_logo_light)) }}"
                     alt="{{ $siteSetting->site_title ?? config('app.name') }}"
                     class="mob-drawer-logo-img logo-dark" />
                <img src="{{ asset('uploads/logo/' . $siteSetting->site_logo_dark) }}"
                     alt="{{ $siteSetting->site_title ?? config('app.name') }}"
                     class="mob-drawer-logo-img logo-white" style="display:none;" />
            @else
                <span class="fw-bold">{{ config('app.name') }}</span>
            @endif
        </a>
        <button class="mob-drawer-close" id="mobDrawerClose" aria-label="Fechar menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
            </svg>
        </button>
    </div>

    <ul class="mob-drawer-menu">
        @foreach ($menu as $item)
            <li class="mob-drawer-item {{ $item->activeChildren->isNotEmpty() ? 'has-children' : '' }}">
                <div class="mob-drawer-link-wrap">
                    <a href="{{ $item->url ?: '#' }}" target="{{ $item->target }}" class="mob-drawer-link">
                        {{ $item->title }}
                    </a>
                    @if ($item->activeChildren->isNotEmpty())
                        <button class="mob-drawer-arrow" aria-label="Expandir">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                            </svg>
                        </button>
                    @endif
                </div>
                @if ($item->activeChildren->isNotEmpty())
                    <ul class="mob-drawer-submenu">
                        @foreach ($item->activeChildren as $child)
                            <li>
                                <a href="{{ $child->url ?: '#' }}" target="{{ $child->target }}" class="mob-drawer-sublink">
                                    {{ $child->title }}
                                </a>
                                @if ($child->activeChildren->isNotEmpty())
                                    <ul class="mob-drawer-submenu mob-drawer-submenu-2">
                                        @foreach ($child->activeChildren as $grandchild)
                                            <li>
                                                <a href="{{ $grandchild->url ?: '#' }}" target="{{ $grandchild->target }}" class="mob-drawer-sublink">
                                                    {{ $grandchild->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>

    @auth
    <div class="mob-drawer-footer">
        <a href="{{ route('admin.dashboard') }}" class="mob-drawer-admin">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3A1.5 1.5 0 0 1 15 10.5v3A1.5 1.5 0 0 1 13.5 15h-3A1.5 1.5 0 0 1 9 13.5v-3z"/>
            </svg>
            Dashboard
        </a>
    </div>
    @endauth

</nav>

{{-- Search Overlay --}}
<div class="search-overlay d-none" id="searchOverlay">
    <div class="search-modal">
        <div class="search-header">
            <h6 class="mb-0">Buscar</h6>
            <button type="button" class="search-close">✕</button>
        </div>
        <div class="search-body">
            <form action="{{ route('frontend.search') }}" method="GET" class="search-input">
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="O que você está procurando?" required>
                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button>
            </form>
            @if($popularTags->isNotEmpty())
                <div class="search-suggestions mt-3">
                    <small>Pesquisas populares:</small>
                    <div class="search-tags mt-2">
                        @foreach($popularTags as $tag)
                            <a href="{{ route('frontend.search') }}?q={{ urlencode($tag->name) }}">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div style="height: 70px;"></div>

{{-- ── CSS do Drawer ─────────────────────────────────────────────────────── --}}
<style>
.mob-drawer-toggle {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
    width: 32px;
    height: 32px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
}
.mob-drawer-toggle span {
    display: block;
    width: 22px;
    height: 2px;
    background: #191B1D;
    border-radius: 2px;
    transition: all 0.3s ease;
}
.dark .mob-drawer-toggle span { background: #ffffff; }
.mob-drawer-toggle.active span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.mob-drawer-toggle.active span:nth-child(2) { opacity: 0; }
.mob-drawer-toggle.active span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

.mob-drawer-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 1080;
    backdrop-filter: blur(2px);
}
.mob-drawer-overlay.active { display: block; }

.mob-drawer {
    position: fixed;
    top: 0;
    left: 0;
    width: 280px;
    height: 100%;
    background: #ffffff;
    z-index: 1090;
    transform: translateX(-100%);
    transition: transform 0.3s cubic-bezier(.4,0,.2,1);
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}
.mob-drawer.active { transform: translateX(0); }
.dark .mob-drawer { background: #101213; }

.mob-drawer-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #e9ecef;
    flex-shrink: 0;
}
.dark .mob-drawer-header { border-color: #2a2d30; }
.mob-drawer-logo-img { max-height: 36px; }
.mob-drawer-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #6b7280;
    padding: 4px;
    border-radius: 6px;
    transition: background .15s;
}
.mob-drawer-close:hover { background: #f3f4f6; }
.dark .mob-drawer-close { color: #9ca3af; }
.dark .mob-drawer-close:hover { background: #1e2130; }

.mob-drawer-menu {
    list-style: none;
    margin: 0;
    padding: 12px 0;
    flex: 1;
}
.mob-drawer-item { border-bottom: 1px solid #f3f4f6; }
.dark .mob-drawer-item { border-color: #1e2130; }
.mob-drawer-link-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.mob-drawer-link {
    display: block;
    flex: 1;
    padding: 13px 20px;
    color: #191B1D;
    font-weight: 500;
    font-size: 15px;
    text-decoration: none;
    transition: color .2s;
}
.mob-drawer-link:hover { color: #6366f1; }
.dark .mob-drawer-link { color: #e5e7eb; }
.dark .mob-drawer-link:hover { color: #818cf8; }

.mob-drawer-arrow {
    background: none;
    border: none;
    cursor: pointer;
    padding: 13px 20px;
    color: #9ca3af;
    transition: transform .25s, color .2s;
    flex-shrink: 0;
}
.mob-drawer-item.open > .mob-drawer-link-wrap .mob-drawer-arrow {
    transform: rotate(180deg);
    color: #6366f1;
}

.mob-drawer-submenu {
    list-style: none;
    margin: 0;
    padding: 0;
    max-height: 0;
    overflow: hidden;
    transition: max-height .3s ease;
    background: #f9fafb;
}
.dark .mob-drawer-submenu { background: #0d0f10; }
.mob-drawer-item.open > .mob-drawer-submenu { max-height: 500px; }
.mob-drawer-sublink {
    display: block;
    padding: 10px 20px 10px 36px;
    color: #4b5563;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: color .2s, padding .2s;
}
.mob-drawer-sublink:hover { color: #6366f1; padding-left: 42px; }
.dark .mob-drawer-sublink { color: #9ca3af; }
.dark .mob-drawer-sublink:hover { color: #818cf8; }
.mob-drawer-submenu-2 .mob-drawer-sublink { padding-left: 52px; }

.mob-drawer-footer {
    padding: 16px 20px;
    border-top: 1px solid #e9ecef;
    flex-shrink: 0;
}
.dark .mob-drawer-footer { border-color: #2a2d30; }
.mob-drawer-admin {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6366f1;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
}
.mob-drawer-admin:hover { color: #4f46e5; }

@media (min-width: 992px) {
    .mob-drawer-toggle { display: none; }
    .mob-drawer, .mob-drawer-overlay { display: none !important; }
}
</style>

{{-- ── JS do Drawer ──────────────────────────────────────────────────────── --}}
<script>
(function () {
    const toggle   = document.getElementById('mobDrawerToggle');
    const drawer   = document.getElementById('mobDrawer');
    const overlay  = document.getElementById('mobDrawerOverlay');
    const closeBtn = document.getElementById('mobDrawerClose');

    function openDrawer() {
        drawer.classList.add('active');
        overlay.classList.add('active');
        toggle.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        drawer.classList.remove('active');
        overlay.classList.remove('active');
        toggle.classList.remove('active');
        document.body.style.overflow = '';
    }

    toggle.addEventListener('click', function () {
        drawer.classList.contains('active') ? closeDrawer() : openDrawer();
    });

    closeBtn.addEventListener('click', closeDrawer);
    overlay.addEventListener('click', closeDrawer);

    // Submenus
    document.querySelectorAll('.mob-drawer-arrow').forEach(function (btn) {
        btn.addEventListener('click', function () {
            this.closest('.mob-drawer-item').classList.toggle('open');
        });
    });

    // Logo do drawer segue o dark mode
    const darkLogoDrawer  = drawer.querySelector('.logo-white');
    const lightLogoDrawer = drawer.querySelector('.logo-dark');
    function updateDrawerLogo() {
        const isDark = document.body.classList.contains('dark');
        if (darkLogoDrawer && lightLogoDrawer) {
            darkLogoDrawer.style.display  = isDark ? 'block' : 'none';
            lightLogoDrawer.style.display = isDark ? 'none'  : 'block';
        }
    }
    new MutationObserver(updateDrawerLogo).observe(document.body, { attributes: true, attributeFilter: ['class'] });
    updateDrawerLogo();
})();
</script>