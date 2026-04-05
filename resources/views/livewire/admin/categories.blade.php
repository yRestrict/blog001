<div>

    {{-- ================================================================ --}}
    {{-- PAGE HEADER ACTION                                               --}}
    {{-- ================================================================ --}}
    <div class="page-header-action">
        <div class="page-header-left">
            <h1 class="page-header-title">
                Categorias
                <span class="page-header-title-count">{{ $allCategories }}</span>
            </h1>
            <span class="page-header-sub">Gerencie categorias pai e categorias do sistema</span>
        </div>
        @if($isOwner)
            <div class="page-header-right">
                <a href="{{ route('admin.categories.trash') }}" class="mir-btn-neutral">
                    <i class="fa-solid fa-trash-can"></i> Lixeira
                </a>
            </div>
        @endif
    </div>

    {{-- ================================================================ --}}
    {{-- SEÇÃO: CATEGORIAS PAI                                             --}}
    {{-- ================================================================ --}}
    <div class="cat-section">
        <div class="cat-section-header">
            <div class="cat-section-header-left">
                <h2 class="cat-section-title">Categorias Pai</h2>
                <span class="cat-section-sub">Agrupe suas categorias em grupos maiores</span>
            </div>
            @if($canCreate)
                <button class="mir-btn-primary-lg" wire:click="openAddParentCategory">
                    <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                        <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Nova Cat. Pai
                </button>
            @endif
        </div>

        {{-- Filter Header --}}
        <div class="cat-filter-header">
            <div style="position:relative; flex:1; max-width:280px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.75rem;"></i>
                <input type="text" wire:model.live.debounce.300ms="searchParent" class="mir-input" placeholder="Pesquisar categorias pai..." style="padding-left:32px;">
            </div>
        </div>

        {{-- Loading bar --}}
        <div wire:loading class="mir-loading-bar"></div>

        {{-- Table Header --}}
        <div class="mir-table-header">
            <span class="cat-plh-handle"></span>
            <span class="cat-plh-body">Nome</span>
            <span class="cat-plh-meta" style="text-align:right;">Filhas</span>
            <span class="plh-divider"></span>
            <span class="cat-plh-actions" style="text-align:center;">Ações</span>
        </div>

        @if ($parentCategories->isEmpty())
            <div class="mir-empty-state">
                <div class="mir-empty-icon"><i class="fa-solid fa-folder-open"></i></div>
                @if ($searchParent)
                    <h5 class="mir-empty-title">Nenhuma categoria pai encontrada</h5>
                    <p class="mir-empty-desc">Tente ajustar o termo de busca.</p>
                @else
                    <h5 class="mir-empty-title">Nenhuma categoria pai</h5>
                    <p class="mir-empty-desc">Crie a primeira para começar a organizar.</p>
                @endif
            </div>
        @else
            <div class="mir-data-list" id="sortable-parent-categories" wire:loading.class="mir-loading-overlay">
                @foreach ($parentCategories as $item)
                    <div class="mir-data-row"
                         wire:key="parent-cat-{{ $item->id }}"
                         data-id="{{ $item->id }}">

                        <div class="cat-handle">
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
                            <span class="mir-badge-count" data-tooltip="{{ $item->categories_count }} categorias filhas">
                                {{ $item->categories_count }}
                            </span>
                        </div>

                        <div class="mir-divider"></div>

                        <div class="mir-actions">
                            @if($canCreate)
                                <button class="mir-action-btn mir-action-edit"
                                        wire:click="openEditParentCategory({{ $item->id }})"
                                        data-tooltip="Editar">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                        <path d="M9 2l2 2-7.5 7.5H1.5v-2L9 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            @endif
                            @if($canDelete)
                                <button class="mir-action-btn mir-action-delete"
                                        wire:click="$dispatch('confirm-delete-parent', { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}' })"
                                        data-tooltip="Excluir">
                                    <svg width="12" height="13" viewBox="0 0 12 14" fill="none">
                                        <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            @endif
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
            <div class="cat-section-header-left">
                <h2 class="cat-section-title">Categorias</h2>
                <span class="cat-section-sub">Gerencie todas as categorias do sistema</span>
            </div>
            @if($canCreate)
                <button class="mir-btn-primary-lg" wire:click="openAddCategory">
                    <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                        <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Nova Categoria
                </button>
            @endif
        </div>

        {{-- Filter Header --}}
        <div class="cat-filter-header">
            <div style="position:relative; flex:1; max-width:280px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.75rem;"></i>
                <input type="text" wire:model.live.debounce.300ms="searchCategory" class="mir-input" placeholder="Pesquisar categorias..." style="padding-left:32px;">
            </div>
            <select wire:model.live="filterStatus" class="mir-input" style="width:160px;">
                <option value="">Todos os status</option>
                <option value="active">Ativo</option>
                <option value="inactive">Inativo</option>
            </select>
            <select wire:model.live="filterParent" class="mir-input" style="width:180px;">
                <option value="">Todas as categorias pai</option>
                @foreach($allParentCategories as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Loading bar --}}
        <div wire:loading class="mir-loading-bar"></div>

        {{-- Table Header --}}
        <div class="mir-table-header">
            <span class="cat-plh-handle"></span>
            <span class="cat-plh-body">Categoria</span>
            <span class="cat-plh-catmeta" style="text-align:right;">Pai / Posts</span>
            <span class="plh-divider"></span>
            <span class="cat-plh-status" style="text-align:center;">Status</span>
            <span class="plh-divider"></span>
            <span class="cat-plh-actions" style="text-align:center;">Ações</span>
        </div>

        @if ($categories->isEmpty())
            <div class="mir-empty-state">
                <div class="mir-empty-icon"><i class="fa-solid fa-tags"></i></div>
                @if ($searchCategory || $filterStatus || $filterParent)
                    <h5 class="mir-empty-title">Nenhuma categoria encontrada</h5>
                    <p class="mir-empty-desc">Tente ajustar os filtros ou termos de busca.</p>
                @else
                    <h5 class="mir-empty-title">Nenhuma categoria</h5>
                    <p class="mir-empty-desc">Adicione categorias para organizar o seu conteúdo.</p>
                @endif
            </div>
        @else
            <div class="mir-data-list" id="sortable-categories" wire:loading.class="mir-loading-overlay">
                @foreach ($categories as $category)
                    <div class="mir-data-row"
                         wire:key="cat-{{ $category->id }}"
                         data-id="{{ $category->id }}">

                        <div class="cat-handle">
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

                        <div class="cat-catmeta">
                            @if ($category->parentCategory)
                                <span class="mir-badge-parent">
                                    <svg width="8" height="8" viewBox="0 0 8 8" fill="none">
                                        <path d="M1 1h2.5v2.5M1 7h6V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    {{ $category->parentCategory->name }}
                                </span>
                            @else
                                <span class="cat-no-parent">— sem pai —</span>
                            @endif
                            <span class="mir-badge-count" data-tooltip="{{ $category->posts_count }} posts">
                                {{ $category->posts_count }}
                            </span>
                        </div>

                        <div class="mir-divider"></div>

                        {{-- Status: clicável só para quem pode criar/editar --}}
                        <div style="width:90px;flex-shrink:0;display:flex;justify-content:center;">
                            @if($canCreate)
                                <button class="mir-status {{ $category->status ? 'is-active' : 'is-inactive' }}"
                                        wire:click="toggleCategoryStatus({{ $category->id }})"
                                        data-tooltip="{{ $category->status ? 'Clique para desativar' : 'Clique para ativar' }}">
                                    <span class="mir-status-ring"></span>
                                    {{ $category->status ? 'Ativo' : 'Inativo' }}
                                </button>
                            @else
                                <span class="mir-status {{ $category->status ? 'is-active' : 'is-inactive' }}" style="cursor:default;">
                                    <span class="mir-status-ring"></span>
                                    {{ $category->status ? 'Ativo' : 'Inativo' }}
                                </button>
                            @endif
                        </div>

                        <div class="mir-divider"></div>

                        <div class="mir-actions">
                            @if($canCreate)
                                <button class="mir-action-btn mir-action-edit"
                                        wire:click="openEditCategory({{ $category->id }})"
                                        data-tooltip="Editar categoria">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                        <path d="M9 2l2 2-7.5 7.5H1.5v-2L9 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            @endif
                            @if($canDelete)
                                <button class="mir-action-btn mir-action-delete"
                                        wire:click="$dispatch('confirm-delete-category', { id: {{ $category->id }}, name: '{{ addslashes($category->name) }}' })"
                                        data-tooltip="Excluir categoria">
                                    <svg width="12" height="13" viewBox="0 0 12 14" fill="none">
                                        <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>


    {{-- ================================================================ --}}
    {{-- MODAL: CATEGORIA PAI (Add / Edit)                                 --}}
    {{-- ================================================================ --}}
    @if ($showParentCategoryModal && $canCreate)
    <div class="mir-modal-overlay">
        <div class="mir-modal-dialog" style="max-width:540px;">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <div class="mir-modal-icon {{ $isEditingParent ? 'mir-modal-icon-edit' : 'mir-modal-icon-add' }}">
                            <i class="fa-solid fa-{{ $isEditingParent ? 'pen-to-square' : 'plus' }}"></i>
                        </div>
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
                        <i class="fa-solid fa-xmark"></i>
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
                    <button class="mir-btn-ghost" wire:click="closeParentCategoryModal">Cancelar</button>
                    <button class="mir-btn-primary-lg"
                            wire:click="saveParentCategory"
                            wire:loading.attr="disabled"
                            wire:target="saveParentCategory">
                        <span wire:loading wire:target="saveParentCategory">
                            <span class="spinner-border spinner-border-sm mr-1"></span>
                        </span>
                        <i class="fa-solid fa-floppy-disk" wire:loading.remove wire:target="saveParentCategory"></i>
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
    @if ($showCategoryModal && $canCreate)
    <div class="mir-modal-overlay">
        <div class="mir-modal-dialog" style="max-width:540px;">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <div class="mir-modal-icon {{ $isEditingCategory ? 'mir-modal-icon-edit' : 'mir-modal-icon-add' }}">
                            <i class="fa-solid fa-{{ $isEditingCategory ? 'pen-to-square' : 'plus' }}"></i>
                        </div>
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
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="mir-modal-body">
                    <div class="cat-modal-grid">
                        <div class="cat-modal-full">
                            <label class="mir-label">Nome <span class="mir-required">*</span></label>
                            <input type="text"
                                   class="mir-input @error('categoryName') is-invalid @enderror"
                                   wire:model.live="categoryName"
                                   placeholder="Ex: Smartphones, Design Gráfico...">
                            @error('categoryName')
                                <div class="mir-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
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

                        <div style="display:flex;align-items:flex-end;padding-bottom:4px;">
                            <div>
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

                        <div class="cat-modal-full">
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
                    <button class="mir-btn-ghost" wire:click="closeCategoryModal">Cancelar</button>
                    <button class="mir-btn-primary-lg"
                            wire:click="saveCategory"
                            wire:loading.attr="disabled"
                            wire:target="saveCategory">
                        <span wire:loading wire:target="saveCategory">
                            <span class="spinner-border spinner-border-sm mr-1"></span>
                        </span>
                        <i class="fa-solid fa-floppy-disk" wire:loading.remove wire:target="saveCategory"></i>
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
    @if($canDelete)
    <div x-data="{ show: false, itemId: null, itemName: '' }"
         x-on:confirm-delete-parent.window="itemId = $event.detail.id; itemName = $event.detail.name; show = true"
         x-show="show"
         x-cloak
         class="mir-modal-overlay"
         tabindex="-1">

        <div class="mir-modal-dialog" style="max-width:440px;">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <div class="mir-modal-icon mir-modal-icon-delete">
                            <i class="fa-solid fa-trash-can"></i>
                        </div>
                        <div>
                            <div class="mir-modal-title-text">Excluir Categoria Pai</div>
                            <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" x-on:click="show = false">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="mir-modal-body">
                    <p style="color:#6d7279; font-size:.9rem; line-height:1.6; margin:0;">
                        Tem certeza que deseja excluir a categoria pai
                        <strong style="color:#1a1d23;" x-text="itemName"></strong>?
                        <br>
                        <span style="font-size:.8rem; color:#9ca3af; margin-top:6px; display:block;">
                            As categorias filhas serão desvinculadas, mas não excluídas.
                        </span>
                    </p>
                </div>

                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" x-on:click="show = false">Cancelar</button>
                    <button class="mir-btn-danger"
                            x-on:click="$wire.deleteParentCategory(itemId); show = false">
                        <i class="fa-solid fa-trash-can"></i> Sim, excluir
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL: CONFIRMAÇÃO EXCLUSÃO — CATEGORIA (Alpine.js) --}}
    <div x-data="{ show: false, itemId: null, itemName: '' }"
         x-on:confirm-delete-category.window="itemId = $event.detail.id; itemName = $event.detail.name; show = true"
         x-show="show"
         x-cloak
         class="mir-modal-overlay"
         tabindex="-1">

        <div class="mir-modal-dialog" style="max-width:440px;">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <div class="mir-modal-icon mir-modal-icon-delete">
                            <i class="fa-solid fa-trash-can"></i>
                        </div>
                        <div>
                            <div class="mir-modal-title-text">Excluir Categoria</div>
                            <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" x-on:click="show = false">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="mir-modal-body">
                    <p style="color:#6d7279; font-size:.9rem; line-height:1.6; margin:0;">
                        Tem certeza que deseja excluir a categoria
                        <strong style="color:#1a1d23;" x-text="itemName"></strong>?
                    </p>
                </div>

                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" x-on:click="show = false">Cancelar</button>
                    <button class="mir-btn-danger"
                            x-on:click="$wire.deleteCategory(itemId); show = false">
                        <i class="fa-solid fa-trash-can"></i> Sim, excluir
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif


    {{-- ================================================================ --}}
    {{-- TOAST                                                             --}}
    {{-- ================================================================ --}}
    <div id="cat-toast-container" aria-live="polite"></div>


    {{-- ================================================================ --}}
    {{-- SCOPED STYLES                                                    --}}
    {{-- ================================================================ --}}
    <style>
        .cat-section {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            margin-bottom: 24px;
        }
        .cat-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }
        .cat-section-header-left { min-width: 0; }
        .cat-filter-header {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }
        .cat-section-title { font-size: .95rem; font-weight: 700; color: #1a1d23; margin: 0; }
        .cat-section-sub { font-size: .78rem; color: #9ca3af; margin-top: 2px; }
        .cat-plh-handle  { width: 22px; flex-shrink: 0; }
        .cat-plh-body    { flex: 1 1 0; min-width: 0; }
        .cat-plh-meta    { width: 60px; flex-shrink: 0; text-align: right; }
        .cat-plh-catmeta { width: 180px; flex-shrink: 0; text-align: right; }
        .cat-plh-status  { width: 90px; flex-shrink: 0; }
        .cat-plh-actions { width: 68px; flex-shrink: 0; }
        .cat-handle {
            cursor: grab; color: #c9cdd4; flex-shrink: 0;
            display: flex; align-items: center;
            transition: color .15s; padding: 4px 6px 4px 0; width: 22px;
        }
        .cat-handle:hover { color: #6366f1; }
        .mir-data-row.sortable-chosen .cat-handle { cursor: grabbing; }
        .cat-body { flex: 1 1 0; min-width: 0; }
        .cat-name {
            font-size: .875rem; font-weight: 600; color: #1a1d23;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .cat-slug { font-size: .72rem; color: #9ca3af; font-family: ui-monospace, monospace; margin-top: 1px; }
        .cat-meta { display: flex; align-items: center; gap: 8px; flex-shrink: 0; width: 60px; justify-content: flex-end; }
        .cat-catmeta { display: flex; align-items: center; gap: 8px; flex-shrink: 0; width: 180px; justify-content: flex-end; }
        .cat-no-parent { font-size: .75rem; color: #c4c8cf; }
        .cat-modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .cat-modal-full { grid-column: 1 / -1; }
        .sortable-ghost  { opacity: .4; background: #ede9fe !important; border-radius: 8px; }
        .sortable-chosen { box-shadow: 0 4px 18px rgba(99,102,241,.18); border-radius: 8px; }
    </style>


    {{-- ================================================================ --}}
    {{-- SCRIPTS                                                          --}}
    {{-- ================================================================ --}}
    @push('scripts')
    <script>
    function catShowToast(type, message) {
        const container = document.getElementById('cat-toast-container');
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
        initCatSortable();
        Livewire.on('notify', ({ type, message }) => catShowToast(type, message));
        Livewire.hook('morph.updated', () => { initCatSortable(); });
    });

    function initCatSortable() {
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