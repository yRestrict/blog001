<div>

    {{-- ── Cards de contagem ───────────────────────────────────────────────────── --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card card-box border-left-warning text-center py-2 cursor-pointer"
                 wire:click="$set('filterStatus', 'pending')">
                <div class="card-body py-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendentes</div>
                    <div class="h3 mb-0 font-weight-bold">{{ $pendingCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-box border-left-success text-center py-2 cursor-pointer"
                 wire:click="$set('filterStatus', 'approved')">
                <div class="card-body py-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aprovados</div>
                    <div class="h3 mb-0 font-weight-bold">{{ $approvedCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-box border-left-danger text-center py-2 cursor-pointer"
                 wire:click="$set('filterStatus', 'rejected')">
                <div class="card-body py-2">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejeitados</div>
                    <div class="h3 mb-0 font-weight-bold">{{ $rejectedCount }}</div>
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

            <select wire:model.live="filterStatus" class="form-control" style="width: 160px;">
                <option value="">Todos</option>
                <option value="pending">Pendentes</option>
                <option value="approved">Aprovados</option>
                <option value="rejected">Rejeitados</option>
            </select>
        </div>

        @if ($pendingCount > 0)
            <button wire:click="approveAll()"
                    wire:confirm="Aprovar todos os comentários pendentes?"
                    class="btn btn-success btn-sm">
                <i class="fa fa-check-double"></i> Aprovar Todos ({{ $pendingCount }})
            </button>
        @endif
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
                        <th>Status</th>
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
                                <a href="#" class="text-truncate d-block" style="max-width:150px;"
                                   title="{{ $comment->post?->title }}">
                                    {{ Str::limit($comment->post?->title, 30) }}
                                </a>
                            </td>
                            <td style="max-width: 250px;">
                                @if ($comment->parent_id)
                                    <small class="text-muted">
                                        <i class="fa fa-reply"></i> Em resposta ao comentário #{{ $comment->parent_id }}
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
                            <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                            <td>
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
                                        wire:confirm="Tem certeza que deseja excluir este comentário?"
                                        class="btn btn-sm btn-danger mb-1">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Nenhum comentário encontrado.
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

</div>