{{--
    VIEW: components/frontend/sidebar-area.blade.php

    View principal do componente <x-frontend.sidebar-area slug="..." />.

    Recebe $sidebar do SidebarArea.php (Component).
    Se a sidebar não existir ou estiver inativa, não renderiza nada.

    Para cada widget ativo, inclui o partial correspondente ao tipo.
    O método resolveData() do model busca os dados necessários.
--}}

@if($sidebar)
    <aside class="sidebar" id="sidebar-{{ $sidebar->slug }}">

        {{-- Itera sobre os widgets ativos (já ordenados por position) --}}
        @foreach($sidebar->activeWidgets as $widget)
            <div class="widget widget-{{ $widget->type }}" id="widget-{{ $widget->id }}">

                {{-- Título do widget (se preenchido) --}}
                @if($widget->title)
                    <h3 class="widget-title">{{ $widget->title }}</h3>
                @endif

                {{--
                    Inclui o partial correspondente ao tipo do widget.
                    Se o tipo for 'categories', inclui:
                        components/frontend/widgets/categories.blade.php

                    $data recebe os dados resolvidos pelo model (resolveData())
                    A variável $widget também fica disponível no partial.
                --}}
                @php
                    $data = $widget->resolveData();
                @endphp

                @includeIf(
                    "components.frontend.widgets.{$widget->type}",
                    ['widget' => $widget, 'data' => $data]
                )

            </div>
        @endforeach

    </aside>
@endif


{{-- ============================================================
     WIDGETS PARTIALS
     Cada arquivo abaixo é um partial para um tipo de widget.
     Em produção, crie um arquivo separado para cada um.
     Aqui estão todos juntos como referência.
     ============================================================ --}}

{{-- ============================================================
     FILE: components/frontend/widgets/search.blade.php
     ============================================================ --}}

{{--
    WIDGET: Busca
    Não recebe dados do banco, apenas exibe o formulário.
    A action aponta para a rota de busca do seu blog.
--}}
{{-- <form action="{{ route('search') }}" method="GET" class="widget-search-form">
    <div class="input-group">
        <input
            type="text"
            name="q"
            class="form-control"
            placeholder="Pesquisar..."
            value="{{ request('q') }}"
        >
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search"></i>
        </button>
    </div>
</form> --}}


{{-- ============================================================
     FILE: components/frontend/widgets/categories.blade.php
     ============================================================ --}}

{{--
    WIDGET: Categorias
    $data vem de SidebarWidget::resolveCategoriesData()
    Contém o array de categorias com posts_count.
--}}
{{-- @if(!empty($data))
    <ul class="widget-categories-list list-unstyled">
        @foreach($data as $category)
            <li class="d-flex justify-content-between align-items-center py-1">
                <a href="{{ route('categories.show', $category['slug']) }}">
                    {{ $category['title'] }}
                </a>
                <span class="badge bg-secondary rounded-pill">
                    {{ $category['posts_count'] }}
                </span>
            </li>
        @endforeach
    </ul>
@else
    <p class="text-muted small">Nenhuma categoria encontrada.</p>
@endif --}}


{{-- ============================================================
     FILE: components/frontend/widgets/tags.blade.php
     ============================================================ --}}

{{--
    WIDGET: Tags
    $data vem de SidebarWidget::resolveTagsData()
--}}
{{-- <div class="widget-tags-cloud">
    @foreach($data as $tag)
        <a
            href="{{ route('tags.show', $tag['slug']) }}"
            class="badge bg-light text-dark border me-1 mb-1 text-decoration-none"
        >
            {{ $tag['name'] }}
            <span class="text-muted">({{ $tag['posts_count'] }})</span>
        </a>
    @endforeach
</div> --}}


{{-- ============================================================
     FILE: components/frontend/widgets/popular_posts.blade.php
     ============================================================ --}}

