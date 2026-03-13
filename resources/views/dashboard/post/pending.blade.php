@extends('dashboard.master')
@section('pageTitle', $pageTitle)
@section('content')

<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4>⏳ Posts Aguardando Aprovação</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
                    <li class="breadcrumb-item active">Aprovação</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right">
            <span class="badge badge-warning" style="font-size: 1rem; padding: 8px 16px;">
                {{ $posts->total() }} aguardando
            </span>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card card-box">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Author</th>
                    <th>Categoria</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>
                            <strong>{{ $post->title }}</strong>
                            @if($post->meta_description)
                                <small class="text-muted d-block">{{ Str::limit($post->meta_description, 80) }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $post->author->picture }}"
                                     class="rounded-circle"
                                     style="width:28px;height:28px;object-fit:cover;">
                                <span>{{ $post->author->name }}</span>
                            </div>
                        </td>
                        <td>{{ $post->category->name }}</td>
                        <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            {{-- Ver post --}}
                            <a href="{{ route('admin.posts.edit', $post) }}"
                               class="btn btn-sm btn-info mb-1"
                               target="_blank">
                                <i class="fa fa-eye"></i> Ver
                            </a>

                            {{-- Aprovar --}}
                            <form action="{{ route('admin.posts.approve', $post) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success mb-1">
                                    <i class="fa fa-check"></i> Aprovar
                                </button>
                            </form>

                            {{-- Rejeitar --}}
                            <form action="{{ route('admin.posts.reject', $post) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Rejeitar e mover para rascunho?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-danger mb-1">
                                    <i class="fa fa-times"></i> Rejeitar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fa fa-check-circle text-success" style="font-size:2rem;"></i>
                            <p class="mt-2">Nenhum post aguardando aprovação!</p>
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

@endsection