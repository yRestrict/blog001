<div>

    {{-- ================================================================ --}}
    {{-- TOAST                                                             --}}
    {{-- ================================================================ --}}
    <div id="tag-toast-container" aria-live="polite"></div>

    {{-- ================================================================ --}}
    {{-- PAGE HEADER ACTION                                                --}}
    {{-- ================================================================ --}}
    <div class="page-header-action">
        <div class="page-header-left">
            <h1 class="page-header-title">
                Tags
                <span class="page-header-title-count">{{ $totalTags }}</span>
            </h1>
            <span class="page-header-sub">Gerencie as tags do blog</span>
        </div>
        <div class="page-header-right">
            <button wire:click="openCreateModal" class="mir-btn-primary-lg">
                <i class="fa fa-plus" style="font-size:.7rem"></i> Nova Tag
            </button>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- SECTION CARD                                                      --}}
    {{-- ================================================================ --}}
    <div class="mir-section">
        {{-- Camada 1 — Section Header --}}
        <div class="tag-section-header">
            <div class="tag-section-header-left">
                <h3 class="tag-section-title">Tags</h3>
                <p class="tag-section-sub">Gerencie todas as tags do sistema</p>
            </div>
        </div>

        {{-- Camada 2 — Filter Header --}}
        <div class="tag-filter-header">
            <div style="position:relative; flex:1; max-width:280px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.75rem;"></i>
                <input type="text" wire:model.live.debounce.300ms="search" class="mir-input" placeholder="Buscar tags..." style="padding-left:32px;">
            </div>
        </div>

        {{-- Loading Bar --}}
        <div wire:loading class="mir-loading-bar"></div>

        {{-- Table Header --}}
        <div class="mir-table-header">
            <span class="plh-body">Tag</span>
            <span style="width:100px;flex-shrink:0;text-align:center;">Posts</span>
            <span class="plh-divider"></span>
            <span class="plh-actions">Ações</span>
        </div>

        {{-- Data Rows --}}
        <div class="mir-data-list" wire:loading.class="mir-loading-overlay">
            @forelse($tags as $tag)
                <div class="mir-data-row" wire:key="tag-{{ $tag->id }}">
                    <div style="flex:1 1 0;min-width:0;">
                        <div style="font-size:.875rem;font-weight:600;color:#1a1d23;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $tag->name }}</div>
                        <div style="font-size:.72rem;color:#9ca3af;font-family:ui-monospace,monospace;margin-top:1px;">{{ $tag->slug }}</div>
                    </div>
                    <div style="width:100px;flex-shrink:0;display:flex;justify-content:center;">
                        <span class="mir-badge-count" data-tooltip="{{ $tag->posts_count }} {{ $tag->posts_count === 1 ? 'post' : 'posts' }}">
                            {{ $tag->posts_count }}
                        </span>
                    </div>
                    <div class="mir-divider"></div>
                    <div class="mir-actions">
                        <button wire:click="openEditModal({{ $tag->id }})" class="mir-action-btn mir-action-edit" data-tooltip="Editar tag">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931z"/><path d="M19.5 7.125l-2.652-2.652"/></svg>
                        </button>
                        <button wire:click="prepareDelete({{ $tag->id }})" class="mir-action-btn mir-action-delete" data-tooltip="Excluir tag">
                            <svg width="12" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="mir-empty-state">
                    <div class="mir-empty-icon"><i class="fa fa-tags"></i></div>
                    <h3 class="mir-empty-title">Nenhuma tag encontrada</h3>
                    <p class="mir-empty-desc">
                        @if($search) Tente ajustar os termos de busca. @else Crie sua primeira tag. @endif
                    </p>
                    @unless($search)
                        <button wire:click="openCreateModal" class="mir-btn-primary-lg">
                            <i class="fa fa-plus" style="font-size:.7rem"></i> Criar Tag
                        </button>
                    @endunless
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($tags->hasPages())
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
                    <button wire:click="previousPage" class="mir-page-btn {{ $tags->onFirstPage() ? 'disabled' : '' }}" {{ $tags->onFirstPage() ? 'disabled' : '' }}>
                        <i class="fa fa-chevron-left" style="font-size:.6rem"></i>
                    </button>
                    @foreach($tags->getUrlRange(1, $tags->lastPage()) as $page => $url)
                        <button wire:click="gotoPage({{ $page }})" class="mir-page-btn {{ $page == $tags->currentPage() ? 'active' : '' }}">{{ $page }}</button>
                    @endforeach
                    <button wire:click="nextPage" class="mir-page-btn {{ !$tags->hasMorePages() ? 'disabled' : '' }}" {{ !$tags->hasMorePages() ? 'disabled' : '' }}>
                        <i class="fa fa-chevron-right" style="font-size:.6rem"></i>
                    </button>
                </div>
                <div class="mir-pagination-right">
                    Mostrando {{ $tags->firstItem() }}–{{ $tags->lastItem() }} de {{ $tags->total() }}
                </div>
            </div>
        @endif
    </div>

    {{-- ================================================================ --}}
    {{-- MODAL CRIAR/EDITAR                                                --}}
    {{-- ================================================================ --}}
    @if($showModal)
        <div class="mir-modal-overlay" style="display:flex" x-data x-on:keydown.escape.window="$wire.closeModal()">
            <div class="mir-modal-dialog">
                <div class="mir-modal-content">
                    <div class="mir-modal-header">
                        <div class="mir-modal-title">
                            <div class="mir-modal-icon {{ $isEditing ? 'mir-modal-icon-edit' : 'mir-modal-icon-add' }}">
                                <i class="fa {{ $isEditing ? 'fa-edit' : 'fa-plus' }}"></i>
                            </div>
                            <div>
                                <div class="mir-modal-title-text">{{ $isEditing ? 'Editar Tag' : 'Nova Tag' }}</div>
                                <div class="mir-modal-subtitle">{{ $isEditing ? 'Altere os dados da tag' : 'Preencha os dados da nova tag' }}</div>
                            </div>
                        </div>
                        <button wire:click="closeModal" class="mir-modal-close"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="mir-modal-body">
                        <div class="mb-1">
                            <label class="mir-label">Nome <span class="mir-required">*</span></label>
                            <input type="text"
                                   wire:model.live="tagName"
                                   class="mir-input @error('tagName') is-invalid @enderror"
                                   placeholder="Ex: Laravel, PHP, JavaScript"
                                   wire:keydown.enter="saveTag">
                            @error('tagName')
                                <div class="mir-field-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mir-modal-footer">
                        <button wire:click="closeModal" class="mir-btn-ghost">Cancelar</button>
                        <button wire:click="saveTag" class="mir-btn-primary-lg"
                                wire:loading.attr="disabled"
                                wire:target="saveTag">
                            <span wire:loading wire:target="saveTag">
                                <span class="spinner-border spinner-border-sm mr-1"></span>
                            </span>
                            <i class="fa fa-save" wire:loading.remove wire:target="saveTag"></i>
                            {{ $isEditing ? 'Salvar Alterações' : 'Criar Tag' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ================================================================ --}}
    {{-- MODAL EXCLUIR                                                     --}}
    {{-- ================================================================ --}}
    @if($deletingTagId)
        <div class="mir-modal-overlay" style="display:flex" x-data x-on:keydown.escape.window="$wire.cancelDelete()">
            <div class="mir-modal-dialog" style="max-width:540px">
                <div class="mir-modal-content">
                    <div class="mir-modal-header">
                        <div class="mir-modal-title">
                            <div class="mir-modal-icon mir-modal-icon-delete"><i class="fa fa-trash-alt"></i></div>
                            <div>
                                <div class="mir-modal-title-text">Excluir Tag</div>
                                <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                            </div>
                        </div>
                        <button wire:click="cancelDelete" class="mir-modal-close"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="mir-modal-body">
                        <p style="color:#6d7279;font-size:.9rem;line-height:1.6;margin:0;">
                            Tem certeza que deseja excluir a tag <strong style="color:#ef4444">"{{ $deletingTagName }}"</strong>?
                            <br>
                            <span style="font-size:.8rem;color:#9ca3af;margin-top:6px;display:block;">
                                Os posts vinculados serão desvinculados, mas não excluídos.
                            </span>
                        </p>
                    </div>
                    <div class="mir-modal-footer">
                        <button wire:click="cancelDelete" class="mir-btn-ghost">Cancelar</button>
                        <button wire:click="deleteTag" class="mir-btn-danger">
                            <i class="fa fa-trash-alt" style="font-size:.75rem"></i> Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ================================================================ --}}
    {{-- SCRIPTS: Toast + Tooltips                                         --}}
    {{-- ================================================================ --}}
    @push('scripts')
    <script>
    /* ─── Toast ─────────────────────────────────────────────────────── */
    function tagShowToast(type, message) {
        const container = document.getElementById('tag-toast-container');
        const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle' };
        const toast = document.createElement('div');
        toast.className = `mir-toast mir-toast-${type}`;
        toast.innerHTML = `
            <i class="fa ${icons[type] || icons.info} mir-toast-icon"></i>
            <span class="mir-toast-msg">${message}</span>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'mir-toast-out 200ms ease forwards';
            setTimeout(() => toast.remove(), 210);
        }, 3500);
    }

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', ({ type, message }) => tagShowToast(type, message));
    });
    </script>
    @endpush

    {{-- ================================================================ --}}
    {{-- ESTILOS SCOPED                                                    --}}
    {{-- ================================================================ --}}
    <style>
        .tag-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }
        .tag-section-header-left { min-width: 0; }
        .tag-section-title { font-size: .95rem; font-weight: 700; color: #1a1d23; margin: 0; }
        .tag-section-sub { font-size: .78rem; color: #9ca3af; margin-top: 2px; }
        .tag-filter-header {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }
    </style>

</div>
