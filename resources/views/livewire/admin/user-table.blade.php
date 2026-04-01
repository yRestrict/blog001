<div>

    {{-- ================================================================ --}}
    {{-- PAGE HEADER ACTION                                               --}}
    {{-- ================================================================ --}}
    <div class="page-header-action">
        <div class="page-header-left">
            <h1 class="page-header-title">
                Usuários
                <span class="page-header-title-count">{{ $totalUsers }}</span>
            </h1>
            <span class="page-header-sub">Gerencie todos os usuários do sistema</span>
        </div>
        <div class="page-header-right">
            <a href="{{ route('admin.users.create') }}" class="mir-btn-primary-lg">
                <i class="fa fa-plus" style="font-size:.7rem"></i> Novo Usuário
            </a>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- STAT WIDGETS                                                     --}}
    {{-- ================================================================ --}}
    <div class="widgets-grid" wire:loading.class="widgets-loading">
        <div class="widget-card widget-card-total">
            <div class="widget-info">
                <span class="widget-label">Total</span>
                <span class="widget-value">{{ $totalUsers }}</span>
            </div>
            <div class="widget-icon widget-icon-total">
                <i class="fa fa-users"></i>
            </div>
        </div>
        <div class="widget-card usr-widget-owner">
            <div class="widget-info">
                <span class="widget-label">Owners</span>
                <span class="widget-value">{{ $ownerCount }}</span>
            </div>
            <div class="widget-icon usr-widget-icon-owner">
                <i class="fa fa-crown"></i>
            </div>
        </div>
        <div class="widget-card usr-widget-author">
            <div class="widget-info">
                <span class="widget-label">Authors</span>
                <span class="widget-value">{{ $authorCount }}</span>
            </div>
            <div class="widget-icon usr-widget-icon-author">
                <i class="fa fa-pen-fancy"></i>
            </div>
        </div>
        <div class="widget-card usr-widget-visitor">
            <div class="widget-info">
                <span class="widget-label">Visitors</span>
                <span class="widget-value">{{ $visitorCount }}</span>
            </div>
            <div class="widget-icon usr-widget-icon-visitor">
                <i class="fa fa-user"></i>
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- SECTION CARD                                                     --}}
    {{-- ================================================================ --}}
    <div class="usr-section">

        {{-- Camada 1 — Section Header --}}
        <div class="usr-section-header">
            <div class="usr-section-header-left">
                <h3 class="usr-section-title">Lista de Usuários</h3>
                <p class="usr-section-sub">{{ $users->total() }} usuários encontrados</p>
            </div>
        </div>

        {{-- Camada 2 — Filter Header --}}
        <div class="usr-filter-header">
            <div style="position:relative; flex:1; max-width:280px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.75rem;"></i>
                <input type="text" wire:model.live.debounce.400ms="search" class="mir-input" placeholder="Buscar por nome, email ou username..." style="padding-left:32px;">
            </div>
            <select wire:model.live="role" class="mir-input" style="width:160px;">
                <option value="">Todos os roles</option>
                <option value="owner">Owner</option>
                <option value="author">Author</option>
                <option value="visitor">Visitor</option>
            </select>
            <select wire:model.live="status" class="mir-input" style="width:160px;">
                <option value="">Todos os status</option>
                <option value="active">Ativo</option>
                <option value="pending">Pendente</option>
                <option value="inactive">Inativo</option>
                <option value="banned">Banido</option>
                <option value="rejected">Rejeitado</option>
            </select>
        </div>

        {{-- Loading bar --}}
        <div wire:loading class="mir-loading-bar"></div>

        {{-- Table Header --}}
        <div class="mir-table-header">
            <span class="usr-col-avatar"></span>
            <span class="usr-col-body">Usuário</span>
            <span class="usr-col-username">Username</span>
            <span class="usr-col-role">Role</span>
            <span class="plh-divider"></span>
            <span class="usr-col-status">Status</span>
            <span class="plh-divider"></span>
            <span class="usr-col-date">Cadastro</span>
            <span class="plh-divider"></span>
            <span class="plh-actions">Ações</span>
        </div>

        {{-- Data Rows com loading overlay --}}
        <div class="mir-data-list" wire:loading.class="mir-loading-overlay">
            @forelse($users as $user)
                <div class="mir-data-row" wire:key="user-{{ $user->id }}">

                    {{-- Avatar --}}
                    <div class="usr-avatar">
                        @if($user->getRawOriginal('picture') && file_exists(public_path('uploads/author/' . $user->getRawOriginal('picture'))))
                            <img src="{{ $user->picture }}" alt="{{ $user->name }}">
                        @else
                            <span class="usr-avatar-initials" style="background:{{ ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4'][$user->id % 7] }}">
                                {{ strtoupper(mb_substr($user->name, 0, 1)) }}{{ strtoupper(mb_substr(explode(' ', $user->name)[1] ?? '', 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    {{-- Body: nome + email --}}
                    <div class="usr-body">
                        <div class="usr-name">{{ $user->name }}</div>
                        <div class="usr-email">{{ $user->email }}</div>
                    </div>

                    {{-- Username --}}
                    <div class="usr-col-username">
                        <span class="usr-username">{{ $user->username }}</span>
                    </div>

                    {{-- Role badge --}}
                    <div class="usr-col-role">
                        @if($user->isOwner())
                            <span class="usr-role-badge usr-role-owner">Owner</span>
                        @elseif($user->isAuthor())
                            <span class="usr-role-badge usr-role-author">Author</span>
                        @else
                            <span class="usr-role-badge usr-role-visitor">Visitor</span>
                        @endif
                    </div>

                    {{-- Divider --}}
                    <div class="mir-divider"></div>

                    {{-- Status Pill --}}
                    <div class="usr-col-status">
                        @if(!$user->isOwner() && $user->id !== auth()->id())
                            <button wire:click="toggleStatus({{ $user->id }})" class="mir-status {{ $user->isActive() ? 'is-active' : 'is-inactive' }}" data-tooltip="Clique para alterar status">
                                <span class="mir-status-ring"></span>
                                {{ $user->isActive() ? 'Ativo' : ($user->isBanned() ? 'Banido' : ucfirst($user->status->value)) }}
                            </button>
                        @else
                            <span class="mir-status {{ $user->isActive() ? 'is-active' : 'is-inactive' }}">
                                <span class="mir-status-ring"></span>
                                {{ $user->isActive() ? 'Ativo' : ucfirst($user->status->value) }}
                            </span>
                        @endif
                    </div>

                    {{-- Divider --}}
                    <div class="mir-divider"></div>

                    {{-- Data de cadastro --}}
                    <div class="usr-col-date">
                        <span class="usr-date">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>

                    {{-- Divider --}}
                    <div class="mir-divider"></div>

                    {{-- Actions --}}
                    <div class="mir-actions">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="mir-action-btn mir-action-edit" data-tooltip="Editar usuário">
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                <path d="M9 2l2 2-7.5 7.5H1.5v-2L9 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        @if($user->id !== auth()->id())
                            <button wire:click="prepareDelete({{ $user->id }})" class="mir-action-btn mir-action-delete" data-tooltip="Excluir usuário">
                                <svg width="12" height="13" viewBox="0 0 12 14" fill="none">
                                    <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="mir-empty-state">
                    <div class="mir-empty-icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <h3 class="mir-empty-title">Nenhum usuário encontrado</h3>
                    <p class="mir-empty-desc">
                        @if($search || $role || $status)
                            Tente ajustar os filtros ou termos de busca.
                        @else
                            Comece adicionando seu primeiro usuário.
                        @endif
                    </p>
                    @unless($search || $role || $status)
                        <a href="{{ route('admin.users.create') }}" class="mir-btn-primary-lg">
                            <i class="fa fa-plus" style="font-size:.7rem"></i> Novo Usuário
                        </a>
                    @endunless
                </div>
            @endforelse
        </div>

        {{-- Paginação --}}
        @if($users->hasPages())
            <div class="mir-pagination">
                <div class="mir-pagination-left">
                    <span class="mir-pagination-left-label">Por página:</span>
                    <select wire:model.live="perPage" class="mir-per-page">
                        <option value="6">6</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="mir-pagination-center">
                    {{-- Previous --}}
                    <button wire:click="previousPage" class="mir-page-btn {{ $users->onFirstPage() ? 'disabled' : '' }}" {{ $users->onFirstPage() ? 'disabled' : '' }}>
                        <i class="fa fa-chevron-left" style="font-size:.6rem"></i>
                    </button>
                    {{-- Page numbers --}}
                    @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        <button wire:click="gotoPage({{ $page }})" class="mir-page-btn {{ $page == $users->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </button>
                    @endforeach
                    {{-- Next --}}
                    <button wire:click="nextPage" class="mir-page-btn {{ !$users->hasMorePages() ? 'disabled' : '' }}" {{ !$users->hasMorePages() ? 'disabled' : '' }}>
                        <i class="fa fa-chevron-right" style="font-size:.6rem"></i>
                    </button>
                </div>
                <div class="mir-pagination-right">
                    Mostrando {{ $users->firstItem() }}–{{ $users->lastItem() }} de {{ $users->total() }}
                </div>
            </div>
        @endif
    </div>

    {{-- ================================================================ --}}
    {{-- MODAL DE EXCLUSÃO                                                --}}
    {{-- ================================================================ --}}
    @if($deletingUserId)
        <div class="mir-modal-overlay" style="display:flex" x-data x-on:keydown.escape.window="$wire.cancelDelete()">
            <div class="mir-modal-dialog" style="max-width:540px">
                <div class="mir-modal-content">

                    <div class="mir-modal-header">
                        <div class="mir-modal-title">
                            <div class="mir-modal-icon mir-modal-icon-delete">
                                <i class="fa fa-trash-alt"></i>
                            </div>
                            <div>
                                <div class="mir-modal-title-text">Excluir Usuário</div>
                                <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                            </div>
                        </div>
                        <button wire:click="cancelDelete" class="mir-modal-close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                    <div class="mir-modal-body">
                        <p style="color:#6d7279;font-size:.9rem;line-height:1.6;margin:0;">
                            Tem certeza que deseja excluir o usuário <strong style="color:#ef4444;">"{{ $deletingUserName }}"</strong>?
                            <br>
                            <span style="font-size:.8rem;color:#9ca3af;margin-top:6px;display:block;">
                                Todos os dados associados a este usuário serão removidos permanentemente.
                            </span>
                        </p>
                    </div>

                    <div class="mir-modal-footer">
                        <button wire:click="cancelDelete" class="mir-btn-ghost">Cancelar</button>
                        <button wire:click="deleteUser" class="mir-btn-danger">
                            <i class="fa fa-trash-alt" style="font-size:.75rem"></i> Sim, excluir
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    {{-- ================================================================ --}}
    {{-- TOAST                                                            --}}
    {{-- ================================================================ --}}
    <div id="usr-toast-container" aria-live="polite"></div>

    {{-- ================================================================ --}}
    {{-- SCOPED STYLES                                                    --}}
    {{-- ================================================================ --}}
    <style>
        /* ── Section Card (usr-specific) ──────────────────── */
        .usr-section {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            margin-bottom: 24px;
        }
        .usr-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }
        .usr-section-header-left { min-width: 0; }
        .usr-section-title { font-size: .95rem; font-weight: 700; color: #1a1d23; margin: 0; }
        .usr-section-sub { font-size: .78rem; color: #9ca3af; margin-top: 2px; }
        .usr-filter-header {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }

        /* ── Widget variants (usr-specific) ────────────────── */
        .usr-widget-owner::before   { background: #6366f1; }
        .usr-widget-author::before  { background: #10b981; }
        .usr-widget-visitor::before { background: #9ca3af; }

        .usr-widget-icon-owner {
            background: #e0e7ff;
            color: #4f46e5;
        }
        .usr-widget-icon-author {
            background: #d1fae5;
            color: #059669;
        }
        .usr-widget-icon-visitor {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* ── Column widths ─────────────────────────────────── */
        .usr-col-avatar {
            width: 36px;
            flex-shrink: 0;
        }
        .usr-col-body,
        .mir-table-header .usr-col-body {
            flex: 1 1 0;
            min-width: 0;
        }
        .usr-col-username {
            width: 120px;
            flex-shrink: 0;
        }
        .usr-col-role {
            width: 90px;
            flex-shrink: 0;
            display: flex;
            justify-content: center;
        }
        .usr-col-status {
            width: 90px;
            flex-shrink: 0;
            display: flex;
            justify-content: center;
        }
        .usr-col-date {
            width: 90px;
            flex-shrink: 0;
            text-align: right;
        }

        /* ── Avatar ────────────────────────────────────────── */
        .usr-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid #e5e7eb;
        }
        .usr-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .usr-avatar-initials {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: #fff;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .5px;
        }

        /* ── Body: nome + email ─────────────────────────────── */
        .usr-body {
            flex: 1 1 0;
            min-width: 0;
        }
        .usr-name {
            font-size: .875rem;
            font-weight: 600;
            color: #1a1d23;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .usr-email {
            font-size: .72rem;
            color: #9ca3af;
            margin-top: 1px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── Username ──────────────────────────────────────── */
        .usr-username {
            font-size: .72rem;
            color: #9ca3af;
            font-family: ui-monospace, monospace;
        }

        /* ── Role badges ───────────────────────────────────── */
        .usr-role-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: .72rem;
            font-weight: 700;
            white-space: nowrap;
        }
        .usr-role-owner {
            background: #e0e7ff;
            color: #3730a3;
        }
        .usr-role-author {
            background: #d1fae5;
            color: #065f46;
        }
        .usr-role-visitor {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* ── Date ──────────────────────────────────────────── */
        .usr-date {
            font-size: .75rem;
            color: #9ca3af;
            white-space: nowrap;
        }

        /* ── Toast container ───────────────────────────────── */
        #usr-toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    </style>

    {{-- ================================================================ --}}
    {{-- SCRIPTS: Toast                                                   --}}
    {{-- ================================================================ --}}
    @push('scripts')
    <script>
    /* ─── Toast ─────────────────────────────────────────────────────── */
    function usrShowToast(type, message) {
        const container = document.getElementById('usr-toast-container');
        if (!container) return;
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
        Livewire.on('notify', ({ type, message }) => usrShowToast(type, message));
    });
    </script>
    @endpush

</div>