@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Criar Usuário')
@section('content')

    <ul class="mir-breadcrumb">
        <li class="mir-breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house" style="font-size:.65rem"></i></a></li>
        <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
        <li class="mir-breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
        <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
        <li class="mir-breadcrumb-item active">Criar</li>
    </ul>

    <!-- Page Header Action -->
    <div class="page-header-action">
        <div class="page-header-left">
            <div class="page-header-title">Criar Usuário</div>
            <div class="page-header-sub">Preencha os dados para cadastrar um novo usuário</div>
        </div>
        <div class="page-header-right">
            <a href="{{ route('admin.users.index') }}" class="mir-btn-neutral">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
            <button type="button" class="mir-btn-primary-lg" onclick="document.getElementById('create-user-form').submit()">
                <i class="fa-solid fa-floppy-disk"></i> Criar Usuário
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="warning-box" style="margin-bottom: 20px;">
            <strong><i class="fa fa-exclamation-triangle"></i> Corrija os erros abaixo:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" id="create-user-form">
        @csrf

        {{-- Dados do Usuário --}}
        <div class="post-section">
            <div class="post-section-header">
                <div class="section-icon-header">
                    <span class="section-icon section-icon-indigo"><i class="fa-solid fa-user-plus"></i></span>
                    <div>
                        <div class="post-section-title">Dados do Usuário</div>
                        <div class="post-section-sub">Informações básicas da conta</div>
                    </div>
                </div>
            </div>
            <div style="padding: 20px;">
                <div class="row">

                    {{-- Nome --}}
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="mir-label">Nome <span class="mir-required">*</span></label>
                            <input type="text" name="name"
                                class="mir-input @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Nome completo">
                            @error('name')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Username --}}
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="mir-label">Username <span class="mir-required">*</span></label>
                            <input type="text" name="username"
                                class="mir-input @error('username') is-invalid @enderror"
                                value="{{ old('username') }}" placeholder="@username">
                            @error('username')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="mir-label">Email <span class="mir-required">*</span></label>
                            <input type="email" name="email"
                                class="mir-input @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="email@exemplo.com">
                            @error('email')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="mir-label">Role <span class="mir-required">*</span></label>
                            <select name="role" class="mir-input @error('role') is-invalid @enderror">
                                <option value="">Selecione...</option>
                                <option value="visitor" {{ old('role') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                                <option value="author" {{ old('role') == 'author' ? 'selected' : '' }}>Author</option>
                                <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Senha --}}
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="mir-label">Senha <span class="mir-required">*</span></label>
                            <input type="password" name="password"
                                class="mir-input @error('password') is-invalid @enderror"
                                placeholder="Mínimo 8 caracteres">
                            @error('password')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Confirmar Senha --}}
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="mir-label">Confirmar Senha <span class="mir-required">*</span></label>
                            <input type="password" name="password_confirmation"
                                class="mir-input"
                                placeholder="Repita a senha">
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </form>

@endsection

@push('stylesheets')
<style>
    .post-section {
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .post-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        gap: 16px;
    }
    .post-section-title {
        font-size: .95rem;
        font-weight: 700;
        color: #1a1d23;
        margin: 0;
    }
    .post-section-sub {
        font-size: .78rem;
        color: #9ca3af;
        margin: 2px 0 0;
    }
</style>
@endpush
