@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Editar Usuário')
@section('content')

    <ul class="mir-breadcrumb">
        <li class="mir-breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house" style="font-size:.65rem"></i></a></li>
        <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
        <li class="mir-breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
        <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
        <li class="mir-breadcrumb-item active">Editar</li>
    </ul>

    <!-- Page Header Action -->
    <div class="page-header-action">
        <div class="page-header-left">
            <div class="page-header-title">Editar Usuário</div>
            <div class="page-header-sub">Atualize os dados de {{ $user->name }}</div>
        </div>
        <div class="page-header-right">
            <a href="{{ route('admin.users.index') }}" class="mir-btn-neutral">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
            <button type="button" class="mir-btn-primary-lg" onclick="document.getElementById('edit-user-form').submit()">
                <i class="fa-solid fa-floppy-disk"></i> Salvar Alterações
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

    <form action="{{ route('admin.users.update', $user) }}" method="POST" id="edit-user-form">
        @csrf
        @method('PUT')

        {{-- Dados do Usuário --}}
        <div class="post-section">
            <div class="post-section-header">
                <div class="section-icon-header">
                    <span class="section-icon section-icon-indigo"><i class="fa-solid fa-user-pen"></i></span>
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
                                value="{{ old('name', $user->name) }}">
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
                                value="{{ old('username', $user->username) }}">
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
                                value="{{ old('email', $user->email) }}">
                            @error('email')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Role --}}
                    @if(auth()->user()->isOwner())
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="mir-label">Role</label>
                            <select name="role" class="mir-input @error('role') is-invalid @enderror">
                                <option value="visitor" {{ $user->role->value == 'visitor' ? 'selected' : '' }}>Visitor</option>
                                <option value="author"  {{ $user->role->value == 'author'  ? 'selected' : '' }}>Author</option>
                                <option value="owner"   {{ $user->role->value == 'owner'   ? 'selected' : '' }}>Owner</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @endif

                    {{-- Bio --}}
                    <div class="col-12">
                        <div class="form-group">
                            <label class="mir-label">Bio</label>
                            <textarea name="bio" rows="4"
                                class="mir-input @error('bio') is-invalid @enderror"
                                placeholder="Breve descrição...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Auto-aprovação de posts (somente para authors) --}}
                    @if(auth()->user()->isOwner() && $user->isAuthor())
                    <div class="col-12">
                        <hr class="form-divider">
                        <div class="form-group" style="margin-bottom: 0;">
                            <div class="mir-switch-wrap">
                                <input type="hidden" name="auto_approve_posts" value="0">
                                <input type="checkbox" name="auto_approve_posts" value="1" id="auto_approve_posts"
                                    class="mir-switch-input"
                                    {{ old('auto_approve_posts', $user->settings?->auto_approve_posts) ? 'checked' : '' }}>
                                <label for="auto_approve_posts" class="mir-switch-label">
                                    <span class="mir-switch-track"><span class="mir-switch-thumb"></span></span>
                                    <span class="mir-switch-text">Aprovar posts automaticamente</span>
                                </label>
                            </div>
                            <div style="margin-top: 6px; font-size: .75rem; color: #9ca3af;">
                                Quando ativado, os posts deste author são publicados diretamente sem precisar de aprovação do admin.
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Timestamps --}}
                <div class="form-timestamps" style="margin-top: 16px;">
                    <span>
                        <i class="fa fa-calendar" style="width: 14px; color: #c4c9d4;"></i>
                        Cadastrado em: {{ $user->created_at->format('d/m/Y \à\s H:i') }}
                    </span>
                    @if ($user->updated_at->ne($user->created_at))
                        <span>
                            <i class="fa fa-edit" style="width: 14px; color: #c4c9d4;"></i>
                            Atualizado em: {{ $user->updated_at->format('d/m/Y \à\s H:i') }}
                        </span>
                    @endif
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
