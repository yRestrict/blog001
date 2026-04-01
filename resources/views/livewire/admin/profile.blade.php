{{-- livewire/admin/profile.blade.php --}}
<div>

{{-- ================================================================ --}}
{{-- PAGE HEADER ACTION                                               --}}
{{-- ================================================================ --}}
<div class="page-header-action">
    <div class="page-header-left">
        <h1 class="page-header-title">Meu Perfil</h1>
        <span class="page-header-sub">Gerencie suas informações pessoais e segurança</span>
    </div>
</div>

{{-- Input de arquivo oculto --}}
<input type="file" id="ProfilePicture" class="d-none"
       accept="image/jpeg,image/jpg,image/png,image/webp"
       onchange="showCropModal(event)">

{{-- ====================================================================== --}}
{{-- MODAL: RECORTAR FOTO                                                    --}}
{{-- ====================================================================== --}}
<div class="mir-modal-overlay" id="cropModal" style="display:none;">
    <div class="mir-modal-dialog" style="max-width:600px;">
        <div class="mir-modal-content">
            <div class="mir-modal-header">
                <div class="mir-modal-title">
                    <div class="mir-modal-icon" style="background:rgba(99,102,241,.12);color:#6366f1;">
                        <i class="fa-solid fa-crop"></i>
                    </div>
                    <div>
                        <div class="mir-modal-title-text">Recortar Foto de Perfil</div>
                        <div class="mir-modal-subtitle">Ajuste o recorte da sua foto</div>
                    </div>
                </div>
                <button type="button" class="mir-modal-close" onclick="document.getElementById('cropModal').style.display='none'">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="mir-modal-body" style="max-height:70vh;overflow-y:auto;">
                <div class="prf-img-container">
                    <img id="CropImagePreview" style="max-width:100%;display:block;">
                </div>
            </div>
            <div class="mir-modal-footer">
                <button type="button" class="mir-btn-ghost" onclick="document.getElementById('cropModal').style.display='none'">Cancelar</button>
                <button type="button" class="mir-btn-primary-lg" id="crop_button">
                    <i class="fa-solid fa-crop"></i> Cortar e Salvar
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
               class="prf-avatar-edit" data-tooltip="Alterar foto">
                <i class="fa-solid fa-pen"></i>
            </a>
            <div class="prf-name">{{ $user->name }}</div>
            <div class="prf-email">{{ $user->email }}</div>
        </div>

        <div class="prf-info-list">
            <div class="prf-info-item">
                <div class="prf-info-icon"><i class="fa-solid fa-at"></i></div>
                <div>
                    <div class="prf-info-label">Username</div>
                    <div class="prf-info-value">{{ $user->username }}</div>
                </div>
            </div>
            <div class="prf-info-item">
                <div class="prf-info-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div>
                    <div class="prf-info-label">Papel</div>
                    <div class="prf-info-value" style="text-transform:capitalize;">{{ $user->role ?? 'Admin' }}</div>
                </div>
            </div>
            @if($user->bio)
            <div class="prf-info-item">
                <div class="prf-info-icon"><i class="fa-solid fa-align-left"></i></div>
                <div>
                    <div class="prf-info-label">Bio</div>
                    <div class="prf-info-value" style="font-weight:400;font-size:.78rem;line-height:1.5;">{{ $user->bio }}</div>
                </div>
            </div>
            @endif
        </div>

        {{-- Social links --}}
        @php $hasAny = $user->socialLinks?->hasAnyLink(); @endphp
        <div class="prf-social">
            <div class="prf-social-title">Redes Sociais</div>
            @if($hasAny)
                <div class="prf-social-links">
                    @if($user->socialLinks?->facebook_url)
                        <a href="{{ $user->socialLinks->facebook_url }}" target="_blank"
                           class="prf-social-btn" style="background:#3b5998;" data-tooltip="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    @endif
                    @if($user->socialLinks?->twitter_url)
                        <a href="{{ $user->socialLinks->twitter_url }}" target="_blank"
                           class="prf-social-btn" style="background:#1da1f2;" data-tooltip="Twitter/X">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                    @endif
                    @if($user->socialLinks?->instagram_url)
                        <a href="{{ $user->socialLinks->instagram_url }}" target="_blank"
                           class="prf-social-btn" style="background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);" data-tooltip="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    @endif
                    @if($user->socialLinks?->youtube_url)
                        <a href="{{ $user->socialLinks->youtube_url }}" target="_blank"
                           class="prf-social-btn" style="background:#ff0000;" data-tooltip="YouTube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    @endif
                    @if($user->socialLinks?->whatsapp_url)
                        <a href="{{ $user->socialLinks->whatsapp_url }}" target="_blank"
                           class="prf-social-btn" style="background:#25d366;" data-tooltip="WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    @endif
                    @if($user->socialLinks?->steam_url)
                        <a href="{{ $user->socialLinks->steam_url }}" target="_blank"
                           class="prf-social-btn" style="background:#171a21;" data-tooltip="Steam">
                            <i class="fa-brands fa-steam"></i>
                        </a>
                    @endif
                </div>
            @else
                <p style="font-size:.78rem;color:#9ca3af;margin:0;">Nenhuma rede social cadastrada.</p>
            @endif
        </div>

    </div>

    {{-- ── PAINEL PRINCIPAL: Seções em grid ──────────────────────────── --}}
    <div class="prf-main">

        {{-- ── SEÇÃO: Dados Pessoais ─────────────────────────────────── --}}
        <div class="prf-section-card">
            <div class="prf-section-header">
                <div class="prf-section-icon"><i class="fa-solid fa-user"></i></div>
                <div>
                    <div class="prf-section-title">Dados Pessoais</div>
                    <div class="prf-section-sub">Atualize seu nome, username e bio</div>
                </div>
            </div>
            <div class="prf-section-body">
                <form wire:submit.prevent="updatePersonalDetails">
                    <div class="prf-form-grid">
                        <div class="prf-form-full">
                            <label class="mir-label">Nome completo <span class="mir-required">*</span></label>
                            <input type="text"
                                   class="mir-input @error('name') is-invalid @enderror"
                                   wire:model.defer="name"
                                   placeholder="Seu nome completo">
                            @error('name') <div class="prf-field-error">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="mir-label">E-mail <span class="prf-optional">(não editável)</span></label>
                            <div class="prf-input-group">
                                <i class="fa-solid fa-envelope prf-input-icon"></i>
                                <input type="email" class="mir-input" value="{{ $user->email }}" disabled style="padding-left:34px;">
                            </div>
                        </div>

                        <div>
                            <label class="mir-label">Username <span class="mir-required">*</span></label>
                            <div class="prf-input-group">
                                <i class="fa-solid fa-at prf-input-icon"></i>
                                <input type="text"
                                       class="mir-input @error('username') is-invalid @enderror"
                                       wire:model.defer="username"
                                       placeholder="seu_username"
                                       style="padding-left:34px;">
                            </div>
                            @error('username') <div class="prf-field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="prf-form-full">
                            <label class="mir-label">Bio <span class="prf-optional">(opcional)</span></label>
                            <textarea class="mir-input"
                                      wire:model.defer="bio"
                                      rows="3"
                                      style="resize:vertical;"
                                      placeholder="Fale um pouco sobre você..."></textarea>
                            @error('bio') <div class="prf-field-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="prf-section-footer">
                        <button type="submit" class="mir-btn-primary-lg"
                                wire:loading.attr="disabled" wire:target="updatePersonalDetails">
                            <span wire:loading wire:target="updatePersonalDetails">
                                <span class="spinner-border spinner-border-sm mr-1"></span>
                            </span>
                            <i class="fa-solid fa-floppy-disk" wire:loading.remove wire:target="updatePersonalDetails"></i>
                            Salvar Dados
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── SEÇÃO: Alterar Senha ──────────────────────────────────── --}}
        <div class="prf-section-card">
            <div class="prf-section-header">
                <div class="prf-section-icon prf-section-icon-danger"><i class="fa-solid fa-lock"></i></div>
                <div>
                    <div class="prf-section-title">Alterar Senha</div>
                    <div class="prf-section-sub">Atualize sua senha de acesso</div>
                </div>
            </div>
            <div class="prf-section-body">
                <div class="prf-info-box">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Após alterar a senha, você será <strong>desconectado automaticamente</strong> e precisará fazer login novamente.</span>
                </div>

                <form wire:submit.prevent="updatePassword">
                    <div class="prf-form-grid">
                        <div class="prf-form-full">
                            <label class="mir-label">Senha atual <span class="mir-required">*</span></label>
                            <div class="prf-input-group">
                                <i class="fa-solid fa-key prf-input-icon"></i>
                                <input type="password"
                                       class="mir-input @error('currentPassword') is-invalid @enderror"
                                       wire:model.defer="currentPassword"
                                       placeholder="Digite sua senha atual"
                                       style="padding-left:34px;">
                            </div>
                            @error('currentPassword') <div class="prf-field-error">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="mir-label">Nova senha <span class="mir-required">*</span></label>
                            <div class="prf-input-group">
                                <i class="fa-solid fa-lock prf-input-icon"></i>
                                <input type="password"
                                       class="mir-input @error('newPassword') is-invalid @enderror"
                                       wire:model.defer="newPassword"
                                       placeholder="Mínimo 5 caracteres"
                                       style="padding-left:34px;">
                            </div>
                            @error('newPassword') <div class="prf-field-error">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="mir-label">Confirmar nova senha <span class="mir-required">*</span></label>
                            <div class="prf-input-group">
                                <i class="fa-solid fa-check prf-input-icon"></i>
                                <input type="password"
                                       class="mir-input @error('newPassword_confirmation') is-invalid @enderror"
                                       wire:model.defer="newPassword_confirmation"
                                       placeholder="Repita a nova senha"
                                       style="padding-left:34px;">
                            </div>
                            @error('newPassword_confirmation') <div class="prf-field-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="prf-section-footer">
                        <button type="submit" class="mir-btn-danger"
                                wire:loading.attr="disabled" wire:target="updatePassword">
                            <span wire:loading wire:target="updatePassword">
                                <span class="spinner-border spinner-border-sm mr-1"></span>
                            </span>
                            <i class="fa-solid fa-lock" wire:loading.remove wire:target="updatePassword"></i>
                            Atualizar Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── SEÇÃO: Redes Sociais ──────────────────────────────────── --}}
        <div class="prf-section-card prf-section-full">
            <div class="prf-section-header">
                <div class="prf-section-icon prf-section-icon-social"><i class="fa-solid fa-share-nodes"></i></div>
                <div>
                    <div class="prf-section-title">Redes Sociais</div>
                    <div class="prf-section-sub">Configure os links das suas redes sociais</div>
                </div>
            </div>
            <div class="prf-section-body">
                @livewire('admin.social-links')
            </div>
        </div>

    </div>

</div>

{{-- ================================================================ --}}
{{-- SCOPED STYLES                                                    --}}
{{-- ================================================================ --}}
<style>
    /* ── Layout ──────────────────────────────────────────── */
    .prf-wrap { display: flex; gap: 24px; align-items: flex-start; }

    /* ── Card Lateral (Avatar) ───────────────────────────── */
    .prf-sidebar {
        width: 260px; flex-shrink: 0;
        background: #fff; border-radius: 10px;
        border: 1px solid #e9ecef;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
        overflow: hidden;
    }
    .prf-avatar-wrap {
        position: relative;
        display: flex; flex-direction: column; align-items: center;
        padding: 32px 20px 20px;
        background: linear-gradient(160deg, #6366f1 0%, #4f46e5 100%);
    }
    .prf-avatar-img {
        width: 96px; height: 96px; border-radius: 50%;
        object-fit: cover; border: 3px solid rgba(255,255,255,.6);
        box-shadow: 0 4px 16px rgba(0,0,0,.2);
    }
    .prf-avatar-edit {
        position: absolute; right: calc(50% - 56px);
        width: 28px; height: 28px; border-radius: 50%;
        background: #fff; display: flex; align-items: center; justify-content: center;
        cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,.2);
        transition: transform .15s; color: #6366f1; font-size: .75rem;
    }
    .prf-avatar-edit:hover { transform: scale(1.12); }
    .prf-name { font-size: .95rem; font-weight: 700; color: #fff; margin: 12px 0 2px; text-align: center; }
    .prf-email { font-size: .75rem; color: rgba(255,255,255,.75); text-align: center; }
    .prf-info-list { padding: 16px 20px; }
    .prf-info-item {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 8px 0; border-bottom: 1px solid #f5f5f5; font-size: .8rem;
    }
    .prf-info-item:last-child { border-bottom: none; }
    .prf-info-icon {
        width: 28px; height: 28px; border-radius: 8px;
        background: #ede9fe; color: #6366f1;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: .72rem;
    }
    .prf-info-label { font-size: .7rem; color: #9ca3af; }
    .prf-info-value { font-weight: 600; color: #374151; word-break: break-all; }

    /* ── Social links (sidebar) ─────────────────────────── */
    .prf-social { padding: 0 20px 20px; }
    .prf-social-title {
        font-size: .72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: #9ca3af; margin-bottom: 10px;
    }
    .prf-social-links { display: flex; flex-wrap: wrap; gap: 8px; }
    .prf-social-btn {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: .82rem; color: #fff;
        transition: opacity .15s, transform .15s; text-decoration: none;
    }
    .prf-social-btn:hover { opacity: .85; transform: scale(1.08); color: #fff; }

    /* ── Painel direito: grid de seções ──────────────────── */
    .prf-main {
        flex: 1; min-width: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    /* ── Section Card ────────────────────────────────────── */
    .prf-section-card {
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .prf-section-full {
        grid-column: 1 / -1;
    }
    .prf-section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
    }
    .prf-section-icon {
        width: 34px; height: 34px; border-radius: 8px;
        background: #ede9fe; color: #6366f1;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: .82rem;
    }
    .prf-section-icon-danger { background: #fee2e2; color: #ef4444; }
    .prf-section-icon-social { background: #d1fae5; color: #059669; }
    .prf-section-title { font-size: .88rem; font-weight: 700; color: #1a1d23; }
    .prf-section-sub { font-size: .72rem; color: #9ca3af; margin-top: 1px; }
    .prf-section-body { padding: 20px; flex: 1; }
    .prf-section-footer {
        padding: 16px 20px;
        border-top: 1px solid #f0f0f0;
        background: #fafafa;
        display: flex;
        justify-content: flex-end;
    }

    /* ── Form grid interno ──────────────────────────────── */
    .prf-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .prf-form-full { grid-column: 1 / -1; }

    /* ── Form extras ─────────────────────────────────────── */
    .prf-optional { font-size: .72rem; font-weight: 400; color: #9ca3af; }
    .prf-field-error { font-size: .78rem; color: #ef4444; margin-top: 4px; }
    .prf-input-group { position: relative; }
    .prf-input-icon {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: .82rem; pointer-events: none;
    }

    /* ── Info box (aviso senha) ───────────────────────────── */
    .prf-info-box {
        display: flex; gap: 12px;
        background: #fffbeb; border: 1px solid #fde68a;
        border-radius: 10px; padding: 12px 16px;
        margin-bottom: 20px; font-size: .8rem; color: #92400e;
    }
    .prf-info-box i { margin-top: 1px; flex-shrink: 0; }

    /* ── Crop modal extras ───────────────────────────────── */
    .prf-img-container {
        width: 100%; max-height: 400px;
        overflow: hidden; background: #f8f9fa;
    }
    #CropImagePreview { max-width: 100%; display: block; }

    /* ── Responsivo ──────────────────────────────────────── */
    @media (max-width: 992px) {
        .prf-main { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .prf-wrap { flex-direction: column; }
        .prf-sidebar { width: 100%; }
        .prf-form-grid { grid-template-columns: 1fr; }
    }
</style>

</div>
