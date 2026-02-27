{{-- livewire/admin/categories-trash.blade.php --}}
<div>

    {{-- ================================================================ --}}
    {{-- ESTILOS: Mesmo padrão mir- das categorias                        --}}
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

        /* ── Rows ───────────────────────────────────────────────────── */
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

        /* Badge de data de exclusão */
        .mir-badge-deleted {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 50px;
            font-size: .72rem;
            font-weight: 600;
            background: #fee2e2;
            color: #991b1b;
        }

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
        .mir-action-restore:hover { background: #d1fae5; border-color: #6ee7b7; color: #065f46; }
        .mir-action-delete:hover  { background: #fee2e2; border-color: #fca5a5; color: #b91c1c; }

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

        /* Botões */
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

        .mir-btn-danger-lg {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            font-size: .82rem; font-weight: 600;
            border: none; cursor: pointer;
            transition: opacity .15s;
            box-shadow: 0 2px 8px rgba(239,68,68,.35);
        }
        .mir-btn-danger-lg:hover { opacity: .9; }

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
            text-decoration: none;
        }
        .mir-btn-ghost:hover { background: #f5f5f5; color: #6d7279; }

        .mir-btn-success {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            font-size: .82rem; font-weight: 600;
            border: none; cursor: pointer;
            transition: opacity .15s;
            box-shadow: 0 2px 8px rgba(16,185,129,.35);
        }
        .mir-btn-success:hover { opacity: .9; }

        /* Header da lixeira */
        .trash-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .trash-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .trash-header-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1a1d23;
        }
        .trash-header-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            padding: 0 8px;
            border-radius: 50px;
            font-size: .72rem;
            font-weight: 700;
            background: #fee2e2;
            color: #991b1b;
        }

        /* Modais */
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
        .mir-modal-icon-delete { background: rgba(239,68,68,.12); color: #ef4444; }
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

        /* Toast */
        #trash-toast-container {
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

    </style>

    {{-- ================================================================ --}}
    {{-- TOAST                                                             --}}
    {{-- ================================================================ --}}
    <div id="trash-toast-container" aria-live="polite"></div>


    {{-- ================================================================ --}}
    {{-- HEADER DA LIXEIRA                                                 --}}
    {{-- ================================================================ --}}
    <div class="trash-header">
        <div class="trash-header-left">
            <a href="{{ route('admin.categories.index') }}" class="mir-btn-ghost">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M7.5 1.5L3 6l4.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Voltar
            </a>
            <div>
                <div class="trash-header-title">Lixeira de Categorias</div>
            </div>
            @php $totalTrashed = $trashedCategories->count() + $trashedParentCategories->count(); @endphp
            @if ($totalTrashed > 0)
                <span class="trash-header-count">{{ $totalTrashed }} {{ $totalTrashed === 1 ? 'item' : 'itens' }}</span>
            @endif
        </div>

        @if ($totalTrashed > 0)
            <div style="display: flex; gap: 8px;">
                <button class="mir-btn-success" wire:click="restoreAll" wire:loading.attr="disabled" wire:target="restoreAll">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path d="M1.5 6.5a4.5 4.5 0 018.14-2.64M10.5 1.5v2.36h-2.36" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.5 5.5a4.5 4.5 0 01-8.14 2.64M1.5 10.5V8.14h2.36" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Restaurar Tudo
                </button>
            </div>
        @endif
    </div>


    {{-- ================================================================ --}}
    {{-- SEÇÃO: CATEGORIAS PAI NA LIXEIRA                                  --}}
    {{-- ================================================================ --}}
    <div class="cat-section">
        <div class="cat-section-header">
            <div>
                <div class="cat-section-title">Categorias Pai</div>
                <div class="cat-section-sub">Categorias pai que foram excluidas</div>
            </div>
            @if ($trashedParentCategories->isNotEmpty())
                <span class="mir-badge-count">{{ $trashedParentCategories->count() }}</span>
            @endif
        </div>

        @if ($trashedParentCategories->isEmpty())
            <div class="mir-empty-state">
                <div class="mir-empty-icon"><i class="fa fa-folder-open"></i></div>
                <h5 class="mir-empty-title">Nenhuma categoria pai na lixeira</h5>
                <p class="mir-empty-desc">Categorias pai excluidas aparecerao aqui.</p>
            </div>
        @else
            <div class="cat-list">
                @foreach ($trashedParentCategories as $parent)
                    <div class="cat-row" wire:key="trash-parent-{{ $parent->id }}">

                        {{-- Icone de lixeira no lugar do handle --}}
                        <div style="padding: 4px 10px 4px 0; flex-shrink: 0; display: flex; align-items: center; color: #ef4444; opacity: .5;">
                            <svg width="14" height="14" viewBox="0 0 12 14" fill="none">
                                <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <div class="cat-body">
                            <div class="cat-name">{{ $parent->name }}</div>
                            <div class="cat-slug">{{ $parent->slug }}</div>
                        </div>

                        <div class="cat-meta">
                            <span class="mir-badge-count" title="Subcategorias excluidas junto">
                                {{ $parent->categories_count }}
                            </span>

                            <span class="mir-badge-deleted">
                                <svg width="10" height="10" viewBox="0 0 12 12" fill="none">
                                    <circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.2"/>
                                    <path d="M6 3.5V6.5L7.5 7.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ $parent->deleted_at->format('d/m/Y H:i') }}
                            </span>
                        </div>

                        <div class="mir-divider"></div>

                        <div class="mir-actions">
                            <button class="mir-action-btn mir-action-restore"
                                    wire:click="restoreParentCategory({{ $parent->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="restoreParentCategory({{ $parent->id }})"
                                    title="Restaurar">
                                <svg width="13" height="13" viewBox="0 0 12 12" fill="none">
                                    <path d="M1.5 6.5a4.5 4.5 0 018.14-2.64M10.5 1.5v2.36h-2.36" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.5 5.5a4.5 4.5 0 01-8.14 2.64M1.5 10.5V8.14h2.36" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button class="mir-action-btn mir-action-delete"
                                    wire:click="$dispatch('confirm-force-delete-parent', { id: {{ $parent->id }}, name: '{{ addslashes($parent->name) }}' })"
                                    title="Excluir permanentemente">
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
    {{-- SEÇÃO: CATEGORIAS NA LIXEIRA                                      --}}
    {{-- ================================================================ --}}
    <div class="cat-section">
        <div class="cat-section-header">
            <div>
                <div class="cat-section-title">Categorias</div>
                <div class="cat-section-sub">Categorias que foram excluidas</div>
            </div>
            @if ($trashedCategories->isNotEmpty())
                <span class="mir-badge-count">{{ $trashedCategories->count() }}</span>
            @endif
        </div>

        @if ($trashedCategories->isEmpty())
            <div class="mir-empty-state">
                <div class="mir-empty-icon"><i class="fa fa-tags"></i></div>
                <h5 class="mir-empty-title">Nenhuma categoria na lixeira</h5>
                <p class="mir-empty-desc">Categorias excluidas aparecerao aqui.</p>
            </div>
        @else
            <div class="cat-list">
                @foreach ($trashedCategories as $category)
                    <div class="cat-row" wire:key="trash-cat-{{ $category->id }}">

                        {{-- Icone de lixeira no lugar do handle --}}
                        <div style="padding: 4px 10px 4px 0; flex-shrink: 0; display: flex; align-items: center; color: #ef4444; opacity: .5;">
                            <svg width="14" height="14" viewBox="0 0 12 14" fill="none">
                                <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
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
                                <span class="mir-badge-none">&mdash; sem pai &mdash;</span>
                            @endif

                            <span class="mir-badge-deleted">
                                <svg width="10" height="10" viewBox="0 0 12 12" fill="none">
                                    <circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.2"/>
                                    <path d="M6 3.5V6.5L7.5 7.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ $category->deleted_at->format('d/m/Y H:i') }}
                            </span>
                        </div>

                        <div class="mir-divider"></div>

                        <div class="mir-actions">
                            <button class="mir-action-btn mir-action-restore"
                                    wire:click="restoreCategory({{ $category->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="restoreCategory({{ $category->id }})"
                                    title="Restaurar">
                                <svg width="13" height="13" viewBox="0 0 12 12" fill="none">
                                    <path d="M1.5 6.5a4.5 4.5 0 018.14-2.64M10.5 1.5v2.36h-2.36" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.5 5.5a4.5 4.5 0 01-8.14 2.64M1.5 10.5V8.14h2.36" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button class="mir-action-btn mir-action-delete"
                                    wire:click="$dispatch('confirm-force-delete-category', { id: {{ $category->id }}, name: '{{ addslashes($category->name) }}' })"
                                    title="Excluir permanentemente">
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
    {{-- ESTADO VAZIO GLOBAL (nada na lixeira)                             --}}
    {{-- ================================================================ --}}
    @if ($trashedCategories->isEmpty() && $trashedParentCategories->isEmpty())
        <div class="cat-section">
            <div class="mir-empty-state" style="padding: 64px 24px;">
                <div class="mir-empty-icon" style="background: #fee2e2;">
                    <i class="fa fa-trash" style="color: #ef4444;"></i>
                </div>
                <h5 class="mir-empty-title">A lixeira esta vazia</h5>
                <p class="mir-empty-desc">Categorias excluidas aparecerao aqui para que voce possa restaura-las.</p>
                <a href="{{ route('admin.categories.index') }}" class="mir-btn-primary-lg">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path d="M7.5 1.5L3 6l4.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Voltar para categorias
                </a>
            </div>
        </div>
    @endif


    {{-- ================================================================ --}}
    {{-- MODAL: CONFIRMAÇÃO EXCLUSÃO PERMANENTE — CATEGORIA PAI            --}}
    {{-- ================================================================ --}}
    <div x-data="{ show: false, itemId: null, itemName: '' }"
         x-on:confirm-force-delete-parent.window="itemId = $event.detail.id; itemName = $event.detail.name; show = true"
         x-show="show"
         x-cloak
         class="mir-modal-overlay"
         tabindex="-1">

        <div class="mir-modal-dialog">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <span class="mir-modal-icon mir-modal-icon-delete">
                            <i class="fa fa-exclamation-triangle"></i>
                        </span>
                        <div>
                            <div class="mir-modal-title-text">Excluir Permanentemente</div>
                            <div class="mir-modal-subtitle">Esta acao nao pode ser desfeita</div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" x-on:click="show = false">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <div class="mir-modal-body">
                    <p style="color:#6d7279; font-size:.9rem; line-height:1.6; margin:0;">
                        Tem certeza que deseja excluir <strong>permanentemente</strong> a categoria pai
                        <strong style="color:#ef4444;" x-text="itemName"></strong>?
                        <br>
                        <span style="font-size:.8rem; color:#991b1b; margin-top:6px; display:block; background: #fee2e2; padding: 8px 12px; border-radius: 6px;">
                            <i class="fa fa-exclamation-triangle" style="margin-right:4px;"></i>
                            As subcategorias excluidas junto tambem serao removidas permanentemente. Este processo e irreversivel.
                        </span>
                    </p>
                </div>

                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" x-on:click="show = false">Cancelar</button>
                    <button class="mir-btn-danger-lg"
                            x-on:click="$wire.forceDeleteParentCategory(itemId); show = false">
                        <i class="fa fa-trash"></i> Sim, excluir permanentemente
                    </button>
                </div>

            </div>
        </div>
    </div>


    {{-- ================================================================ --}}
    {{-- MODAL: CONFIRMAÇÃO EXCLUSÃO PERMANENTE — CATEGORIA                --}}
    {{-- ================================================================ --}}
    <div x-data="{ show: false, itemId: null, itemName: '' }"
         x-on:confirm-force-delete-category.window="itemId = $event.detail.id; itemName = $event.detail.name; show = true"
         x-show="show"
         x-cloak
         class="mir-modal-overlay"
         tabindex="-1">

        <div class="mir-modal-dialog">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <span class="mir-modal-icon mir-modal-icon-delete">
                            <i class="fa fa-exclamation-triangle"></i>
                        </span>
                        <div>
                            <div class="mir-modal-title-text">Excluir Permanentemente</div>
                            <div class="mir-modal-subtitle">Esta acao nao pode ser desfeita</div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" x-on:click="show = false">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <div class="mir-modal-body">
                    <p style="color:#6d7279; font-size:.9rem; line-height:1.6; margin:0;">
                        Tem certeza que deseja excluir <strong>permanentemente</strong> a categoria
                        <strong style="color:#ef4444;" x-text="itemName"></strong>?
                        <br>
                        <span style="font-size:.8rem; color:#991b1b; margin-top:6px; display:block; background: #fee2e2; padding: 8px 12px; border-radius: 6px;">
                            <i class="fa fa-exclamation-triangle" style="margin-right:4px;"></i>
                            Este processo e irreversivel. A categoria sera removida permanentemente do sistema.
                        </span>
                    </p>
                </div>

                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" x-on:click="show = false">Cancelar</button>
                    <button class="mir-btn-danger-lg"
                            x-on:click="$wire.forceDeleteCategory(itemId); show = false">
                        <i class="fa fa-trash"></i> Sim, excluir permanentemente
                    </button>
                </div>

            </div>
        </div>
    </div>


    {{-- ================================================================ --}}
    {{-- SCRIPTS: Toast                                                    --}}
    {{-- ================================================================ --}}
    @push('scripts')
    <script>
    function trashShowToast(type, message) {
        const container = document.getElementById('trash-toast-container');
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
        Livewire.on('notify', ({ type, message }) => trashShowToast(type, message));
    });
    </script>
    @endpush

</div>
