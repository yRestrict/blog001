{{-- livewire/admin/settings.blade.php --}}
<div>
<style>
    /* ── Tabs mir- ───────────────────────────────────────────────────────── */
    .mir-tabs {
        display: flex;
        gap: 2px;
        padding: 16px 20px 0;
        border-bottom: 1px solid #f0f0f0;
        background: #fafafa;
        border-radius: 14px 14px 0 0;
    }
    .mir-tab-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 16px;
        border-radius: 8px 8px 0 0;
        font-size: .82rem; font-weight: 600;
        color: #6d7279; background: transparent;
        border: none; cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: color .15s, background .15s;
        margin-bottom: -1px;
    }
    .mir-tab-btn:hover { color: #6366f1; background: #fff; }
    .mir-tab-btn.active { color: #6366f1; background: #fff; border-bottom: 2px solid #6366f1; }

    /* ── Wrapper principal ───────────────────────────────────────────────── */
    .set-wrap {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
        overflow: hidden;
    }
    .mir-tab-content { padding: 28px; }

    /* ── Inputs mir- ─────────────────────────────────────────────────────── */
    .mir-label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .mir-required { color: #ef4444; }
    .mir-optional { font-size: .72rem; font-weight: 400; color: #9ca3af; }
    .mir-input {
        width: 100%; padding: 8px 12px;
        border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-size: .85rem; color: #1a1d23; background: #fff;
        outline: none; transition: border-color .15s, box-shadow .15s;
        appearance: none;
    }
    .mir-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
    .mir-input.is-invalid { border-color: #ef4444; }
    .mir-field-error { font-size: .78rem; color: #ef4444; margin-top: 4px; }
    .mir-field-hint  { font-size: .75rem; color: #9ca3af; margin-top: 5px; display:flex; align-items:center; gap:4px; }

    /* ── Botões ──────────────────────────────────────────────────────────── */
    .mir-btn-primary-lg {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 20px; border-radius: 8px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff; font-size: .83rem; font-weight: 600;
        border: none; cursor: pointer; transition: opacity .15s;
        box-shadow: 0 2px 8px rgba(99,102,241,.35);
    }
    .mir-btn-primary-lg:hover { opacity: .9; }
    .mir-btn-primary-lg:disabled { opacity: .5; cursor: not-allowed; }

    /* ── Seção de título interno ─────────────────────────────────────────── */
    .mir-section-header {
        margin-bottom: 22px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f0f0f0;
    }
    .mir-section-title {
        font-size: .92rem; font-weight: 700; color: #1a1d23;
        display: flex; align-items: center; gap: 8px; margin: 0 0 2px;
    }
    .mir-section-title-icon {
        width: 30px; height: 30px; border-radius: 8px;
        background: rgba(99,102,241,.1); color: #6366f1;
        display: flex; align-items: center; justify-content: center;
        font-size: .82rem;
    }
    .mir-section-sub { font-size: .78rem; color: #9ca3af; margin: 0; }

    /* ── Cards de logo/favicon ───────────────────────────────────────────── */
    .logo-card {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        overflow: hidden;
    }
    .logo-card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 16px;
        font-size: .82rem; font-weight: 600;
    }
    .logo-card-header-light { background: #f8f9fa; color: #374151; border-bottom: 1px solid #e9ecef; }
    .logo-card-header-dark  { background: #1e2130; color: #e5e7eb; border-bottom: 1px solid #374151; }

    .logo-preview-light {
        min-height: 110px;
        background: #fff;
        display: flex; align-items: center; justify-content: center;
        border-bottom: 1px solid #e9ecef;
        padding: 16px;
    }
    .logo-preview-dark {
        min-height: 110px;
        background: #1a1a2e;
        display: flex; align-items: center; justify-content: center;
        border-bottom: 1px solid #374151;
        padding: 16px;
    }
    .logo-preview-browser {
        min-height: 110px;
        background: #e8e8e8;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        border-bottom: 1px solid #e9ecef;
        padding: 16px;
    }
    .logo-empty {
        display: flex; flex-direction: column; align-items: center;
        gap: 6px; color: #9ca3af; font-size: .75rem; text-align: center;
    }
    .logo-card-footer {
        padding: 14px 16px;
        background: #fafafa;
    }

    /* Upload input estilizado */
    .mir-file-input {
        width: 100%; padding: 7px;
        border: 1.5px dashed #d1d5db;
        border-radius: 8px;
        font-size: .8rem; color: #6d7279;
        cursor: pointer; background: #fff;
        transition: border-color .15s;
    }
    .mir-file-input:hover { border-color: #6366f1; }

    /* ── Social links icons ──────────────────────────────────────────────── */
    .social-input-wrap {
        display: flex; align-items: center;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px; overflow: hidden;
        transition: border-color .15s, box-shadow .15s;
    }
    .social-input-wrap:focus-within {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.12);
    }
    .social-input-wrap.is-invalid { border-color: #ef4444; }
    .social-icon-prefix {
        width: 40px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: #f9fafb;
        border-right: 1px solid #e5e7eb;
        font-size: .9rem; color: #6d7279;
        align-self: stretch;
    }
    .social-input-inner {
        flex: 1; padding: 8px 12px;
        border: none; outline: none;
        font-size: .84rem; color: #1a1d23;
        background: transparent;
    }

    /* Cores das redes sociais */
    .si-facebook  { color: #3b5998; }
    .si-instagram { color: #e1306c; }
    .si-twitter   { color: #1da1f2; }
    .si-youtube   { color: #ff0000; }
    .si-linkedin  { color: #0077b5; }
    .si-whatsapp  { color: #25d366; }
</style>

<div class="set-wrap">

    {{-- ── Tabs ──────────────────────────────────────────────────────────── --}}
    <div class="mir-tabs">
        <button class="mir-tab-btn {{ $tab === 'general_settings' ? 'active' : '' }}"
                wire:click="selectTab('general_settings')">
            <i class="fa fa-cog"></i> Configurações Gerais
        </button>
        <button class="mir-tab-btn {{ $tab === 'logo_favicon' ? 'active' : '' }}"
                wire:click="selectTab('logo_favicon')">
            <i class="fa fa-image"></i> Logo &amp; Favicon
        </button>
        <button class="mir-tab-btn {{ $tab === 'social_link' ? 'active' : '' }}"
                wire:click="selectTab('social_link')">
            <i class="fa fa-share-alt"></i> Redes Sociais
        </button>
    </div>

    <div class="mir-tab-content">

        {{-- ==================================================================== --}}
        {{-- TAB: CONFIGURAÇÕES GERAIS                                             --}}
        {{-- ==================================================================== --}}
        @if($tab === 'general_settings')
            <div class="mir-section-header">
                <div class="mir-section-title">
                    <span class="mir-section-title-icon"><i class="fa fa-cog"></i></span>
                    Configurações Gerais
                </div>
                <p class="mir-section-sub">Informações básicas do seu site</p>
            </div>

            <form wire:submit.prevent="updateGeneralSettings">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="mir-label">Título do Site <span class="mir-required">*</span></label>
                        <input type="text"
                               class="mir-input @error('site_title') is-invalid @enderror"
                               wire:model.defer="site_title"
                               placeholder="Ex: Meu Blog Incrível">
                        @error('site_title') <div class="mir-field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="mir-label">E-mail do Site <span class="mir-required">*</span></label>
                        <input type="email"
                               class="mir-input @error('site_email') is-invalid @enderror"
                               wire:model.defer="site_email"
                               placeholder="contato@meusite.com">
                        @error('site_email') <div class="mir-field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="mir-label">Telefone <span class="mir-optional">(opcional)</span></label>
                        <input type="text"
                               class="mir-input @error('site_phone') is-invalid @enderror"
                               wire:model.defer="site_phone"
                               placeholder="+55 (11) 99999-0000">
                        @error('site_phone') <div class="mir-field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="mir-label">Meta Keywords <span class="mir-optional">(opcional)</span></label>
                        <input type="text"
                               class="mir-input @error('site_meta_keywords') is-invalid @enderror"
                               wire:model.defer="site_meta_keywords"
                               placeholder="blog, tecnologia, laravel">
                        @error('site_meta_keywords') <div class="mir-field-error">{{ $message }}</div> @enderror
                        <div class="mir-field-hint">
                            <i class="fa fa-info-circle"></i> Separe as palavras-chave com vírgulas
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="mir-label">Descrição do Site <span class="mir-optional">(opcional)</span></label>
                        <textarea class="mir-input"
                                  wire:model.defer="site_description"
                                  rows="3"
                                  style="resize:vertical;"
                                  placeholder="Descrição completa para uso em páginas como 'Sobre', rodapé, etc."></textarea>
                        @error('site_description') <div class="mir-field-error">{{ $message }}</div> @enderror
                        <div class="mir-field-hint">
                            <i class="fa fa-info-circle"></i> Texto longo para uso geral no site (máx. 1000 caracteres)
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <label class="mir-label">Meta Description <span class="mir-optional">(opcional)</span></label>
                        <textarea class="mir-input"
                                  wire:model.defer="site_meta_description"
                                  rows="3"
                                  style="resize:vertical;"
                                  placeholder="Descrição exibida nos resultados de busca do Google..."></textarea>
                        @error('site_meta_description') <div class="mir-field-error">{{ $message }}</div> @enderror
                        <div class="mir-field-hint">
                            <i class="fa fa-search"></i> Descrição curta para mecanismos de busca (máx. 500 caracteres)
                        </div>
                    </div>

                </div>

                <button type="submit" class="mir-btn-primary-lg"
                        wire:loading.attr="disabled" wire:target="updateGeneralSettings">
                    <span wire:loading wire:target="updateGeneralSettings">
                        <span class="spinner-border spinner-border-sm mr-1"></span>
                    </span>
                    <i class="fa fa-save" wire:loading.remove wire:target="updateGeneralSettings"></i>
                    Salvar Configurações
                </button>
            </form>
        @endif


        {{-- ==================================================================== --}}
        {{-- TAB: LOGO & FAVICON                                                   --}}
        {{-- ==================================================================== --}}
        @if($tab === 'logo_favicon')
            <div class="mir-section-header">
                <div class="mir-section-title">
                    <span class="mir-section-title-icon"><i class="fa fa-image"></i></span>
                    Logo &amp; Favicon
                </div>
                <p class="mir-section-sub">Gerencie a identidade visual do site. Apenas campos com novo arquivo serão atualizados.</p>
            </div>

            <form wire:submit.prevent="updateLogoFavicon">
                <div class="row g-3">

                    {{-- ── Logo Dark (tema claro) ── --}}
                    <div class="col-md-4">
                        <div class="logo-card">
                            <div class="logo-card-header logo-card-header-light">
                                <span>
                                    <i class="fa fa-sun-o text-warning mr-1"></i>
                                    Logo (Tema Claro)
                                </span>
                                <span style="font-size:.7rem; color:#9ca3af; font-weight:400;">PNG transparente</span>
                            </div>
                            <div class="logo-preview-light" wire:ignore>
                                @if($site_logo_light)
                                    <img id="preview_logo_light"
                                         src="{{ asset('uploads/logo/' . $site_logo_light) }}"
                                         alt="Logo Light"
                                         style="max-height:60px; max-width:100%; object-fit:contain;">
                                    <span id="placeholder_logo_light" style="display:none;" class="logo-empty">
                                        <i class="fa fa-image fa-2x"></i> Nenhuma logo enviada
                                    </span>
                                @else
                                    <img id="preview_logo_light" src="" style="display:none; max-height:60px; max-width:100%;">
                                    <span id="placeholder_logo_light" class="logo-empty">
                                        <i class="fa fa-image fa-2x"></i> Nenhuma logo enviada
                                    </span>
                                @endif
                            </div>
                            <div class="logo-card-footer">
                                <div class="mir-field-hint mb-2">
                                    <i class="fa fa-info-circle"></i> 200×50px · Máx 2MB
                                </div>
                                <input type="file" class="mir-file-input"
                                       wire:model="new_logo_light" accept="image/*"
                                       onchange="previewImage(event,'preview_logo_light','placeholder_logo_light')">
                                @error('new_logo_light') <div class="mir-field-error">{{ $message }}</div> @enderror
                                <div wire:loading wire:target="new_logo_light" class="mir-field-hint mt-1">
                                    <span class="spinner-border spinner-border-sm"></span> Carregando...
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Logo Light (tema escuro) ── --}}
                    <div class="col-md-4">
                        <div class="logo-card">
                            <div class="logo-card-header logo-card-header-dark">
                                <span>
                                    <i class="fa fa-moon-o" style="color:#818cf8;" ></i>
                                    Logo (Tema Escuro)
                                </span>
                                <span style="font-size:.7rem; color:#6b7280; font-weight:400;">PNG branco</span>
                            </div>
                            <div class="logo-preview-dark" wire:ignore>
                                @if($site_logo_dark)
                                    <img id="preview_logo_dark"
                                         src="{{ asset('uploads/logo/' . $site_logo_dark) }}"
                                         alt="Logo Dark"
                                         style="max-height:60px; max-width:100%; object-fit:contain;">
                                    <span id="placeholder_logo_dark" style="display:none;" class="logo-empty">
                                        <i class="fa fa-image fa-2x"></i> Nenhuma logo enviada
                                    </span>
                                @else
                                    <img id="preview_logo_dark" src="" style="display:none; max-height:60px; max-width:100%;">
                                    <span id="placeholder_logo_dark" class="logo-empty" style="color:#6b7280;">
                                        <i class="fa fa-image fa-2x"></i> Nenhuma logo enviada
                                    </span>
                                @endif
                            </div>
                            <div class="logo-card-footer">
                                <div class="mir-field-hint mb-2">
                                    <i class="fa fa-info-circle"></i> 200×50px · Máx 2MB
                                </div>
                                <input type="file" class="mir-file-input"
                                       wire:model="new_logo_dark" accept="image/*"
                                       onchange="previewImage(event,'preview_logo_dark','placeholder_logo_dark')">
                                @error('new_logo_dark') <div class="mir-field-error">{{ $message }}</div> @enderror
                                <div wire:loading wire:target="new_logo_dark" class="mir-field-hint mt-1">
                                    <span class="spinner-border spinner-border-sm"></span> Carregando...
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Favicon ── --}}
                    <div class="col-md-4">
                        <div class="logo-card">
                            <div class="logo-card-header logo-card-header-light">
                                <span>
                                    <i class="fa fa-chrome text-primary mr-1"></i>
                                    Favicon
                                </span>
                                <span style="font-size:.7rem; color:#9ca3af; font-weight:400;">Aba do navegador</span>
                            </div>
                            <div class="logo-preview-browser" wire:ignore>
                                <div style="background:#e0e0e0; border-radius:8px 8px 0 0; padding:6px 14px; display:inline-flex; align-items:center; gap:6px; border:1px solid #bbb; border-bottom:none;">
                                    @if($site_favicon)
                                        <img id="preview_favicon" src="{{ asset('uploads/logo/' . $site_favicon) }}"
                                             style="width:14px; height:14px; object-fit:contain;">
                                        <span id="placeholder_favicon" style="display:none; font-size:13px; color:#999;"><i class="fa fa-globe"></i></span>
                                    @else
                                        <img id="preview_favicon" src="" style="display:none; width:14px; height:14px;">
                                        <span id="placeholder_favicon" style="font-size:13px; color:#999;"><i class="fa fa-globe"></i></span>
                                    @endif
                                    <span style="font-size:12px; color:#444; white-space:nowrap;">Meu Site</span>
                                    <span style="font-size:11px; color:#999;">✕</span>
                                </div>
                                <div style="height:4px; width:180px; background:#fff; border:1px solid #bbb; border-top:none;"></div>
                            </div>
                            <div class="logo-card-footer">
                                <div class="mir-field-hint mb-2">
                                    <i class="fa fa-info-circle"></i> ICO ou PNG · 32×32px · Máx 1MB
                                </div>
                                <input type="file" class="mir-file-input"
                                       wire:model="new_favicon" accept="image/*,.ico"
                                       onchange="previewImage(event,'preview_favicon','placeholder_favicon')">
                                @error('new_favicon') <div class="mir-field-error">{{ $message }}</div> @enderror
                                <div wire:loading wire:target="new_favicon" class="mir-field-hint mt-1">
                                    <span class="spinner-border spinner-border-sm"></span> Carregando...
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-4 d-flex align-items-center gap-3">
                    <button type="submit"
                            class="mir-btn-primary-lg"
                            {{ !$new_logo_light && !$new_logo_dark && !$new_favicon ? 'disabled' : '' }}
                            wire:loading.attr="disabled" wire:target="updateLogoFavicon">
                        <span wire:loading wire:target="updateLogoFavicon">
                            <span class="spinner-border spinner-border-sm mr-1"></span>
                        </span>
                        <i class="fa fa-upload" wire:loading.remove wire:target="updateLogoFavicon"></i>
                        Upload &amp; Salvar
                    </button>
                    <span class="mir-field-hint" style="margin:0;">
                        <i class="fa fa-info-circle"></i>
                        Apenas os campos com novo arquivo serão atualizados.
                    </span>
                </div>
            </form>
        @endif


        {{-- ==================================================================== --}}
        {{-- TAB: REDES SOCIAIS                                                    --}}
        {{-- ==================================================================== --}}
        @if($tab === 'social_link')
            <div class="mir-section-header">
                <div class="mir-section-title">
                    <span class="mir-section-title-icon"><i class="fa fa-share-alt"></i></span>
                    Redes Sociais
                </div>
                <p class="mir-section-sub">Links exibidos no site e na página de perfil</p>
            </div>

            <form wire:submit.prevent="updateSocialLinks">
                <div class="row">

                    @php
                        $socials = [
                            'facebook_url'  => ['label' => 'Facebook',    'icon' => 'fa-facebook',  'class' => 'si-facebook',  'placeholder' => 'https://facebook.com/suapagina'],
                            'instagram_url' => ['label' => 'Instagram',   'icon' => 'fa-instagram', 'class' => 'si-instagram', 'placeholder' => 'https://instagram.com/seuperfil'],
                            'twitter_url'   => ['label' => 'Twitter / X', 'icon' => 'fa-twitter',   'class' => 'si-twitter',   'placeholder' => 'https://twitter.com/seuusuario'],
                            'youtube_url'   => ['label' => 'YouTube',     'icon' => 'fa-youtube',   'class' => 'si-youtube',   'placeholder' => 'https://youtube.com/seucanal'],
                            'linkedin_url'  => ['label' => 'LinkedIn',    'icon' => 'fa-linkedin',  'class' => 'si-linkedin',  'placeholder' => 'https://linkedin.com/in/voce'],
                            'whatsapp_url'  => ['label' => 'WhatsApp',    'icon' => 'fa-whatsapp',  'class' => 'si-whatsapp',  'placeholder' => 'https://wa.me/5511999990000'],
                        ];
                    @endphp

                    @foreach($socials as $key => $social)
                        <div class="col-md-6 mb-3">
                            <label class="mir-label">{{ $social['label'] }}</label>
                            <div class="social-input-wrap @error('site_social_links.' . $key) is-invalid @enderror">
                                <span class="social-icon-prefix {{ $social['class'] }}">
                                    <i class="fa {{ $social['icon'] }}"></i>
                                </span>
                                <input type="text"
                                       class="social-input-inner"
                                       wire:model="site_social_links.{{ $key }}"
                                       placeholder="{{ $social['placeholder'] }}">
                            </div>
                            @error('site_social_links.' . $key)
                                <div class="mir-field-error">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                </div>

                <div class="mt-2">
                    <button type="submit" class="mir-btn-primary-lg"
                            wire:loading.attr="disabled" wire:target="updateSocialLinks">
                        <span wire:loading wire:target="updateSocialLinks">
                            <span class="spinner-border spinner-border-sm mr-1"></span>
                        </span>
                        <i class="fa fa-save" wire:loading.remove wire:target="updateSocialLinks"></i>
                        Salvar Redes Sociais
                    </button>
                </div>
            </form>
        @endif

    </div>
</div>

@push('scripts')
<script>
function previewImage(event, previewId, placeholderId) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById(previewId);
        const ph  = document.getElementById(placeholderId);
        img.src = e.target.result;
        img.style.display = 'block';
        if (ph) ph.style.display = 'none';
    };
    reader.readAsDataURL(file);
}
</script>
@endpush

</div>