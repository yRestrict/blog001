@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Editar Usuário')
@section('content')

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

            <div class="usr-form-card">

                <div class="usr-form-header">
                    <div class="usr-form-icon usr-form-icon-edit">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M13 3l2 2-9 9H4v-2l9-9z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <p class="usr-form-title">Editando: {{ $user->name }}</p>
                        <p class="usr-form-subtitle">Altere os campos que desejar e salve</p>
                    </div>
                </div>

                <div style="padding: 24px;">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 col-sm-12">
                                <div class="usr-form-group">
                                    <label class="mir-label">Nome <span class="mir-required">*</span></label>
                                    <input type="text" name="name"
                                        class="mir-input @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}">
                                    @error('name')<div class="mir-field-error">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="usr-form-group">
                                    <label class="mir-label">Username <span class="mir-required">*</span></label>
                                    <input type="text" name="username"
                                        class="mir-input @error('username') is-invalid @enderror"
                                        value="{{ old('username', $user->username) }}">
                                    @error('username')<div class="mir-field-error">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="usr-form-group">
                                    <label class="mir-label">Email <span class="mir-required">*</span></label>
                                    <input type="email" name="email"
                                        class="mir-input @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}">
                                    @error('email')<div class="mir-field-error">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            @if(auth()->user()->isOwner())
                            <div class="col-md-6 col-sm-12">
                                <div class="usr-form-group">
                                    <label class="mir-label">Role</label>
                                    <select name="role" class="mir-input @error('role') is-invalid @enderror">
                                        <option value="visitor" {{ $user->role->value == 'visitor' ? 'selected' : '' }}>Visitor</option>
                                        <option value="author"  {{ $user->role->value == 'author'  ? 'selected' : '' }}>Author</option>
                                        <option value="owner"   {{ $user->role->value == 'owner'   ? 'selected' : '' }}>Owner</option>
                                    </select>
                                    @error('role')<div class="mir-field-error">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            @endif

                            <div class="col-12">
                                <div class="usr-form-group">
                                    <label class="mir-label">Bio</label>
                                    <textarea name="bio" rows="4"
                                        class="mir-input @error('bio') is-invalid @enderror"
                                        style="height:auto; resize:vertical; padding: 10px 12px; line-height:1.6;"
                                        placeholder="Breve descrição...">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')<div class="mir-field-error">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Auto-aprovação de posts (somente para authors) --}}
                            @if(auth()->user()->isOwner() && $user->isAuthor())
                            <div class="col-12">
                                <div class="usr-setting-block">
                                    <div class="usr-setting-icon">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M2 8l4 4 8-8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <div class="usr-setting-content">
                                        <p class="usr-setting-title">Auto-aprovação de Posts</p>
                                        <p class="usr-setting-desc">
                                            Quando ativado, os posts deste author são publicados diretamente sem precisar de aprovação do admin.
                                        </p>
                                    </div>
                                    <div style="flex-shrink:0;">
                                        <div class="mir-switch-wrap">
                                            <input type="checkbox"
                                                   class="mir-switch-input"
                                                   id="autoApprovePosts"
                                                   name="auto_approve_posts"
                                                   value="1"
                                                   {{ old('auto_approve_posts', $user->settings?->auto_approve_posts) ? 'checked' : '' }}>
                                            <label class="mir-switch-label" for="autoApprovePosts">
                                                <span class="mir-switch-track">
                                                    <span class="mir-switch-thumb"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>

                        <div class="usr-form-footer">
                            <a href="{{ route('admin.users.index') }}" class="mir-btn-ghost">Cancelar</a>
                            <button type="submit" class="mir-btn-primary-lg">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M2 6l3 3 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Salvar Alterações
                            </button>
                        </div>

                    </form>
                </div>
            </div>
@endsection