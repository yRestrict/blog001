<header class="navbar navbar-expand-lg shadow fixed-top">
    <div class="container-fluid">

        {{-- Logo --}}
        <div class="logo">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="/">
                @if ($siteSetting && $siteSetting->site_logo_light)
                    <img src="{{ asset('uploads/logo/' . $siteSetting->site_logo_light) }}"
                         alt="{{ $siteSetting->site_title ?? config('app.name') }}"
                         class="logo-dark"/>
                    <img src="{{ asset('uploads/logo/' . $siteSetting->site_logo_dark) }}"
                         alt="{{ $siteSetting->site_title ?? config('app.name') }}"
                         class="logo-white display-none"/>
                @else
                    <span class="fw-bold">{{ config('app.name') }}</span>
                @endif
            </a>
        </div>

        {{-- Toggle Mobile --}}
        <button class="navbar-toggler" type="button"
                data-toggle="collapse"
                data-target="#navbarDeskApp"
                aria-controls="navbarDeskApp"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menu Principal --}}
        <div class="collapse navbar-collapse" id="navbarDeskApp">
            <ul class="navbar-nav mx-auto">
                @foreach ($menu as $item)
                    @include('components.menu._nav-item', ['item' => $item, 'depth' => 0])
                @endforeach
            </ul>

            {{-- Ações: Dark Mode + Search --}}
            <div class="d-flex align-items-center ms-lg-3">

                {{-- Dark/Light Mode Toggle --}}
                <div class="theme-switch-wrapper">
                    <label class="theme-switch" for="checkbox">
                        <input type="checkbox" id="checkbox" aria-label="Alternar tema claro/escuro"/>
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

                {{-- Search --}}
                <button class="btn btn-link p-0 border-0 search-toggle ms-3" type="button" title="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search text-muted" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button>
            </div>
        </div>

    </div>
</header>

{{-- Search Overlay --}}
<div class="search-overlay d-none" id="searchOverlay">
    <div class="search-modal">
        <div class="search-header">
            <h6 class="mb-0">Search DeskApp</h6>
            <button type="button" class="search-close">✕</button>
        </div>
        <div class="search-body">
            <div class="search-input">
                <input type="text" placeholder="O que você está procurando?">
                <button type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button>
            </div>
            <div class="search-suggestions mt-3">
                <small>Popular searches:</small>
                <div class="search-tags">
                    <a href="#">Dashboard</a>
                    <a href="#">Tasks</a>
                    <a href="#">Calendar</a>
                    <a href="#">Reports</a>
                    <a href="#">Settings</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="height: 70px;"></div>

