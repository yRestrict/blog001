{{--
    Livewire View: livewire/admin/menus.blade.php
--}}

<div>

    {{-- ================================================================ --}}
    {{-- ALERTAS DE FEEDBACK                                              --}}
    {{-- ================================================================ --}}

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif


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
                            <div class="custom-control custom-switch mt-3">
                                <input type="checkbox"
                                    class="custom-control-input"
                                    id="is_active"
                                    wire:model="is_active">
                                <label class="custom-control-label" for="is_active">Item ativo</label>
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
            <ul class="mir-tree" data-sortable data-parent-id="">
                @foreach ($items as $item)
                    @include('livewire.admin.menu-item-row', [
                        'item'  => $item,
                        'depth' => 0,
                    ])
                @endforeach
            </ul>
        </div>

    @endif


    {{-- ================================================================ --}}
    {{-- ESTILOS                                                          --}}
    {{-- ================================================================ --}}
    <style>
    /* ─── Card wrapper ───────────────────────────────────────────────── */
    .mir-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
    }

    .mir-tree {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    /* ─── Botão primário grande ──────────────────────────────────────── */
    .mir-btn-primary-lg {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 36px;
        padding: 0 16px;
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 160ms ease, box-shadow 160ms ease;
    }
    .mir-btn-primary-lg:hover {
        background: #1d4ed8;
        box-shadow: 0 4px 12px rgba(37,99,235,.3);
        color: #fff;
        text-decoration: none;
    }

    /* ─── Botão ghost ────────────────────────────────────────────────── */
    .mir-btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 36px;
        padding: 0 16px;
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e3e8ef;
        border-radius: 8px;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 160ms ease;
    }
    .mir-btn-ghost:hover {
        background: #e2e8f0;
        color: #1a2332;
    }

    /* ─── Modal ──────────────────────────────────────────────────────── */
    .mir-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .55);
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        backdrop-filter: blur(2px);
    }

    .mir-modal-dialog {
        width: 100%;
        max-width: 640px;
    }

    .mir-modal-content {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 24px 64px rgba(0,0,0,.18);
        overflow: hidden;
    }

    .mir-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .mir-modal-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .mir-modal-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
        flex-shrink: 0;
    }

    .mir-modal-icon-add  { background: #dcfce7; color: #16a34a; }
    .mir-modal-icon-edit { background: #dbeafe; color: #2563eb; }

    .mir-modal-title-text {
        font-size: .95rem;
        font-weight: 700;
        color: #1a2332;
        line-height: 1.2;
    }

    .mir-modal-subtitle {
        font-size: .75rem;
        color: #8494a9;
        margin-top: 2px;
    }

    .mir-modal-close {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #e3e8ef;
        background: #f8fafc;
        color: #8494a9;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 160ms ease;
        flex-shrink: 0;
    }
    .mir-modal-close:hover { background: #fee2e2; color: #ef4444; border-color: #fecaca; }

    .mir-modal-body { padding: 20px 24px; }

    .mir-modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        padding: 16px 24px 20px;
        border-top: 1px solid #f1f5f9;
    }

    /* ─── Form inputs ────────────────────────────────────────────────── */
    .mir-label {
        display: block;
        font-size: .8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
    }

    .mir-required { color: #ef4444; }

    .mir-input {
        display: block;
        width: 100%;
        height: 38px;
        padding: 0 12px;
        background: #f8fafc;
        border: 1px solid #e3e8ef;
        border-radius: 8px;
        font-size: .85rem;
        color: #1a2332;
        transition: border-color 160ms ease, box-shadow 160ms ease;
        appearance: none;
    }

    select.mir-input { height: 38px; }

    .mir-input:focus {
        outline: none;
        border-color: #93c5fd;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    }

    .mir-input.is-invalid {
        border-color: #fca5a5;
    }

    /* ─── Empty state ────────────────────────────────────────────────── */
    .mir-empty-state {
        border: 2px dashed #e3e8ef;
        border-radius: 14px;
        background: #f8fafc;
        padding: 52px 24px;
        text-align: center;
    }

    .mir-empty-icon {
        width: 60px;
        height: 60px;
        background: #eff6ff;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        font-size: 1.5rem;
        color: #93c5fd;
    }

    .mir-empty-title {
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 6px;
    }

    .mir-empty-desc {
        font-size: .85rem;
        color: #8494a9;
        margin-bottom: 20px;
    }
    </style>


    {{-- ================================================================ --}}
    {{-- SCRIPTS: SortableJS + Livewire                                   --}}
    {{-- ================================================================ --}}

    @push('scripts')
    <script>
    document.addEventListener('livewire:initialized', () => {
        initSortable();
        Livewire.hook('morph.updated', () => { initSortable(); });
    });

    function initSortable() {
        document.querySelectorAll('[data-sortable]').forEach(container => {
            if (container._sortable) container._sortable.destroy();
            container._sortable = new Sortable(container, {
                group:       'menu-items',
                animation:   150,
                handle:      '.mir-handle',
                ghostClass:  'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd() {
                    const orderedItems = collectAllItems();
                    @this.dispatch('items-reordered', { orderedItems });
                }
            });
        });
    }

    function collectAllItems() {
        const result = [];
        document.querySelectorAll('[data-sortable]').forEach(container => {
            const rawParent = container.dataset.parentId;
            const parentId  = rawParent !== '' ? parseInt(rawParent) : null;
            Array.from(container.children)
                .filter(el => el.dataset.itemId)
                .forEach((el, index) => {
                    result.push({
                        id:        parseInt(el.dataset.itemId),
                        parent_id: parentId,
                        order:     index,
                    });
                });
        });
        return result;
    }
    </script>
    @endpush

</div>