{{--
    Livewire View: livewire/admin/menus.blade.php
--}}

<div>

    {{-- ================================================================ --}}
    {{-- TOAST DE FEEDBACK (substitui os alertas antigos)                 --}}
    {{-- ================================================================ --}}
    <div id="mir-toast-container" aria-live="polite"></div>

    {{-- Dispara toasts a partir de eventos Livewire --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', ({ type, message }) => showToast(type, message));
        });
    </script>


    {{-- ================================================================ --}}
    {{-- BOTÃO: ADICIONAR ITEM RAIZ                                       --}}
    {{-- ================================================================ --}}

    @if (!$showForm)
        <div class="d-flex justify-content-end mb-3">
            <button class="mir-btn-primary-lg" wire:click="openAddForm(null)">
                <i class="fa fa-plus"></i> Adicionar Item
            </button>
        </div>
    @endif


    {{-- ================================================================ --}}
    {{-- MODAL: ADICIONAR / EDITAR ITEM                                   --}}
    {{-- ================================================================ --}}

    @if ($showForm)
    <div class="mir-modal-overlay" tabindex="-1">
        <div class="mir-modal-dialog">
            <div class="mir-modal-content">

                {{-- HEADER --}}
                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <span class="mir-modal-icon {{ $editingId ? 'mir-modal-icon-edit' : 'mir-modal-icon-add' }}">
                            <i class="fa fa-{{ $editingId ? 'edit' : 'plus' }}"></i>
                        </span>
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
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                {{-- BODY --}}
                <div class="mir-modal-body">
                    <div class="row">

                        {{-- Título --}}
                        <div class="col-md-6 mb-3">
                            <label class="mir-label">Título <span class="mir-required">*</span></label>
                            <input type="text"
                                class="mir-input @error('title') is-invalid @enderror"
                                wire:model="title"
                                placeholder="Ex: Sobre nós">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- URL --}}
                        <div class="col-md-6 mb-3">
                            <label class="mir-label">URL <span class="mir-required">*</span></label>
                            <input type="text"
                                class="mir-input @error('url') is-invalid @enderror"
                                wire:model="url"
                                placeholder="/sobre ou https://...">
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Abrir em --}}
                        <div class="col-md-4 mb-3">
                            <label class="mir-label">Abrir em</label>
                            <select class="mir-input" wire:model="target">
                                <option value="_self">Mesma aba</option>
                                <option value="_blank">Nova aba</option>
                            </select>
                        </div>

                        {{-- Item pai --}}
                        <div class="col-md-4 mb-3">
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
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ativo --}}
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="mir-switch-wrap mt-3">
                                <input type="checkbox"
                                    class="mir-switch-input"
                                    id="is_active"
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

                {{-- FOOTER --}}
                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" wire:click="cancelForm">
                        Cancelar
                    </button>
                    <button class="mir-btn-primary-lg"
                            wire:click="save"
                            wire:loading.attr="disabled">
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm mr-1"></span>
                        </span>
                        <i class="fa fa-save" wire:loading.remove wire:target="save"></i>
                        {{ $editingId ? 'Salvar alterações' : 'Adicionar item' }}
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif


    {{-- ================================================================ --}}
    {{-- ÁRVORE DE ITENS                                                  --}}
    {{-- ================================================================ --}}

    @if ($items->isEmpty())

        <div class="mir-empty-state">
            <div class="mir-empty-icon">
                <i class="fa fa-sitemap"></i>
            </div>
            <h5 class="mir-empty-title">Seu menu está vazio</h5>
            <p class="mir-empty-desc">Comece adicionando o primeiro item ao menu.</p>
            <button class="mir-btn-primary-lg" wire:click="openAddForm(null)">
                <i class="fa fa-plus"></i> Adicionar primeiro item
            </button>
        </div>

    @else
        <div class="mir-card">
            <div class="mir-tree" data-sortable data-parent-id="">
                @foreach ($items as $item)
                    @include('livewire.admin.menu-item-row', [
                        'item'  => $item,
                        'depth' => 0,
                    ])
                @endforeach
            </div>
        </div>
    @endif



    {{-- ================================================================ --}}
    {{-- SCRIPTS: SortableJS + Livewire                                   --}}
    {{-- ================================================================ --}}

    @push('scripts')
    <script>
    /* ─── Toast ─────────────────────────────────────────────────────── */
    function showToast(type, message) {
        const container = document.getElementById('mir-toast-container');
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

    /* ─── Sortable ──────────────────────────────────────────────────── */
    document.addEventListener('livewire:initialized', () => {
        initSortable();

        // Ouve eventos do backend
        Livewire.on('notify', ({ type, message }) => showToast(type, message));

        Livewire.hook('morph.updated', () => { initSortable(); });
    });

    function initSortable() {
        document.querySelectorAll('[data-sortable]').forEach(container => {
            if (container._sortable) container._sortable.destroy();

            container._sortable = new Sortable(container, {
                group: 'menu-items',
                animation: 150,
                handle: '.mir-handle',
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

                    // 🔹 Atualiza classe depth
                    item.className = item.className.replace(/depth-\d+/g, '');
                    item.classList.add(`depth-${newDepth}`);

                    // 🔹 Atualiza badge visualmente
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
        const badge = item.querySelector('.mir-badge');
        if (!badge) return;

        // Remove classes antigas
        badge.className = 'mir-badge';

        let label = 'Raiz';
        let className = 'mir-badge-root';

        if (depth > 0) {
            label = `Sub ${depth}`;
            className = `mir-badge-sub${depth}`;
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
        const seen   = new Set(); // evita duplicatas quando um item aparece em múltiplos containers

        // Percorre do container raiz para os filhos — a ordem importa
        document.querySelectorAll('[data-sortable]').forEach(container => {
            const rawParent = container.dataset.parentId;
            const parentId  = rawParent !== '' ? parseInt(rawParent) : null;

            Array.from(container.children)
                .filter(el => el.dataset.itemId)
                .forEach((el, index) => {
                    const id = parseInt(el.dataset.itemId);
                    if (seen.has(id)) return; // pula se já registrado
                    seen.add(id);
                    result.push({ id, parent_id: parentId, order: index });
                });
        });

        return result;
    }
    </script>
    @endpush

</div>