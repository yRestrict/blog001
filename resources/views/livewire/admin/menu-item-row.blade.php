{{--
    Partial: livewire/admin/menu-item-row.blade.php
    Variáveis: $item, $depth (int, começa em 0)
--}}

<div class="mir-row"
     wire:key="menu-item-{{ $item->id }}"
     data-item-id="{{ $item->id }}">

    {{-- ── Cabeçalho ─────────────────────────────────────────────── --}}
    <div class="mir-header">

        {{-- Handle de drag --}}
        <span class="mir-handle" title="Arrastar para reordenar">
            <i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i>
        </span>

        @switch($depth)
            @case(0)
                <span class="mir-depth-badge badge-depth-1">Raiz</span>
                @break
            @case(1)
                <span class="mir-depth-badge badge-depth-2">Sub 1</span>
                @break
            @case(2)
                <span class="mir-depth-badge badge-depth-3">Sub 2</span>
                @break
            @default
                <span class="mir-depth-badge badge-depth-{{ $depth + 1 }}">Sub {{ $depth }}</span>
        @endswitch

        {{-- Título + URL --}}
        <div class="mir-meta">
            <div class="mir-title">
                {{ $item->title }}
                @if ($item->target === '_blank')
                    <i class="fa fa-external-link-alt mir-ext-icon" title="Abre em nova aba"></i>
                @endif
            </div>
            <div class="mir-url">
                <i class="fa fa-link"></i>
                <span>{{ $item->url ?: '#' }}</span>
            </div>
        </div>

        {{-- Status toggle --}}
        <button class="mir-status-btn {{ $item->is_active ? 'mir-status-active' : 'mir-status-inactive' }}"
                wire:click="toggleActive({{ $item->id }})"
                title="Clique para {{ $item->is_active ? 'desativar' : 'ativar' }}">
            <span class="mir-status-dot"></span>
            {{ $item->is_active ? 'Ativo' : 'Inativo' }}
        </button>

        {{-- Ações --}}
        <div class="mir-actions">

            @if ($depth < 2)
                <button class="mir-btn mir-btn-add"
                        wire:click="openAddForm({{ $item->id }})"
                        title="Adicionar subitem">
                    <i class="fa fa-plus"></i>
                    <span class="mir-btn-label">Subitem</span>
                </button>
            @endif

            <button class="mir-btn mir-btn-edit"
                    wire:click="edit({{ $item->id }})"
                    title="Editar">
                <i class="fa fa-edit"></i>
            </button>

            <button class="mir-btn mir-btn-delete"
                    wire:click="delete({{ $item->id }})"
                    wire:confirm="Tem certeza que deseja excluir '{{ $item->title }}'?"
                    title="Excluir">
                <i class="fa fa-trash"></i>
            </button>

        </div>
    </div>

    {{-- ── Filhos (recursivo) ─────────────────────────────────────── --}}
    @if ($item->children->isNotEmpty())
        <div class="mir-children"
             data-sortable
             data-parent-id="{{ $item->id }}">
            @foreach ($item->children as $child)
                @include('livewire.admin.menu-item-row', [
                    'item'  => $child,
                    'depth' => $depth + 1,
                ])
            @endforeach
        </div>

    @elseif ($depth < 2)
        {{-- Container vazio para drop zone --}}
        <div class="mir-children mir-children-empty"
             data-sortable
             data-parent-id="{{ $item->id }}">
        </div>
    @endif

</div>

{{-- ================================================================ --}}
{{-- ESTILOS — renderizado apenas uma vez graças ao @once             --}}
{{-- ================================================================ --}}
@once
<style>
/* ─── Variáveis ──────────────────────────────────────────────────── */
.mir-row {
    --mir-border:    #e3e8ef;
    --mir-bg:        #ffffff;
    --mir-bg-sub:    #f8fafc;
    --mir-text:      #1a2332;
    --mir-muted:     #8494a9;
    --mir-handle:    #c8d3df;
    --mir-radius:    9px;
    --mir-radius-sm: 6px;
    --mir-indent:    36px;
    --mir-gap:       6px;
    --mir-ease:      160ms ease;
}

/* ─── Row container ──────────────────────────────────────────────── */
.mir-row {
    background: var(--mir-bg);
    border: 1px solid var(--mir-border);
    border-radius: var(--mir-radius);
    margin-bottom: var(--mir-gap);
    transition: box-shadow var(--mir-ease), border-color var(--mir-ease);
    overflow: hidden;
}

