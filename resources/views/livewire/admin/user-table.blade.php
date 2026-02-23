<div>

    @include('components.form-alerts')

    {{-- Filtros --}}
    <div class="card-box mb-20 pd-20">
        <div class="row">

            {{-- Busca --}}
            <div class="col-md-4 col-sm-12">
                <input wire:model.live.debounce.400ms="search" type="text"
                    class="form-control" placeholder="Buscar por nome, email ou username...">
            </div>

            {{-- Role --}}
            <div class="col-md-3 col-sm-12">
                <select wire:model.live="role" class="form-control">
                    <option value="">Todos os roles</option>
                    <option value="owner">Owner</option>
                    <option value="author">Author</option>
                    <option value="visitor">Visitor</option>
                </select>
            </div>

            {{-- Status --}}
            <div class="col-md-3 col-sm-12">
                <select wire:model.live="status" class="form-control">
                    <option value="">Todos os status</option>
                    <option value="active">Ativo</option>
                    <option value="pending">Pending</option>
                    <option value="banned">Banido</option>
                    <option value="inactive">Inativo</option>
                    <option value="rejected">Rejeitado</option>
                </select>
            </div>

            {{-- Limpar filtros --}}
            <div class="col-md-2 col-sm-12">
                <button wire:click="$set('search', ''); $set('role', ''); $set('status', '')"
                    class="btn btn-secondary btn-block">
                    Limpar
                </button>
            </div>

        </div>
    </div>

    {{-- Tabela --}}
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">
                Lista de Usuários
                <small class="text-muted" style="font-size: 14px;">
                    ({{ $users->total() }} encontrados)
                </small>
            </h4>
        </div>
        <div class="pb-20">
            <table class="table hover nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Foto</th>
                        <th wire:click="sort('name')" style="cursor:pointer">
                            Nome
                            @if($sortBy === 'name')
                                <i class="fa fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fa fa-sort text-muted"></i>
                            @endif
                        </th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th wire:click="sort('created_at')" style="cursor:pointer">
                            Criado em
                            @if($sortBy === 'created_at')
                                <i class="fa fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fa fa-sort text-muted"></i>
                            @endif
                        </th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <img src="{{ $user->picture }}" alt="{{ $user->name }}"
                                    class="border-radius-100" width="35" height="35"
                                    style="object-fit: cover;">
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->isOwner())
                                    <span class="badge badge-danger">Owner</span>
                                @elseif($user->isAuthor())
                                    <span class="badge badge-primary">Author</span>
                                @else
                                    <span class="badge badge-secondary">Visitor</span>
                                @endif
                            </td>
                            <td>
                                @if(!$user->isOwner() && $user->id !== auth()->id())
                                    <span wire:click="toggleStatus({{ $user->id }})"
                                        style="cursor:pointer"
                                        class="badge {{ $user->isActive() ? 'badge-success' : ($user->isBanned() ? 'badge-danger' : 'badge-warning') }}">
                                        {{ $user->isActive() ? 'Ativo' : ($user->isBanned() ? 'Banido' : ucfirst($user->status->value)) }}
                                    </span>
                                @else
                                    <span class="badge {{ $user->isActive() ? 'badge-success' : 'badge-warning' }}">
                                        {{ $user->isActive() ? 'Ativo' : ucfirst($user->status->value) }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle"
                                        type="button" data-toggle="dropdown">
                                        Ações
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">

                                        {{-- Editar --}}
                                        <a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}">
                                            <i class="fa fa-edit"></i> Editar
                                        </a>

                                        @if(!$user->isOwner())

                                            {{-- Promover / Remover cargo --}}
                                            @if($user->isVisitor())
                                                <button wire:click="promote({{ $user->id }})"
                                                    class="dropdown-item text-primary">
                                                    <i class="fa fa-arrow-up"></i> Promover a Autor
                                                </button>
                                            @endif

                                            @if($user->isAuthor())
                                                <button wire:click="demote({{ $user->id }})"
                                                    class="dropdown-item text-warning">
                                                    <i class="fa fa-arrow-down"></i> Remover cargo de Autor
                                                </button>
                                            @endif

                                            {{-- Banir --}}
                                            @if($user->id !== auth()->id())
                                                <button wire:click="ban({{ $user->id }})"
                                                    class="dropdown-item {{ $user->isBanned() ? 'text-success' : 'text-danger' }}">
                                                    <i class="fa fa-ban"></i>
                                                    {{ $user->isBanned() ? 'Desbanir' : 'Banir' }}
                                                </button>
                                            @endif

                                        @endif

                                        {{-- Deletar --}}
                                        @if($user->id !== auth()->id())
                                            <div class="dropdown-divider"></div>
                                            <button wire:click="delete({{ $user->id }})"
                                                wire:confirm="Tem certeza que deseja remover este usuário?"
                                                class="dropdown-item text-danger">
                                                <i class="fa fa-trash"></i> Remover
                                            </button>
                                        @endif

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Nenhum usuário encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-20 pt-10">
                {{ $users->links() }}
            </div>

        </div>
    </div>

</div>