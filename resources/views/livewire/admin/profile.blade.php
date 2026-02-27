{{-- livewire/admin/profile.blade.php --}}
<div>
<style>
    /* ── Layout ──────────────────────────────────────────────────────────── */
    .prf-wrap { display: flex; gap: 24px; align-items: flex-start; }

    /* ── Card Lateral (Avatar) ───────────────────────────────────────────── */
    .prf-sidebar {
        width: 260px;
        flex-shrink: 0;
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e9ecef;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
        overflow: hidden;
    }
    .prf-avatar-wrap {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 32px 20px 20px;
        background: linear-gradient(160deg, #6366f1 0%, #4f46e5 100%);
    }
    .prf-avatar-img {
        width: 96px; height: 96px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,.6);
        box-shadow: 0 4px 16px rgba(0,0,0,.2);
    }
    .prf-avatar-edit {
        position: absolute;
        right: calc(50% - 56px);
        width: 28px; height: 28px;
        border-radius: 50%;
        background: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,.2);
        transition: transform .15s;
        color: #6366f1;
        font-size: .75rem;
    }
    .prf-avatar-edit:hover { transform: scale(1.12); }
    .prf-name {
        font-size: .95rem;
        font-weight: 700;
        color: #fff;
        margin: 12px 0 2px;
        text-align: center;
    }
    .prf-email {
        font-size: .75rem;
        color: rgba(255,255,255,.75);
        text-align: center;
    }
    .prf-info-list { padding: 16px 20px; }
    .prf-info-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid #f5f5f5;
        font-size: .8rem;
    }
    .prf-info-item:last-child { border-bottom: none; }
    .prf-info-icon {
        width: 28px; height: 28px;
        border-radius: 8px;
        background: #ede9fe;
        color: #6366f1;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: .72rem;
    }
    .prf-info-label { font-size: .7rem; color: #9ca3af; }
    .prf-info-value { font-weight: 600; color: #374151; word-break: break-all; }

    /* Social links */
    .prf-social { padding: 0 20px 20px; }
    .prf-social-title {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #9ca3af;
        margin-bottom: 10px;
    }
    .prf-social-links { display: flex; flex-wrap: wrap; gap: 8px; }
    .prf-social-btn {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: .82rem;
        color: #fff;
        transition: opacity .15s, transform .15s;
        text-decoration: none;
    }
    .prf-social-btn:hover { opacity: .85; transform: scale(1.08); color: #fff; }

    /* ── Painel direito ───────────────────────────────────────────────────── */
    .prf-main {
        flex: 1;
        min-width: 0;
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e9ecef;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
        overflow: hidden;
    }

    /* ── Tabs mir- ───────────────────────────────────────────────────────── */
    .mir-tabs {
        display: flex;
        gap: 2px;
        padding: 16px 20px 0;
        border-bottom: 1px solid #f0f0f0;
        background: #fafafa;
    }
    .mir-tab-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 16px;
        border-radius: 8px 8px 0 0;
        font-size: .82rem; font-weight: 600;
        color: #6d7279;
        background: transparent;
        border: none; cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: color .15s, background .15s;
        margin-bottom: -1px;
    }
    .mir-tab-btn:hover { color: #6366f1; background: #fff; }
    .mir-tab-btn.active {
        color: #6366f1;
        background: #fff;
        border-bottom: 2px solid #6366f1;
    }
    .mir-tab-content { padding: 28px; }

    /* ── Form fields (mir-) ──────────────────────────────────────────────── */
    .mir-label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .mir-required { color: #ef4444; }
    .mir-optional { font-size: .72rem; font-weight: 400; color: #9ca3af; }
    .mir-input {
        width: 100%; padding: 8px 12px;
        border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-size: .85rem; color: #1a1d23; background: #fff;
        outline: none; transition: border-color .15s, box-shadow .15s;
    }
    .mir-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
    .mir-input.is-invalid { border-color: #ef4444; }
    .mir-input:disabled { background: #f9fafb; color: #9ca3af; cursor: not-allowed; }
    .mir-field-error { font-size: .78rem; color: #ef4444; margin-top: 4px; }
    .mir-field-hint  { font-size: .75rem; color: #9ca3af; margin-top: 4px; }

    /* Input com ícone prefixo */
    .mir-input-group { position: relative; }
    .mir-input-icon {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: .82rem; pointer-events: none;
    }
    .mir-input-group .mir-input { padding-left: 34px; }

    /* ── Botão primário ──────────────────────────────────────────────────── */
    .mir-btn-primary-lg {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 20px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff; font-size: .83rem; font-weight: 600;
        border: none; cursor: pointer;
        transition: opacity .15s;
        box-shadow: 0 2px 8px rgba(99,102,241,.35);
    }
    .mir-btn-primary-lg:hover { opacity: .9; }

    /* ── Seção info ──────────────────────────────────────────────────────── */
    .mir-info-box {
        display: flex; gap: 12px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 24px;
        font-size: .8rem;
        color: #92400e;
    }
    .mir-info-box i { margin-top: 1px; flex-shrink: 0; }

    /* ── Danger zone (senha) ─────────────────────────────────────────────── */
    .mir-danger-card {
        background: #fff5f5;
        border: 1px solid #fecaca;
        border-radius: 10px;
        padding: 20px;
        margin-top: 24px;
    }
    .mir-danger-title {
        font-size: .82rem; font-weight: 700; color: #b91c1c;
        display: flex; align-items: center; gap: 6px; margin-bottom: 4px;
    }
    .mir-danger-desc { font-size: .78rem; color: #9ca3af; margin-bottom: 0; }

    /* Responsivo */
    @media (max-width: 768px) {
        .prf-wrap { flex-direction: column; }
        .prf-sidebar { width: 100%; }
    }

    /* Modal crop (mantido igual ao master.blade) */
    .mir-modal-overlay {
        position: fixed; inset: 0;
        background: rgba(17,24,39,.55);
        backdrop-filter: blur(2px);
        z-index: 1060;
        display: flex; align-items: center; justify-content: center;
        padding: 16px;
    }
    .mir-modal-dialog-lg { width: 100%; max-width: 680px; animation: mir-modal-in .2s ease; }
    @keyframes mir-modal-in {
        from { opacity:0; transform:translateY(-12px) scale(.97); }
        to   { opacity:1; transform:translateY(0) scale(1); }
    }
    .mir-modal-content {
        background: #fff; border-radius: 14px;
        box-shadow: 0 20px 60px rgba(0,0,0,.18); overflow: hidden;
    }
    .mir-modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 22px; border-bottom: 1px solid #f0f0f0;
    }
    .mir-modal-title-text { font-size: .93rem; font-weight: 700; color: #1a1d23; }
    .mir-modal-close {
        width: 32px; height: 32px; border-radius: 8px;
        border: none; background: transparent; color: #9ca3af;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: background .15s; font-size: .9rem;
    }
    .mir-modal-close:hover { background: #f3f4f6; color: #374151; }
    .mir-modal-body   { padding: 22px; }
    .mir-modal-footer {
        display: flex; justify-content: flex-end; gap: 10px;
        padding: 16px 22px; border-top: 1px solid #f0f0f0; background: #fafafa;
    }
    .mir-btn-ghost {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; border-radius: 8px;
        background: transparent; color: #6d7279;
        font-size: .82rem; font-weight: 600;
        border: 1px solid #e0e0e0; cursor: pointer;
        transition: background .15s;
    }
    .mir-btn-ghost:hover { background: #f5f5f5; }
</style>

{{-- Input de arquivo oculto --}}
<input type="file" id="ProfilePicture" class="d-none"
       accept="image/jpeg,image/jpg,image/png,image/webp"
       onchange="showCropModal(event)">

{{-- ====================================================================== --}}
{{-- MODAL: RECORTAR FOTO                                                    --}}
{{-- ====================================================================== --}}
<div class="mir-modal-overlay" id="cropModal" style="display:none;">
    <div class="mir-modal-dialog-lg">
        <div class="mir-modal-content">
            <div class="mir-modal-header">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="width:34px;height:34px;border-radius:9px;background:rgba(99,102,241,.12);color:#6366f1;display:flex;align-items:center;justify-content:center;">
                        <i class="fa fa-crop"></i>
                    </span>
                    <div class="mir-modal-title-text">Recortar Foto de Perfil</div>
                </div>
                <button type="button" class="mir-modal-close" data-dismiss="modal" onclick="$('#cropModal').modal('hide')">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="mir-modal-body">
                <div class="img-container">
                    <img id="CropImagePreview" style="max-width:100%; display:block;">
                </div>
            </div>
            <div class="mir-modal-footer">
                <button type="button" class="mir-btn-ghost" data-dismiss="modal">Cancelar</button>
                <button type="button" class="mir-btn-primary-lg" id="crop_button">
                    <i class="fa fa-crop"></i> Cortar e Salvar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ====================================================================== --}}
{{-- LAYOUT PRINCIPAL                                                         --}}
{{-- ====================================================================== --}}
<div class="prf-wrap">

    {{-- ── SIDEBAR: Avatar + Info ─────────────────────────────────────── --}}
    <div class="prf-sidebar">

        <div class="prf-avatar-wrap">
            <img src="{{ $user->picture }}" alt="{{ $user->name }}"
                 class="prf-avatar-img" id="user_profile_img">
            <a href="javascript:void(0)"
               onclick="document.getElementById('ProfilePicture').click();"
               class="prf-avatar-edit" title="Alterar foto">
                <i class="fa fa-pencil"></i>
            </a>
            <div class="prf-name">{{ $user->name }}</div>
            <div class="prf-email">{{ $user->email }}</div>
        </div>

        <div class="prf-info-list">
            <div class="prf-info-item">
                <div class="prf-info-icon"><i class="fa fa-at"></i></div>
                <div>
                    <div class="prf-info-label">Username</div>
                    <div class="prf-info-value">{{ $user->username }}</div>
                </div>
            </div>
            <div class="prf-info-item">
                <div class="prf-info-icon"><i class="fa fa-shield"></i></div>
                <div>
                    <div class="prf-info-label">Papel</div>
                    <div class="prf-info-value" style="text-transform:capitalize;">{{ $user->role ?? 'Admin' }}</div>
                </div>
            </div>
            @if($user->bio)
            <div class="prf-info-item">
                <div class="prf-info-icon"><i class="fa fa-align-left"></i></div>
                <div>
                    <div class="prf-info-label">Bio</div>
                    <div class="prf-info-value" style="font-weight:400; font-size:.78rem; line-height:1.5;">{{ $user->bio }}</div>
                </div>
            </div>
            @endif
        </div>

        {{-- Social links --}}
        @php
            $hasAny = $user->socialLinks?->hasAnyLink();
        @endphp
        <div class="prf-social">
            <div class="prf-social-title">Redes Sociais</div>
            @if($hasAny)
                <div class="prf-social-links">
                    @if($user->socialLinks?->facebook_url)
                        <a href="{{ $user->socialLinks->facebook_url }}" target="_blank"
                           class="prf-social-btn" style="background:#3b5998;" title="Facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                    @endif
                    @if($user->socialLinks?->twitter_url)
                        <a href="{{ $user->socialLinks->twitter_url }}" target="_blank"
                           class="prf-social-btn" style="background:#1da1f2;" title="Twitter/X">
                            <i class="fa fa-twitter"></i>
                        </a>
                    @endif
                    @if($user->socialLinks?->instagram_url)
                        <a href="{{ $user->socialLinks->instagram_url }}" target="_blank"
                           class="prf-social-btn" style="background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);" title="Instagram">
                            <i class="fa fa-instagram"></i>
                        </a>
                    @endif
                    @if($user->socialLinks?->youtube_url)
                        <a href="{{ $user->socialLinks->youtube_url }}" target="_blank"
                           class="prf-social-btn" style="background:#ff0000;" title="YouTube">
                            <i class="fa fa-youtube"></i>
                        </a>
                    @endif
                    @if($user->socialLinks?->whatsapp_url)
                        <a href="{{ $user->socialLinks->whatsapp_url }}" target="_blank"
                           class="prf-social-btn" style="background:#25d366;" title="WhatsApp">
                            <i class="fa fa-whatsapp"></i>
                        </a>
                    @endif
                    @if($user->socialLinks?->steam_url)
                        <a href="{{ $user->socialLinks->steam_url }}" target="_blank"
                           class="prf-social-btn" style="background:#171a21;" title="Steam">
                            <i class="fa fa-steam"></i>
                        </a>
                    @endif
                </div>
            @else
                <p style="font-size:.78rem; color:#9ca3af; margin:0;">
                    Nenhuma rede social cadastrada.
                </p>
            @endif
        </div>

    </div>

    {{-- ── PAINEL PRINCIPAL: Tabs ──────────────────────────────────────── --}}
    <div class="prf-main">

        {{-- Tabs --}}
        <div class="mir-tabs">
            <button class="mir-tab-btn {{ $tab === 'personal_details' ? 'active' : '' }}"
                    wire:click="selectTab('personal_details')">
                <i class="fa fa-user"></i> Dados Pessoais
            </button>
            <button class="mir-tab-btn {{ $tab === 'update_password' ? 'active' : '' }}"
                    wire:click="selectTab('update_password')">
                <i class="fa fa-lock"></i> Senha
            </button>
            <button class="mir-tab-btn {{ $tab === 'social_link' ? 'active' : '' }}"
                    wire:click="selectTab('social_link')">
                <i class="fa fa-share-alt"></i> Redes Sociais
            </button>
        </div>

        <div class="mir-tab-content">

            {{-- ── TAB: Dados Pessoais ────────────────────────────────── --}}
            @if($tab === 'personal_details')
                <form wire:submit.prevent="updatePersonalDetails">
                    <div class="row">

                        <div class="col-12 mb-3">
                            <label class="mir-label">Nome completo <span class="mir-required">*</span></label>
                            <input type="text"
                                   class="mir-input @error('name') is-invalid @enderror"
                                   wire:model.defer="name"
                                   placeholder="Seu nome completo">
                            @error('name') <div class="mir-field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="mir-label">E-mail <span class="mir-optional">(não editável)</span></label>
                            <div class="mir-input-group">
                                <i class="fa fa-envelope mir-input-icon"></i>
                                <input type="email" class="mir-input" value="{{ $user->email }}" disabled>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="mir-label">Username <span class="mir-required">*</span></label>
                            <div class="mir-input-group">
                                <i class="fa fa-at mir-input-icon"></i>
                                <input type="text"
                                       class="mir-input @error('username') is-invalid @enderror"
                                       wire:model.defer="username"
                                       placeholder="seu_username">
                            </div>
                            @error('username') <div class="mir-field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mb-4">
                            <label class="mir-label">Bio <span class="mir-optional">(opcional)</span></label>
                            <textarea class="mir-input"
                                      wire:model.defer="bio"
                                      rows="4"
                                      style="resize:vertical;"
                                      placeholder="Fale um pouco sobre você..."></textarea>
                            @error('bio') <div class="mir-field-error">{{ $message }}</div> @enderror
                        </div>

                    </div>
                    <button type="submit" class="mir-btn-primary-lg"
                            wire:loading.attr="disabled" wire:target="updatePersonalDetails">
                        <span wire:loading wire:target="updatePersonalDetails">
                            <span class="spinner-border spinner-border-sm mr-1"></span>
                        </span>
                        <i class="fa fa-save" wire:loading.remove wire:target="updatePersonalDetails"></i>
                        Salvar alterações
                    </button>
                </form>
            @endif

            {{-- ── TAB: Senha ─────────────────────────────────────────── --}}
            @if($tab === 'update_password')
                <div class="mir-info-box">
                    <i class="fa fa-info-circle"></i>
                    <span>Após alterar a senha, você será <strong>desconectado automaticamente</strong> e precisará fazer login novamente.</span>
                </div>

                <form wire:submit.prevent="updatePassword">
                    <div class="row">

                        <div class="col-12 mb-3">
                            <label class="mir-label">Senha atual <span class="mir-required">*</span></label>
                            <div class="mir-input-group">
                                <i class="fa fa-key mir-input-icon"></i>
                                <input type="password"
                                       class="mir-input @error('currentPassword') is-invalid @enderror"
                                       wire:model.defer="currentPassword"
                                       placeholder="Digite sua senha atual">
                            </div>
                            @error('currentPassword') <div class="mir-field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="mir-label">Nova senha <span class="mir-required">*</span></label>
                            <div class="mir-input-group">
                                <i class="fa fa-lock mir-input-icon"></i>
                                <input type="password"
                                       class="mir-input @error('newPassword') is-invalid @enderror"
                                       wire:model.defer="newPassword"
                                       placeholder="Mínimo 5 caracteres">
                            </div>
                            @error('newPassword') <div class="mir-field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="mir-label">Confirmar nova senha <span class="mir-required">*</span></label>
                            <div class="mir-input-group">
                                <i class="fa fa-check mir-input-icon"></i>
                                <input type="password"
                                       class="mir-input @error('newPassword_confirmation') is-invalid @enderror"
                                       wire:model.defer="newPassword_confirmation"
                                       placeholder="Repita a nova senha">
                            </div>
                            @error('newPassword_confirmation') <div class="mir-field-error">{{ $message }}</div> @enderror
                        </div>

                    </div>

                    <button type="submit"
                            class="mir-btn-primary-lg"
                            style="background:linear-gradient(135deg,#ef4444,#dc2626); box-shadow:0 2px 8px rgba(239,68,68,.35);"
                            wire:loading.attr="disabled" wire:target="updatePassword">
                        <span wire:loading wire:target="updatePassword">
                            <span class="spinner-border spinner-border-sm mr-1"></span>
                        </span>
                        <i class="fa fa-lock" wire:loading.remove wire:target="updatePassword"></i>
                        Atualizar senha
                    </button>
                </form>
            @endif

            {{-- ── TAB: Redes Sociais ─────────────────────────────────── --}}
            @if($tab === 'social_link')
                @livewire('admin.social-links')
            @endif

        </div>
    </div>

</div>
</div>