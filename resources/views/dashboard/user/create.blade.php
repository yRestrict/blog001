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
                            <h4>Criar Usuário</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Criar</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            {{-- Formulário --}}
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Novo Usuário</h4>
                </div>
                <div class="pd-20">

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="row">

                            {{-- Nome --}}
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nome <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" placeholder="Nome completo">
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
                                        value="{{ old('username') }}" placeholder="@username">
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
                                        value="{{ old('email') }}" placeholder="email@exemplo.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Role --}}
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Role <span class="text-danger">*</span></label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                                        <option value="">Selecione...</option>
                                        <option value="visitor" {{ old('role') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                                        <option value="author" {{ old('role') == 'author' ? 'selected' : '' }}>Author</option>
                                        <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Senha --}}
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Senha <span class="text-danger">*</span></label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Mínimo 8 caracteres">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Confirmar Senha --}}
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Confirmar Senha <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control"
                                        placeholder="Repita a senha">
                                </div>
                            </div>

                        </div>

                        <div class="form-group text-right">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Criar Usuário</button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection