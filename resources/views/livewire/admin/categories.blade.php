{{-- livewire/admin/categories.blade.php --}}
<div>

    {{-- ================================================================ --}}
    {{-- ESTILOS: Mesmo padrão mir- do menu                               --}}
    {{-- ================================================================ --}}
    <style>

        /* ── Seções ─────────────────────────────────────────────────── */
        .cat-section {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .cat-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        .cat-section-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1a1d23;
            margin: 0;
        }
        .cat-section-sub {
            font-size: .78rem;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* ── Rows sortable ──────────────────────────────────────────── */
        .cat-list { padding: 8px 0; }

        .cat-row {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            border-bottom: 1px solid #f5f5f5;
            transition: background .15s;
        }
        .cat-row:last-child { border-bottom: none; }
        .cat-row:hover { background: #f9fafb; }

        .cat-handle {
            cursor: grab;
            color: #c9cdd4;
            padding: 4px 10px 4px 0;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            transition: color .15s;
        }
        .cat-handle:hover { color: #6366f1; }
        .cat-row.sortable-chosen .cat-handle { cursor: grabbing; }

        .cat-body { flex: 1; min-width: 0; }
        .cat-name {
            font-size: .875rem;
            font-weight: 600;
            color: #1a1d23;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cat-slug {
            font-size: .72rem;
            color: #9ca3af;
            font-family: ui-monospace, monospace;
            margin-top: 1px;
        }

        .cat-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            margin: 0 20px;
        }

        /* Badges */
        .mir-badge-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            padding: 0 7px;
            border-radius: 50px;
            font-size: .72rem;
            font-weight: 700;
            background: #ede9fe;
            color: #6d28d9;
        }
        .mir-badge-parent {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 50px;
            font-size: .72rem;
            font-weight: 600;
            background: #d1fae5;
            color: #065f46;
        }
        .mir-badge-none { font-size: .75rem; color: #c4c8cf; }

        /* Status pill (idêntico ao menu) */
        .mir-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: .72rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background .15s;
            flex-shrink: 0;
        }
        .mir-status.is-active  { background: #d1fae5; color: #065f46; }
        .mir-status.is-active:hover  { background: #a7f3d0; }
        .mir-status.is-inactive { background: #fee2e2; color: #991b1b; }
        .mir-status.is-inactive:hover { background: #fecaca; }
        .mir-status-ring { width: 7px; height: 7px; border-radius: 50%; }
        .is-active .mir-status-ring  { background: #10b981; }
        .is-inactive .mir-status-ring { background: #ef4444; }

        /* Divider */
        .mir-divider {
            width: 1px;
            height: 28px;
            background: #e9ecef;
            margin: 0 10px;
            flex-shrink: 0;
        }

        /* Botões de ação */
        .mir-actions { display: flex; align-items: center; gap: 4px; flex-shrink: 0; }
        .mir-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 6px;
            border: 1px solid transparent;
            background: transparent;
            cursor: pointer;
            transition: background .15s, border-color .15s, color .15s;
            color: #6d7279;
        }
        .mir-action-edit:hover   { background: #ede9fe; border-color: #c4b5fd; color: #5b21b6; }
        .mir-action-delete:hover { background: #fee2e2; border-color: #fca5a5; color: #b91c1c; }

        /* Empty state */
        .mir-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
            text-align: center;
        }
        .mir-empty-icon {
            width: 56px; height: 56px;
            border-radius: 14px;
            background: #f3f4f6;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: #9ca3af;
            margin-bottom: 14px;
        }
        .mir-empty-title { font-size: .95rem; font-weight: 700; color: #374151; margin: 0 0 6px; }
        .mir-empty-desc  { font-size: .82rem; color: #9ca3af; margin: 0 0 16px; }

        /* Botões primários */
        .mir-btn-primary-lg {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            font-size: .82rem; font-weight: 600;
            border: none; cursor: pointer;
            transition: opacity .15s;
            box-shadow: 0 2px 8px rgba(99,102,241,.35);
        }
        .mir-btn-primary-lg:hover { opacity: .9; }

        .mir-btn-ghost {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            background: transparent;
            color: #6d7279;
            font-size: .82rem; font-weight: 600;
            border: 1px solid #e0e0e0;
            cursor: pointer;
            transition: background .15s;
        }
        .mir-btn-ghost:hover { background: #f5f5f5; }

        /* Modais (idêntico ao menu) */
        .mir-modal-overlay {
            position: fixed; inset: 0;
            background: rgba(17,24,39,.55);
            backdrop-filter: blur(2px);
            z-index: 1060;
            display: flex; align-items: center; justify-content: center;
            padding: 16px;
        }
        .mir-modal-dialog { width: 100%; max-width: 540px; animation: mir-modal-in .2s ease; }
        @keyframes mir-modal-in {
            from { opacity: 0; transform: translateY(-12px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .mir-modal-content {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0,0,0,.18);
            overflow: hidden;
        }
        .mir-modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 22px;
            border-bottom: 1px solid #f0f0f0;
        }
        .mir-modal-title { display: flex; align-items: center; gap: 12px; }
        .mir-modal-icon {
            width: 36px; height: 36px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem; flex-shrink: 0;
        }
        .mir-modal-icon-add    { background: rgba(99,102,241,.12);  color: #6366f1; }
        .mir-modal-icon-edit   { background: rgba(16,185,129,.12);  color: #059669; }
        .mir-modal-icon-delete { background: rgba(239,68,68,.12);   color: #ef4444; }
        .mir-modal-title-text  { font-size: .93rem; font-weight: 700; color: #1a1d23; }
        .mir-modal-subtitle    { font-size: .75rem; color: #9ca3af; margin-top: 1px; }
        .mir-modal-close {
            width: 32px; height: 32px; border-radius: 8px;
            border: none; background: transparent; color: #9ca3af;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: background .15s, color .15s; font-size: .9rem;
        }
        .mir-modal-close:hover { background: #f3f4f6; color: #374151; }
        .mir-modal-body   { padding: 22px; }
        .mir-modal-footer {
            display: flex; justify-content: flex-end; gap: 10px;
            padding: 16px 22px;
            border-top: 1px solid #f0f0f0;
            background: #fafafa;
        }

        /* Inputs */
        .mir-label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .mir-required { color: #ef4444; }
        .mir-input {
            width: 100%; padding: 8px 12px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: .85rem; color: #1a1d23;
            background: #fff; outline: none;
            transition: border-color .15s, box-shadow .15s;
            appearance: none;
        }
        .mir-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        .mir-input.is-invalid { border-color: #ef4444; }
        .mir-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }
        .mir-field-error { font-size: .78rem; color: #ef4444; margin-top: 4px; }

        /* Switch */
        .mir-switch-wrap { display: flex; align-items: center; }
        .mir-switch-input { display: none; }
        .mir-switch-label { display: inline-flex; align-items: center; gap: 10px; cursor: pointer; }
        .mir-switch-track {
            position: relative; width: 38px; height: 22px;
            border-radius: 50px; background: #d1d5db;
            transition: background .2s; flex-shrink: 0;
        }
        .mir-switch-input:checked + .mir-switch-label .mir-switch-track { background: #6366f1; }
        .mir-switch-thumb {
            position: absolute; top: 3px; left: 3px;
            width: 16px; height: 16px; border-radius: 50%;
            background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.2);
            transition: left .2s;
        }
        .mir-switch-text { font-size: .82rem; font-weight: 600; color: #374151; }

        /* Toast */
        #cat-toast-container {
            position: fixed; bottom: 24px; right: 24px;
            z-index: 9999;
            display: flex; flex-direction: column; gap: 10px;
        }
        .mir-toast {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 16px; border-radius: 10px;
            font-size: .83rem; font-weight: 500;
            box-shadow: 0 4px 20px rgba(0,0,0,.14);
            animation: mir-toast-in .25s ease;
            min-width: 240px; max-width: 340px;
        }
        @keyframes mir-toast-in  { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        @keyframes mir-toast-out { to { opacity:0; transform:translateY(12px); } }
        .mir-toast-success { background: #d1fae5; color: #065f46; }
        .mir-toast-error   { background: #fee2e2; color: #991b1b; }
        .mir-toast-info    { background: #ede9fe; color: #4c1d95; }
        .mir-toast-icon    { font-size: 1rem; flex-shrink: 0; }

        /* Sortable */
        .sortable-ghost  { opacity: .4; background: #ede9fe !important; border-radius: 8px; }
        .sortable-chosen { box-shadow: 0 4px 18px rgba(99,102,241,.18); border-radius: 8px; }

    </style>

    {{-- ================================================================ --}}
    {{-- TOAST                                                             --}}
    {{-- ================================================================ --}}
    <div id="cat-toast-container" aria-live="polite"></div>


    {{-- ================================================================ --}}
    {{-- SEÇÃO: CATEGORIAS PAI                                             --}}
    {{-- ================================================================ --}}
    <div class="cat-section">
        <div class="cat-section-header">
            <div>
                <div class="cat-section-title">Categorias Pai</div>
                <div class="cat-section-sub">Agrupe suas categorias em grupos maiores</div>
            </div>
            <button class="mir-btn-primary-lg" wire:click="openAddParentCategory">
                <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                    <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Nova Cat. Pai
            </button>
        </div>

        @if ($parentCategories->isEmpty())
            <div class="mir-empty-state">
                <div class="mir-empty-icon"><i class="fa fa-folder-open"></i></div>
                <h5 class="mir-empty-title">Nenhuma categoria pai</h5>
                <p class="mir-empty-desc">Crie a primeira para começar a organizar.</p>
                <button class="mir-btn-primary-lg" wire:click="openAddParentCategory">
                    <i class="fa fa-plus"></i> Criar agora
                </button>
            </div>
        @else
            <div class="cat-list" id="sortable-parent-categories">
                @foreach ($parentCategories as $item)
                    <div class="cat-row"
                         wire:key="parent-cat-{{ $item->id }}"
                         data-id="{{ $item->id }}">

                        <div class="cat-handle" title="Arrastar para reordenar">
                            <svg width="8" height="14" viewBox="0 0 8 14" fill="none">
                                <circle cx="2" cy="2"  r="1.5" fill="currentColor"/>
                                <circle cx="6" cy="2"  r="1.5" fill="currentColor"/>
                                <circle cx="2" cy="7"  r="1.5" fill="currentColor"/>
                                <circle cx="6" cy="7"  r="1.5" fill="currentColor"/>
                                <circle cx="2" cy="12" r="1.5" fill="currentColor"/>
                                <circle cx="6" cy="12" r="1.5" fill="currentColor"/>
                            </svg>
                        </div>

                        <div class="cat-body">
                            <div class="cat-name">{{ $item->name }}</div>
                            <div class="cat-slug">{{ $item->slug }}</div>
                        </div>

                        <div class="cat-meta">
                            <span class="mir-badge-count" title="Nº de categorias filhas">
                                {{ $item->categories_count }}
                            </span>
                        </div>

                        <div class="mir-divider"></div>

                        <div class="mir-actions">
                            <button class="mir-action-btn mir-action-edit"
                                    wire:click="openEditParentCategory({{ $item->id }})"
                                    title="Editar">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                    <path d="M9 2l2 2-7.5 7.5H1.5v-2L9 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button class="mir-action-btn mir-action-delete"
                                    wire:click="$dispatch('confirm-delete-parent', { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}' })"
                                    title="Excluir">
                                <svg width="12" height="13" viewBox="0 0 12 14" fill="none">
                                    <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>


    {{-- ================================================================ --}}
    {{-- SEÇÃO: CATEGORIAS                                                 --}}
    {{-- ================================================================ --}}
    <div class="cat-section">
        <div class="cat-section-header">
            <div>
                <div class="cat-section-title">Categorias</div>
                <div class="cat-section-sub">Gerencie todas as categorias do sistema</div>
            </div>
            <button class="mir-btn-primary-lg" wire:click="openAddCategory">
                <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                    <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Nova Categoria
            </button>
        </div>

        @if ($categories->isEmpty())
            <div class="mir-empty-state">
                <div class="mir-empty-icon"><i class="fa fa-tags"></i></div>
                <h5 class="mir-empty-title">Nenhuma categoria</h5>
                <p class="mir-empty-desc">Adicione categorias para organizar o seu conteúdo.</p>
                <button class="mir-btn-primary-lg" wire:click="openAddCategory">
                    <i class="fa fa-plus"></i> Criar agora
                </button>
            </div>
        @else
            <div class="cat-list" id="sortable-categories">
                @foreach ($categories as $category)
                    <div class="cat-row"
                         wire:key="cat-{{ $category->id }}"
                         data-id="{{ $category->id }}">

                        <div class="cat-handle" title="Arrastar para reordenar">
                            <svg width="8" height="14" viewBox="0 0 8 14" fill="none">
                                <circle cx="2" cy="2"  r="1.5" fill="currentColor"/>
                                <circle cx="6" cy="2"  r="1.5" fill="currentColor"/>
                                <circle cx="2" cy="7"  r="1.5" fill="currentColor"/>
                                <circle cx="6" cy="7"  r="1.5" fill="currentColor"/>
                                <circle cx="2" cy="12" r="1.5" fill="currentColor"/>
                                <circle cx="6" cy="12" r="1.5" fill="currentColor"/>
                            </svg>
                        </div>

                        <div class="cat-body">
                            <div class="cat-name">{{ $category->name }}</div>
                            <div class="cat-slug">{{ $category->slug }}</div>
                        </div>

                        <div class="cat-meta">
                            @if ($category->parentCategory)
                                <span class="mir-badge-parent">
                                    <svg width="8" height="8" viewBox="0 0 8 8" fill="none">
                                        <path d="M1 1h2.5v2.5M1 7h6V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    {{ $category->parentCategory->name }}
                                </span>
                            @else
                                <span class="mir-badge-none">— sem pai —</span>
                            @endif
                        </div>

                        <div class="mir-divider"></div>

                        <button class="mir-status {{ $category->status ? 'is-active' : 'is-inactive' }}"
                                wire:click="toggleCategoryStatus({{ $category->id }})"
                                title="{{ $category->status ? 'Clique para desativar' : 'Clique para ativar' }}">
                            <span class="mir-status-ring"></span>
                            <span>{{ $category->status ? 'Ativo' : 'Inativo' }}</span>
                        </button>

                        <div class="mir-divider"></div>

                        <div class="mir-actions">
                            <button class="mir-action-btn mir-action-edit"
                                    wire:click="openEditCategory({{ $category->id }})"
                                    title="Editar">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                    <path d="M9 2l2 2-7.5 7.5H1.5v-2L9 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button class="mir-action-btn mir-action-delete"
                                    wire:click="$dispatch('confirm-delete-category', { id: {{ $category->id }}, name: '{{ addslashes($category->name) }}' })"
                                    title="Excluir">
                                <svg width="12" height="13" viewBox="0 0 12 14" fill="none">
                                    <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>


    {{-- ================================================================ --}}
    {{-- MODAL: CATEGORIA PAI (Add / Edit)                                 --}}
    {{-- ================================================================ --}}
    @if ($showParentCategoryModal)
    <div class="mir-modal-overlay">
        <div class="mir-modal-dialog">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <span class="mir-modal-icon {{ $isEditingParent ? 'mir-modal-icon-edit' : 'mir-modal-icon-add' }}">
                            <i class="fa fa-{{ $isEditingParent ? 'edit' : 'plus' }}"></i>
                        </span>
                        <div>
                            <div class="mir-modal-title-text">
                                {{ $isEditingParent ? 'Editar Categoria Pai' : 'Nova Categoria Pai' }}
                            </div>
                            <div class="mir-modal-subtitle">
                                {{ $isEditingParent ? 'Atualize o nome da categoria' : 'Crie um novo grupo para organizar categorias' }}
                            </div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" wire:click="closeParentCategoryModal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <div class="mir-modal-body">
                    <div class="mb-1">
                        <label class="mir-label">Nome <span class="mir-required">*</span></label>
                        <input type="text"
                               class="mir-input @error('parentCategoryName') is-invalid @enderror"
                               wire:model.live="parentCategoryName"
                               placeholder="Ex: Tecnologia, Tutoriais..."
                               wire:keydown.enter="saveParentCategory">
                        @error('parentCategoryName')
                            <div class="mir-field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" wire:click="closeParentCategoryModal">
                        Cancelar
                    </button>
                    <button class="mir-btn-primary-lg"
                            wire:click="saveParentCategory"
                            wire:loading.attr="disabled"
                            wire:target="saveParentCategory">
                        <span wire:loading wire:target="saveParentCategory">
                            <span class="spinner-border spinner-border-sm mr-1"></span>
                        </span>
                        <i class="fa fa-save" wire:loading.remove wire:target="saveParentCategory"></i>
                        {{ $isEditingParent ? 'Salvar alterações' : 'Criar categoria pai' }}
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif


    {{-- ================================================================ --}}
    {{-- MODAL: CATEGORIA (Add / Edit)                                     --}}
    {{-- ================================================================ --}}
    @if ($showCategoryModal)
    <div class="mir-modal-overlay">
        <div class="mir-modal-dialog">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <span class="mir-modal-icon {{ $isEditingCategory ? 'mir-modal-icon-edit' : 'mir-modal-icon-add' }}">
                            <i class="fa fa-{{ $isEditingCategory ? 'edit' : 'plus' }}"></i>
                        </span>
                        <div>
                            <div class="mir-modal-title-text">
                                {{ $isEditingCategory ? 'Editar Categoria' : 'Nova Categoria' }}
                            </div>
                            <div class="mir-modal-subtitle">
                                {{ $isEditingCategory ? 'Atualize os dados da categoria' : 'Preencha os dados da nova categoria' }}
                            </div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" wire:click="closeCategoryModal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <div class="mir-modal-body">
                    <div class="row">

                        <div class="col-12 mb-3">
                            <label class="mir-label">Nome <span class="mir-required">*</span></label>
                            <input type="text"
                                   class="mir-input @error('categoryName') is-invalid @enderror"
                                   wire:model.live="categoryName"
                                   placeholder="Ex: Smartphones, Design Gráfico...">
                            @error('categoryName')
                                <div class="mir-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-7 mb-3">
                            <label class="mir-label">Categoria Pai</label>
                            <select class="mir-input @error('categoryParentId') is-invalid @enderror"
                                    wire:model="categoryParentId">
                                <option value="">— Nenhuma —</option>
                                @foreach ($allParentCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            @error('categoryParentId')
                                <div class="mir-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-5 mb-3 d-flex align-items-end" style="padding-bottom: 10px;">
                            <div class="mir-switch-wrap">
                                <input type="checkbox"
                                       class="mir-switch-input"
                                       id="cat_status"
                                       wire:model="categoryStatus">
                                <label class="mir-switch-label" for="cat_status">
                                    <span class="mir-switch-track">
                                        <span class="mir-switch-thumb"></span>
                                    </span>
                                    <span class="mir-switch-text">Categoria ativa</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-12 mb-1">
                            <label class="mir-label">
                                Descrição
                                <span style="color:#9ca3af; font-weight:400;">(opcional)</span>
                            </label>
                            <textarea class="mir-input"
                                      wire:model="categoryDescription"
                                      rows="3"
                                      style="resize:vertical;"
                                      placeholder="Descrição breve da categoria..."></textarea>
                        </div>

                    </div>
                </div>

                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" wire:click="closeCategoryModal">
                        Cancelar
                    </button>
                    <button class="mir-btn-primary-lg"
                            wire:click="saveCategory"
                            wire:loading.attr="disabled"
                            wire:target="saveCategory">
                        <span wire:loading wire:target="saveCategory">
                            <span class="spinner-border spinner-border-sm mr-1"></span>
                        </span>
                        <i class="fa fa-save" wire:loading.remove wire:target="saveCategory"></i>
                        {{ $isEditingCategory ? 'Salvar alterações' : 'Criar categoria' }}
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif


    {{-- ================================================================ --}}
    {{-- MODAL: CONFIRMAÇÃO EXCLUSÃO — CATEGORIA PAI (Alpine.js)           --}}
    {{-- ================================================================ --}}
    <div x-data="{ show: false, itemId: null, itemName: '' }"
         x-on:confirm-delete-parent.window="itemId = $event.detail.id; itemName = $event.detail.name; show = true"
         x-show="show"
         x-cloak
         class="mir-modal-overlay"
         tabindex="-1">

        <div class="mir-modal-dialog">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <span class="mir-modal-icon mir-modal-icon-delete">
                            <i class="fa fa-trash"></i>
                        </span>
                        <div>
                            <div class="mir-modal-title-text">Excluir Categoria Pai</div>
                            <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" x-on:click="show = false">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <div class="mir-modal-body">
                    <p style="color:#6d7279; font-size:.9rem; line-height:1.6; margin:0;">
                        Tem certeza que deseja excluir a categoria pai
                        <strong style="color:#ee0b0b;" x-text="itemName"></strong>?
                        <br>
                        <span style="font-size:.8rem; color:#9ca3af; margin-top:6px; display:block;">
                            As categorias filhas serão desvinculadas, mas não excluídas.
                        </span>
                    </p>
                </div>

                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" x-on:click="show = false">Cancelar</button>
                    <button class="mir-btn-primary-lg"
                            style="background:linear-gradient(135deg,#ef4444,#dc2626); box-shadow:0 2px 8px rgba(239,68,68,.35);"
                            x-on:click="$wire.deleteParentCategory(itemId); show = false">
                        <i class="fa fa-trash"></i> Sim, excluir
                    </button>
                </div>

            </div>
        </div>
    </div>


    {{-- ================================================================ --}}
    {{-- MODAL: CONFIRMAÇÃO EXCLUSÃO — CATEGORIA (Alpine.js)               --}}
    {{-- ================================================================ --}}
    <div x-data="{ show: false, itemId: null, itemName: '' }"
         x-on:confirm-delete-category.window="itemId = $event.detail.id; itemName = $event.detail.name; show = true"
         x-show="show"
         x-cloak
         class="mir-modal-overlay"
         tabindex="-1">

        <div class="mir-modal-dialog">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <span class="mir-modal-icon mir-modal-icon-delete">
                            <i class="fa fa-trash"></i>
                        </span>
                        <div>
                            <div class="mir-modal-title-text">Excluir Categoria</div>
                            <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" x-on:click="show = false">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <div class="mir-modal-body">
                    <p style="color:#6d7279; font-size:.9rem; line-height:1.6; margin:0;">
                        Tem certeza que deseja excluir a categoria
                        <strong style="color:#ee0b0b;" x-text="itemName"></strong>?
                    </p>
                </div>

                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" x-on:click="show = false">Cancelar</button>
                    <button class="mir-btn-primary-lg"
                            style="background:linear-gradient(135deg,#ef4444,#dc2626); box-shadow:0 2px 8px rgba(239,68,68,.35);"
                            x-on:click="$wire.deleteCategory(itemId); show = false">
                        <i class="fa fa-trash"></i> Sim, excluir
                    </button>
                </div>

            </div>
        </div>
    </div>


    {{-- ================================================================ --}}
    {{-- SCRIPTS: Toast + Sortable (idêntico ao padrão do menu)           --}}
    {{-- ================================================================ --}}
    @push('scripts')
    <script>
    /* ─── Toast ─────────────────────────────────────────────────────── */
    function catShowToast(type, message) {
        const container = document.getElementById('cat-toast-container');
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
        initCatSortable();

        Livewire.on('notify', ({ type, message }) => catShowToast(type, message));
        Livewire.hook('morph.updated', () => { initCatSortable(); });
    });

    function initCatSortable() {
        // Categorias Pai
        const parentList = document.getElementById('sortable-parent-categories');
        if (parentList) {
            if (parentList._sortable) parentList._sortable.destroy();
            parentList._sortable = new Sortable(parentList, {
                animation: 150,
                handle: '.cat-handle',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd() {
                    const ordered = Array.from(parentList.children)
                        .filter(el => el.dataset.id)
                        .map((el, index) => ({ id: parseInt(el.dataset.id), ordering: index + 1 }));
                    @this.reorderParentCategories(ordered);
                    catShowToast('info', 'Ordem atualizada');
                }
            });
        }

        // Categorias
        const catList = document.getElementById('sortable-categories');
        if (catList) {
            if (catList._sortable) catList._sortable.destroy();
            catList._sortable = new Sortable(catList, {
                animation: 150,
                handle: '.cat-handle',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd() {
                    const ordered = Array.from(catList.children)
                        .filter(el => el.dataset.id)
                        .map((el, index) => ({ id: parseInt(el.dataset.id), ordering: index + 1 }));
                    @this.reorderCategories(ordered);
                    catShowToast('info', 'Ordem atualizada');
                }
            });
        }
    }
    </script>
    @endpush

</div>