.mir-row:hover {
    border-color: #c0cad8;
    box-shadow: 0 2px 10px rgba(59,130,246,.08), 0 1px 3px rgba(0,0,0,.05);
}

/* ─── Header ─────────────────────────────────────────────────────── */
.mir-header {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    gap: 8px;
}

/* ─── Handle ─────────────────────────────────────────────────────── */
.mir-handle {
    cursor: grab;
    color: var(--mir-handle);
    font-size: .75rem;
    flex-shrink: 0;
    padding: 2px 3px;
    border-radius: 4px;
    transition: color var(--mir-ease);
    letter-spacing: -3px;
    display: inline-flex;
}
.mir-handle:active { cursor: grabbing; }
.mir-row:hover .mir-handle { color: var(--mir-muted); }

/* ─── Depth badge ────────────────────────────────────────────────── */
.mir-depth-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .3px;
    padding: 2px 7px;
    border-radius: 20px;
    flex-shrink: 0;
    line-height: 1.4;
}
.badge-depth-1 { background: #f0fdf4; color: #15803d; }
.badge-depth-2 { background: #fef3c7; color: #b45309; }

/* ─── Meta (title + url) ─────────────────────────────────────────── */
.mir-meta {
    flex: 1 1 auto;
    min-width: 0;
    overflow: hidden;
}

.mir-title {
    font-size: .875rem;
    font-weight: 600;
    color: var(--mir-text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.35;
}

.mir-ext-icon {
    font-size: .6rem;
    color: var(--mir-muted);
    margin-left: 4px;
    vertical-align: middle;
}

.mir-url {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: .73rem;
    color: var(--mir-muted);
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.mir-url .fa { font-size: .6rem; flex-shrink: 0; }

/* ─── Status button ──────────────────────────────────────────────── */
.mir-status-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .72rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    border: none;
    cursor: pointer;
    flex-shrink: 0;
    transition: background var(--mir-ease);
    line-height: 1;
}

.mir-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
}

.mir-status-active              { background: #dcfce7; color: #166534; }
.mir-status-active .mir-status-dot  { background: #16a34a; }
.mir-status-active:hover        { background: #bbf7d0; }

.mir-status-inactive             { background: #f1f5f9; color: #64748b; }
.mir-status-inactive .mir-status-dot { background: #94a3b8; }
.mir-status-inactive:hover       { background: #e2e8f0; }

/* ─── Action buttons ─────────────────────────────────────────────── */
.mir-actions {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-shrink: 0;
}

.mir-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    height: 30px;
    padding: 0 10px;
    border-radius: var(--mir-radius-sm);
    font-size: .75rem;
    font-weight: 600;
    border: 1px solid transparent;
    cursor: pointer;
    white-space: nowrap;
    transition: all var(--mir-ease);
    line-height: 1;
    background: none;
}

.mir-btn-add {
    background: #eff6ff;
    color: #2563eb;
    border-color: #bfdbfe;
}
.mir-btn-add:hover {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
}

.mir-btn-edit {
    background: #f8fafc;
    color: var(--mir-muted);
    border-color: var(--mir-border);
    width: 30px;
    padding: 0;
}
.mir-btn-edit:hover {
    background: #f1f5f9;
    color: var(--mir-text);
    border-color: #c8d0dc;
}

.mir-btn-delete {
    background: #f8fafc;
    color: #fca5a5;
    border-color: var(--mir-border);
    width: 30px;
    padding: 0;
}
.mir-btn-delete:hover {
    background: #fef2f2;
    color: #ef4444;
    border-color: #fecaca;
}

/* Label "Subitem" — some em mobile */
.mir-btn-label { display: none; }
@media (min-width: 768px) {
    .mir-btn-label { display: inline; }
}

/* ─── Children container ─────────────────────────────────────────── */
.mir-children {
    border-top: 1px solid var(--mir-border);
    background: var(--mir-bg-sub);
    padding: 10px var(--mir-indent) 10px var(--mir-indent);
    position: relative;
}

.mir-children::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 8px;
    width: 2px;
    background: linear-gradient(to bottom, #e2e8f0 60%, transparent);
    border-radius: 2px;
}

.mir-children-empty {
    min-height: 8px;
    padding-top: 4px;
    padding-bottom: 4px;
}

/* ─── Drag states ─────────────────────────────────────────────────── */
.sortable-ghost { opacity: .35; }
.sortable-ghost .mir-header { background: #eff6ff; }

.sortable-chosen .mir-row {
    box-shadow: 0 6px 20px rgba(59,130,246,.15);
    border-color: #93c5fd;
}
</style>
@endonce