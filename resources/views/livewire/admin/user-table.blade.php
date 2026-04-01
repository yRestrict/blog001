<div>
    <style>

        /* ── Filtros ─────────────────────────────────────────────────── */
        .usr-filters-bar {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            padding: 14px 18px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .usr-filter-input,
        .usr-filter-select {
            height: 36px;
            padding: 0 12px;
            background: #f8fafc;
            border: 1.5px solid #e3e8ef;
            border-radius: 8px;
            font-size: .82rem;
            color: #1a2332;
            outline: none;
            transition: border-color 140ms ease, box-shadow 140ms ease;
            appearance: none;
        }
        .usr-filter-input  { flex: 1; min-width: 200px; }
        .usr-filter-select { min-width: 150px; }
        .usr-filter-input:focus,
        .usr-filter-select:focus {
            border-color: #93c5fd;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59,130,246,.12);
        }

        /* ── Card da lista ───────────────────────────────────────────── */
        .usr-section {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            overflow: hidden;
        }
        .usr-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        .usr-section-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1a1d23;
            margin: 0;
        }
        .usr-section-sub {
            font-size: .78rem;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* ── Linhas de usuário ───────────────────────────────────────── */
        .usr-list { padding: 6px 0; }

        .usr-row {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            border-bottom: 1px solid #f5f7fa;
            transition: background 140ms ease;
            gap: 0;
        }
        .usr-row:last-child  { border-bottom: none; }
        .usr-row:hover       { background: #f9fafb; }

        /* Faixa lateral de role */
        .usr-stripe {
            width: 4px;
            height: 42px;
            border-radius: 3px;
            flex-shrink: 0;
            margin-right: 14px;
        }
        .usr-row.role-owner   .usr-stripe { background: #7c3aed; }
        .usr-row.role-author  .usr-stripe { background: #2563eb; }
        .usr-row.role-visitor .usr-stripe { background: #d1d5db; }

        /* Avatar */
        .usr-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            margin-right: 12px;
        }

        /* Corpo */
        .usr-body { flex: 1 1 auto; min-width: 0; }
        .usr-name {
            font-size: .875rem;
            font-weight: 600;
            color: #1a1d23;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .usr-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 2px;
            font-size: .72rem;
            color: #9ca3af;
        }
        .usr-meta-sep { color: #e2e8f0; }

        /* Badges de role */
        .usr-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
            padding: 2.5px 8px;
            border-radius: 20px;
            white-space: nowrap;
            border: 1px solid transparent;
            flex-shrink: 0;
        }
        .usr-badge-owner   { background: #f5f3ff; color: #7c3aed; border-color: #ddd6fe; }
        .usr-badge-author  { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
        .usr-badge-visitor { background: #f9fafb; color: #6b7280; border-color: #e5e7eb; }

        /* Status pill */
        .usr-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .3px;
            padding: 4px 11px;
            border-radius: 99px;
            border: 1.5px solid transparent;
            cursor: pointer;
            white-space: nowrap;
            transition: all 140ms ease;
            background: none;
        }
        .usr-status-ring {
            width: 7px; height: 7px;
            border-radius: 50%; flex-shrink: 0;
        }
        .usr-status.is-active   { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
        .usr-status.is-active   .usr-status-ring { background: #22c55e; box-shadow: 0 0 0 2px #dcfce7; }
        .usr-status.is-active:hover { background: #dcfce7; }
        .usr-status.is-pending  { background: #fffbeb; color: #d97706; border-color: #fde68a; }
        .usr-status.is-pending  .usr-status-ring { background: #f59e0b; }
        .usr-status.is-banned   { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
        .usr-status.is-banned   .usr-status-ring { background: #ef4444; }
        .usr-status.is-inactive,
        .usr-status.is-rejected { background: #f9fafb; color: #6b7280; border-color: #e5e7eb; }
        .usr-status.is-inactive .usr-status-ring,
        .usr-status.is-rejected .usr-status-ring { background: #d1d5db; }
        .usr-status.no-click { cursor: default; }

        /* Divider */
        .usr-divider {
            width: 1px; height: 24px;
            background: #e5e7eb;
            flex-shrink: 0;
            margin: 0 10px;
        }

        /* Botões de ação */
        .usr-actions { display: flex; align-items: center; gap: 4px; flex-shrink: 0; }
        .usr-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            height: 30px;
            padding: 0 10px;
            border-radius: 7px;
            font-size: .74rem;
            font-weight: 600;
            border: 1.5px solid transparent;
            cursor: pointer;
            white-space: nowrap;
            transition: all 140ms ease;
            background: none;
            text-decoration: none;
            line-height: 1;
            color: inherit;
        }
        .usr-action-icon { width: 30px; padding: 0; }

        .usr-action-promote { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
        .usr-action-promote:hover { background: #16a34a; color: #fff; border-color: #16a34a; }

        .usr-action-demote { background: #fffbeb; color: #d97706; border-color: #fde68a; }
        .usr-action-demote:hover { background: #d97706; color: #fff; border-color: #d97706; }

        .usr-action-ban  { background: #f9fafb; color: #d1d5db; border-color: #e5e7eb; }
        .usr-action-ban:hover  { background: #fef2f2; color: #ef4444; border-color: #fecaca; }

        .usr-action-unban { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
        .usr-action-unban:hover { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }

        .usr-action-edit { background: #f9fafb; color: #6b7280; border-color: #e5e7eb; }
        .usr-action-edit:hover { background: #f3f4f6; color: #1a1d23; border-color: #c8d0dc; }

        .usr-action-delete { background: #f9fafb; color: #d1d5db; border-color: #e5e7eb; }
        .usr-action-delete:hover { background: #fef2f2; color: #ef4444; border-color: #fecaca; }

        /* Dropdown de ações */
        .usr-dropdown { position: relative; display: inline-block; }
        .usr-dropdown-menu {
            display: none;
            position: absolute;
            right: 0; top: calc(100% + 6px);
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,.1);
            padding: 4px;
            min-width: 180px;
            z-index: 100;
        }
        .usr-dropdown.open .usr-dropdown-menu { display: block; }
        .usr-dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            font-size: .82rem;
            font-weight: 500;
            color: #374151;
            border-radius: 7px;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            text-decoration: none;
            transition: background 130ms ease, color 130ms ease;
        }
        .usr-dropdown-item:hover { background: #f3f4f6; color: #1a1d23; }
        .usr-dropdown-item.text-danger { color: #ef4444; }
        .usr-dropdown-item.text-danger:hover { background: #fef2f2; }
        .usr-dropdown-item.text-success { color: #16a34a; }
        .usr-dropdown-item.text-success:hover { background: #f0fdf4; }
        .usr-dropdown-item.text-warning { color: #d97706; }
        .usr-dropdown-item.text-warning:hover { background: #fffbeb; }
        .usr-dropdown-divider { height: 1px; background: #f0f0f0; margin: 4px 0; }

        /* Cabeçalho de sort */
        .usr-sort-th {
            background: none; border: none; cursor: pointer;
            display: inline-flex; align-items: center; gap: 4px;
            font-size: .7rem; font-weight: 700; letter-spacing: .4px;
            text-transform: uppercase; color: #b0bac6; padding: 0;
            transition: color 140ms;
        }
        .usr-sort-th:hover { color: #1a1d23; }

        /* Botões globais */
        .mir-btn-primary-lg {
            display: inline-flex; align-items: center; gap: 6px;
            height: 36px; padding: 0 16px;
            background: #2563eb; color: #fff;
            border: none; border-radius: 8px;
            font-size: .8rem; font-weight: 600;
            cursor: pointer; transition: background 140ms ease, box-shadow 140ms ease;
            text-decoration: none;
        }
        .mir-btn-primary-lg:hover { background: #1d4ed8; color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,.3); text-decoration: none; }

        .mir-btn-ghost {
            display: inline-flex; align-items: center; gap: 6px;
            height: 36px; padding: 0 16px;
            background: #f1f5f9; color: #64748b;
            border: 1px solid #e3e8ef; border-radius: 8px;
            font-size: .8rem; font-weight: 600;
            cursor: pointer; transition: all 140ms ease;
        }
        .mir-btn-ghost:hover { background: #e2e8f0; color: #1a2332; }

        /* Empty state */
        .usr-empty-state {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 52px 24px; text-align: center;
        }
        .usr-empty-icon {
            width: 60px; height: 60px; border-radius: 14px;
            background: #eff6ff; color: #93c5fd;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 16px;
        }
        .usr-empty-title { font-size: .95rem; font-weight: 700; color: #1a1d23; margin: 0 0 6px; }
        .usr-empty-desc  { font-size: .82rem; color: #9ca3af; margin: 0 0 18px; }

        /* Paginação */
        .usr-pagination {
            padding: 14px 18px 6px;
            border-top: 1px solid #f1f5f9;
        }

    </style>

    {{-- ── Filtros ───────────────────────────────────────────────────── --}}
    <div class="usr-filters-bar">

        <input wire:model.live.debounce.400ms="search"
               type="text"
               class="usr-filter-input"
               placeholder="Buscar por nome, email ou username...">

        <select wire:model.live="role" class="usr-filter-select">
            <option value="">Todos os roles</option>
            <option value="owner">Owner</option>
            <option value="author">Author</option>
            <option value="visitor">Visitor</option>
        </select>

        <select wire:model.live="status" class="usr-filter-select">
            <option value="">Todos os status</option>
            <option value="active">Ativo</option>
            <option value="pending">Pending</option>
            <option value="banned">Banido</option>
            <option value="inactive">Inativo</option>
            <option value="rejected">Rejeitado</option>
        </select>

        <button wire:click="$set('search', ''); $set('role', ''); $set('status', '')"
                class="mir-btn-ghost">
            Limpar
        </button>

        <a href="{{ route('admin.users.create') }}" class="mir-btn-primary-lg" style="margin-left:auto;">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Novo Usuário
        </a>

    </div>

    {{-- ── Lista ─────────────────────────────────────────────────────── --}}
    <div class="usr-section">

        <div class="usr-section-header">
            <div>
                <div class="usr-section-title">
                    Lista de Usuários
                </div>
                <div class="usr-section-sub">
                    {{ $users->total() }} usuário(s) encontrado(s)
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                {{-- Ordenação por nome --}}
                <button wire:click="sort('name')" class="usr-sort-th">
                    Nome
                    @if($sortBy === 'name')
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
                            @if($sortDir === 'asc')
                                <path d="M5 2v6M2 5l3-3 3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                            @else
                                <path d="M5 8V2M2 5l3 3 3-3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                            @endif
                        </svg>
                    @else
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" style="opacity:.35">
                            <path d="M5 2v6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                        </svg>
                    @endif
                </button>
                <span style="color:#e2e8f0;">|</span>
                {{-- Ordenação por data --}}
                <button wire:click="sort('created_at')" class="usr-sort-th">
                    Data
                    @if($sortBy === 'created_at')
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
                            @if($sortDir === 'asc')
                                <path d="M5 2v6M2 5l3-3 3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                            @else
                                <path d="M5 8V2M2 5l3 3 3-3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                            @endif
                        </svg>
                    @else
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" style="opacity:.35">
                            <path d="M5 2v6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                        </svg>
                    @endif
                </button>
            </div>
        </div>

        @if($users->isEmpty())
            <div class="usr-empty-state">
                <div class="usr-empty-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <p class="usr-empty-title">Nenhum usuário encontrado</p>
                <p class="usr-empty-desc">Ajuste os filtros ou crie um novo usuário.</p>
                <a href="{{ route('admin.users.create') }}" class="mir-btn-primary-lg">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Novo Usuário
                </a>
            </div>
        @else

            <div class="usr-list">
                @forelse($users as $user)

                    @php
                        $roleClass  = match($user->role->value) { 'owner' => 'role-owner', 'author' => 'role-author', default => 'role-visitor' };
                        $roleBadge  = match($user->role->value) { 'owner' => 'usr-badge-owner', 'author' => 'usr-badge-author', default => 'usr-badge-visitor' };
                        $roleLabel  = match($user->role->value) { 'owner' => 'Owner', 'author' => 'Author', default => 'Visitor' };
                        $statusCss  = match($user->status->value) { 'active' => 'is-active', 'pending' => 'is-pending', 'banned' => 'is-banned', 'rejected' => 'is-rejected', default => 'is-inactive' };
                        $statusLbl  = match($user->status->value) { 'active' => 'Ativo', 'pending' => 'Pending', 'banned' => 'Banido', 'rejected' => 'Rejeitado', default => 'Inativo' };
                    @endphp

                    <div class="usr-row {{ $roleClass }}">

                        {{-- Faixa lateral --}}
                        <div class="usr-stripe"></div>

                        {{-- Avatar --}}
                        <img src="{{ $user->picture }}" alt="{{ $user->name }}"
                             class="usr-avatar">

                        {{-- Corpo --}}
                        <div class="usr-body">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span class="usr-badge {{ $roleBadge }}">
                                    <svg width="7" height="7" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" opacity=".9"/></svg>
                                    {{ $roleLabel }}
                                </span>
                                <span class="usr-name">{{ $user->name }}</span>
                            </div>
                            <div class="usr-meta">
                                <span>{{ $user->email }}</span>
                                <span class="usr-meta-sep">·</span>
                                <span>@{{ $user->username }}</span>
                                <span class="usr-meta-sep">·</span>
                                <span>{{ $user->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        {{-- Status --}}
                        @if(!$user->isOwner() && $user->id !== auth()->id())
                            <button class="usr-status {{ $statusCss }}"
                                    wire:click="toggleStatus({{ $user->id }})"
                                    title="{{ $user->isActive() ? 'Clique para desativar' : 'Clique para ativar' }}">
                                <span class="usr-status-ring"></span>
                                <span>{{ $statusLbl }}</span>
                            </button>
                        @else
                            <span class="usr-status {{ $statusCss }} no-click">
                                <span class="usr-status-ring"></span>
                                <span>{{ $statusLbl }}</span>
                            </span>
                        @endif

                        <div class="usr-divider"></div>

                        {{-- Ações --}}
                        <div class="usr-actions">

                            @if(!$user->isOwner())
                                @if($user->isVisitor())
                                    <button class="usr-action-btn usr-action-promote"
                                            wire:click="promote({{ $user->id }})"
                                            title="Promover a Autor">
                                        <svg width="10" height="10" viewBox="0 0 12 12" fill="none">
                                            <path d="M6 10V2M2 6l4-4 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Autor
                                    </button>
                                @endif
                                @if($user->isAuthor())
                                    <button class="usr-action-btn usr-action-demote"
                                            wire:click="demote({{ $user->id }})"
                                            title="Remover cargo de Autor">
                                        <svg width="10" height="10" viewBox="0 0 12 12" fill="none">
                                            <path d="M6 2v8M2 6l4 4 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Visitor
                                    </button>
                                @endif
                            @endif

                            @if(!$user->isOwner() && $user->id !== auth()->id())
                                <button class="usr-action-btn usr-action-icon {{ $user->isBanned() ? 'usr-action-unban' : 'usr-action-ban' }}"
                                        wire:click="ban({{ $user->id }})"
                                        title="{{ $user->isBanned() ? 'Desbanir' : 'Banir' }}">
                                    <svg width="13" height="13" viewBox="0 0 14 14" fill="none">
                                        <circle cx="7" cy="7" r="5.5" stroke="currentColor" stroke-width="1.4"/>
                                        <path d="M3 11L11 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                                    </svg>
                                </button>
                            @endif

                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="usr-action-btn usr-action-icon usr-action-edit"
                               title="Editar">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                    <path d="M9 2l2 2-7.5 7.5H1.5v-2L9 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                                </svg>
                            </a>

                            @if($user->id !== auth()->id())
                                <button class="usr-action-btn usr-action-icon usr-action-delete"
                                        wire:click="delete({{ $user->id }})"
                                        wire:confirm="Tem certeza que deseja remover {{ $user->name }}?"
                                        title="Excluir">
                                    <svg width="12" height="13" viewBox="0 0 12 14" fill="none">
                                        <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            @endif

                        </div>
                    </div>

                @empty
                    <div class="usr-empty-state">
                        <p class="usr-empty-desc">Nenhum usuário encontrado.</p>
                    </div>
                @endforelse
            </div>

            <div class="usr-pagination">
                {{ $users->links() }}
            </div>

        @endif

    </div>

</div>