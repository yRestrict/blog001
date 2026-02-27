<div>
    {{-- Barra de ações --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               class="form-control"
               placeholder="Pesquisar tags..."
               style="width: 260px;">

        <button wire:click="openAdd()" class="btn btn-primary">
            + Nova Tag
        </button>
    </div>

    {{-- Tabela --}}
    <div class="card card-box">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Slug</th>
                        <th>Posts</th>
                        <th>Views</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tags as $tag)
                        <tr>
                            <td>{{ $tag->id }}</td>
                            <td><strong>{{ $tag->name }}</strong></td>
                            <td><code>{{ $tag->slug }}</code></td>
                            <td>{{ $tag->posts_count }}</td>
                            <td>{{ $tag->views }}</td>
                            <td>
                                <button wire:click="openEdit({{ $tag->id }})"
                                        class="btn btn-sm btn-info">Editar</button>

                                @if ($confirmingDelete === $tag->id)
                                    <span class="text-danger small mr-1">Confirmar?</span>
                                    <button wire:click="deleteTag({{ $tag->id }})"
                                            class="btn btn-sm btn-danger">Sim</button>
                                    <button wire:click="cancelDelete()"
                                            class="btn btn-sm btn-secondary">Não</button>
                                @else
                                    <button wire:click="confirmDelete({{ $tag->id }})"
                                            class="btn btn-sm btn-danger">Excluir</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Nenhuma tag encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginação --}}
    <div class="mt-3">
        {{ $tags->links() }}
    </div>

    {{-- ── Modal Criar / Editar Tag ─────────────────────────────────────────── --}}
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background:rgba(0,0,0,.5);">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $isEditing ? 'Editar Tag' : 'Nova Tag' }}</h5>
                        <button type="button" class="close" wire:click="closeModal()">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label><b>Nome da Tag</b></label>
                            <input type="text"
                                   wire:model.defer="tagName"
                                   class="form-control @error('tagName') is-invalid @enderror"
                                   placeholder="Ex: Laravel, PHP, JavaScript">
                            @error('tagName')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="save()" class="btn btn-primary">
                            {{ $isEditing ? 'Atualizar' : 'Criar Tag' }}
                        </button>
                        <button wire:click="closeModal()" class="btn btn-secondary">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>