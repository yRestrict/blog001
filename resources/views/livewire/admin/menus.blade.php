<div>

    {{-- ================================================================ --}}
    {{-- PAGE HEADER ACTION                                               --}}
    {{-- ================================================================ --}}
    <div class="page-header-action">
        <div class="page-header-left">
            <h1 class="page-header-title">
                Menu {{ $type === 'header' ? 'Header' : 'Footer' }}
                <span class="page-header-title-count">{{ $totalItems }}</span>
            </h1>
            <span class="page-header-sub">Arraste para reordenar · Gerencie os itens do menu</span>
        </div>
        <div class="page-header-right">
            <a href="{{ route('admin.settings') }}" class="mir-btn-neutral">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
            @if (!$showForm)
                <button class="mir-btn-primary-lg" wire:click="openAddForm(null)">
                    <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                        <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Adicionar Item
                </button>
            @endif
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- TOAST                                                             --}}
    {{-- ================================================================ --}}
    <div id="mir-toast-container" aria-live="polite"></div>

    {{-- ================================================================ --}}
    {{-- MODAL: ADICIONAR / EDITAR ITEM                                    --}}
    {{-- ================================================================ --}}
    @if ($showForm)
        <div class="mir-modal-overlay" tabindex="-1">
            <div class="mir-modal-dialog" style="max-width:600px;">
                <div class="mir-modal-content">

                    <div class="mir-modal-header">
                        <div class="mir-modal-title">
                            <div class="mir-modal-icon {{ $editingId ? 'mir-modal-icon-edit' : 'mir-modal-icon-add' }}">
                                <i class="fa-solid fa-{{ $editingId ? 'pen-to-square' : 'plus' }}"></i>
                            </div>
                            <div>
                                <div class="mir-modal-title-text">
                                    {{ $editingId ? 'Editar Item do Menu' : 'Adicionar Item ao Menu' }}
                                </div>
                                @if ($parent_id && !$editingId)
                                    <div class="mir-modal-subtitle">Subitem de outro elemento</div>
                                @endif
                            </div>
                        </div>
                        <button type="button" class="mir-modal-close" wire:click="cancelForm">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="mir-modal-body">
                        <div class="mnu-form-grid">
                            <div>
                                <label class="mir-label">Título <span class="mir-required">*</span></label>
                                <input type="text" class="mir-input @error('title') is-invalid @enderror"
                                    wire:model="title" placeholder="Ex: Sobre nós">
                                @error('title')
                                    <div class="mir-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="mir-label">
                                    URL <span class="mir-required">*</span>
                                    @if ($editingHasChildren)
                                        <span class="mnu-url-inactive-badge">
                                            <i class="fa-solid fa-circle-info" style="font-size:.6rem;"></i>
                                            Inativa
                                        </span>
                                    @endif
                                </label>
                                <input type="text"
                                    class="mir-input @error('url') is-invalid @enderror {{ $editingHasChildren ? 'mnu-input-disabled' : '' }}"
                                    wire:model="url" placeholder="/sobre ou https://..."
                                    @if ($editingHasChildren) disabled @endif>
                                @if ($editingHasChildren)
                                    <div class="mnu-url-hint">
                                        <i class="fa-solid fa-circle-info" style="font-size:.65rem;flex-shrink:0;margin-top:1px;"></i>
                                        Este item tem subitens — a URL fica inativa.
                                    </div>
                                @else
                                    @error('url')
                                        <div class="mir-field-error">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>

                            <div>
                                <label class="mir-label">Abrir em</label>
                                <select class="mir-input" wire:model="target">
                                    <option value="_self">Mesma aba</option>
                                    <option value="_blank">Nova aba</option>
                                </select>
                            </div>

                            <div>
                                <label class="mir-label">Item pai</label>
                                <select class="mir-input @error('parent_id') is-invalid @enderror"
                                    wire:model="parent_id">
                                    <option value="">— Nenhum (item raiz) —</option>
                                    @foreach ($allItems as $menuItem)
                                        @if (!$editingId || $menuItem->id !== $editingId)
                                            <option value="{{ $menuItem->id }}">
                                                {{ $menuItem->parent_id ? '→ ' : '' }}{{ $menuItem->title }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="mir-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div style="display:flex;align-items:flex-end;padding-bottom:4px;">
                                <div>
                                    <input type="checkbox" class="mir-switch-input" id="is_active"
                                        wire:model="is_active">
                                    <label class="mir-switch-label" for="is_active">
                                        <span class="mir-switch-track">
                                            <span class="mir-switch-thumb"></span>
                                        </span>
                                        <span class="mir-switch-text">Item ativo</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mir-modal-footer">
                        <button class="mir-btn-ghost" wire:click="cancelForm">Cancelar</button>
                        <button class="mir-btn-primary-lg" wire:click="save" wire:loading.attr="disabled">
                            <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm mr-1"></span>
                            </span>
                            <i class="fa-solid fa-floppy-disk" wire:loading.remove wire:target="save"></i>
                            {{ $editingId ? 'Salvar alterações' : 'Adicionar item' }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    {{-- ================================================================ --}}
    {{-- MODAL: CONFIRMAÇÃO EXCLUSÃO                                       --}}
    {{-- ================================================================ --}}
    <div x-data="{ show: false, itemId: null, itemTitle: '' }"
        x-on:confirm-delete.window="itemId = $event.detail.id; itemTitle = $event.detail.title; show = true"
        x-show="show" x-cloak class="mir-modal-overlay" tabindex="-1">

        <div class="mir-modal-dialog" style="max-width:440px;">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <div class="mir-modal-icon mir-modal-icon-delete">
                            <i class="fa-solid fa-trash-can"></i>
                        </div>
                        <div>
                            <div class="mir-modal-title-text">Excluir Item do Menu</div>
                            <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" x-on:click="show = false">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="mir-modal-body">
                    <p style="color:#6d7279; font-size:.9rem; line-height:1.6; margin:0;">
                        Tem certeza que deseja excluir o item
                        <strong style="color:#1a1d23;" x-text="itemTitle"></strong>?
                    </p>
                </div>

                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" x-on:click="show = false">Cancelar</button>
                    <button class="mir-btn-danger"
                        x-on:click="$wire.delete(itemId); show = false">
                        <i class="fa-solid fa-trash-can"></i> Sim, excluir
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- SECTION CARD: ÁRVORE DE MENU                                      --}}
    {{-- ================================================================ --}}
    <div class="mnu-section">

        {{-- Section Header: busca + filtros --}}
        <div class="mnu-filter-header">
            <div style="position:relative; flex:1; max-width:280px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.75rem;"></i>
                <input type="text" wire:model.live.debounce.300ms="search" class="mir-input" placeholder="Pesquisar itens..." style="padding-left:32px;">
            </div>
            <select wire:model.live="filterStatus" class="mir-input" style="width:160px;">
                <option value="">Todos os status</option>
                <option value="active">Ativo</option>
                <option value="inactive">Inativo</option>
            </select>
        </div>

        {{-- Loading bar --}}
        <div wire:loading class="mir-loading-bar"></div>

        {{-- Table Header --}}
        <div class="mir-table-header">
            <span class="mnu-plh-handle"></span>
            <span class="mnu-plh-badge">Nível</span>
            <span class="mnu-plh-body">Item</span>
            <span class="plh-divider"></span>
            <span class="mnu-plh-status" style="text-align:center;">Status</span>
            <span class="plh-divider"></span>
            <span class="mnu-plh-actions" style="text-align:center;">Ações</span>
        </div>

        @if ($items->isEmpty())
            <div class="mir-empty-state">
                <div class="mir-empty-icon"><i class="fa-solid fa-sitemap"></i></div>
                @if ($search || $filterStatus)
                    <h5 class="mir-empty-title">Nenhum item encontrado</h5>
                    <p class="mir-empty-desc">Tente ajustar os filtros ou termos de busca.</p>
                @else
                    <h5 class="mir-empty-title">Seu menu está vazio</h5>
                    <p class="mir-empty-desc">Comece adicionando o primeiro item ao menu.</p>
                    <button class="mir-btn-primary-lg" wire:click="openAddForm(null)">
                        <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                            <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Adicionar primeiro item
                    </button>
                @endif
            </div>
        @else
            <div class="mir-data-list mnu-tree" data-sortable data-parent-id="" wire:loading.class="mir-loading-overlay">
                @foreach ($items as $item)
                    @include('livewire.admin.menu-item-row', [
                        'item' => $item,
                        'depth' => 0,
                    ])
                @endforeach
            </div>
        @endif
    </div>

    {{-- ================================================================ --}}
    {{-- SCOPED STYLES (apenas mnu- específicos)                          --}}
    {{-- ================================================================ --}}
    <style>
        /* ── Section Card ──────────────────────────────────── */
        .mnu-section {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            margin-bottom: 24px;
        }

        /* ── Filter header ─────────────────────────────────── */
        .mnu-filter-header {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }

        /* ── Table Header: larguras ────────────────────────── */
        .mnu-plh-handle  { width: 22px; flex-shrink: 0; }
        .mnu-plh-badge   { width: 56px; flex-shrink: 0; }
        .mnu-plh-body    { flex: 1 1 0; min-width: 0; }
        .mnu-plh-status  { width: 90px; flex-shrink: 0; }
        .mnu-plh-actions { width: 100px; flex-shrink: 0; }

        /* ── Tree container ────────────────────────────────── */
        .mnu-tree { padding: 0; }
        .mnu-tree .mir-data-row { gap: 14px; }

        /* ── Row wrapper (para indentação + filhos) ────────── */
        .mnu-row { position: relative; }
        .mnu-row .mir-data-row { transition: background .15s; }

        /* ── Handle ────────────────────────────────────────── */
        .mnu-handle {
            cursor: grab;
            color: #c9cdd4;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            transition: color .15s;
            padding: 4px 6px 4px 0;
            width: 22px;
        }
        .mnu-handle:hover { color: #6366f1; }

        /* ── Depth badge ───────────────────────────────────── */
        .mnu-depth-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 50px;
            font-size: .68rem;
            font-weight: 700;
            flex-shrink: 0;
            white-space: nowrap;
        }
        .mnu-badge-root { background: #ede9fe; color: #6d28d9; }
        .mnu-badge-sub  { background: #e0f2fe; color: #0369a1; }

        /* ── Body ──────────────────────────────────────────── */
        .mnu-body { flex: 1 1 0; min-width: 0; }
        .mnu-name {
            font-size: .875rem; font-weight: 600; color: #1a1d23;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            display: flex; align-items: center; gap: 5px;
        }
        .mnu-url {
            font-size: .72rem; color: #9ca3af;
            font-family: ui-monospace, monospace; margin-top: 1px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .mnu-url-inactive { opacity: .55; }

        /* ── Actions container (largura fixa para alinhar com header) ── */
        .mnu-tree .mir-actions {
            width: 100px;
            flex-shrink: 0;
            justify-content: center;
        }

        /* ── Add subitem button ────────────────────────────── */
        .mnu-action-add:hover { background: #ede9fe; border-color: #c4b5fd; color: #5b21b6; }

        /* ── Children container ────────────────────────────── */
        .mnu-children { min-height: 2px; }
        .mnu-children-empty { min-height: 4px; }

        /* ── Form grid (modal) ─────────────────────────────── */
        .mnu-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* ── URL inativa (modal) ───────────────────────────── */
        .mnu-url-inactive-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: .68rem;
            font-weight: 600;
            color: #f59e0b;
            background: rgba(245,158,11,.12);
            border: 1px solid rgba(245,158,11,.25);
            border-radius: 4px;
            padding: 1px 6px;
            margin-left: 6px;
            vertical-align: middle;
        }
        .mnu-input-disabled {
            background: #f3f4f6 !important;
            color: #9ca3af !important;
            cursor: not-allowed;
            border-color: #e5e7eb !important;
        }
        .mnu-url-hint {
            display: flex;
            align-items: flex-start;
            gap: 5px;
            margin-top: 5px;
            font-size: .78rem;
            color: #f59e0b;
            line-height: 1.4;
        }

        /* ── Sortable ──────────────────────────────────────── */
        .sortable-ghost  { opacity: .4; background: #ede9fe !important; border-radius: 8px; }
        .sortable-chosen { box-shadow: 0 4px 18px rgba(99,102,241,.18); border-radius: 8px; }
    </style>

    {{-- ================================================================ --}}
    {{-- SCRIPTS                                                           --}}
    {{-- ================================================================ --}}
    @push('scripts')
    <script>
    function showToast(type, message) {
        const container = document.getElementById('mir-toast-container');
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
        initSortable();

        Livewire.on('notify', ({ type, message }) => showToast(type, message));
        Livewire.hook('morph.updated', () => { initSortable(); });
    });

    function initSortable() {
        document.querySelectorAll('[data-sortable]').forEach(container => {
            if (container._sortable) container._sortable.destroy();

            container._sortable = new Sortable(container, {
                group: 'menu-items',
                animation: 150,
                handle: '.mnu-handle',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',

                onMove(evt) {
                    const item = evt.dragged;
                    const newContainer = evt.to;
                    const parentRow = newContainer.closest('[data-item-id]');
                    let newDepth = 0;

                    if (parentRow) {
                        const depthClass = Array.from(parentRow.classList)
                            .find(c => c.startsWith('depth-'));
                        if (depthClass) {
                            newDepth = parseInt(depthClass.replace('depth-', '')) + 1;
                        }
                    }

                    item.className = item.className.replace(/depth-\d+/g, '');
                    item.classList.add(`depth-${newDepth}`);
                    updateBadge(item, newDepth);
                },

                onEnd() {
                    requestAnimationFrame(() => {
                        const orderedItems = collectAllItems();
                        @this.dispatch('items-reordered', { orderedItems });
                        showToast('info', 'Ordem atualizada');
                    });
                }
            });
        });
    }

    function updateBadge(item, depth) {
        const badge = item.querySelector('.mnu-depth-badge');
        if (!badge) return;

        badge.className = 'mir-badge mnu-depth-badge';
        let label = 'Raiz';
        let className = 'mnu-badge-root';

        if (depth > 0) {
            label = `Sub ${depth}`;
            className = 'mnu-badge-sub';
        }

        badge.classList.add(className);
        badge.innerHTML = `
            <svg width="8" height="8" viewBox="0 0 8 8">
                <circle cx="4" cy="4" r="3.5" fill="currentColor" opacity=".9"/>
            </svg>
            ${label}
        `;
    }

    function collectAllItems() {
        const result = [];
        const seen = new Set();

        document.querySelectorAll('[data-sortable]').forEach(container => {
            const rawParent = container.dataset.parentId;
            const parentId = rawParent !== '' ? parseInt(rawParent) : null;

            Array.from(container.children)
                .filter(el => el.dataset.itemId)
                .forEach((el, index) => {
                    const id = parseInt(el.dataset.itemId);
                    if (seen.has(id)) return;
                    seen.add(id);
                    result.push({ id, parent_id: parentId, order: index });
                });
        });

        return result;
    }
    </script>
    @endpush

</div>
