<div class="mnu-row depth-{{ $depth }}"
     wire:key="menu-item-{{ $item->id }}"
     data-item-id="{{ $item->id }}">

    <div class="mir-data-row">

        {{-- Indent spacer --}}
        @if ($depth > 0)
            <div style="width:{{ $depth * 24 }}px;flex-shrink:0;"></div>
        @endif

        {{-- Handle --}}
        <div class="mnu-handle" data-tooltip="Arrastar para reordenar">
            <svg width="8" height="14" viewBox="0 0 8 14" fill="none">
                <circle cx="2" cy="2"  r="1.5" fill="currentColor"/>
                <circle cx="6" cy="2"  r="1.5" fill="currentColor"/>
                <circle cx="2" cy="7"  r="1.5" fill="currentColor"/>
                <circle cx="6" cy="7"  r="1.5" fill="currentColor"/>
                <circle cx="2" cy="12" r="1.5" fill="currentColor"/>
                <circle cx="6" cy="12" r="1.5" fill="currentColor"/>
            </svg>
        </div>

        {{-- Depth badge --}}
        @php
            $badgeClass = $depth === 0 ? 'mnu-badge-root' : 'mnu-badge-sub';
            $badgeLabel = $depth === 0 ? 'Raiz' : 'Sub ' . $depth;
        @endphp
        <span class="mir-badge mnu-depth-badge {{ $badgeClass }}">
            <svg width="8" height="8" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" opacity=".9"/></svg>
            {{ $badgeLabel }}
        </span>

        {{-- Body: título + URL --}}
        <div class="mnu-body">
            <div class="mnu-name">
                {{ $item->title }}
                @if ($item->target === '_blank')
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" style="color:#9ca3af;" data-tooltip="Abre em nova aba">
                        <path d="M4 2H2a1 1 0 00-1 1v5a1 1 0 001 1h5a1 1 0 001-1V6M6 1h3m0 0v3M9 1L4.5 5.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                @endif
            </div>
            <div class="mnu-url {{ $item->children->isNotEmpty() ? 'mnu-url-inactive' : '' }}">
                @if ($item->children->isNotEmpty())
                    <span style="font-style:italic;">URL inativa (subitens)</span>
                @else
                    {{ $item->url ?: '#' }}
                @endif
            </div>
        </div>

        <div class="mir-divider"></div>

        {{-- Status pill --}}
        <div style="width:90px;flex-shrink:0;display:flex;justify-content:center;">
            <button class="mir-status {{ $item->is_active ? 'is-active' : 'is-inactive' }}"
                    wire:click="toggleActive({{ $item->id }})"
                    data-tooltip="{{ $item->is_active ? 'Clique para desativar' : 'Clique para ativar' }}">
                <span class="mir-status-ring"></span>
                {{ $item->is_active ? 'Ativo' : 'Inativo' }}
            </button>
        </div>

        <div class="mir-divider"></div>

        {{-- Ações --}}
        <div class="mir-actions">
            @if ($depth < 4)
                <button class="mir-action-btn mnu-action-add"
                        wire:click="openAddForm({{ $item->id }})"
                        data-tooltip="Adicionar subitem">
                    <svg width="11" height="11" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </button>
            @endif

            <button class="mir-action-btn mir-action-edit"
                    wire:click="edit({{ $item->id }})"
                    data-tooltip="Editar item">
                <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                    <path d="M9 2l2 2-7.5 7.5H1.5v-2L9 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                </svg>
            </button>

            <button class="mir-action-btn mir-action-delete"
                    wire:click="$dispatch('confirm-delete', { id: {{ $item->id }}, title: '{{ addslashes($item->title) }}' })"
                    data-tooltip="Excluir item">
                <svg width="12" height="13" viewBox="0 0 12 14" fill="none">
                    <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ── Filhos (recursivo) ─────────────────────────────────────── --}}
    @if ($item->children->isNotEmpty())
        <div class="mnu-children"
             data-sortable
             data-parent-id="{{ $item->id }}">
            @foreach ($item->children as $child)
                @include('livewire.admin.menu-item-row', [
                    'item'  => $child,
                    'depth' => $depth + 1,
                ])
            @endforeach
        </div>
    @elseif ($depth < 4)
        <div class="mnu-children mnu-children-empty"
             data-sortable
             data-parent-id="{{ $item->id }}">
        </div>
    @endif
</div>
