<div>
    {{-- Barra de ações --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   class="form-control"
                   placeholder="Pesquisar posts..."
                   style="width: 260px;">

            <select wire:model.live="filterStatus" class="form-control" style="width: 160px;">
                <option value="">Todos os status</option>
                <option value="draft">Rascunho</option>
                <option value="published">Publicado</option>
                <option value="private">Privado</option>
            </select>
        </div>

        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            + Novo Post
        </a>
    </div>

    {{-- Tabela --}}
    <div class="card card-box">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Categoria</th>
                        <th>Tags</th>
                        <th>Autor</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>
                                <strong>{{ $post->title }}</strong>
                                @if ($post->featured)
                                    <span class="badge badge-warning ml-1">Destaque</span>
                                @endif
                            </td>
                            <td>{{ $post->category?->name ?? '—' }}</td>
                            <td>
                                @foreach ($post->tags as $tag)
                                    <span class="badge badge-secondary">{{ $tag->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $post->author?->name ?? '—' }}</td>
                            <td>
                                <button wire:click="toggleStatus({{ $post->id }})"
                                        class="badge border-0
                                            @if ($post->status === 'published') badge-success
                                            @elseif ($post->status === 'private') badge-dark
                                            @else badge-secondary @endif">
                                    {{ match($post->status) {
                                        'published' => 'Publicado',
                                        'private'   => 'Privado',
                                        default     => 'Rascunho',
                                    } }}
                                </button>
                            </td>
                            <td>{{ $post->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.posts.edit', $post->id) }}"
                                   class="btn btn-sm btn-info">Editar</a>

                                @if ($confirmingDelete === $post->id)
                                    <span class="text-danger small mr-1">Confirmar?</span>
                                    <button wire:click="deletePost({{ $post->id }})"
                                            class="btn btn-sm btn-danger">Sim</button>
                                    <button wire:click="cancelDelete()"
                                            class="btn btn-sm btn-secondary">Não</button>
                                @else
                                    <button wire:click="confirmDelete({{ $post->id }})"
                                            class="btn btn-sm btn-danger">Excluir</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Nenhum post encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginação --}}
    <div class="mt-3">
        {{ $posts->links() }}
    </div>
</div>