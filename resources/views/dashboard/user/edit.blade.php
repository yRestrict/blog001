@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Editar Usuário')
@section('content')
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">

            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title"><h4>Editar Usuário</h4></div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
                                <li class="breadcrumb-item active">Editar</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Editando: {{ $user->name }}</h4>
                </div>
                <div class="pd-20">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nome <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username', $user->username) }}">
                                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            @if(auth()->user()->isOwner())
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                                        <option value="visitor" {{ $user->role->value == 'visitor' ? 'selected' : '' }}>Visitor</option>
                                        <option value="author"  {{ $user->role->value == 'author'  ? 'selected' : '' }}>Author</option>
                                        <option value="owner"   {{ $user->role->value == 'owner'   ? 'selected' : '' }}>Owner</option>
                                    </select>
                                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            @endif

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Bio</label>
                                    <textarea name="bio" rows="4"
                                        class="form-control @error('bio') is-invalid @enderror"
                                        placeholder="Breve descrição...">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Auto-aprovação de posts (somente para authors) --}}
                            @if(auth()->user()->isOwner() && $user->isAuthor())
                            <div class="col-12">
                                <div class="card card-box mb-3" style="border-left: 4px solid #007bff;">
                                    <div class="card-body py-3">
                                        <h6 class="font-weight-bold mb-1">
                                            ✅ Auto-aprovação de Posts
                                        </h6>
                                        <p class="text-muted small mb-2">
                                            Quando ativado, os posts deste author são publicados diretamente sem precisar de aprovação do admin.
                                        </p>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox"
                                                   class="custom-control-input"
                                                   id="autoApprovePosts"
                                                   name="auto_approve_posts"
                                                   value="1"
                                                   {{ old('auto_approve_posts', $user->settings?->auto_approve_posts) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="autoApprovePosts">
                                                Publicar posts automaticamente (sem aprovação)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>

                        <div class="form-group text-right">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection