@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Criar Usuário')
@section('content')
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">

            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title"><h4>Criar Usuário</h4></div>
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

            <div class="usr-form-card">

                <div class="usr-form-header">
                    <div class="usr-form-icon usr-form-icon-add">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M9 4v10M4 9h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <p class="usr-form-title">Novo Usuário</p>
                        <p class="usr-form-subtitle">Preencha os dados para criar a conta</p>
                    </div>
                </div>

                <div style="padding: 24px;">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="row">

                            <div class="col-md-6 col-sm-12">
                                <div class="usr-form-group">
                                    <label class="mir-label">Nome <span class="mir-required">*</span></label>
                                    <input type="text" name="name"
                                        class="mir-input @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" placeholder="Nome completo">
                                    @error('name')
                                        <div class="mir-field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="usr-form-group">
                                    <label class="mir-label">Username <span class="mir-required">*</span></label>
                                    <input type="text" name="username"
                                        class="mir-input @error('username') is-invalid @enderror"
                                        value="{{ old('username') }}" placeholder="@username">
                                    @error('username')
                                        <div class="mir-field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="usr-form-group">
                                    <label class="mir-label">Email <span class="mir-required">*</span></label>
                                    <input type="email" name="email"
                                        class="mir-input @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" placeholder="email@exemplo.com">
                                    @error('email')
                                        <div class="mir-field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="usr-form-group">
                                    <label class="mir-label">Role <span class="mir-required">*</span></label>
                                    <select name="role" class="mir-input @error('role') is-invalid @enderror">
                                        <option value="">Selecione...</option>
                                        <option value="visitor" {{ old('role') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                                        <option value="author"  {{ old('role') == 'author'  ? 'selected' : '' }}>Author</option>
                                        <option value="owner"   {{ old('role') == 'owner'   ? 'selected' : '' }}>Owner</option>
                                    </select>
                                    @error('role')
                                        <div class="mir-field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="usr-form-group">
                                    <label class="mir-label">Senha <span class="mir-required">*</span></label>
                                    <input type="password" name="password"
                                        class="mir-input @error('password') is-invalid @enderror"
                                        placeholder="Mínimo 8 caracteres">
                                    @error('password')
                                        <div class="mir-field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="usr-form-group">
                                    <label class="mir-label">Confirmar Senha <span class="mir-required">*</span></label>
                                    <input type="password" name="password_confirmation"
                                        class="mir-input"
                                        placeholder="Repita a senha">
                                </div>
                            </div>

                        </div>

                        <div class="usr-form-footer">
                            <a href="{{ route('admin.users.index') }}" class="mir-btn-ghost">Cancelar</a>
                            <button type="submit" class="mir-btn-primary-lg">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M2 6l3 3 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Criar Usuário
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection