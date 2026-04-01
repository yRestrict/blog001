{{-- livewire/admin/categories-trash.blade.php --}}
<div>

    @php $totalTrashed = $trashedCategories->count() + $trashedParentCategories->count(); @endphp

    {{-- ================================================================ --}}
    {{-- PAGE HEADER ACTION                                               --}}
    {{-- ================================================================ --}}
    <div class="page-header-action">
        <div class="page-header-left">
            <h1 class="page-header-title">
                Lixeira de Categorias
                @if ($totalTrashed > 0)
                    <span class="page-header-title-count" style="background:#fee2e2;color:#991b1b;">{{ $totalTrashed }}</span>
                @endif
            </h1>
            <span class="page-header-sub">Restaure ou exclua permanentemente categorias</span>
        </div>
        <div class="page-header-right">
            <a href="{{ route('admin.categories.index') }}" class="mir-btn-neutral">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
            @if ($totalTrashed > 0)
                <button class="mir-btn-success" wire:click="restoreAll" wire:loading.attr="disabled" wire:target="restoreAll">
                    <i class="fa-solid fa-rotate-left"></i> Restaurar Tudo
                </button>
            @endif
        </div>
    </div>


    {{-- ================================================================ --}}
    {{-- ESTADO VAZIO GLOBAL                                               --}}
    {{-- ================================================================ --}}
    @if ($trashedCategories->isEmpty() && $trashedParentCategories->isEmpty())
        <div class="cat-trash-section">
            <div class="mir-empty-state">
                <div class="mir-empty-icon"><i class="fa-solid fa-trash-can"></i></div>
                <h5 class="mir-empty-title">A lixeira está vazia</h5>
                <p class="mir-empty-desc">Categorias excluídas aparecerão aqui para que você possa restaurá-las.</p>
            </div>
        </div>
    @endif


    {{-- ================================================================ --}}
    {{-- SEÇÃO: CATEGORIAS PAI NA LIXEIRA                                  --}}
    {{-- ================================================================ --}}
    @if ($trashedParentCategories->isNotEmpty())
    <div class="cat-trash-section">
        <div class="cat-trash-section-header">
            <div class="cat-trash-section-header-left">
                <h2 class="cat-trash-section-title">Categorias Pai</h2>
                <span class="cat-trash-section-sub">Categorias pai que foram excluídas</span>
            </div>
            <span class="mir-badge-count">{{ $trashedParentCategories->count() }}</span>
        </div>

        {{-- Table Header --}}
        <div wire:loading class="mir-loading-bar"></div>

        <div class="mir-table-header">
            <span class="ctt-plh-icon"></span>
            <span class="ctt-plh-body">Nome</span>
            <span class="ctt-plh-meta" style="text-align:right;">Filhas / Excluído em</span>
            <span class="plh-divider"></span>
            <span class="ctt-plh-actions" style="text-align:center;">Ações</span>
        </div>

        <div class="mir-data-list" wire:loading.class="mir-loading-overlay">
            @foreach ($trashedParentCategories as $parent)
                <div class="mir-data-row" wire:key="trash-parent-{{ $parent->id }}">

                    {{-- Ícone de lixeira --}}
                    <div class="ctt-trash-icon">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>

                    {{-- Body --}}
                    <div class="ctt-body">
                        <div class="ctt-name">{{ $parent->name }}</div>
                        <div class="ctt-slug">{{ $parent->slug }}</div>
                    </div>

                    {{-- Meta --}}
                    <div class="ctt-meta">
                        <span class="mir-badge-count" data-tooltip="{{ $parent->categories_count }} subcategorias">
                            {{ $parent->categories_count }}
                        </span>
                        <span class="mir-badge-deleted">
                            <i class="fa-solid fa-clock" style="font-size:.6rem;"></i>
                            {{ $parent->deleted_at->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    <div class="mir-divider"></div>

                    {{-- Ações --}}
                    <div class="mir-actions">
                        <button class="mir-action-btn mir-action-restore"
                                wire:click="restoreParentCategory({{ $parent->id }})"
                                wire:loading.attr="disabled"
                                wire:target="restoreParentCategory({{ $parent->id }})"
                                data-tooltip="Restaurar">
                            <i class="fa-solid fa-rotate-left" style="font-size:.7rem;"></i>
                        </button>
                        <button class="mir-action-btn mir-action-delete"
                                wire:click="$dispatch('confirm-force-delete-parent', { id: {{ $parent->id }}, name: '{{ addslashes($parent->name) }}' })"
                                data-tooltip="Excluir permanentemente">
                            <i class="fa-solid fa-trash-can" style="font-size:.7rem;"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif


    {{-- ================================================================ --}}
    {{-- SEÇÃO: CATEGORIAS NA LIXEIRA                                      --}}
    {{-- ================================================================ --}}
    @if ($trashedCategories->isNotEmpty())
    <div class="cat-trash-section">
        <div class="cat-trash-section-header">
            <div class="cat-trash-section-header-left">
                <h2 class="cat-trash-section-title">Categorias</h2>
                <span class="cat-trash-section-sub">Categorias que foram excluídas</span>
            </div>
            <span class="mir-badge-count">{{ $trashedCategories->count() }}</span>
        </div>

        {{-- Table Header --}}
        <div wire:loading class="mir-loading-bar"></div>

        <div class="mir-table-header">
            <span class="ctt-plh-icon"></span>
            <span class="ctt-plh-body">Categoria</span>
            <span class="ctt-plh-catmeta" style="text-align:right;">Pai / Excluído em</span>
            <span class="plh-divider"></span>
            <span class="ctt-plh-actions" style="text-align:center;">Ações</span>
        </div>

        <div class="mir-data-list" wire:loading.class="mir-loading-overlay">
            @foreach ($trashedCategories as $category)
                <div class="mir-data-row" wire:key="trash-cat-{{ $category->id }}">

                    {{-- Ícone de lixeira --}}
                    <div class="ctt-trash-icon">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>

                    {{-- Body --}}
                    <div class="ctt-body">
                        <div class="ctt-name">{{ $category->name }}</div>
                        <div class="ctt-slug">{{ $category->slug }}</div>
                    </div>

                    {{-- Meta --}}
                    <div class="ctt-catmeta">
                        @if ($category->parentCategory)
                            <span class="mir-badge-parent">
                                <svg width="8" height="8" viewBox="0 0 8 8" fill="none">
                                    <path d="M1 1h2.5v2.5M1 7h6V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ $category->parentCategory->name }}
                            </span>
                        @else
                            <span class="ctt-no-parent">— sem pai —</span>
                        @endif
                        <span class="mir-badge-deleted">
                            <i class="fa-solid fa-clock" style="font-size:.6rem;"></i>
                            {{ $category->deleted_at->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    <div class="mir-divider"></div>

                    {{-- Ações --}}
                    <div class="mir-actions">
                        <button class="mir-action-btn mir-action-restore"
                                wire:click="restoreCategory({{ $category->id }})"
                                wire:loading.attr="disabled"
                                wire:target="restoreCategory({{ $category->id }})"
                                data-tooltip="Restaurar">
                            <i class="fa-solid fa-rotate-left" style="font-size:.7rem;"></i>
                        </button>
                        <button class="mir-action-btn mir-action-delete"
                                wire:click="$dispatch('confirm-force-delete-category', { id: {{ $category->id }}, name: '{{ addslashes($category->name) }}' })"
                                data-tooltip="Excluir permanentemente">
                            <i class="fa-solid fa-trash-can" style="font-size:.7rem;"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif


    {{-- ================================================================ --}}
    {{-- MODAL: EXCLUSÃO PERMANENTE — CATEGORIA PAI                        --}}
    {{-- ================================================================ --}}
    <div x-data="{ show: false, itemId: null, itemName: '' }"
         x-on:confirm-force-delete-parent.window="itemId = $event.detail.id; itemName = $event.detail.name; show = true"
         x-show="show" x-cloak class="mir-modal-overlay" tabindex="-1">
        <div class="mir-modal-dialog" style="max-width:440px;">
            <div class="mir-modal-content">
                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <div class="mir-modal-icon mir-modal-icon-delete">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <div class="mir-modal-title-text">Excluir Permanentemente</div>
                            <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" x-on:click="show = false">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="mir-modal-body">
                    <p style="color:#6d7279;font-size:.9rem;line-height:1.6;margin:0;">
                        Tem certeza que deseja excluir <strong>permanentemente</strong> a categoria pai
                        <strong style="color:#1a1d23;" x-text="itemName"></strong>?
                    </p>
                    <div class="ctt-warning-box">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        As subcategorias excluídas junto também serão removidas permanentemente.
                    </div>
                </div>
                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" x-on:click="show = false">Cancelar</button>
                    <button class="mir-btn-danger" x-on:click="$wire.forceDeleteParentCategory(itemId); show = false">
                        <i class="fa-solid fa-trash-can"></i> Sim, excluir permanentemente
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- ================================================================ --}}
    {{-- MODAL: EXCLUSÃO PERMANENTE — CATEGORIA                            --}}
    {{-- ================================================================ --}}
    <div x-data="{ show: false, itemId: null, itemName: '' }"
         x-on:confirm-force-delete-category.window="itemId = $event.detail.id; itemName = $event.detail.name; show = true"
         x-show="show" x-cloak class="mir-modal-overlay" tabindex="-1">
        <div class="mir-modal-dialog" style="max-width:440px;">
            <div class="mir-modal-content">
                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <div class="mir-modal-icon mir-modal-icon-delete">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <div class="mir-modal-title-text">Excluir Permanentemente</div>
                            <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" x-on:click="show = false">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="mir-modal-body">
                    <p style="color:#6d7279;font-size:.9rem;line-height:1.6;margin:0;">
                        Tem certeza que deseja excluir <strong>permanentemente</strong> a categoria
                        <strong style="color:#1a1d23;" x-text="itemName"></strong>?
                    </p>
                    <div class="ctt-warning-box">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Este processo é irreversível. A categoria será removida permanentemente do sistema.
                    </div>
                </div>
                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" x-on:click="show = false">Cancelar</button>
                    <button class="mir-btn-danger" x-on:click="$wire.forceDeleteCategory(itemId); show = false">
                        <i class="fa-solid fa-trash-can"></i> Sim, excluir permanentemente
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- ================================================================ --}}
    {{-- TOAST                                                             --}}
    {{-- ================================================================ --}}
    <div id="trash-toast-container" aria-live="polite"></div>


    {{-- ================================================================ --}}
    {{-- SCOPED STYLES (apenas ctt- específicos)                          --}}
    {{-- ================================================================ --}}
    <style>
        /* ── Section Card ──────────────────────────────────── */
        .cat-trash-section {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            margin-bottom: 24px;
        }
        .cat-trash-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }
        .cat-trash-section-header-left { min-width: 0; }
        .cat-trash-section-title { font-size: .95rem; font-weight: 700; color: #1a1d23; margin: 0; }
        .cat-trash-section-sub { font-size: .78rem; color: #9ca3af; margin-top: 2px; }

        /* ── Table Header: larguras ────────────────────────── */
        .ctt-plh-icon    { width: 24px; flex-shrink: 0; }
        .ctt-plh-body    { flex: 1 1 0; min-width: 0; }
        .ctt-plh-meta    { width: 200px; flex-shrink: 0; text-align: right; }
        .ctt-plh-catmeta { width: 240px; flex-shrink: 0; text-align: right; }
        .ctt-plh-actions { width: 68px; flex-shrink: 0; }

        /* ── Trash icon ────────────────────────────────────── */
        .ctt-trash-icon {
            width: 24px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            color: #ef4444; opacity: .4; font-size: .75rem;
        }

        /* ── Body ──────────────────────────────────────────── */
        .ctt-body { flex: 1 1 0; min-width: 0; }
        .ctt-name {
            font-size: .875rem; font-weight: 600; color: #1a1d23;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .ctt-slug {
            font-size: .72rem; color: #9ca3af;
            font-family: ui-monospace, monospace; margin-top: 1px;
        }

        /* ── Meta (parent categories) ──────────────────────── */
        .ctt-meta {
            display: flex; align-items: center; gap: 8px;
            flex-shrink: 0; width: 200px; justify-content: flex-end;
        }

        /* ── Meta (categories — pai + deleted) ─────────────── */
        .ctt-catmeta {
            display: flex; align-items: center; gap: 8px;
            flex-shrink: 0; width: 240px; justify-content: flex-end;
        }
        .ctt-no-parent { font-size: .75rem; color: #c4c8cf; }

        /* ── Warning box (modais) ──────────────────────────── */
        .ctt-warning-box {
            display: flex; align-items: flex-start; gap: 8px;
            margin-top: 12px; padding: 10px 14px;
            background: #fee2e2; border-radius: 8px;
            font-size: .8rem; color: #991b1b; line-height: 1.5;
        }
        .ctt-warning-box i { margin-top: 2px; flex-shrink: 0; }
    </style>


    {{-- ================================================================ --}}
    {{-- SCRIPTS: Toast                                                    --}}
    {{-- ================================================================ --}}
    @push('scripts')
    <script>
    function trashShowToast(type, message) {
        const container = document.getElementById('trash-toast-container');
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
        Livewire.on('notify', ({ type, message }) => trashShowToast(type, message));
    });
    </script>
    @endpush

</div>
