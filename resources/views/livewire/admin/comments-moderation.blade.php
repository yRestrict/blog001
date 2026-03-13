<div>

    {{-- ── Cards de contagem ───────────────────────────────────────────────────── --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-box border-left-warning text-center py-2 cursor-pointer"
                 wire:click="$set('filterStatus', 'pending'); $set('showTrash', false)">
                <div class="card-body py-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendentes</div>
                    <div class="h3 mb-0 font-weight-bold">{{ $pendingCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-box border-left-success text-center py-2 cursor-pointer"
                 wire:click="$set('filterStatus', 'approved'); $set('showTrash', false)">
                <div class="card-body py-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aprovados</div>
                    <div class="h3 mb-0 font-weight-bold">{{ $approvedCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-box border-left-danger text-center py-2 cursor-pointer"
                 wire:click="$set('filterStatus', 'rejected'); $set('showTrash', false)">
                <div class="card-body py-2">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejeitados</div>
                    <div class="h3 mb-0 font-weight-bold">{{ $rejectedCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-box border-left-secondary text-center py-2 cursor-pointer {{ $showTrash ? 'border-dark' : '' }}"
                 wire:click="$set('showTrash', true)">
                <div class="card-body py-2">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">🗑 Lixeira</div>
                    <div class="h3 mb-0 font-weight-bold">{{ $trashCount }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filtros ──────────────────────────────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   class="form-control"
                   placeholder="Pesquisar por conteúdo ou nome..."
                   style="width: 300px;">

            @if(! $showTrash)
                <select wire:model.live="filterStatus" class="form-control" style="width: 160px;">
                    <option value="">Todos</option>
                    <option value="pending">Pendentes</option>
                    <option value="approved">Aprovados</option>
                    <option value="rejected">Rejeitados</option>
                </select>
            @endif
        </div>

        <div class="d-flex gap-2">
            @if ($showTrash)
                <button wire:click="$set('showTrash', false)" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left"></i> Voltar
                </button>
            @elseif ($pendingCount > 0)
                <button wire:click="approveAll()"
                        wire:confirm="Aprovar todos os comentários pendentes?"
                        class="btn btn-success btn-sm">
                    <i class="fa fa-check-double"></i> Aprovar Todos ({{ $pendingCount }})
                </button>
            @endif
        </div>
    </div>

    {{-- ── Tabela ───────────────────────────────────────────────────────────────── --}}
    <div class="card card-box">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Autor</th>
                        <th>Post</th>
                        <th>Comentário</th>
                        <th>Tipo</th>
                        @if(! $showTrash)
                            <th>Status</th>
                        @endif
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($comments as $comment)
                        <tr>
                            <td>{{ $comment->id }}</td>
                            <td>
                                <strong>{{ $comment->author_name }}</strong>
                                @if ($comment->guest_email)
                                    <small class="text-muted d-block">{{ $comment->guest_email }}</small>
                                @endif
                                <small class="text-muted d-block">IP: {{ $comment->ip_address }}</small>
                            </td>
                            <td>
                                <a href="{{ route('frontend.post', $comment->post?->slug) }}"
                                   target="_blank"
                                   class="text-truncate d-block"
                                   style="max-width:150px;"
                                   title="{{ $comment->post?->title }}">
                                    {{ Str::limit($comment->post?->title, 30) }}
                                </a>
                                {{-- Botão de mute por post --}}
                                @if($comment->post)
                                    <button wire:click="openMuteModal({{ $comment->post->id }}, '{{ addslashes($comment->post->title) }}')"
                                            class="btn btn-xs btn-outline-secondary mt-1"
                                            title="Silenciar notificações deste post"
                                            style="font-size:11px; padding:1px 6px;">
                                        🔕 Mute
                                    </button>
                                @endif
                            </td>
                            <td style="max-width: 250px;">
                                @if ($comment->parent_id)
                                    <small class="text-muted">
                                        <i class="fa fa-reply"></i> Resposta ao #{{ $comment->parent_id }}
                                    </small><br>
                                @endif
                                {{ Str::limit($comment->body, 100) }}
                            </td>
                            <td>
                                @if ($comment->parent_id)
                                    <span class="badge badge-info">Reply</span>
                                @else
                                    <span class="badge badge-secondary">Comentário</span>
                                @endif
                            </td>

                            @if(! $showTrash)
                                <td>
                                    <span class="badge
                                        @if ($comment->status === 'approved') badge-success
                                        @elseif ($comment->status === 'rejected') badge-danger
                                        @else badge-warning @endif">
                                        {{ match($comment->status) {
                                            'approved' => 'Aprovado',
                                            'rejected' => 'Rejeitado',
                                            default    => 'Pendente',
                                        } }}
                                    </span>
                                </td>
                            @endif

                            <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>

                            <td>
                                @if($showTrash)
                                    {{-- Ações da lixeira --}}
                                    <button wire:click="restore({{ $comment->id }})"
                                            class="btn btn-sm btn-success mb-1">
                                        <i class="fa fa-undo"></i> Restaurar
                                    </button>
                                    <button wire:click="forceDelete({{ $comment->id }})"
                                            wire:confirm="Excluir permanentemente? Esta ação não pode ser desfeita."
                                            class="btn btn-sm btn-danger mb-1">
                                        <i class="fa fa-times"></i> Excluir
                                    </button>
                                @else
                                    {{-- Ações normais --}}
                                    @if ($comment->status !== 'approved')
                                        <button wire:click="approve({{ $comment->id }})"
                                                class="btn btn-sm btn-success mb-1">
                                            <i class="fa fa-check"></i> Aprovar
                                        </button>
                                    @endif

                                    @if ($comment->status !== 'rejected')
                                        <button wire:click="reject({{ $comment->id }})"
                                                class="btn btn-sm btn-warning mb-1">
                                            <i class="fa fa-ban"></i> Rejeitar
                                        </button>
                                    @endif

                                    <button wire:click="destroy({{ $comment->id }})"
                                            wire:confirm="Mover para lixeira?"
                                            class="btn btn-sm btn-danger mb-1">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                {{ $showTrash ? 'Lixeira vazia.' : 'Nenhum comentário encontrado.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $comments->links() }}
    </div>

    {{-- ── Modal de Mute ────────────────────────────────────────────────────────── --}}
    @if($muteModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">🔕 Silenciar notificações</h5>
                        <button wire:click="closeMuteModal" class="close"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-3">
                            Post: <strong>{{ $mutePostTitle }}</strong>
                        </p>

                        <div class="custom-control custom-switch mb-3">
                            <input type="checkbox"
                                   class="custom-control-input"
                                   id="muteLikes"
                                   wire:model="muteLikes">
                            <label class="custom-control-label" for="muteLikes">
                                😍 Silenciar likes/dislikes deste post
                            </label>
                        </div>

                        <div class="custom-control custom-switch">
                            <input type="checkbox"
                                   class="custom-control-input"
                                   id="muteComments"
                                   wire:model="muteComments">
                            <label class="custom-control-label" for="muteComments">
                                💬 Silenciar comentários deste post
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="closeMuteModal" class="btn btn-secondary">Cancelar</button>
                        <button wire:click="saveMute" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>