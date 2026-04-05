{{-- livewire/admin/comments-moderation.blade.php --}}
<div>

    {{-- ================================================================ --}}
    {{-- SCOPED STYLES                                                    --}}
    {{-- ================================================================ --}}
    <style>
        .cmt-section {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            margin-bottom: 24px;
        }
        .cmt-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }
        .cmt-section-header-left { min-width: 0; }
        .cmt-section-title { font-size: .95rem; font-weight: 700; color: #1a1d23; margin: 0; }
        .cmt-section-sub { font-size: .78rem; color: #9ca3af; margin-top: 2px; }
        .cmt-filter-header {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }
        .cmth-author  { width: 160px; flex-shrink: 0; }
        .cmth-body    { flex: 1 1 0; min-width: 0; }
        .cmth-post    { width: 140px; flex-shrink: 0; }
        .cmth-date    { width: 90px; flex-shrink: 0; text-align: right; }
        .cmth-status  { width: 90px; flex-shrink: 0; text-align: center; }
        .cmth-actions { width: 100px; flex-shrink: 0; text-align: center; }
        .plh-divider  { width: 1px; height: 16px; background: #e5e7eb; margin: 0 10px; flex-shrink: 0; }
        .cmt-author   { width: 160px; flex-shrink: 0; min-width: 0; }
        .cmt-author-name {
            font-size: .875rem; font-weight: 600; color: #1a1d23;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .cmt-author-email {
            font-size: .72rem; color: #9ca3af;
            font-family: ui-monospace, monospace;
            margin-top: 1px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .cmt-body { flex: 1 1 0; min-width: 0; }
        .cmt-reply-badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 2px 8px; border-radius: 50px;
            font-size: .65rem; font-weight: 600;
            background: #ede9fe; color: #6d28d9;
            margin-bottom: 3px;
        }
        .cmt-text {
            font-size: .82rem; color: #374151; line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .cmt-post { width: 140px; flex-shrink: 0; min-width: 0; }
        .cmt-post-link {
            font-size: .78rem; font-weight: 500; color: #6366f1;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            display: block; text-decoration: none; transition: color .15s;
        }
        .cmt-post-link:hover { color: #4f46e5; text-decoration: underline; }
        .cmt-mute-btn {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 2px 7px; border-radius: 4px;
            font-size: .65rem; font-weight: 600;
            cursor: pointer; transition: all .15s;
            margin-top: 4px; border: 1px solid;
        }
        .cmt-mute-btn.is-muted { background: #fef3c7; color: #92400e; border-color: #fde68a; }
        .cmt-mute-btn.is-muted:hover { background: #fde68a; border-color: #f59e0b; }
        .cmt-mute-btn.not-muted { background: transparent; color: #9ca3af; border-color: #e5e7eb; }
        .cmt-mute-btn.not-muted:hover { background: #f3f4f6; color: #6b7280; border-color: #d1d5db; }
        .cmt-date {
            width: 90px; flex-shrink: 0;
            font-size: .72rem; color: #9ca3af; text-align: right;
        }
        #cmt-toast-container {
            position: fixed; bottom: 24px; right: 24px;
            z-index: 9999; display: flex; flex-direction: column; gap: 10px;
        }

        /* ── Aviso de replies no modal ──────────────────── */
        .cmt-delete-warning {
            display: flex; gap: 12px; align-items: flex-start;
            background: #fff7ed; border: 1px solid #fed7aa;
            border-radius: 8px; padding: 12px 14px;
            margin-top: 14px; font-size: .82rem; color: #9a3412;
        }
        .cmt-delete-warning i { flex-shrink: 0; margin-top: 2px; }
    </style>

    {{-- ================================================================ --}}
    {{-- TOAST                                                            --}}
    {{-- ================================================================ --}}
    <div id="cmt-toast-container" aria-live="polite"></div>

    {{-- ================================================================ --}}
    {{-- PAGE HEADER ACTION                                               --}}
    {{-- ================================================================ --}}
    <div class="page-header-action">
        <div class="page-header-left">
            <h1 class="page-header-title">
                Comentários
                <span class="page-header-title-count">{{ $totalCount }}</span>
            </h1>
            <span class="page-header-sub">Modere os comentários do blog</span>
        </div>
        <div class="page-header-right">
            @if($showTrash)
                <button wire:click="$set('showTrash', false)" class="mir-btn-neutral">
                    <i class="fa-solid fa-arrow-left"></i> Voltar
                </button>
            @else
                @if($isOwner)
                    <button wire:click="$set('showTrash', true)" class="mir-btn-neutral">
                        <i class="fa-solid fa-trash-can"></i> Lixeira
                        @if($trashCount > 0)
                            <span style="display:inline-flex;align-items:center;justify-content:center;min-width:18px;height:18px;padding:0 5px;border-radius:50px;font-size:.65rem;font-weight:700;background:#fee2e2;color:#991b1b;margin-left:2px;">{{ $trashCount }}</span>
                        @endif
                    </button>
                @endif
                @if($pendingCount > 0)
                    <button wire:click="approveAll()"
                            wire:confirm="Aprovar todos os comentários pendentes?"
                            class="mir-btn-success">
                        <i class="fa-solid fa-check-double"></i> Aprovar Todos ({{ $pendingCount }})
                    </button>
                @endif
            @endif
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- STAT WIDGETS                                                     --}}
    {{-- ================================================================ --}}
    @unless($showTrash)
    <div class="widgets-grid">
        <div class="widget-card widget-card-pending">
            <div class="widget-info">
                <span class="widget-label">Pendentes</span>
                <span class="widget-value">{{ $pendingCount }}</span>
            </div>
            <div class="widget-icon widget-icon-pending">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
        <div class="widget-card widget-card-approved">
            <div class="widget-info">
                <span class="widget-label">Aprovados</span>
                <span class="widget-value">{{ $approvedCount }}</span>
            </div>
            <div class="widget-icon widget-icon-approved">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
        <div class="widget-card widget-card-rejected">
            <div class="widget-info">
                <span class="widget-label">Rejeitados</span>
                <span class="widget-value">{{ $rejectedCount }}</span>
            </div>
            <div class="widget-icon widget-icon-rejected">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        </div>
        <div class="widget-card widget-card-comments">
            <div class="widget-info">
                <span class="widget-label">Total</span>
                <span class="widget-value">{{ $totalCount }}</span>
            </div>
            <div class="widget-icon widget-icon-comments">
                <i class="fa-solid fa-comments"></i>
            </div>
        </div>
    </div>
    @endunless

    {{-- ================================================================ --}}
    {{-- SECTION CARD                                                     --}}
    {{-- ================================================================ --}}
    <div class="cmt-section">

        <div class="cmt-section-header">
            <div class="cmt-section-header-left">
                <h3 class="cmt-section-title">{{ $showTrash ? 'Lixeira' : 'Comentários' }}</h3>
                <p class="cmt-section-sub">{{ $comments->total() }} {{ $showTrash ? 'na lixeira' : 'comentários encontrados' }}</p>
            </div>
        </div>

        <div class="cmt-filter-header">
            <div style="position:relative; flex:1; max-width:280px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.75rem;"></i>
                <input type="text" wire:model.live.debounce.300ms="search" class="mir-input" placeholder="Pesquisar comentários..." style="padding-left:32px;">
            </div>
            @if(! $showTrash)
                <select wire:model.live="filterStatus" class="mir-input" style="width:160px;">
                    <option value="">Todos os status</option>
                    <option value="pending">Pendentes</option>
                    <option value="approved">Aprovados</option>
                    <option value="rejected">Rejeitados</option>
                </select>
            @endif
        </div>

        <div class="mir-loading-track" wire:loading.class="mir-loading-active"></div>

        <div class="mir-table-header">
            <span class="cmth-author">Autor</span>
            <span class="cmth-body">Comentário</span>
            <span class="cmth-post">Post</span>
            <span class="plh-divider"></span>
            @if(! $showTrash)
                <span class="cmth-status">Status</span>
                <span class="plh-divider"></span>
            @endif
            <span class="cmth-date">Data</span>
            <span class="plh-divider"></span>
            <span class="cmth-actions">Ações</span>
        </div>

        <div class="mir-data-list" wire:loading.class="mir-loading-overlay">
            @forelse($comments as $comment)
                <div class="mir-data-row" wire:key="cmt-{{ $comment->id }}">

                    <div class="cmt-author">
                        <div class="cmt-author-name">{{ $comment->author_name }}</div>
                        @if($comment->guest_email)
                            <div class="cmt-author-email">{{ $comment->guest_email }}</div>
                        @elseif($comment->user?->email)
                            <div class="cmt-author-email">{{ $comment->user->email }}</div>
                        @endif
                    </div>

                    <div class="cmt-body">
                        @if($comment->parent_id)
                            <span class="cmt-reply-badge">
                                <i class="fa-solid fa-reply" style="font-size:.55rem"></i> Resposta #{{ $comment->parent_id }}
                            </span>
                        @endif
                        <div class="cmt-text">{{ Str::limit($comment->body, 100) }}</div>
                    </div>

                    <div class="cmt-post">
                        @if($comment->post)
                            @php $isMuted = in_array($comment->post->id, $mutedPostIds); @endphp
                            <a href="{{ route('frontend.post', $comment->post->slug) }}"
                               target="_blank"
                               class="cmt-post-link"
                               data-tooltip="{{ $comment->post->title }}">
                                {{ Str::limit($comment->post->title, 25) }}
                            </a>
                            <button wire:click="openMuteModal({{ $comment->post->id }}, '{{ addslashes($comment->post->title) }}')"
                                    class="cmt-mute-btn {{ $isMuted ? 'is-muted' : 'not-muted' }}"
                                    data-tooltip="{{ $isMuted ? 'Notificações silenciadas — clique para ajustar' : 'Silenciar notificações deste post' }}">
                                <i class="fa-solid {{ $isMuted ? 'fa-bell-slash' : 'fa-bell' }}" style="font-size:.6rem"></i>
                                {{ $isMuted ? 'Mutado' : 'Mute' }}
                            </button>
                        @else
                            <span style="font-size:.78rem;color:#d1d5db;">— removido —</span>
                        @endif
                    </div>

                    <div class="mir-divider"></div>

                    @if(! $showTrash)
                        <div style="width:90px;flex-shrink:0;display:flex;justify-content:center;">
                            <span class="mir-status is-{{ $comment->status }}" data-tooltip="Status do comentário">
                                <span class="mir-status-ring"></span>
                                {{ match($comment->status) {
                                    'approved' => 'Aprovado',
                                    'rejected' => 'Rejeitado',
                                    default    => 'Pendente',
                                } }}
                            </span>
                        </div>
                        <div class="mir-divider"></div>
                    @endif

                    <div class="cmt-date">
                        {{ $comment->created_at->format('d/m/Y') }}
                        <div style="font-size:.65rem;margin-top:1px;">{{ $comment->created_at->format('H:i') }}</div>
                    </div>

                    <div class="mir-divider"></div>

                    <div class="mir-actions" style="width:100px;justify-content:center;">
                        @if($showTrash)
                            @if($isOwner)
                                <button wire:click="restore({{ $comment->id }})"
                                        class="mir-action-btn mir-action-restore"
                                        data-tooltip="Restaurar comentário">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="1 4 1 10 7 10"></polyline>
                                        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                                    </svg>
                                </button>
                                <button wire:click="forceDelete({{ $comment->id }})"
                                        wire:confirm="Excluir permanentemente? Esta ação não pode ser desfeita."
                                        class="mir-action-btn mir-action-delete"
                                        data-tooltip="Excluir permanentemente">
                                    <svg width="12" height="13" viewBox="0 0 12 14" fill="none">
                                        <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            @endif
                        @else
                            @if($comment->status !== 'approved')
                                <button wire:click="approve({{ $comment->id }})"
                                        class="mir-action-btn mir-action-restore"
                                        data-tooltip="Aprovar comentário">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </button>
                            @endif
                            @if($comment->status !== 'rejected')
                                <button wire:click="reject({{ $comment->id }})"
                                        class="mir-action-btn mir-action-delete"
                                        data-tooltip="Rejeitar comentário">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            @endif
                            @if($isOwner)
                                <button wire:click="prepareDelete({{ $comment->id }})"
                                        class="mir-action-btn mir-action-delete"
                                        data-tooltip="Mover para lixeira">
                                    <svg width="12" height="13" viewBox="0 0 12 14" fill="none">
                                        <path d="M1 3.5h10M4 3.5V2.5h4v1M2 3.5l.8 8a1 1 0 001 .9h4.4a1 1 0 001-.9l.8-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="mir-empty-state">
                    <div class="mir-empty-icon"><i class="fa-solid fa-comments"></i></div>
                    <h3 class="mir-empty-title">
                        {{ $showTrash ? 'Lixeira vazia' : 'Nenhum comentário encontrado' }}
                    </h3>
                    <p class="mir-empty-desc">
                        @if($showTrash) Nenhum comentário na lixeira.
                        @elseif($search || $filterStatus) Tente ajustar os filtros ou termos de busca.
                        @else Ainda não há comentários para moderar.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        @if($comments->hasPages())
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
                    <button wire:click="previousPage" class="mir-page-btn {{ $comments->onFirstPage() ? 'disabled' : '' }}" {{ $comments->onFirstPage() ? 'disabled' : '' }}>
                        <i class="fa-solid fa-chevron-left" style="font-size:.6rem"></i>
                    </button>
                    @foreach($comments->getUrlRange(1, $comments->lastPage()) as $page => $url)
                        <button wire:click="gotoPage({{ $page }})" class="mir-page-btn {{ $page == $comments->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </button>
                    @endforeach
                    <button wire:click="nextPage" class="mir-page-btn {{ !$comments->hasMorePages() ? 'disabled' : '' }}" {{ !$comments->hasMorePages() ? 'disabled' : '' }}>
                        <i class="fa-solid fa-chevron-right" style="font-size:.6rem"></i>
                    </button>
                </div>
                <div class="mir-pagination-right">
                    Mostrando {{ $comments->firstItem() }}–{{ $comments->lastItem() }} de {{ $comments->total() }}
                </div>
            </div>
        @endif
    </div>

    {{-- ================================================================ --}}
    {{-- MODAL DE EXCLUSÃO                                                --}}
    {{-- ================================================================ --}}
    @if($deletingCommentId)
        <div class="mir-modal-overlay" style="display:flex" x-data x-on:keydown.escape.window="$wire.cancelDelete()">
            <div class="mir-modal-dialog" style="max-width:540px">
                <div class="mir-modal-content">
                    <div class="mir-modal-header">
                        <div class="mir-modal-title">
                            <div class="mir-modal-icon mir-modal-icon-delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </div>
                            <div>
                                <div class="mir-modal-title-text">Mover para lixeira</div>
                                <div class="mir-modal-subtitle">Esta ação pode ser revertida</div>
                            </div>
                        </div>
                        <button wire:click="cancelDelete" class="mir-modal-close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="mir-modal-body">
                        <p style="color:#6d7279;font-size:.9rem;line-height:1.6;margin:0;">
                            Tem certeza que deseja mover este comentário para a lixeira?
                            <br>
                            <span style="font-size:.82rem;color:#9ca3af;margin-top:6px;display:block;">
                                "{{ $deletingCommentBody }}"
                            </span>
                        </p>

                        {{-- Aviso de replies — só aparece se houver respostas --}}
                        @if($deletingRepliesCount > 0)
                            <div class="cmt-delete-warning">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <div>
                                    <strong>Atenção:</strong> este comentário possui
                                    <strong>{{ $deletingRepliesCount }} {{ $deletingRepliesCount === 1 ? 'resposta' : 'respostas' }}</strong>.
                                    Ao movê-lo para a lixeira, todas as respostas serão movidas junto.
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="mir-modal-footer">
                        <button wire:click="cancelDelete" class="mir-btn-ghost">Cancelar</button>
                        <button wire:click="destroy" class="mir-btn-danger">
                            <i class="fa fa-trash-alt" style="font-size:.75rem"></i>
                            {{ $deletingRepliesCount > 0 ? 'Sim, mover tudo para lixeira' : 'Mover para lixeira' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ================================================================ --}}
    {{-- MODAL DE MUTE                                                    --}}
    {{-- ================================================================ --}}
    @if($muteModal)
        <div class="mir-modal-overlay" style="display:flex" x-data x-on:keydown.escape.window="$wire.closeMuteModal()">
            <div class="mir-modal-dialog" style="max-width:480px">
                <div class="mir-modal-content">
                    <div class="mir-modal-header">
                        <div class="mir-modal-title">
                            <div class="mir-modal-icon mir-modal-icon-edit">
                                <i class="fa-solid fa-bell-slash"></i>
                            </div>
                            <div>
                                <div class="mir-modal-title-text">Silenciar notificações</div>
                                <div class="mir-modal-subtitle">{{ $mutePostTitle }}</div>
                            </div>
                        </div>
                        <button wire:click="closeMuteModal" class="mir-modal-close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="mir-modal-body">
                        <div class="mb-3">
                            <div class="mir-switch-wrap">
                                <input type="checkbox" class="mir-switch-input" id="muteLikes" wire:model="muteLikes">
                                <label class="mir-switch-label" for="muteLikes">
                                    <span class="mir-switch-track"><span class="mir-switch-thumb"></span></span>
                                    <span class="mir-switch-text">Silenciar likes/dislikes deste post</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <div class="mir-switch-wrap">
                                <input type="checkbox" class="mir-switch-input" id="muteComments" wire:model="muteComments">
                                <label class="mir-switch-label" for="muteComments">
                                    <span class="mir-switch-track"><span class="mir-switch-thumb"></span></span>
                                    <span class="mir-switch-text">Silenciar comentários deste post</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mir-modal-footer">
                        <button wire:click="closeMuteModal" class="mir-btn-ghost">Cancelar</button>
                        <button wire:click="saveMute" class="mir-btn-primary-lg">
                            <i class="fa-solid fa-floppy-disk" style="font-size:.75rem"></i> Salvar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ================================================================ --}}
    {{-- SCRIPTS                                                          --}}
    {{-- ================================================================ --}}
    @push('scripts')
    <script>
    function cmtShowToast(type, message) {
        const container = document.getElementById('cmt-toast-container');
        const icons    = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
        const classes  = { success: 'mir-toast-success', error: 'mir-toast-error', warning: 'mir-toast-error', info: 'mir-toast-info' };
        const toast    = document.createElement('div');
        toast.className = `mir-toast ${classes[type] || classes.info}`;
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
        Livewire.on('notify', ({ type, message }) => cmtShowToast(type, message));
    });
    </script>
    @endpush

</div>