<div>

    {{-- ================================================================ --}}
    {{-- PAGE HEADER ACTION                                               --}}
    {{-- ================================================================ --}}
    <div class="page-header-action">
        <div class="page-header-left">
            <h1 class="page-header-title">
                Posts
                <span class="page-header-title-count">{{ $totalPosts }}</span>
            </h1>
            <span class="page-header-sub">Gerencie todos os posts do blog</span>
        </div>
        <div class="page-header-right">
            @if($isOwner)
                <a href="{{ route('admin.posts.pending') }}" class="mir-btn-neutral">
                    <i class="fa-solid fa-clock"></i> Pendentes
                    @if($pendingPosts > 0)
                        <span style="display:inline-flex;align-items:center;justify-content:center;min-width:18px;height:18px;padding:0 5px;border-radius:50px;font-size:.65rem;font-weight:700;background:#fef3c7;color:#92400e;margin-left:2px;">{{ $pendingPosts }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.posts.trash') }}" class="mir-btn-neutral">
                    <i class="fa-solid fa-trash-can"></i> Lixeira
                    @if($trashedPosts > 0)
                        <span style="display:inline-flex;align-items:center;justify-content:center;min-width:18px;height:18px;padding:0 5px;border-radius:50px;font-size:.65rem;font-weight:700;background:#fee2e2;color:#991b1b;margin-left:2px;">{{ $trashedPosts }}</span>
                    @endif
                </a>
            @endif
            <a href="{{ route('admin.posts.create') }}" class="mir-btn-primary-lg">
                <svg width="11" height="11" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Novo Post
            </a>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- STAT WIDGETS                                                     --}}
    {{-- ================================================================ --}}
    <div class="widgets-grid" wire:loading.class="widgets-loading">
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
    {{-- SECTION CARD                                                     --}}
    {{-- ================================================================ --}}
    <div class="post-section">

        {{-- Camada 1 — Section Header --}}
        <div class="post-section-header">
            <div class="post-section-header-left">
                <h3 class="post-section-title">Posts</h3>
                <p class="post-section-sub">Gerencie todos os posts do blog</p>
            </div>
        </div>

        {{-- Camada 2 — Filter Header --}}
        <div class="post-filter-header">
            <div style="position:relative; flex:1; max-width:280px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.75rem;"></i>
                <input type="text" wire:model.live.debounce.300ms="search" class="mir-input" placeholder="Pesquisar posts..." style="padding-left:32px;">
            </div>
            <select wire:model.live="filterStatus" class="mir-input" style="width:160px;">
                <option value="">Todos os status</option>
                <option value="published">Publicado</option>
                <option value="draft">Rascunho</option>
                <option value="private">Privado</option>
            </select>
            <div style="flex:1;"></div>
            @if($isOwner)
                <div class="filter-dropdown-wrap">
                    <button wire:click="toggleFilters" class="filter-dropdown-btn {{ $showFilters ? 'open' : '' }}">
                        <i class="fa-solid fa-sliders" style="font-size:.75rem;"></i>
                        Filtros
                        @if($this->activeFilterCount > 0)
                            <span class="filter-badge">{{ $this->activeFilterCount }}</span>
                        @endif
                    </button>
                    @if($showFilters)
                        <div class="filter-dropdown-panel open">
                            <div class="filter-panel-header">
                                <span class="filter-panel-title">Filtros</span>
                                <button wire:click="clearFilters" class="filter-panel-clear">Limpar tudo</button>
                            </div>
                            <div class="filter-panel-body">
                                <div class="filter-group">
                                    <div class="filter-group-label">Categoria</div>
                                    <div class="filter-chips">
                                        @foreach($categories as $cat)
                                            <span wire:click="setFilterCategory({{ $cat->id }})" class="filter-chip {{ $filterCategory === $cat->id ? 'active' : '' }}">{{ $cat->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="filter-group">
                                    <div class="filter-group-label">Autor</div>
                                    <div class="filter-chips">
                                        @foreach($authors as $author)
                                            <span wire:click="setFilterAuthor({{ $author->id }})" class="filter-chip {{ $filterAuthor === $author->id ? 'active' : '' }}">{{ $author->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="filter-group">
                                    <div class="filter-group-label">Período</div>
                                    <div class="filter-chips">
                                        <span wire:click="setFilterPeriod('today')" class="filter-chip {{ $filterPeriod === 'today' ? 'active' : '' }}">Hoje</span>
                                        <span wire:click="setFilterPeriod('this_week')" class="filter-chip {{ $filterPeriod === 'this_week' ? 'active' : '' }}">Esta semana</span>
                                        <span wire:click="setFilterPeriod('this_month')" class="filter-chip {{ $filterPeriod === 'this_month' ? 'active' : '' }}">Este mês</span>
                                        <span wire:click="setFilterPeriod('this_year')" class="filter-chip {{ $filterPeriod === 'this_year' ? 'active' : '' }}">Este ano</span>
                                    </div>
                                </div>
                                <div class="filter-group" style="margin-bottom:0;">
                                    <div class="filter-group-label">Opções</div>
                                    <div class="filter-chips">
                                        <span wire:click="toggleFilterFeatured" class="filter-chip {{ $filterFeatured ? 'active' : '' }}">
                                            <span class="filter-chip-dot" style="background:#f59e0b;"></span>
                                            Destaques
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-panel-footer">
                                <button wire:click="toggleFilters" class="mir-btn-ghost" style="padding:5px 12px;font-size:.76rem;">Fechar</button>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Loading bar --}}
        <div class="post-loading-track" wire:loading.class="post-loading-active"></div>

        {{-- Table Header --}}
        <div class="mir-table-header">
            <span class="plh-thumb"></span>
            <span class="plh-body">Post</span>
            <span class="plh-meta">Categoria / Tags</span>
            <span class="plh-divider"></span>
            <span class="plh-status">Status</span>
            <span class="plh-divider"></span>
            <span class="plh-actions">Ações</span>
        </div>

        {{-- Data Rows --}}
        <div class="mir-data-list" wire:loading.class="mir-loading-overlay">
            @forelse($posts as $post)
                <div class="mir-data-row" wire:key="post-{{ $post->id }}">

                    {{-- Thumbnail --}}
                    <div class="post-thumb">
                        @if($post->thumbnail)
                            <img src="{{ asset('uploads/posts/' . $post->thumbnail) }}" alt="">
                        @else
                            <i class="fa-solid fa-image"></i>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="post-body">
                        <div class="post-name">
                            <span class="post-name-text">{{ $post->title }}</span>
                            @if($post->featured)
                                <span class="mir-badge-feat">
                                    <i class="fa-solid fa-star" style="font-size:.55rem"></i> Destaque
                                </span>
                            @endif
                            @if($post->pending_review)
                                <span class="mir-badge-pending">
                                    <i class="fa-solid fa-clock" style="font-size:.55rem"></i> Pendente
                                </span>
                            @endif
                        </div>
                        <div class="post-info">
                            <span>por {{ $post->author?->name ?? '—' }}</span>
                            <span class="post-info-dot"></span>
                            <span>{{ $post->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    {{-- Meta: Categoria + Tags --}}
                    <div class="post-meta">
                        @if($post->category)
                            <span class="mir-badge-parent">
                                <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><path d="M1 1h2.5v2.5M1 7h6V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                {{ $post->category->name }}
                            </span>
                        @endif
                        @if($post->tags->count() > 0)
                            <span class="mir-badge-count" data-tooltip="{{ $post->tags->pluck('name')->join(', ') }}">
                                {{ $post->tags->count() }}
                            </span>
                        @endif
                    </div>

                    {{-- Divider --}}
                    <div class="mir-divider"></div>

                    {{-- Status Pill — owner alterna, author só visualiza --}}
                    <div style="width:90px;flex-shrink:0;display:flex;justify-content:center;">
                        @if($isOwner)
                            <button wire:click="toggleStatus({{ $post->id }})" class="mir-status is-{{ $post->status }}" data-tooltip="Clique para alterar status">
                                <span class="mir-status-ring"></span>
                                {{ match($post->status) { 'published' => 'Publicado', 'private' => 'Privado', default => 'Rascunho' } }}
                            </button>
                        @else
                            <span class="mir-status is-{{ $post->status }}">
                                <span class="mir-status-ring"></span>
                                {{ match($post->status) { 'published' => 'Publicado', 'private' => 'Privado', default => 'Rascunho' } }}
                            </span>
                        @endif
                    </div>

                    {{-- Divider --}}
                    <div class="mir-divider"></div>

                    {{-- Actions --}}
                    <div class="mir-actions">
                        <a href="{{ route('frontend.post', $post->slug) }}"
                           class="mir-action-btn mir-action-view"
                           data-tooltip="Visualizar post"
                           target="_blank">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="mir-action-btn mir-action-edit" data-tooltip="Editar post">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <button wire:click="prepareDelete({{ $post->id }})" class="mir-action-btn mir-action-delete" data-tooltip="Mover para lixeira">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="mir-empty-state">
                    <div class="mir-empty-icon">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                    <h3 class="mir-empty-title">Nenhum post encontrado</h3>
                    <p class="mir-empty-desc">
                        @if($search || $filterStatus || $this->activeFilterCount > 0)
                            Tente ajustar os filtros ou termos de busca.
                        @else
                            Comece criando seu primeiro post.
                        @endif
                    </p>
                    @unless($search || $filterStatus || $this->activeFilterCount > 0)
                        <a href="{{ route('admin.posts.create') }}" class="mir-btn-primary-lg">
                            <svg width="11" height="11" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            Criar Post
                        </a>
                    @endunless
                </div>
            @endforelse
        </div>

        {{-- Paginação --}}
        <div class="mir-pagination">
            <div class="mir-pagination-left">
                <span class="mir-pagination-left-label">Por página</span>
                <select wire:model.live="perPage" class="mir-per-page">
                    <option value="6">6</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div class="mir-pagination-center">
                @if($posts->lastPage() > 1)
                    <button wire:click="previousPage" class="mir-page-btn {{ $posts->onFirstPage() ? 'disabled' : '' }}" {{ $posts->onFirstPage() ? 'disabled' : '' }}>
                        <i class="fa-solid fa-chevron-left" style="font-size:.65rem"></i>
                    </button>
                    @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                        <button wire:click="gotoPage({{ $page }})" class="mir-page-btn {{ $page == $posts->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </button>
                    @endforeach
                    <button wire:click="nextPage" class="mir-page-btn {{ !$posts->hasMorePages() ? 'disabled' : '' }}" {{ !$posts->hasMorePages() ? 'disabled' : '' }}>
                        <i class="fa-solid fa-chevron-right" style="font-size:.65rem"></i>
                    </button>
                @else
                    <button class="mir-page-btn active">1</button>
                @endif
            </div>
            <div class="mir-pagination-right">
                @if($posts->total() > 0)
                    Mostrando {{ $posts->firstItem() }}–{{ $posts->lastItem() }} de {{ $posts->total() }}
                @else
                    0 resultados
                @endif
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- MODAL DE EXCLUSÃO                                                --}}
    {{-- ================================================================ --}}
    @if($deletingPostId)
        <div class="mir-modal-overlay" style="display:flex" x-data x-on:keydown.escape.window="$wire.cancelDelete()">
            <div class="mir-modal-dialog" style="max-width:540px">
                <div class="mir-modal-content">

                    <div class="mir-modal-header">
                        <div class="mir-modal-title">
                            <div class="mir-modal-icon mir-modal-icon-delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </div>
                            <div>
                                <div class="mir-modal-title-text">Mover para lixeira</div>
                                <div class="mir-modal-subtitle">Esta ação pode ser revertida</div>
                            </div>
                        </div>
                        <button wire:click="cancelDelete" class="mir-modal-close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="mir-modal-body">
                        <p style="color:#6d7279;font-size:.9rem;line-height:1.6;margin:0;">
                            Tem certeza que deseja mover o post <strong>"{{ $deletingPostTitle }}"</strong> para a lixeira?
                        </p>
                    </div>

                    <div class="mir-modal-footer">
                        <button wire:click="cancelDelete" class="mir-btn-ghost">Cancelar</button>
                        <button wire:click="deletePost" class="mir-btn-danger">
                            <i class="fa-solid fa-trash-can" style="font-size:.75rem"></i> Mover para lixeira
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    {{-- ================================================================ --}}
    {{-- TOAST                                                            --}}
    {{-- ================================================================ --}}
    <div id="post-toast-container" aria-live="polite"></div>

    {{-- ================================================================ --}}
    {{-- SCOPED STYLES                                                    --}}
    {{-- ================================================================ --}}
    <style>
        .post-section {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            margin-bottom: 24px;
        }
        .post-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }
        .post-section-header-left { min-width: 0; }
        .post-section-title { font-size: .95rem; font-weight: 700; color: #1a1d23; margin: 0; }
        .post-section-sub { font-size: .78rem; color: #9ca3af; margin-top: 2px; }
        .post-filter-header {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }
        .post-thumb {
            width: 48px; height: 48px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            flex-shrink: 0;
            background: #f3f4f6;
            display: flex; align-items: center; justify-content: center;
            color: #d1d5db; font-size: 1.1rem;
            overflow: hidden;
        }
        .post-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .post-body { flex: 1 1 0; min-width: 0; }
        .post-name {
            font-size: .875rem; font-weight: 600; color: #1a1d23;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            display: flex; align-items: center; gap: 6px; min-width: 0;
        }
        .post-name-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            min-width: 0;
        }
        .post-info {
            font-size: .72rem; color: #9ca3af; margin-top: 2px;
            display: flex; align-items: center; gap: 6px;
        }
        .post-info-dot { width: 3px; height: 3px; border-radius: 50%; background: #d1d5db; }
        .post-meta {
            display: flex; align-items: center; gap: 8px;
            flex-shrink: 0; width: 160px; justify-content: flex-end;
        }
        .mir-badge-pending {
            display: inline-flex; align-items: center; gap: 3px;
            padding: 2px 7px; border-radius: 50px;
            font-size: .65rem; font-weight: 700;
            background: #fef3c7; color: #92400e;
            flex-shrink: 0;
        }
        .post-loading-track {
            height: 3px;
            background: #f0f0f0;
            position: relative;
            overflow: hidden;
        }
        .post-loading-track.post-loading-active::after {
            content: '';
            position: absolute;
            top: 0; left: -40%;
            width: 40%; height: 100%;
            background: linear-gradient(90deg, transparent, #6366f1, transparent);
            border-radius: 2px;
            animation: mir-loading-slide 1.2s ease-in-out infinite;
        }
        #post-toast-container {
            position: fixed;
            bottom: 24px; right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    </style>

    @push('scripts')
    <script>
    function postShowToast(type, message) {
        const container = document.getElementById('post-toast-container');
        if (!container) return;
        const icons = { success: 'fa-circle-check', error: 'fa-circle-exclamation', info: 'fa-circle-info' };
        const toast = document.createElement('div');
        toast.className = `mir-toast mir-toast-${type}`;
        toast.innerHTML = `
            <i class="fa-solid ${icons[type] || icons.info} mir-toast-icon"></i>
            <span class="mir-toast-msg">${message}</span>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'mir-toast-out 200ms ease forwards';
            setTimeout(() => toast.remove(), 210);
        }, 3500);
    }

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', ({ type, message }) => postShowToast(type, message));
    });
    </script>
    @endpush

</div>