{{-- livewire/admin/sidebar.blade.php --}}
<div>

    {{-- ================================================================ --}}
    {{-- ESTILOS                                                           --}}
    {{-- ================================================================ --}}
    <style>
        /* ── Seção ─────────────────────────────────────────────────── */
        .sdb-section {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            overflow: hidden;
        }
        .sdb-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        .sdb-title { font-size: .95rem; font-weight: 700; color: #1a1d23; margin: 0; }
        .sdb-sub   { font-size: .78rem; color: #9ca3af; margin-top: 2px; }

        /* ── Lista ──────────────────────────────────────────────────── */
        .sdb-list { padding: 6px 0; }
        .sdb-row {
            display: flex;
            align-items: center;
            padding: 11px 16px;
            border-bottom: 1px solid #f5f5f5;
            transition: background .15s;
            gap: 10px;
        }
        .sdb-row:last-child { border-bottom: none; }
        .sdb-row:hover { background: #f9fafb; }

        /* Handle */
        .sdb-handle {
            cursor: grab;
            color: #c9cdd4;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            transition: color .15s;
            padding: 4px 6px 4px 0;
        }
        .sdb-handle:hover { color: #6366f1; }
        .sdb-row.sortable-chosen .sdb-handle { cursor: grabbing; }

        /* Ícone do tipo */
        .sdb-type-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem;
            flex-shrink: 0;
        }

        /* Corpo */
        .sdb-body { flex: 1; min-width: 0; }
        .sdb-widget-title {
            font-size: .875rem; font-weight: 600; color: #1a1d23;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sdb-widget-type {
            font-size: .72rem; color: #9ca3af; margin-top: 1px;
        }

        /* Badge de tipo */
        .sdb-type-badge {
            display: inline-flex; align-items: center; gap: 3px;
            padding: 3px 9px; border-radius: 50px;
            font-size: .7rem; font-weight: 600;
            flex-shrink: 0;
        }

        /* Status pill */
        .mir-status {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 50px;
            font-size: .72rem; font-weight: 600;
            border: none; cursor: pointer;
            transition: background .15s; flex-shrink: 0;
        }
        .mir-status.is-active   { background: #d1fae5; color: #065f46; }
        .mir-status.is-active:hover   { background: #a7f3d0; }
        .mir-status.is-inactive { background: #fee2e2; color: #991b1b; }
        .mir-status.is-inactive:hover { background: #fecaca; }
        .mir-status-ring { width: 7px; height: 7px; border-radius: 50%; }
        .is-active .mir-status-ring   { background: #10b981; }
        .is-inactive .mir-status-ring { background: #ef4444; }

        /* Divider */
        .mir-divider { width: 1px; height: 26px; background: #e9ecef; flex-shrink: 0; }

        /* Botões de ação */
        .mir-actions { display: flex; align-items: center; gap: 4px; flex-shrink: 0; }
        .mir-action-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: 6px;
            border: 1px solid transparent; background: transparent;
            cursor: pointer; transition: background .15s, border-color .15s, color .15s;
            color: #6d7279;
        }
        .mir-action-btn:disabled { opacity: .38; cursor: not-allowed; }
        .mir-action-edit:hover   { background: #ede9fe; border-color: #c4b5fd; color: #5b21b6; }
        .mir-action-delete:hover { background: #fee2e2; border-color: #fca5a5; color: #b91c1c; }

        /* Fixed badge */
        .sdb-fixed-badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 8px; border-radius: 50px;
            font-size: .68rem; font-weight: 700;
            background: #e0f2fe; color: #0369a1; flex-shrink: 0;
        }

        /* Empty state */
        .mir-empty-state {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; padding: 56px 24px; text-align: center;
        }
        .mir-empty-icon {
            width: 56px; height: 56px; border-radius: 14px;
            background: #f3f4f6;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: #9ca3af; margin-bottom: 14px;
        }
        .mir-empty-title { font-size: .95rem; font-weight: 700; color: #374151; margin: 0 0 6px; }
        .mir-empty-desc  { font-size: .82rem; color: #9ca3af; margin: 0 0 16px; }

        /* Botões primários / ghost */
        .mir-btn-primary-lg {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff;
            font-size: .82rem; font-weight: 600;
            border: none; cursor: pointer; transition: opacity .15s;
            box-shadow: 0 2px 8px rgba(99,102,241,.35);
        }
        .mir-btn-primary-lg:hover { opacity: .9; }
        .mir-btn-ghost {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            background: transparent; color: #6d7279;
            font-size: .82rem; font-weight: 600;
            border: 1px solid #e0e0e0; cursor: pointer; transition: background .15s;
        }
        .mir-btn-ghost:hover { background: #f5f5f5; }

        /* Botão danger */
        .mir-btn-danger {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff;
            font-size: .82rem; font-weight: 600;
            border: none; cursor: pointer; transition: opacity .15s;
            box-shadow: 0 2px 8px rgba(239,68,68,.35);
        }
        .mir-btn-danger:hover { opacity: .9; }

        /* ── Modais ───────────────────────────────────────────────── */
        .mir-modal-overlay {
            position: fixed; inset: 0;
            background: rgba(17,24,39,.55);
            backdrop-filter: blur(2px);
            z-index: 1060;
            display: flex; align-items: center; justify-content: center;
            padding: 16px;
        }
        .mir-modal-dialog { width: 100%; max-width: 600px; animation: mir-modal-in .2s ease; }
        .mir-modal-dialog-sm { width: 100%; max-width: 440px; animation: mir-modal-in .2s ease; }
        @keyframes mir-modal-in {
            from { opacity: 0; transform: translateY(-14px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .mir-modal-content {
            background: #fff; border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0,0,0,.18); overflow: hidden;
        }
        .mir-modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 22px; border-bottom: 1px solid #f0f0f0;
        }
        .mir-modal-title-wrap { display: flex; align-items: center; gap: 12px; }
        .mir-modal-icon {
            width: 36px; height: 36px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem; flex-shrink: 0;
        }
        .mir-modal-icon-add    { background: rgba(99,102,241,.12); color: #6366f1; }
        .mir-modal-icon-edit   { background: rgba(16,185,129,.12); color: #059669; }
        .mir-modal-icon-delete { background: rgba(239,68,68,.12);  color: #ef4444; }
        .mir-modal-title-text  { font-size: .93rem; font-weight: 700; color: #1a1d23; }
        .mir-modal-subtitle    { font-size: .75rem; color: #9ca3af; margin-top: 1px; }
        .mir-modal-close {
            width: 32px; height: 32px; border-radius: 8px;
            border: none; background: transparent; color: #9ca3af;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: background .15s, color .15s; font-size: .9rem;
        }
        .mir-modal-close:hover { background: #f3f4f6; color: #374151; }
        .mir-modal-body   { padding: 22px; max-height: 70vh; overflow-y: auto; }
        .mir-modal-footer {
            display: flex; justify-content: flex-end; gap: 10px;
            padding: 14px 22px; border-top: 1px solid #f0f0f0; background: #fafafa;
        }

        /* Inputs */
        .mir-label   { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: 5px; }
        .mir-required { color: #ef4444; }
        .mir-input {
            width: 100%; padding: 8px 12px;
            border: 1.5px solid #e5e7eb; border-radius: 8px;
            font-size: .85rem; color: #1a1d23; background: #fff;
            outline: none; transition: border-color .15s, box-shadow .15s;
            appearance: none;
        }
        .mir-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        .mir-input.is-invalid { border-color: #ef4444; }
        .mir-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }
        .mir-field-error { font-size: .78rem; color: #ef4444; margin-top: 4px; }
        .mir-hint { font-size: .75rem; color: #9ca3af; margin-top: 3px; }

        /* Switch */
        .mir-switch-input  { display: none; }
        .mir-switch-label  { display: inline-flex; align-items: center; gap: 10px; cursor: pointer; }
        .mir-switch-track  {
            position: relative; width: 38px; height: 22px;
            border-radius: 50px; background: #d1d5db; transition: background .2s; flex-shrink: 0;
        }
        .mir-switch-input:checked + .mir-switch-label .mir-switch-track { background: #6366f1; }
        .mir-switch-thumb  {
            position: absolute; top: 3px; left: 3px;
            width: 16px; height: 16px; border-radius: 50%;
            background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.2); transition: left .2s;
        }
        .mir-switch-text   { font-size: .82rem; font-weight: 600; color: #374151; }

        /* Radio */
        .mir-radio-group { display: flex; flex-wrap: wrap; gap: 8px; }
        .mir-radio-option {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 14px; border-radius: 8px; cursor: pointer;
            border: 1.5px solid #e5e7eb; transition: border-color .15s, background .15s;
            font-size: .82rem; font-weight: 600; color: #374151;
        }
        .mir-radio-option input[type="radio"] { display: none; }
        .mir-radio-option.is-selected {
            border-color: #6366f1; background: rgba(99,102,241,.07); color: #4338ca;
        }

        /* Type picker cards */
        .sdb-type-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(138px, 1fr));
            gap: 10px; padding: 4px 0;
        }
        .sdb-type-card {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 8px; padding: 16px 10px; border-radius: 10px; cursor: pointer;
            border: 1.5px solid #e9ecef; background: #fafafa;
            transition: border-color .15s, background .15s, transform .1s;
            text-align: center;
        }
        .sdb-type-card:hover { border-color: #6366f1; background: rgba(99,102,241,.05); transform: translateY(-1px); }
        .sdb-type-card-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem;
        }
        .sdb-type-card-label { font-size: .78rem; font-weight: 600; color: #374151; }

        /* Social links */
        .sdb-social-item {
            display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
            background: #f9fafb; border-radius: 10px; padding: 14px;
            border: 1px solid #e9ecef; margin-bottom: 10px; position: relative;
        }
        .sdb-social-remove {
            position: absolute; top: 10px; right: 10px;
            background: transparent; border: none; color: #9ca3af;
            cursor: pointer; font-size: .85rem; transition: color .15s;
            padding: 4px;
        }
        .sdb-social-remove:hover { color: #ef4444; }

        /* Slide items */
        .sdb-slide-item {
            background: #f9fafb; border-radius: 10px; padding: 14px;
            border: 1px solid #e9ecef; margin-bottom: 10px; position: relative;
        }
        .sdb-slide-remove {
            position: absolute; top: 10px; right: 10px;
            background: transparent; border: none; color: #9ca3af;
            cursor: pointer; font-size: .85rem; transition: color .15s; padding: 4px;
        }
        .sdb-slide-remove:hover { color: #ef4444; }
        .sdb-slide-preview {
            width: 100%; max-height: 100px; object-fit: cover;
            border-radius: 6px; margin-top: 6px;
        }

        /* Checkbox categoria/tag */
        .sdb-check-list {
            display: grid; grid-template-columns: 1fr 1fr; gap: 6px;
            max-height: 200px; overflow-y: auto;
            padding: 10px; border: 1.5px solid #e5e7eb; border-radius: 8px;
            background: #fafafa;
        }
        .sdb-check-item {
            display: flex; align-items: center; gap: 6px;
            padding: 5px 8px; border-radius: 6px; cursor: pointer;
            transition: background .12s; font-size: .8rem; color: #374151;
        }
        .sdb-check-item:hover { background: #ede9fe; }
        .sdb-check-item input[type="checkbox"] { accent-color: #6366f1; flex-shrink: 0; }

        /* Toast */
        #sdb-toast-container {
            position: fixed; bottom: 24px; right: 24px;
            z-index: 9999; display: flex; flex-direction: column; gap: 10px;
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
        @keyframes mir-toast-out { to   { opacity:0; transform:translateY(12px); } }
        .mir-toast-success { background: #d1fae5; color: #065f46; }
        .mir-toast-error   { background: #fee2e2; color: #991b1b; }
        .mir-toast-info    { background: #ede9fe; color: #4c1d95; }
        .mir-toast-icon    { font-size: 1rem; flex-shrink: 0; }

        /* Sortable */
        .sortable-ghost  { opacity: .4; background: #ede9fe !important; border-radius: 8px; }
        .sortable-chosen { box-shadow: 0 4px 18px rgba(99,102,241,.18); border-radius: 8px; }

        /* Loading overlay */
        .sdb-loading-bar {
            height: 3px; background: linear-gradient(90deg, #6366f1, #a78bfa, #6366f1);
            background-size: 200%; animation: sdb-loading 1.2s linear infinite;
            border-radius: 3px 3px 0 0;
        }
        @keyframes sdb-loading { 0% { background-position: 200%; } 100% { background-position: 0%; } }
    </style>

    {{-- ================================================================ --}}
    {{-- TOAST                                                             --}}
    {{-- ================================================================ --}}
    <div id="sdb-toast-container" aria-live="polite"></div>


    {{-- ================================================================ --}}
    {{-- CABEÇALHO DA PÁGINA                                               --}}
    {{-- ================================================================ --}}
    <div class="sdb-section" style="margin-bottom: 20px;">
        <div class="sdb-header">
            <div>
                <div class="sdb-title">
                    <i class="fa fa-th-large" style="color:#6366f1; margin-right: 6px;"></i>
                    Gerenciar Sidebar
                </div>
                <div class="sdb-sub">Arraste para reordenar · Clique no status para ativar/desativar</div>
            </div>
            <button class="mir-btn-primary-lg" wire:click="openCreate">
                <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                    <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Novo Widget
            </button>
        </div>

        {{-- Loading bar --}}
        <div wire:loading wire:target="save,toggleStatus,delete,updateOrder" class="sdb-loading-bar"></div>
    </div>


    {{-- ================================================================ --}}
    {{-- LISTA DE WIDGETS                                                  --}}
    {{-- ================================================================ --}}
    <div class="sdb-section">
        @if ($widgets->isEmpty())
            <div class="mir-empty-state">
                <div class="mir-empty-icon"><i class="fa fa-th-large"></i></div>
                <h5 class="mir-empty-title">Nenhum widget criado</h5>
                <p class="mir-empty-desc">Crie o primeiro widget para personalizar sua sidebar.</p>
                <button class="mir-btn-primary-lg" wire:click="openCreate">
                    <i class="fa fa-plus"></i> Criar agora
                </button>
            </div>
        @else
            <div class="sdb-list" id="sdb-sortable-list">
                @foreach ($widgets as $widget)
                    @php
                        $typeInfo = $widgetTypes[$widget->type] ?? ['label' => $widget->type, 'icon' => 'fa-cube', 'color' => '#6366f1'];
                    @endphp
                    <div class="sdb-row"
                         wire:key="widget-{{ $widget->id }}"
                         data-id="{{ $widget->id }}">

                        {{-- Drag handle --}}
                        <div class="sdb-handle" title="Arrastar para reordenar">
                            <svg width="8" height="14" viewBox="0 0 8 14" fill="none">
                                <circle cx="2" cy="2"  r="1.5" fill="currentColor"/>
                                <circle cx="6" cy="2"  r="1.5" fill="currentColor"/>
                                <circle cx="2" cy="7"  r="1.5" fill="currentColor"/>
                                <circle cx="6" cy="7"  r="1.5" fill="currentColor"/>
                                <circle cx="2" cy="12" r="1.5" fill="currentColor"/>
                                <circle cx="6" cy="12" r="1.5" fill="currentColor"/>
                            </svg>
                        </div>

                        {{-- Ícone do tipo --}}
                        <div class="sdb-type-icon"
                             style="background: {{ $typeInfo['color'] }}1a; color: {{ $typeInfo['color'] }};">
                            <i class="fa {{ $typeInfo['icon'] }}"></i>
                        </div>

                        {{-- Corpo --}}
                        <div class="sdb-body">
                            <div class="sdb-widget-title">
                                {{ $widget->title ?: '('.$typeInfo['label'].')' }}
                            </div>
                            <div class="sdb-widget-type">{{ $typeInfo['label'] }}</div>
                        </div>

                        {{-- Badge fixo --}}
                        @if ($widget->fixed)
                            <span class="sdb-fixed-badge">
                                <i class="fa fa-lock" style="font-size:.65rem;"></i> Fixo
                            </span>
                        @endif

                        <div class="mir-divider"></div>

                        {{-- Toggle status --}}
                        <button class="mir-status {{ $widget->status ? 'is-active' : 'is-inactive' }}"
                                wire:click="toggleStatus({{ $widget->id }})"
                                wire:loading.attr="disabled"
                                wire:target="toggleStatus({{ $widget->id }})"
                                title="{{ $widget->status ? 'Desativar widget' : 'Ativar widget' }}">
                            <span class="mir-status-ring"></span>
                            <span wire:loading.remove wire:target="toggleStatus({{ $widget->id }})">
                                {{ $widget->status ? 'Ativo' : 'Inativo' }}
                            </span>
                            <span wire:loading wire:target="toggleStatus({{ $widget->id }})">...</span>
                        </button>

                        <div class="mir-divider"></div>

                        {{-- Ações --}}
                        <div class="mir-actions">
                            <button class="mir-action-btn mir-action-edit"
                                    wire:click="openEdit({{ $widget->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="openEdit({{ $widget->id }})"
                                    title="Editar widget">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                    <path d="M9 2l2 2-7.5 7.5H1.5v-2L9 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                                </svg>
                            </button>

                            <button class="mir-action-btn mir-action-delete"
                                    wire:click="confirmDelete({{ $widget->id }})"
                                    {{ $widget->fixed ? 'disabled' : '' }}
                                    title="{{ $widget->fixed ? 'Widget fixo não pode ser excluído' : 'Excluir widget' }}">
                                <svg width="12" height="13" viewBox="0 0 12 14" fill="none">
                                    <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8"
                                          stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>


    {{-- ================================================================ --}}
    {{-- MODAL: CRIAR / EDITAR WIDGET                                      --}}
    {{-- ================================================================ --}}
    @if ($showModal)
    <div class="mir-modal-overlay" wire:key="modal-create-edit">
        <div class="mir-modal-dialog">
            <div class="mir-modal-content">

                {{-- Header --}}
                <div class="mir-modal-header">
                    <div class="mir-modal-title-wrap">
                        <span class="mir-modal-icon {{ $isEditing ? 'mir-modal-icon-edit' : 'mir-modal-icon-add' }}">
                            <i class="fa {{ $isEditing ? 'fa-edit' : 'fa-plus' }}"></i>
                        </span>
                        <div>
                            <div class="mir-modal-title-text">
                                @if ($showTypePicker)
                                    Selecionar Tipo de Widget
                                @elseif ($isEditing)
                                    Editar Widget
                                @else
                                    Novo Widget — {{ $widgetTypes[$type]['label'] ?? '' }}
                                @endif
                            </div>
                            <div class="mir-modal-subtitle">
                                @if ($showTypePicker)
                                    Escolha qual tipo de widget deseja adicionar
                                @elseif ($isEditing)
                                    Atualize as configurações do widget
                                @else
                                    Preencha os dados do novo widget
                                @endif
                            </div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" wire:click="closeModal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="mir-modal-body">

                    {{-- ─── STEP 1: Type Picker (apenas no criar) ─── --}}
                    @if ($showTypePicker)
                        <div class="sdb-type-grid">
                            @foreach ($widgetTypes as $typeKey => $typeInfo)
                                <div class="sdb-type-card" wire:click="selectType('{{ $typeKey }}')">
                                    <div class="sdb-type-card-icon"
                                         style="background:{{ $typeInfo['color'] }}1a; color:{{ $typeInfo['color'] }};">
                                        <i class="fa {{ $typeInfo['icon'] }}"></i>
                                    </div>
                                    <div class="sdb-type-card-label">{{ $typeInfo['label'] }}</div>
                                </div>
                            @endforeach
                        </div>

                    {{-- ─── STEP 2: Formulário do tipo selecionado ─── --}}
                    @else

                        {{-- Campo título comum --}}
                        <div class="mb-3">
                            <label class="mir-label">
                                Título <span style="color:#9ca3af; font-weight:400;">(opcional)</span>
                            </label>
                            <input type="text"
                                   class="mir-input @error('title') is-invalid @enderror"
                                   wire:model.live.debounce.400ms="title"
                                   placeholder="Título exibido acima do widget...">
                            @error('title')
                                <div class="mir-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-4" style="display:flex; align-items:center; gap:10px;">
                            <input type="checkbox"
                                   class="mir-switch-input"
                                   id="sw_status"
                                   wire:model="status">
                            <label class="mir-switch-label" for="sw_status">
                                <span class="mir-switch-track">
                                    <span class="mir-switch-thumb"></span>
                                </span>
                                <span class="mir-switch-text">Widget ativo</span>
                            </label>
                        </div>

                        <hr style="margin:0 0 18px; border-color:#f0f0f0;">

                        {{-- ── SEARCH ── --}}
                        @if ($type === 'search')
                            <div style="display:flex; align-items:center; gap:10px; color:#6d7279; font-size:.85rem; padding: 10px 0;">
                                <i class="fa fa-info-circle" style="color:#6366f1;"></i>
                                O widget de busca não possui configurações adicionais.
                            </div>

                        {{-- ── CATEGORIES ── --}}
                        @elseif ($type === 'categories')
                            <div class="mb-3">
                                <label class="mir-label">Exibição <span class="mir-required">*</span></label>
                                <div class="mir-radio-group">
                                    @foreach (['most_posts' => 'Mais Posts', 'most_visited' => 'Mais Visitadas', 'manual' => 'Seleção Manual'] as $val => $lbl)
                                        <label class="mir-radio-option {{ $category_display_type === $val ? 'is-selected' : '' }}">
                                            <input type="radio" wire:model.live="category_display_type" value="{{ $val }}">
                                            {{ $lbl }}
                                        </label>
                                    @endforeach
                                </div>
                                @error('category_display_type')
                                    <div class="mir-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="mir-label">Limite <span class="mir-required">*</span></label>
                                <input type="number" class="mir-input @error('category_limit') is-invalid @enderror"
                                       wire:model.live="category_limit" min="1" max="8">
                                @error('category_limit')
                                    <div class="mir-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($category_display_type === 'manual')
                                <div class="mb-3">
                                    <label class="mir-label">Selecionar Categorias <span class="mir-required">*</span></label>
                                    <div class="sdb-check-list">
                                        @forelse ($categoriesList as $cat)
                                            <label class="sdb-check-item">
                                                <input type="checkbox"
                                                       wire:model.live="selected_categories"
                                                       value="{{ $cat->id }}">
                                                <span>{{ $cat->name }}
                                                    <span style="color:#9ca3af;">({{ $cat->posts_count }})</span>
                                                </span>
                                            </label>
                                        @empty
                                            <div style="color:#9ca3af; font-size:.8rem; padding:8px;">Nenhuma categoria ativa.</div>
                                        @endforelse
                                    </div>
                                    @error('selected_categories')
                                        <div class="mir-field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                        {{-- ── POPULAR POSTS ── --}}
                        @elseif ($type === 'popular_posts')
                            <div class="mb-3">
                                <label class="mir-label">Número de posts <span class="mir-required">*</span></label>
                                <input type="number" class="mir-input @error('limit') is-invalid @enderror"
                                       wire:model.live="limit" min="1" max="10">
                                @error('limit')
                                    <div class="mir-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                        {{-- ── POPULAR DOWNLOADS ── --}}
                        @elseif ($type === 'popular_downloads')
                            <div class="mb-3">
                                <label class="mir-label">Número de downloads <span class="mir-required">*</span></label>
                                <input type="number" class="mir-input @error('limit') is-invalid @enderror"
                                       wire:model.live="limit" min="1" max="20">
                                @error('limit')
                                    <div class="mir-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="mir-label">Período <span class="mir-required">*</span></label>
                                <div class="mir-radio-group">
                                    @foreach (['week' => 'Semana', 'month' => 'Mês', 'total' => 'Total'] as $val => $lbl)
                                        <label class="mir-radio-option {{ $period_type === $val ? 'is-selected' : '' }}">
                                            <input type="radio" wire:model.live="period_type" value="{{ $val }}">
                                            {{ $lbl }}
                                        </label>
                                    @endforeach
                                </div>
                                @error('period_type')
                                    <div class="mir-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                        {{-- ── TAGS ── --}}
                        @elseif ($type === 'tags')
                            <div class="mb-3">
                                <label class="mir-label">Exibição <span class="mir-required">*</span></label>
                                <div class="mir-radio-group">
                                    @foreach (['most_posts' => 'Mais Posts', 'most_visited' => 'Mais Visitadas', 'manual' => 'Seleção Manual'] as $val => $lbl)
                                        <label class="mir-radio-option {{ $tag_display_type === $val ? 'is-selected' : '' }}">
                                            <input type="radio" wire:model.live="tag_display_type" value="{{ $val }}">
                                            {{ $lbl }}
                                        </label>
                                    @endforeach
                                </div>
                                @error('tag_display_type')
                                    <div class="mir-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="mir-label">Limite de Tags <span class="mir-required">*</span></label>
                                <input type="number" class="mir-input @error('tag_limit') is-invalid @enderror"
                                       wire:model.live="tag_limit" min="1" max="12">
                                @error('tag_limit')
                                    <div class="mir-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($tag_display_type === 'manual')
                                <div class="mb-3">
                                    <label class="mir-label">Selecionar Tags <span class="mir-required">*</span></label>
                                    <div class="sdb-check-list">
                                        @forelse ($tagsList as $tag)
                                            <label class="sdb-check-item">
                                                <input type="checkbox"
                                                       wire:model.live="selected_tags"
                                                       value="{{ $tag->id }}">
                                                <span>{{ $tag->name }}
                                                    <span style="color:#9ca3af;">({{ $tag->posts_count }})</span>
                                                </span>
                                            </label>
                                        @empty
                                            <div style="color:#9ca3af; font-size:.8rem; padding:8px;">Nenhuma tag com posts.</div>
                                        @endforelse
                                    </div>
                                    @error('selected_tags')
                                        <div class="mir-field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                        {{-- ── SOCIAL LINKS ── --}}
                        @elseif ($type === 'social_links')
                            @error('social_data')
                                <div class="mir-field-error mb-3">{{ $message }}</div>
                            @enderror

                            @foreach ($social_data as $i => $social)
                                <div class="sdb-social-item" wire:key="social-{{ $i }}">
                                    <button type="button" class="sdb-social-remove"
                                            wire:click="removeSocialLink({{ $i }})"
                                            title="Remover">
                                        <i class="fa fa-times"></i>
                                    </button>

                                    <div>
                                        <label class="mir-label">Nome <span class="mir-required">*</span></label>
                                        <input type="text"
                                               class="mir-input @error('social_data.'.$i.'.name') is-invalid @enderror"
                                               wire:model.live="social_data.{{ $i }}.name"
                                               placeholder="Ex: Instagram">
                                        @error('social_data.'.$i.'.name')
                                            <div class="mir-field-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="mir-label">Ícone <span class="mir-required">*</span></label>
                                        <input type="text"
                                               class="mir-input @error('social_data.'.$i.'.icon') is-invalid @enderror"
                                               wire:model.live="social_data.{{ $i }}.icon"
                                               placeholder="fa-instagram">
                                        @error('social_data.'.$i.'.icon')
                                            <div class="mir-field-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="mir-label">Cor <span class="mir-required">*</span></label>
                                        <input type="color"
                                               class="mir-input @error('social_data.'.$i.'.color') is-invalid @enderror"
                                               wire:model.live="social_data.{{ $i }}.color"
                                               style="height:40px; padding: 4px 8px; cursor:pointer;">
                                        @error('social_data.'.$i.'.color')
                                            <div class="mir-field-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="mir-label">Link <span class="mir-required">*</span></label>
                                        <input type="url"
                                               class="mir-input @error('social_data.'.$i.'.link') is-invalid @enderror"
                                               wire:model.live="social_data.{{ $i }}.link"
                                               placeholder="https://...">
                                        @error('social_data.'.$i.'.link')
                                            <div class="mir-field-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach

                            @if (count($social_data) < 10)
                                <button type="button" class="mir-btn-ghost" wire:click="addSocialLink"
                                        style="width:100%; justify-content:center;">
                                    <i class="fa fa-plus"></i> Adicionar rede social
                                </button>
                            @endif

                        {{-- ── IMAGE LINK ── --}}
                        @elseif ($type === 'image_link')
                            {{-- Modo single / slide --}}
                            <div class="mb-3">
                                <label class="mir-label">Modo de exibição</label>
                                <div class="mir-radio-group">
                                    <label class="mir-radio-option {{ $display_mode === 'single' ? 'is-selected' : '' }}">
                                        <input type="radio" wire:model.live="display_mode" value="single">
                                        <i class="fa fa-image" style="font-size:.75rem;"></i> Imagem única
                                    </label>
                                    <label class="mir-radio-option {{ $display_mode === 'slide' ? 'is-selected' : '' }}">
                                        <input type="radio" wire:model.live="display_mode" value="slide">
                                        <i class="fa fa-film" style="font-size:.75rem;"></i> Slideshow
                                    </label>
                                </div>
                            </div>

                            {{-- Dimensões --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="mir-label">Largura (px)</label>
                                    <input type="number" class="mir-input" wire:model.live="image_width" min="50" max="800">
                                </div>
                                <div class="col-md-6">
                                    <label class="mir-label">Altura (px)</label>
                                    <input type="number" class="mir-input" wire:model.live="image_height" min="50" max="600">
                                </div>
                            </div>

                            @if ($display_mode === 'single')
                                {{-- Imagem única --}}
                                <div class="mb-3">
                                    <label class="mir-label">
                                        Imagem
                                        @if (!$existingImage) <span class="mir-required">*</span> @endif
                                    </label>
                                    @if ($existingImage && !$imageFile)
                                        <div style="margin-bottom:8px;">
                                            <img src="{{ Storage::url($existingImage) }}"
                                                 style="height:80px; border-radius:8px; object-fit:cover;">
                                            <div class="mir-hint">Imagem atual. Selecione outra para substituir.</div>
                                        </div>
                                    @endif
                                    @if ($imageFile)
                                        <div style="margin-bottom:8px;">
                                            <img src="{{ $imageFile->temporaryUrl() }}"
                                                 style="height:80px; border-radius:8px; object-fit:cover;">
                                        </div>
                                    @endif
                                    <input type="file"
                                           class="mir-input @error('imageFile') is-invalid @enderror"
                                           wire:model="imageFile"
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           style="padding: 6px 10px;">
                                    @error('imageFile')
                                        <div class="mir-field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="mir-label">Link de destino</label>
                                    <input type="url" class="mir-input @error('link') is-invalid @enderror"
                                           wire:model.live="link" placeholder="https://...">
                                    @error('link')
                                        <div class="mir-field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                            @else
                                {{-- Slideshow --}}
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="mir-label">Intervalo (ms)</label>
                                        <input type="number" class="mir-input" wire:model.live="slide_interval" min="1000" max="30000" step="500">
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end" style="padding-bottom:10px; gap:16px; flex-wrap:wrap;">
                                        <div>
                                            <input type="checkbox" class="mir-switch-input" id="sw_autoplay" wire:model="slide_autoplay">
                                            <label class="mir-switch-label" for="sw_autoplay">
                                                <span class="mir-switch-track"><span class="mir-switch-thumb"></span></span>
                                                <span class="mir-switch-text">Autoplay</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <input type="checkbox" class="mir-switch-input" id="sw_controls" wire:model="slide_controls">
                                        <label class="mir-switch-label" for="sw_controls">
                                            <span class="mir-switch-track"><span class="mir-switch-thumb"></span></span>
                                            <span class="mir-switch-text">Setas</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <input type="checkbox" class="mir-switch-input" id="sw_indicators" wire:model="slide_indicators">
                                        <label class="mir-switch-label" for="sw_indicators">
                                            <span class="mir-switch-track"><span class="mir-switch-thumb"></span></span>
                                            <span class="mir-switch-text">Indicadores</span>
                                        </label>
                                    </div>
                                </div>

                                @error('slide_items')
                                    <div class="mir-field-error mb-2">{{ $message }}</div>
                                @enderror

                                @foreach ($slide_items as $si => $slide)
                                    <div class="sdb-slide-item" wire:key="slide-{{ $si }}">
                                        <button type="button" class="sdb-slide-remove"
                                                wire:click="removeSlideItem({{ $si }})"
                                                title="Remover slide">
                                            <i class="fa fa-times"></i>
                                        </button>

                                        <div style="font-size:.78rem; font-weight:700; color:#6d7279; margin-bottom:10px;">
                                            Slide {{ $si + 1 }}
                                        </div>

                                        @if (!empty($slide['existing']))
                                            <img src="{{ Storage::url($slide['existing']) }}" class="sdb-slide-preview">
                                        @endif

                                        <div class="mb-2 mt-2">
                                            <label class="mir-label">Imagem</label>
                                            <input type="file"
                                                   class="mir-input"
                                                   wire:model="slide_items.{{ $si }}.file"
                                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                                   style="padding: 6px 10px;">
                                        </div>

                                        <div class="mb-1">
                                            <label class="mir-label">Link <span class="mir-required">*</span></label>
                                            <input type="url"
                                                   class="mir-input @error('slide_items.'.$si.'.link') is-invalid @enderror"
                                                   wire:model.live="slide_items.{{ $si }}.link"
                                                   placeholder="https://...">
                                            @error('slide_items.'.$si.'.link')
                                                <div class="mir-field-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach

                                @if (count($slide_items) < 5)
                                    <button type="button" class="mir-btn-ghost" wire:click="addSlideItem"
                                            style="width:100%; justify-content:center;">
                                        <i class="fa fa-plus"></i> Adicionar slide
                                        <span style="color:#9ca3af; font-size:.75rem;">
                                            ({{ count($slide_items) }}/5)
                                        </span>
                                    </button>
                                @endif
                            @endif

                        {{-- ── CUSTOM ── --}}
                        @elseif ($type === 'custom')
                            <div class="mb-3">
                                <label class="mir-label">Conteúdo HTML <span class="mir-required">*</span></label>
                                <textarea class="mir-input @error('content') is-invalid @enderror"
                                          wire:model.live="content"
                                          rows="6"
                                          style="resize:vertical; font-family: ui-monospace, monospace; font-size:.82rem;"
                                          placeholder="<p>Seu conteúdo HTML aqui...</p>"></textarea>
                                @error('content')
                                    <div class="mir-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="mir-label">Link <span style="color:#9ca3af; font-weight:400;">(opcional)</span></label>
                                <input type="url" class="mir-input @error('link') is-invalid @enderror"
                                       wire:model.live="link" placeholder="https://...">
                                @error('link')
                                    <div class="mir-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                    @endif {{-- end type picker else --}}
                </div>

                {{-- Footer --}}
                <div class="mir-modal-footer">
                    @if (!$showTypePicker && !$isEditing)
                        <button class="mir-btn-ghost" wire:click="backToTypePicker">
                            <i class="fa fa-arrow-left"></i> Voltar
                        </button>
                    @else
                        <button class="mir-btn-ghost" wire:click="closeModal">Cancelar</button>
                    @endif

                    @if (!$showTypePicker)
                        <button class="mir-btn-primary-lg"
                                wire:click="save"
                                wire:loading.attr="disabled"
                                wire:target="save">
                            <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm mr-1"></span>
                            </span>
                            <i class="fa fa-save" wire:loading.remove wire:target="save"></i>
                            {{ $isEditing ? 'Salvar alterações' : 'Criar widget' }}
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
    @endif


    {{-- ================================================================ --}}
    {{-- MODAL: CONFIRMAÇÃO DE EXCLUSÃO (Livewire puro)                   --}}
    {{-- ================================================================ --}}
    @if ($confirmDeleteId !== null)
    <div class="mir-modal-overlay" wire:key="modal-delete">
        <div class="mir-modal-dialog-sm">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title-wrap">
                        <span class="mir-modal-icon mir-modal-icon-delete">
                            <i class="fa fa-trash"></i>
                        </span>
                        <div>
                            <div class="mir-modal-title-text">Excluir Widget</div>
                            <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" wire:click="cancelDelete">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <div class="mir-modal-body">
                    @php
                        $toDelete = $widgets->firstWhere('id', $confirmDeleteId);
                    @endphp
                    <p style="color:#6d7279; font-size:.9rem; line-height:1.6; margin:0;">
                        Tem certeza que deseja excluir o widget
                        <strong style="color:#1a1d23;">
                            {{ $toDelete?->title ?: '('.$widgetTypes[$toDelete?->type ?? 'custom']['label'].')' }}
                        </strong>?
                        @if ($toDelete?->type === 'image_link' || $toDelete?->slide_images)
                            <br>
                            <span style="font-size:.8rem; color:#9ca3af; margin-top:6px; display:block;">
                                As imagens associadas também serão removidas.
                            </span>
                        @endif
                    </p>
                </div>

                <div class="mir-modal-footer">
                    <button class="mir-btn-ghost" wire:click="cancelDelete">Cancelar</button>
                    <button class="mir-btn-danger"
                            wire:click="delete({{ $confirmDeleteId }})"
                            wire:loading.attr="disabled"
                            wire:target="delete({{ $confirmDeleteId }})">
                        <span wire:loading wire:target="delete({{ $confirmDeleteId }})">
                            <span class="spinner-border spinner-border-sm mr-1"></span>
                        </span>
                        <i class="fa fa-trash" wire:loading.remove wire:target="delete({{ $confirmDeleteId }})"></i>
                        Sim, excluir
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif


    {{-- ================================================================ --}}
    {{-- SCRIPTS                                                           --}}
    {{-- ================================================================ --}}
    @push('scripts')
    <script>
    /* ─── Toast ─────────────────────────────────────────────────────── */
    function sdbShowToast(type, message) {
        const container = document.getElementById('sdb-toast-container');
        const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle' };
        const toast = document.createElement('div');
        toast.className = `mir-toast mir-toast-${type}`;
        toast.innerHTML = `
            <i class="fa ${icons[type] || icons.info} mir-toast-icon"></i>
            <span>${message}</span>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'mir-toast-out 200ms ease forwards';
            setTimeout(() => toast.remove(), 210);
        }, 3500);
    }

    /* ─── Sortable ──────────────────────────────────────────────────── */
    function sdbInitSortable() {
        const list = document.getElementById('sdb-sortable-list');
        if (!list || !window.Sortable) return;
        if (list._sortableInstance) {
            list._sortableInstance.destroy();
        }
        list._sortableInstance = new Sortable(list, {
            animation: 180,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            handle: '.sdb-handle',
            onEnd() {
                const rows = list.querySelectorAll('[data-id]');
                const items = Array.from(rows).map((row, index) => ({
                    id: parseInt(row.dataset.id),
                    order: index + 1
                }));
                @this.updateOrder(items);
            }
        });
    }

    document.addEventListener('livewire:initialized', () => {
        sdbInitSortable();

        Livewire.on('notify', ({ type, message }) => {
            sdbShowToast(type, message);
        });
    });

    document.addEventListener('livewire:navigated', sdbInitSortable);

    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        succeed(({ snapshot, effect }) => {
            window.setTimeout(() => sdbInitSortable(), 50);
        });
    });
    </script>
    @endpush

</div>