{{--
    WIDGET: Posts Populares
    $data vem de SidebarWidget::resolvePopularPostsData()
--}}
{{-- <ol class="widget-popular-posts list-unstyled">
    @foreach($data as $post)
        <li class="d-flex gap-2 mb-3">
            @if($post['thumbnail'])
                <img
                    src="{{ Storage::url($post['thumbnail']) }}"
                    alt="{{ $post['title'] }}"
                    width="60" height="60"
                    class="object-fit-cover rounded flex-shrink-0"
                >
            @endif
            <div>
                <a href="{{ route('posts.show', $post['slug']) }}" class="fw-semibold text-decoration-none">
                    {{ $post['title'] }}
                </a>
                <small class="d-block text-muted">{{ number_format($post['views_count']) }} visualizações</small>
            </div>
        </li>
    @endforeach
</ol> --}}


{{-- ============================================================
     FILE: components/frontend/widgets/popular_downloads.blade.php
     ============================================================ --}}

{{--
    WIDGET: Downloads Populares
    $data vem de SidebarWidget::resolvePopularDownloadsData()
--}}
{{-- <ol class="widget-popular-downloads list-unstyled">
    @foreach($data as $post)
        <li class="mb-2">
            <a href="{{ route('posts.show', $post['slug']) }}" class="text-decoration-none">
                <i class="bi bi-download me-1 text-primary"></i>
                {{ $post['title'] }}
            </a>
            <small class="d-block text-muted">{{ $post['downloads_count'] }} downloads</small>
        </li>
    @endforeach
</ol> --}}


{{-- ============================================================
     FILE: components/frontend/widgets/social_links.blade.php
     ============================================================ --}}

{{--
    WIDGET: Redes Sociais
    $data vem de SidebarWidget::resolveSocialLinksData()
    É um array de {name, icon, color, url}
--}}
{{-- <div class="widget-social-links d-flex flex-wrap gap-2">
    @foreach($data as $link)
        <a
            href="{{ $link['url'] }}"
            target="_blank"
            rel="noopener noreferrer"
            class="btn btn-sm rounded-circle"
            style="background-color: {{ $link['color'] }}; color: white; width: 40px; height: 40px;"
            title="{{ $link['name'] }}"
        >
            <i class="{{ $link['icon'] }}"></i>
        </a>
    @endforeach
</div> --}}


{{-- ============================================================
     FILE: components/frontend/widgets/image_link.blade.php
     ============================================================ --}}

{{--
    WIDGET: Imagem com Link (modo único ou slide)
    $data vem de SidebarWidget::resolveImageLinkData()
--}}
{{-- @if($data['mode'] === 'single')

    {{-- Modo imagem única --}}
{{--     @if($data['image'])
        <a href="{{ $data['link'] ?? '#' }}" target="_blank" rel="noopener">
            <img
                src="{{ Storage::url($data['image']) }}"
                alt="{{ $widget->title }}"
                class="img-fluid rounded"
            >
        </a>
    @endif

@else

    {{-- Modo slideshow --}}
{{--     @if(!empty($data['slides']))
        <div
            id="carousel-widget-{{ $widget->id }}"
            class="carousel slide"
            data-bs-ride="carousel"
            data-bs-interval="{{ $data['slide_interval'] }}"
        >
            <div class="carousel-inner">
                @foreach($data['slides'] as $index => $slide)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <a href="{{ $slide['link'] }}" target="_blank" rel="noopener">
                            <img
                                src="{{ Storage::url($slide['image']) }}"
                                alt="Slide {{ $index + 1 }}"
                                class="d-block w-100 rounded"
                            >
                        </a>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-widget-{{ $widget->id }}" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel-widget-{{ $widget->id }}" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    @endif

@endif --}}


{{-- ============================================================
     FILE: components/frontend/widgets/custom.blade.php
     ============================================================ --}}

{{--
    WIDGET: HTML Customizado
    $data['content'] contém o HTML inserido pelo admin.
    {!! !!} renderiza o HTML sem escape — cuidado com XSS.
    Garanta que apenas admins confiáveis possam editar este widget.
--}}
{{-- @if(!empty($data['content']))
    <div class="widget-custom-content">
        {!! $data['content'] !!}
    </div>
@endif --}}