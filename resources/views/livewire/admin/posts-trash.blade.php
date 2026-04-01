<div>

    {{-- ================================================================ --}}
    {{-- PAGE HEADER ACTION                                               --}}
    {{-- ================================================================ --}}
    <div class="page-header-action">
        <div class="page-header-left">
            <h1 class="page-header-title">
                Lixeira
                @if($this->trashedCount > 0)
                    <span style="display:inline-flex;align-items:center;justify-content:center;min-width:28px;height:24px;padding:0 8px;border-radius:50px;font-size:.72rem;font-weight:700;background:#fee2e2;color:#991b1b;">{{ $this->trashedCount }}</span>
                @endif
            </h1>
            <span class="page-header-sub">Posts movidos para a lixeira</span>
        </div>
        <div class="page-header-right">
            <a href="{{ route('admin.posts.index') }}" class="mir-btn-neutral">
                <i class="fa fa-arrow-left" style="font-size:.7rem"></i> Voltar
            </a>
            @if($this->trashedCount > 0)
                <button wire:click="restoreAll" class="mir-btn-success">
                    <i class="fa fa-undo" style="font-size:.7rem"></i> Restaurar Todos
                </button>
                <button wire:click="confirmEmptyTrash" class="mir-btn-danger">
                    <i class="fa fa-trash-alt" style="font-size:.7rem"></i> Esvaziar Lixeira
                </button>
            @endif
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- SECTION CARD                                                     --}}
    {{-- ================================================================ --}}
    <div class="post-section">

        {{-- Camada 1 — Section Header --}}
        <div class="post-section-header">
            <div class="post-section-header-left">
                <h3 class="post-section-title">Posts Excluídos</h3>
                <p class="post-section-sub">{{ $posts->total() }} na lixeira</p>
            </div>
        </div>

        {{-- Camada 2 — Filter Header --}}
        <div class="post-filter-header">
            <div style="position:relative; flex:1; max-width:280px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.75rem;"></i>
                <input type="text" wire:model.live.debounce.300ms="search" class="mir-input" placeholder="Buscar na lixeira..." style="padding-left:32px;">
            </div>
        </div>

        {{-- Loading bar --}}
        <div wire:loading class="mir-loading-bar"></div>

        {{-- Table Header --}}
        <div class="mir-table-header">
            <span class="plh-thumb"></span>
            <span class="plh-body">Post</span>
            <span style="width:140px;flex-shrink:0;text-align:right;">Excluído em</span>
            <span class="plh-divider"></span>
            <span class="plh-status">Status</span>
            <span class="plh-divider"></span>
            <span class="plh-actions">Ações</span>
        </div>

        {{-- Data Rows --}}
        <div class="mir-data-list" wire:loading.class="mir-loading-overlay">
            @forelse($posts as $post)
                <div class="mir-data-row post-row-trashed">

                    {{-- Thumbnail --}}
                    <div class="post-thumb" style="opacity:.5">
                        @if($post->thumbnail)
                            <img src="{{ asset('uploads/posts/' . $post->thumbnail) }}" alt="">
                        @else
                            <i class="fa fa-image"></i>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="post-body">
                        <div class="post-name">{{ $post->title }}</div>
                        <div class="post-info">
                            <span>por {{ $post->author?->name ?? '—' }}</span>
                            <span class="post-info-dot"></span>
                            <span>{{ $post->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    {{-- Deleted at --}}
                    <div style="width:140px;flex-shrink:0;text-align:right;">
                        <span class="mir-badge-deleted">
                            <i class="fa fa-clock" style="font-size:.6rem"></i>
                            {{ $post->deleted_at->format('d/m/Y') }}
                        </span>
                        <div style="font-size:.7rem;color:#9ca3af;margin-top:2px;">há {{ $post->deleted_at->diffForHumans(null, true) }}</div>
                    </div>

                    {{-- Divider --}}
                    <div class="mir-divider"></div>

                    {{-- Status (read-only, antes da exclusão) --}}
                    <div style="width:90px;flex-shrink:0;display:flex;justify-content:center;">
                        <span class="mir-status is-{{ $post->status }}" style="cursor:default" data-tooltip="Status antes da exclusão">
                            <span class="mir-status-ring"></span>
                            {{ match($post->status) { 'published' => 'Publicado', 'private' => 'Privado', default => 'Rascunho' } }}
                        </span>
                    </div>

                    {{-- Divider --}}
                    <div class="mir-divider"></div>

                    {{-- Actions --}}
                    <div class="mir-actions">
                        <button wire:click="restorePost({{ $post->id }})" class="mir-action-btn mir-action-restore" data-tooltip="Restaurar post">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 0 1 0 12h-3"/></svg>
                        </button>
                        <button wire:click="prepareForceDelete({{ $post->id }})" class="mir-action-btn mir-action-delete" data-tooltip="Excluir permanentemente">
                            <svg width="12" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="mir-empty-state">
                    <div class="mir-empty-icon">
                        <i class="fa fa-trash-alt"></i>
                    </div>
                    <h3 class="mir-empty-title">A lixeira está vazia</h3>
                    <p class="mir-empty-desc">Nenhum post na lixeira.</p>
                    <a href="{{ route('admin.posts.index') }}" class="mir-btn-primary-lg">
                        <i class="fa fa-arrow-left" style="font-size:.7rem"></i> Voltar para Posts
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
            <div class="mir-pagination">
                <div class="mir-pagination-left">
                    <span class="mir-pagination-left-label">Por página:</span>
                    <select wire:model.live="perPage" class="mir-per-page">
                        <option value="6">6</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="mir-pagination-center">
                    <button wire:click="previousPage" class="mir-page-btn {{ $posts->onFirstPage() ? 'disabled' : '' }}" {{ $posts->onFirstPage() ? 'disabled' : '' }}>
                        <i class="fa fa-chevron-left" style="font-size:.6rem"></i>
                    </button>
                    @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                        <button wire:click="gotoPage({{ $page }})" class="mir-page-btn {{ $page == $posts->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </button>
                    @endforeach
                    <button wire:click="nextPage" class="mir-page-btn {{ !$posts->hasMorePages() ? 'disabled' : '' }}" {{ !$posts->hasMorePages() ? 'disabled' : '' }}>
                        <i class="fa fa-chevron-right" style="font-size:.6rem"></i>
                    </button>
                </div>
                <div class="mir-pagination-right">
                    Mostrando {{ $posts->firstItem() }}–{{ $posts->lastItem() }} de {{ $posts->total() }}
                </div>
            </div>
        @endif
    </div>

    {{-- ================================================================ --}}
    {{-- MODAL: Exclusão permanente                                       --}}
    {{-- ================================================================ --}}
    @if($deletingPostId)
        <div class="mir-modal-overlay" style="display:flex" x-data x-on:keydown.escape.window="$wire.cancelForceDelete()">
            <div class="mir-modal-dialog" style="max-width:540px">
                <div class="mir-modal-content">
                    <div class="mir-modal-header">
                        <div class="mir-modal-title">
                            <div class="mir-modal-icon mir-modal-icon-delete">
                                <i class="fa fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <div class="mir-modal-title-text">Excluir permanentemente</div>
                                <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                            </div>
                        </div>
                        <button wire:click="cancelForceDelete" class="mir-modal-close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div class="mir-modal-body">
                        <p>Tem certeza que deseja excluir permanentemente o post <strong>"{{ $deletingPostTitle }}"</strong>?</p>
                        <div class="warning-box">
                            <strong>Consequências:</strong>
                            <ul>
                                <li>O post será removido permanentemente do banco de dados</li>
                                <li>Todos os comentários associados serão excluídos</li>
                                <li>A imagem destacada será removida</li>
                                <li>Esta ação não pode ser revertida</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mir-modal-footer">
                        <button wire:click="cancelForceDelete" class="mir-btn-ghost">Cancelar</button>
                        <button wire:click="forceDeletePost" class="mir-btn-danger">
                            <i class="fa fa-trash-alt" style="font-size:.75rem"></i> Excluir permanentemente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ================================================================ --}}
    {{-- MODAL: Esvaziar lixeira                                          --}}
    {{-- ================================================================ --}}
    @if($confirmingEmptyTrash)
        <div class="mir-modal-overlay" style="display:flex" x-data x-on:keydown.escape.window="$wire.cancelEmptyTrash()">
            <div class="mir-modal-dialog" style="max-width:540px">
                <div class="mir-modal-content">
                    <div class="mir-modal-header">
                        <div class="mir-modal-title">
                            <div class="mir-modal-icon mir-modal-icon-delete">
                                <i class="fa fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <div class="mir-modal-title-text">Esvaziar lixeira</div>
                                <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                            </div>
                        </div>
                        <button wire:click="cancelEmptyTrash" class="mir-modal-close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div class="mir-modal-body">
                        <p>Tem certeza que deseja excluir permanentemente <strong>todos os {{ $this->trashedCount }} posts</strong> da lixeira?</p>
                        <div class="warning-box">
                            <strong>Atenção:</strong>
                            <ul>
                                <li>Todos os posts na lixeira serão removidos permanentemente</li>
                                <li>Comentários e imagens associados serão excluídos</li>
                                <li>Esta ação não pode ser revertida</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mir-modal-footer">
                        <button wire:click="cancelEmptyTrash" class="mir-btn-ghost">Cancelar</button>
                        <button wire:click="emptyTrash" class="mir-btn-danger">
                            <i class="fa fa-trash-alt" style="font-size:.75rem"></i> Esvaziar lixeira
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
            width: 48px;
            height: 48px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            flex-shrink: 0;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d1d5db;
            font-size: 1.1rem;
            overflow: hidden;
        }
        .post-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .post-body {
            flex: 1 1 0;
            min-width: 0;
        }
        .post-name {
            font-size: .875rem;
            font-weight: 600;
            color: #1a1d23;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .post-info {
            font-size: .72rem;
            color: #9ca3af;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .post-info-dot {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: #d1d5db;
        }
        .post-row-trashed .post-name {
            opacity: .7;
        }
    </style>
</div>
