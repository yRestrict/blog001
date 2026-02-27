<div>

    {{-- ── Boas-vindas ───────────────────────────────────────────────────────── --}}
    <div class="mb-4">
        <h5 class="text-muted">
            Olá, <strong>{{ auth()->user()->name }}</strong>! Aqui está um resumo do seu blog.
        </h5>
    </div>

    {{-- ── Cards de Posts ─────────────────────────────────────────────────────── --}}
    <div class="row mb-4">

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-box h-100 border-left-primary">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total de Posts</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalPosts }}</div>
                    </div>
                    <div class="fa-2x text-gray-300"><i class="fa fa-file-alt"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-box h-100 border-left-success">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Publicados</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $publishedPosts }}</div>
                    </div>
                    <div class="fa-2x text-gray-300"><i class="fa fa-check-circle"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-box h-100 border-left-warning">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Rascunhos</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $draftPosts }}</div>
                    </div>
                    <div class="fa-2x text-gray-300"><i class="fa fa-pencil-alt"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-box h-100 border-left-secondary">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Privados</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $privatePosts }}</div>
                    </div>
                    <div class="fa-2x text-gray-300"><i class="fa fa-lock"></i></div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Cards de Tags, Categorias, Likes e Comentários Pendentes ────────────── --}}
    <div class="row mb-4">

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-box h-100 border-left-info">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tags</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalTags }}</div>
                    </div>
                    <div class="fa-2x text-gray-300"><i class="fa fa-tags"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-box h-100 border-left-danger">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Categorias</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalCategories }}</div>
                    </div>
                    <div class="fa-2x text-gray-300"><i class="fa fa-folder"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-box h-100 border-left-danger">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total de Likes</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalLikes }}</div>
                    </div>
                    <div class="fa-2x text-gray-300"><i class="fa fa-heart"></i></div>
                </div>
            </div>
        </div>

        {{-- Comentários Pendentes — clicável, muda de cor se houver pendentes --}}
        <div class="col-xl-3 col-md-6 mb-3">
            <a href="{{ route('admin.comments.index') }}" class="text-decoration-none">
                <div class="card card-box h-100 {{ $pendingComments > 0 ? 'border-left-warning' : 'border-left-success' }}">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1
                                {{ $pendingComments > 0 ? 'text-warning' : 'text-success' }}">
                                Comentários Pendentes
                            </div>
                            <div class="h3 mb-0 font-weight-bold">{{ $pendingComments }}</div>
                            <small class="{{ $pendingComments > 0 ? 'text-warning' : 'text-success' }}">
                                {{ $pendingComments > 0 ? 'Clique para moderar' : 'Tudo em dia!' }}
                            </small>
                        </div>
                        <div class="fa-2x text-gray-300"><i class="fa fa-comments"></i></div>
                    </div>
                </div>
            </a>
        </div>

    </div>

    {{-- ── Atalhos rápidos ──────────────────────────────────────────────────────── --}}
    <div class="row mb-4">

        <div class="col-xl-3 col-md-6 mb-3">
            <a href="{{ route('admin.posts.create') }}" class="text-decoration-none">
                <div class="card card-box h-100 bg-primary text-white">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1 opacity-75">Ação Rápida</div>
                            <div class="h5 mb-0 font-weight-bold">+ Novo Post</div>
                        </div>
                        <div class="fa-2x opacity-50"><i class="fa fa-plus-circle"></i></div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <a href="{{ route('admin.tags.index') }}" class="text-decoration-none">
                <div class="card card-box h-100 bg-info text-white">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1 opacity-75">Ação Rápida</div>
                            <div class="h5 mb-0 font-weight-bold">Gerenciar Tags</div>
                        </div>
                        <div class="fa-2x opacity-50"><i class="fa fa-tags"></i></div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <a href="{{ route('admin.comments.index') }}" class="text-decoration-none">
                <div class="card card-box h-100 bg-secondary text-white">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1 opacity-75">Ação Rápida</div>
                            <div class="h5 mb-0 font-weight-bold">Moderar Comentários</div>
                        </div>
                        <div class="fa-2x opacity-50"><i class="fa fa-comments"></i></div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <a href="{{ route('admin.categories.index') }}" class="text-decoration-none">
                <div class="card card-box h-100 bg-danger text-white">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1 opacity-75">Ação Rápida</div>
                            <div class="h5 mb-0 font-weight-bold">Categorias</div>
                        </div>
                        <div class="fa-2x opacity-50"><i class="fa fa-folder"></i></div>
                    </div>
                </div>
            </a>
        </div>

    </div>

    {{-- ── Últimos Posts ───────────────────────────────────────────────────────── --}}
    <div class="card card-box">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold">Últimos Posts</h6>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Título</th>
                        <th>Categoria</th>
                        <th>Autor</th>
                        <th><i class="fa fa-heart text-danger"></i> Likes</th>
                        <th><i class="fa fa-comments text-info"></i> Coments</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestPosts as $post)
                        <tr>
                            <td>
                                <strong>{{ Str::limit($post->title, 40) }}</strong>
                                @if ($post->featured)
                                    <span class="badge badge-warning ml-1">Destaque</span>
                                @endif
                            </td>
                            <td>{{ $post->category?->name ?? '—' }}</td>
                            <td>{{ $post->author?->name ?? '—' }}</td>
                            <td>
                                <span class="text-danger font-weight-bold">
                                    <i class="fa fa-heart"></i> {{ $post->likes_count }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.comments.index') }}" class="text-info font-weight-bold">
                                    <i class="fa fa-comments"></i> {{ $post->comments_count }}
                                </a>
                            </td>
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
                            <td>{{ $post->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.posts.edit', $post->id) }}"
                                   class="btn btn-sm btn-info">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Nenhum post criado ainda.
                                <a href="{{ route('admin.posts.create') }}">Criar o primeiro!</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>