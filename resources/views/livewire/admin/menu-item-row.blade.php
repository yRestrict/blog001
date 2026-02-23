<div class="mir-row depth-{{ $depth }}"
     wire:key="menu-item-{{ $item->id }}"
     data-item-id="{{ $item->id }}">

    <div class="mir-card-inner">

        {{-- ── Faixa lateral de profundidade ────────────────────────────── --}}
        <div class="mir-depth-stripe"></div>

        {{-- ── Handle ──────────────────────────────────────────────────── --}}
        <div class="mir-handle" title="Arrastar para reordenar">
            <svg width="8" height="14" viewBox="0 0 8 14" fill="none">
                <circle cx="2" cy="2"  r="1.5" fill="currentColor"/>
                <circle cx="6" cy="2"  r="1.5" fill="currentColor"/>
                <circle cx="2" cy="7"  r="1.5" fill="currentColor"/>
                <circle cx="6" cy="7"  r="1.5" fill="currentColor"/>
                <circle cx="2" cy="12" r="1.5" fill="currentColor"/>
                <circle cx="6" cy="12" r="1.5" fill="currentColor"/>
            </svg>
        </div>

        {{-- ── Conteúdo principal ────────────────────────────────────────── --}}
        <div class="mir-body">

            {{-- Linha superior: badge + título --}}
            <div class="mir-top-row">
                @switch($depth)
                    @case(0)
                        <span class="mir-badge mir-badge-root">
                            <svg width="8" height="8" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" opacity=".9"/></svg>
                            Raiz
                        </span>
                        @break
                    @case(1)
                        <span class="mir-badge mir-badge-sub1">
                            <svg width="8" height="8" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" opacity=".9"/></svg>
                            Sub 1
                        </span>
                        @break
                    @case(2)
                        <span class="mir-badge mir-badge-sub2">
                            <svg width="8" height="8" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" opacity=".9"/></svg>
                            Sub 2
                        </span>
                        @break
                    @case(3)
                        <span class="mir-badge mir-badge-sub3">
                            <svg width="8" height="8" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3.5" fill="currentColor" opacity=".9"/>
                            </svg>
                            Sub 3
                        </span>
                        @break

                    @case(4)
                        <span class="mir-badge mir-badge-sub4">
                            <svg width="8" height="8" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3.5" fill="currentColor" opacity=".9"/>
                            </svg>
                            Sub 4
                        </span>
                        @break

                    @case(5)
                        <span class="mir-badge mir-badge-sub5">
                            <svg width="8" height="8" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3.5" fill="currentColor" opacity=".9"/>
                            </svg>
                            Sub 5
                        </span>
                        @break
                @endswitch

                <span class="mir-title">
                    {{ $item->title }}
                    @if ($item->target === '_blank')
                        <svg class="mir-newtab" width="10" height="10" viewBox="0 0 10 10" fill="none" title="Abre em nova aba">
                            <path d="M4 2H2a1 1 0 00-1 1v5a1 1 0 001 1h5a1 1 0 001-1V6M6 1h3m0 0v3M9 1L4.5 5.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @endif
                </span>
            </div>

            {{-- Linha inferior: URL --}}
            <div class="mir-url-row {{ $item->children->isNotEmpty() ? 'mir-url-row-inactive' : '' }}">
                @if ($item->children->isNotEmpty())
                    <svg width="9" height="9" viewBox="0 0 12 12" fill="none" title="URL inativa — item com subitens">
                        <path d="M2 2l8 8M5.5 3A4.5 4.5 0 0110 7.5M6.5 9A4.5 4.5 0 012 4.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                    <span class="mir-url-inactive-text">URL inativa (tem subitens)</span>
                @else
                    <svg width="9" height="9" viewBox="0 0 10 10" fill="none">
                        <path d="M4 6.5a2.5 2.5 0 003.5 0l1.5-1.5a2.5 2.5 0 00-3.5-3.5L4.8 2.3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        <path d="M6 3.5a2.5 2.5 0 00-3.5 0L1 5a2.5 2.5 0 003.5 3.5l.7-.7" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                    <span>{{ $item->url ?: '#' }}</span>
                @endif
            </div>

        </div>

        {{-- ── Lado direito: status + ações ─────────────────────────────── --}}
        <div class="mir-right">

            {{-- Status pill --}}
            <button class="mir-status {{ $item->is_active ? 'is-active' : 'is-inactive' }}"
                    wire:click="toggleActive({{ $item->id }})"
                    title="{{ $item->is_active ? 'Clique para desativar' : 'Clique para ativar' }}">
                <span class="mir-status-ring"></span>
                <span>{{ $item->is_active ? 'Ativo' : 'Inativo' }}</span>
            </button>

            {{-- Divider vertical --}}
            <div class="mir-divider"></div>

            {{-- Botões de ação --}}
            <div class="mir-actions">
                @if ($depth < 4)
                    <button class="mir-action-btn mir-action-add"
                            wire:click="openAddForm({{ $item->id }})"
                            title="Adicionar subitem">
                        <svg width="11" height="11" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Sub</span>
                    </button>
                @endif

                <button class="mir-action-btn mir-action-icon mir-action-edit"
                        wire:click="edit({{ $item->id }})"
                        title="Editar">
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                        <path d="M9 2l2 2-7.5 7.5H1.5v-2L9 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                    </svg>
                </button>

                <button class="mir-action-btn mir-action-icon mir-action-delete"
                        wire:click="$dispatch('confirm-delete', { id: {{ $item->id }}, title: '{{ addslashes($item->title) }}' })"
                        title="Excluir">
                    <svg width="12" height="13" viewBox="0 0 12 14" fill="none">
                        <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
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
    @elseif ($depth < 4)
        <div class="mir-children mir-children-empty"
             data-sortable
             data-parent-id="{{ $item->id }}">
            <div class="mir-dropzone-hint">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M6 1v7M3 5l3 3 3-3M2 11h8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Arraste um item aqui para criar subitem
            </div>
        </div>
    @endif
</div>