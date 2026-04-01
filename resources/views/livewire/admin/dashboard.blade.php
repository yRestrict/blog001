<div>

    {{-- ================================================================ --}}
    {{-- PAGE HEADER ACTION                                               --}}
    {{-- ================================================================ --}}
    <div class="page-header-action">
        <div class="page-header-left">
            <h1 class="page-header-title">Dashboard</h1>
            <span class="page-header-sub">Olá, <strong>{{ auth()->user()->name }}</strong>! Aqui está um resumo do seu blog.</span>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- STAT WIDGETS — POSTS                                             --}}
    {{-- ================================================================ --}}
    <div class="widgets-grid">
        <div class="widget-card widget-card-total">
            <div class="widget-info">
                <span class="widget-label">Total de Posts</span>
                <span class="widget-value">{{ $totalPosts }}</span>
            </div>
            <div class="widget-icon widget-icon-total">
                <i class="fa-solid fa-file-lines"></i>
            </div>
        </div>
        <div class="widget-card widget-card-published">
            <div class="widget-info">
                <span class="widget-label">Publicados</span>
                <span class="widget-value">{{ $publishedPosts }}</span>
            </div>
            <div class="widget-icon widget-icon-published">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
        <div class="widget-card widget-card-draft">
            <div class="widget-info">
                <span class="widget-label">Rascunhos</span>
                <span class="widget-value">{{ $draftPosts }}</span>
            </div>
            <div class="widget-icon widget-icon-draft">
                <i class="fa-solid fa-pen-clip"></i>
            </div>
        </div>
        <div class="widget-card widget-card-private">
            <div class="widget-info">
                <span class="widget-label">Privados</span>
                <span class="widget-value">{{ $privatePosts }}</span>
            </div>
            <div class="widget-icon widget-icon-private">
                <i class="fa-solid fa-lock"></i>
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- STAT WIDGETS — OUTROS                                            --}}
    {{-- ================================================================ --}}
    <div class="widgets-grid">
        <div class="widget-card widget-card-tags">
            <div class="widget-info">
                <span class="widget-label">Tags</span>
                <span class="widget-value">{{ $totalTags }}</span>
            </div>
            <div class="widget-icon widget-icon-tags">
                <i class="fa-solid fa-tags"></i>
            </div>
        </div>
        <div class="widget-card widget-card-categories">
            <div class="widget-info">
                <span class="widget-label">Categorias</span>
                <span class="widget-value">{{ $totalCategories }}</span>
            </div>
            <div class="widget-icon widget-icon-categories">
                <i class="fa-solid fa-folder"></i>
            </div>
        </div>
        <div class="widget-card widget-card-likes">
            <div class="widget-info">
                <span class="widget-label">Likes</span>
                <span class="widget-value">{{ $totalLikes }}</span>
            </div>
            <div class="widget-icon widget-icon-likes">
                <i class="fa-solid fa-heart"></i>
            </div>
        </div>
        <div class="widget-card widget-card-comments">
            <div class="widget-info">
                <span class="widget-label">Comentários Pendentes</span>
                <span class="widget-value">{{ $pendingComments }}</span>
            </div>
            <div class="widget-icon widget-icon-comments">
                <i class="fa-solid fa-comments"></i>
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- ATALHOS RÁPIDOS                                                  --}}
    {{-- ================================================================ --}}
    <div class="dash-shortcuts-grid">
        <a href="{{ route('admin.posts.create') }}" class="quick-action" style="background:#6366f1;">
            <div class="dash-shortcut-icon"><i class="fa-solid fa-plus"></i></div>
            <span class="dash-shortcut-label">Novo Post</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="quick-action" style="background:#10b981;">
            <div class="dash-shortcut-icon"><i class="fa-solid fa-folder"></i></div>
            <span class="dash-shortcut-label">Categorias</span>
        </a>
        <a href="{{ route('admin.comments.index') }}" class="quick-action" style="background:#f59e0b;">
            <div class="dash-shortcut-icon"><i class="fa-solid fa-comments"></i></div>
            <span class="dash-shortcut-label">Comentários</span>
        </a>
        <a href="{{ route('admin.settings') }}" class="quick-action" style="background:#6b7280;">
            <div class="dash-shortcut-icon"><i class="fa-solid fa-gear"></i></div>
            <span class="dash-shortcut-label">Configurações</span>
        </a>
    </div>

    {{-- ================================================================ --}}
    {{-- SECTION CARD — ÚLTIMOS POSTS (usando componentes mir-)           --}}
    {{-- ================================================================ --}}
    <x-mir.section>

        <x-mir.section-header icon="newspaper" icon-color="indigo" title="Últimos Posts" subtitle="Posts mais recentes do blog">
            <x-slot:right>
                <a href="{{ route('admin.posts.index') }}" class="mir-btn-neutral">
                    Ver todos <i class="fa-solid fa-arrow-right" style="font-size:.7rem"></i>
                </a>
            </x-slot:right>
        </x-mir.section-header>

        <x-mir.table-header>
            <span class="plh-thumb"></span>
            <span class="plh-body">Post</span>
            <span class="plh-divider"></span>
            <span class="plh-status">Status</span>
            <span class="plh-divider"></span>
            <span style="width:100px;flex-shrink:0;text-align:right;">Data</span>
        </x-mir.table-header>

        <div class="mir-data-list">
            @forelse($latestPosts as $post)
                <x-mir.data-row>

                    <x-mir.thumb :src="$post->thumbnail ? asset('uploads/posts/' . $post->thumbnail) : null" />

                    <div class="mir-body">
                        <div class="mir-name">
                            {{ Str::limit($post->title, 50) }}
                            @if($post->featured)
                                <span class="mir-badge-feat">
                                    <i class="fa-solid fa-star" style="font-size:.55rem"></i> Destaque
                                </span>
                            @endif
                        </div>
                        <div class="mir-info">
                            <span>por {{ $post->author?->name ?? '—' }}</span>
                            @if($post->category)
                                <span class="mir-info-dot"></span>
                                <span>{{ $post->category->name }}</span>
                            @endif
                        </div>
                    </div>

                    <x-mir.divider />

                    <x-mir.status-pill :status="$post->status" />

                    <x-mir.divider />

                    <div style="width:100px;flex-shrink:0;text-align:right;">
                        <span class="dash-post-date">{{ $post->created_at->format('d/m/Y') }}</span>
                    </div>

                </x-mir.data-row>
            @empty
                <x-mir.empty-state icon="newspaper" title="Nenhum post criado" description="Comece criando o primeiro post do seu blog.">
                    <a href="{{ route('admin.posts.create') }}" class="mir-btn-primary-lg">
                        <i class="fa-solid fa-plus" style="font-size:.7rem"></i> Criar Post
                    </a>
                </x-mir.empty-state>
            @endforelse
        </div>

    </x-mir.section>

    {{-- ================================================================ --}}
    {{-- SCOPED STYLES                                                    --}}
    {{-- ================================================================ --}}
    <style>
    .dash-shortcuts-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .dash-shortcuts-grid .quick-action {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 22px;
        text-decoration: none;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }
    .dash-shortcut-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: rgba(255,255,255,.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .dash-shortcut-label {
        font-size: .9rem; font-weight: 700; letter-spacing: .2px;
    }
    .dash-post-date {
        font-size: .78rem; color: #9ca3af; font-weight: 500;
    }
    @media (max-width: 768px) {
        .dash-shortcuts-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .dash-shortcuts-grid { grid-template-columns: 1fr; }
    }
    </style>

</div>
