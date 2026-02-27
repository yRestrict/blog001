<div>

    {{-- ── Barra de ações ──────────────────────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   class="form-control"
                   placeholder="Pesquisar na lixeira..."
                   style="width: 260px;">

            @if ($trashCount > 0)
                <span class="badge badge-danger ml-2" style="font-size: 0.9rem;">
                    {{ $trashCount }} {{ Str::plural('post', $trashCount) }} na lixeira
                </span>
            @endif
        </div>

        <div>
            @if ($trashCount > 0)
                <button wire:click="restoreAll()"
                        wire:confirm="Restaurar todos os posts da lixeira?"
                        class="btn btn-sm btn-success mr-2">
                    <i class="fa fa-undo"></i> Restaurar Todos
                </button>

                <button wire:click="emptyTrash()"
                        wire:confirm="Tem certeza? Esta ação é irreversível e excluirá todos os posts permanentemente!"
                        class="btn btn-sm btn-danger">
                    <i class="fa fa-trash"></i> Esvaziar Lixeira
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
                        <th>Título</th>
                        <th>Categoria</th>
                        <th>Autor</th>
                        <th>Status</th>
                        <th>Excluído em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>
                                <strong>{{ Str::limit($post->title, 50) }}</strong>
                                @if ($post->featured)
                                    <span class="badge badge-warning ml-1">Destaque</span>
                                @endif
                            </td>
                            <td>{{ $post->category?->name ?? '—' }}</td>
                            <td>{{ $post->author?->name ?? '—' }}</td>
                            <td>
                                <span class="badge
                                    @if ($post->status === 'published') badge-success
                                    @elseif ($post->status === 'private') badge-dark
                                    @else badge-secondary @endif">
                                    {{ match($post->status) {
                                        'published' => 'Publicado',
                                        'private'   => 'Privado',
                                        default     => 'Rascunho',
                                    } }}
                                </span>
                            </td>
                            <td>
                                <span class="text-danger">
                                    {{ $post->deleted_at->format('d/m/Y H:i') }}
                                </span>
                                <small class="text-muted d-block">
                                    {{ $post->deleted_at->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                {{-- Restaurar --}}
                                <button wire:click="restore({{ $post->id }})"
                                        class="btn btn-sm btn-success mb-1">
                                    <i class="fa fa-undo"></i> Restaurar
                                </button>

                                {{-- Excluir permanentemente com confirmação inline --}}
                                @if ($confirmingForceDelete === $post->id)
                                    <span class="text-danger small d-block mb-1">
                                        <i class="fa fa-exclamation-triangle"></i> Isso é irreversível!
                                    </span>
                                    <button wire:click="forceDelete({{ $post->id }})"
                                            class="btn btn-sm btn-danger mb-1">
                                        <i class="fa fa-trash"></i> Confirmar
                                    </button>
                                    <button wire:click="cancelForceDelete()"
                                            class="btn btn-sm btn-secondary mb-1">
                                        Cancelar
                                    </button>
                                @else
                                    <button wire:click="confirmForceDelete({{ $post->id }})"
                                            class="btn btn-sm btn-outline-danger mb-1">
                                        <i class="fa fa-times"></i> Excluir
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fa fa-trash fa-2x mb-2 d-block"></i>
                                A lixeira está vazia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $posts->links() }}
    </div>

</div>