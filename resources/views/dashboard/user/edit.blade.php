@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title Here')
@section('content')
 <div class="main-container">

    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="min-height-200px">

            {{-- Breadcrumb --}}
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Editar Usuário</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Editar</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            {{-- Formulário --}}
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Editando: {{ $user->name }}</h4>
                </div>
                <div class="pd-20">

                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            {{-- Nome --}}
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nome <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}" placeholder="Nome completo">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Username --}}
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username', $user->username) }}" placeholder="@username">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}" placeholder="email@exemplo.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Role — só owner pode mudar --}}
                            @if(auth()->user()->isOwner())
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                                        <option value="visitor" {{ $user->role->value == 'visitor' ? 'selected' : '' }}>Visitor</option>
                                        <option value="author" {{ $user->role->value == 'author' ? 'selected' : '' }}>Author</option>
                                        <option value="owner" {{ $user->role->value == 'owner' ? 'selected' : '' }}>Owner</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @endif

                            {{-- Bio --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Bio</label>
                                    <textarea name="bio" rows="4"
                                        class="form-control @error('bio') is-invalid @enderror"
                                        placeholder="Uma breve descrição sobre o usuário...">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

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