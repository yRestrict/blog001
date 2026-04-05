{{-- livewire/admin/settings.blade.php --}}
<div>

<div class="page-header-action">
    <div class="page-header-left">
        <h1 class="page-header-title">Configurações</h1>
        <span class="page-header-sub">Gerencie as configurações gerais do site</span>
    </div>
</div>

<div class="set-grid">

    <div class="set-section-card">
        <div class="set-section-header">
            <div class="set-section-icon"><i class="fa-solid fa-gear"></i></div>
            <div>
                <div class="set-section-title">Configurações Gerais</div>
                <div class="set-section-sub">Informações básicas do seu site</div>
            </div>
        </div>
        <div class="set-section-body">
            <form wire:submit.prevent="updateGeneralSettings">
                <div class="set-form-grid">
                    <div>
                        <label class="mir-label">Título do Site <span class="mir-required">*</span></label>
                        <input type="text" class="mir-input @error('site_title') is-invalid @enderror" wire:model.defer="site_title" placeholder="Ex: Meu Blog Incrível">
                        @error('site_title') <div class="set-field-error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="mir-label">E-mail do Site <span class="mir-required">*</span></label>
                        <input type="email" class="mir-input @error('site_email') is-invalid @enderror" wire:model.defer="site_email" placeholder="contato@meusite.com">
                        @error('site_email') <div class="set-field-error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="mir-label">Telefone <span class="set-optional">(opcional)</span></label>
                        <input type="text" class="mir-input @error('site_phone') is-invalid @enderror" wire:model.defer="site_phone" placeholder="+55 (11) 99999-0000">
                        @error('site_phone') <div class="set-field-error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="mir-label">Meta Keywords <span class="set-optional">(opcional)</span></label>
                        <input type="text" class="mir-input @error('site_meta_keywords') is-invalid @enderror" wire:model.defer="site_meta_keywords" placeholder="blog, tecnologia, laravel">
                        @error('site_meta_keywords') <div class="set-field-error">{{ $message }}</div> @enderror
                        <div class="set-field-hint"><i class="fa-solid fa-circle-info"></i> Separe as palavras-chave com vírgulas</div>
                    </div>
                    <div class="set-form-full">
                        <label class="mir-label">Descrição do Site <span class="set-optional">(opcional)</span></label>
                        <textarea class="mir-input" wire:model.defer="site_description" rows="3" style="resize:vertical;" placeholder="Descrição completa..."></textarea>
                        @error('site_description') <div class="set-field-error">{{ $message }}</div> @enderror
                        <div class="set-field-hint"><i class="fa-solid fa-circle-info"></i> Texto longo para uso geral no site (máx. 1000 caracteres)</div>
                    </div>
                    <div class="set-form-full">
                        <label class="mir-label">Meta Description <span class="set-optional">(opcional)</span></label>
                        <textarea class="mir-input" wire:model.defer="site_meta_description" rows="3" style="resize:vertical;" placeholder="Descrição exibida nos resultados de busca do Google..."></textarea>
                        @error('site_meta_description') <div class="set-field-error">{{ $message }}</div> @enderror
                        <div class="set-field-hint"><i class="fa-solid fa-magnifying-glass"></i> Descrição curta para mecanismos de busca (máx. 500 caracteres)</div>
                    </div>
                </div>
                <div class="set-section-footer">
                    <button type="submit" class="mir-btn-primary-lg" wire:loading.attr="disabled" wire:target="updateGeneralSettings">
                        <span wire:loading wire:target="updateGeneralSettings"><span class="spinner-border spinner-border-sm mr-1"></span></span>
                        <i class="fa-solid fa-floppy-disk" wire:loading.remove wire:target="updateGeneralSettings"></i>
                        Salvar Configurações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="set-section-card">
        <div class="set-section-header">
            <div class="set-section-icon set-section-icon-social"><i class="fa-solid fa-share-nodes"></i></div>
            <div>
                <div class="set-section-title">Redes Sociais</div>
                <div class="set-section-sub">Links exibidos no site</div>
            </div>
        </div>
        <div class="set-section-body">
            <form wire:submit.prevent="updateSocialLinks">
                @php
                    $socials = [
                        'facebook_url'  => ['label' => 'Facebook',    'icon' => 'fa-brands fa-facebook-f',  'class' => 'set-si-facebook',  'placeholder' => 'https://facebook.com/suapagina'],
                        'instagram_url' => ['label' => 'Instagram',   'icon' => 'fa-brands fa-instagram',   'class' => 'set-si-instagram', 'placeholder' => 'https://instagram.com/seuperfil'],
                        'twitter_url'   => ['label' => 'Twitter / X', 'icon' => 'fa-brands fa-x-twitter',   'class' => 'set-si-twitter',   'placeholder' => 'https://twitter.com/seuusuario'],
                        'youtube_url'   => ['label' => 'YouTube',     'icon' => 'fa-brands fa-youtube',     'class' => 'set-si-youtube',   'placeholder' => 'https://youtube.com/seucanal'],
                        'linkedin_url'  => ['label' => 'LinkedIn',    'icon' => 'fa-brands fa-linkedin-in', 'class' => 'set-si-linkedin',  'placeholder' => 'https://linkedin.com/in/voce'],
                        'whatsapp_url'  => ['label' => 'WhatsApp',    'icon' => 'fa-brands fa-whatsapp',    'class' => 'set-si-whatsapp',  'placeholder' => 'https://wa.me/5511999990000'],
                    ];
                @endphp
                <div class="set-form-grid">
                    @foreach($socials as $key => $social)
                        <div>
                            <label class="mir-label">{{ $social['label'] }}</label>
                            <div class="set-social-input @error('site_social_links.' . $key) is-invalid @enderror">
                                <span class="set-social-icon {{ $social['class'] }}"><i class="{{ $social['icon'] }}"></i></span>
                                <input type="text" class="set-social-field" wire:model="site_social_links.{{ $key }}" placeholder="{{ $social['placeholder'] }}">
                            </div>
                            @error('site_social_links.' . $key) <div class="set-field-error">{{ $message }}</div> @enderror
                        </div>
                    @endforeach
                </div>
                <div class="set-section-footer">
                    <button type="submit" class="mir-btn-primary-lg" wire:loading.attr="disabled" wire:target="updateSocialLinks">
                        <span wire:loading wire:target="updateSocialLinks"><span class="spinner-border spinner-border-sm mr-1"></span></span>
                        <i class="fa-solid fa-floppy-disk" wire:loading.remove wire:target="updateSocialLinks"></i>
                        Salvar Redes Sociais
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="set-section-card set-section-full">
        <div class="set-section-header">
            <div class="set-section-icon set-section-icon-amber"><i class="fa-solid fa-image"></i></div>
            <div>
                <div class="set-section-title">Logo & Favicon</div>
                <div class="set-section-sub">Gerencie a identidade visual do site</div>
            </div>
        </div>
        <div class="set-section-body">
            <form wire:submit.prevent="updateLogoFavicon">
                <div class="set-logo-grid">
                    <div class="set-logo-card">
                        <div class="set-logo-header set-logo-header-light">
                            <span><i class="fa-solid fa-sun" style="color:#d97706;margin-right:4px;"></i> Logo (Tema Claro)</span>
                            <span style="font-size:.7rem;color:#9ca3af;font-weight:400;">PNG transparente</span>
                        </div>
                        <div class="set-logo-preview-light" wire:ignore>
                            @if($site_logo_light)
                                <img id="preview_logo_light" src="{{ asset('uploads/logo/' . $site_logo_light) }}" alt="Logo Light" style="max-height:60px;max-width:100%;object-fit:contain;">
                                <span id="placeholder_logo_light" style="display:none;" class="set-logo-empty"><i class="fa-solid fa-image" style="font-size:1.5rem"></i> Nenhuma logo enviada</span>
                            @else
                                <img id="preview_logo_light" src="" style="display:none;max-height:60px;max-width:100%;">
                                <span id="placeholder_logo_light" class="set-logo-empty"><i class="fa-solid fa-image" style="font-size:1.5rem"></i> Nenhuma logo enviada</span>
                            @endif
                        </div>
                        <div class="set-logo-footer">
                            <div class="set-field-hint mb-2"><i class="fa-solid fa-circle-info"></i> 200×50px · Máx 2MB</div>
                            <input type="file" class="set-file-input" wire:model="new_logo_light" accept="image/*" onchange="previewImage(event,'preview_logo_light','placeholder_logo_light')">
                            @error('new_logo_light') <div class="set-field-error">{{ $message }}</div> @enderror
                            <div wire:loading wire:target="new_logo_light" class="set-field-hint mt-1"><span class="spinner-border spinner-border-sm"></span> Carregando...</div>
                        </div>
                    </div>
                    <div class="set-logo-card">
                        <div class="set-logo-header set-logo-header-dark">
                            <span><i class="fa-solid fa-moon" style="color:#818cf8;margin-right:4px;"></i> Logo (Tema Escuro)</span>
                            <span style="font-size:.7rem;color:#6b7280;font-weight:400;">PNG branco</span>
                        </div>
                        <div class="set-logo-preview-dark" wire:ignore>
                            @if($site_logo_dark)
                                <img id="preview_logo_dark" src="{{ asset('uploads/logo/' . $site_logo_dark) }}" alt="Logo Dark" style="max-height:60px;max-width:100%;object-fit:contain;">
                                <span id="placeholder_logo_dark" style="display:none;" class="set-logo-empty"><i class="fa-solid fa-image" style="font-size:1.5rem"></i> Nenhuma logo enviada</span>
                            @else
                                <img id="preview_logo_dark" src="" style="display:none;max-height:60px;max-width:100%;">
                                <span id="placeholder_logo_dark" class="set-logo-empty" style="color:#6b7280;"><i class="fa-solid fa-image" style="font-size:1.5rem"></i> Nenhuma logo enviada</span>
                            @endif
                        </div>
                        <div class="set-logo-footer">
                            <div class="set-field-hint mb-2"><i class="fa-solid fa-circle-info"></i> 200×50px · Máx 2MB</div>
                            <input type="file" class="set-file-input" wire:model="new_logo_dark" accept="image/*" onchange="previewImage(event,'preview_logo_dark','placeholder_logo_dark')">
                            @error('new_logo_dark') <div class="set-field-error">{{ $message }}</div> @enderror
                            <div wire:loading wire:target="new_logo_dark" class="set-field-hint mt-1"><span class="spinner-border spinner-border-sm"></span> Carregando...</div>
                        </div>
                    </div>
                    <div class="set-logo-card">
                        <div class="set-logo-header set-logo-header-light">
                            <span><i class="fa-solid fa-globe" style="color:#6366f1;margin-right:4px;"></i> Favicon</span>
                            <span style="font-size:.7rem;color:#9ca3af;font-weight:400;">Aba do navegador</span>
                        </div>
                        <div class="set-logo-preview-browser" wire:ignore>
                            <div style="background:#e0e0e0;border-radius:8px 8px 0 0;padding:6px 14px;display:inline-flex;align-items:center;gap:6px;border:1px solid #bbb;border-bottom:none;">
                                @if($site_favicon)
                                    <img id="preview_favicon" src="{{ asset('uploads/logo/' . $site_favicon) }}" style="width:14px;height:14px;object-fit:contain;">
                                    <span id="placeholder_favicon" style="display:none;font-size:13px;color:#999;"><i class="fa-solid fa-globe"></i></span>
                                @else
                                    <img id="preview_favicon" src="" style="display:none;width:14px;height:14px;">
                                    <span id="placeholder_favicon" style="font-size:13px;color:#999;"><i class="fa-solid fa-globe"></i></span>
                                @endif
                                <span style="font-size:12px;color:#444;white-space:nowrap;">Meu Site</span>
                                <span style="font-size:11px;color:#999;">✕</span>
                            </div>
                            <div style="height:4px;width:180px;background:#fff;border:1px solid #bbb;border-top:none;"></div>
                        </div>
                        <div class="set-logo-footer">
                            <div class="set-field-hint mb-2"><i class="fa-solid fa-circle-info"></i> ICO ou PNG · 32×32px · Máx 1MB</div>
                            <input type="file" class="set-file-input" wire:model="new_favicon" accept="image/*,.ico" onchange="previewImage(event,'preview_favicon','placeholder_favicon')">
                            @error('new_favicon') <div class="set-field-error">{{ $message }}</div> @enderror
                            <div wire:loading wire:target="new_favicon" class="set-field-hint mt-1"><span class="spinner-border spinner-border-sm"></span> Carregando...</div>
                        </div>
                    </div>
                </div>
                <div class="set-section-footer">
                    <span class="set-field-hint" style="margin:0;"><i class="fa-solid fa-circle-info"></i> Apenas os campos com novo arquivo serão atualizados.</span>
                    <button type="submit" class="mir-btn-primary-lg"
                            {{ !$new_logo_light && !$new_logo_dark && !$new_favicon ? 'disabled' : '' }}
                            wire:loading.attr="disabled" wire:target="updateLogoFavicon">
                        <span wire:loading wire:target="updateLogoFavicon"><span class="spinner-border spinner-border-sm mr-1"></span></span>
                        <i class="fa-solid fa-cloud-arrow-up" wire:loading.remove wire:target="updateLogoFavicon"></i>
                        Upload & Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Toast container --}}
<div id="set-toast-container" aria-live="polite"></div>

<style>
    .set-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;}
    .set-section-card{background:#fff;border-radius:10px;border:1px solid #e9ecef;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;display:flex;flex-direction:column;}
    .set-section-full{grid-column:1 / -1;}
    .set-section-header{display:flex;align-items:center;gap:12px;padding:16px 20px;border-bottom:1px solid #f0f0f0;}
    .set-section-icon{width:34px;height:34px;border-radius:8px;background:#ede9fe;color:#6366f1;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.82rem;}
    .set-section-icon-social{background:#d1fae5;color:#059669;} .set-section-icon-amber{background:#fef3c7;color:#d97706;}
    .set-section-title{font-size:.88rem;font-weight:700;color:#1a1d23;} .set-section-sub{font-size:.72rem;color:#9ca3af;margin-top:1px;}
    .set-section-body{padding:20px;flex:1;}
    .set-section-footer{padding:16px 20px;border-top:1px solid #f0f0f0;background:#fafafa;display:flex;align-items:center;justify-content:flex-end;gap:16px;}
    .set-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:0;} .set-form-full{grid-column:1 / -1;}
    .set-optional{font-size:.72rem;font-weight:400;color:#9ca3af;} .set-field-error{font-size:.78rem;color:#ef4444;margin-top:4px;}
    .set-field-hint{font-size:.75rem;color:#9ca3af;margin-top:5px;display:flex;align-items:center;gap:4px;}
    .set-logo-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
    .set-logo-card{border-radius:10px;border:1px solid #e9ecef;overflow:hidden;}
    .set-logo-header{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;font-size:.82rem;font-weight:600;}
    .set-logo-header-light{background:#f8f9fa;color:#374151;border-bottom:1px solid #e9ecef;}
    .set-logo-header-dark{background:#1e2130;color:#e5e7eb;border-bottom:1px solid #374151;}
    .set-logo-preview-light{min-height:110px;background:#fff;display:flex;align-items:center;justify-content:center;border-bottom:1px solid #e9ecef;padding:16px;}
    .set-logo-preview-dark{min-height:110px;background:#1a1a2e;display:flex;align-items:center;justify-content:center;border-bottom:1px solid #374151;padding:16px;}
    .set-logo-preview-browser{min-height:110px;background:#e8e8e8;display:flex;flex-direction:column;align-items:center;justify-content:center;border-bottom:1px solid #e9ecef;padding:16px;}
    .set-logo-empty{display:flex;flex-direction:column;align-items:center;gap:6px;color:#9ca3af;font-size:.75rem;text-align:center;}
    .set-logo-footer{padding:14px 16px;background:#fafafa;}
    .set-file-input{width:100%;padding:7px;border:1.5px dashed #d1d5db;border-radius:8px;font-size:.8rem;color:#6d7279;cursor:pointer;background:#fff;transition:border-color .15s;}
    .set-file-input:hover{border-color:#6366f1;}
    .set-social-input{display:flex;align-items:center;border:1.5px solid #e5e7eb;border-radius:8px;overflow:hidden;transition:border-color .15s,box-shadow .15s;}
    .set-social-input:focus-within{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.12);} .set-social-input.is-invalid{border-color:#ef4444;}
    .set-social-icon{width:40px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:#f9fafb;border-right:1px solid #e5e7eb;font-size:.9rem;color:#6d7279;align-self:stretch;}
    .set-social-field{flex:1;padding:8px 12px;border:none;outline:none;font-size:.84rem;color:#1a1d23;background:transparent;}
    .set-si-facebook{color:#3b5998;} .set-si-instagram{color:#e1306c;} .set-si-twitter{color:#1da1f2;}
    .set-si-youtube{color:#ff0000;} .set-si-linkedin{color:#0077b5;} .set-si-whatsapp{color:#25d366;}
    @media(max-width:992px){.set-grid{grid-template-columns:1fr;} .set-logo-grid{grid-template-columns:1fr;}}
    @media(max-width:768px){.set-form-grid{grid-template-columns:1fr;}}
</style>

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

(function () {
    function setShowToast(type, message) {
        const container = document.getElementById('set-toast-container');
        const icons = { success: 'fa-circle-check', error: 'fa-circle-exclamation', warning: 'fa-triangle-exclamation', info: 'fa-circle-info' };
        const toast = document.createElement('div');
        toast.className = `mir-toast mir-toast-${type}`;
        toast.innerHTML = `<i class="fa-solid ${icons[type] || icons.info} mir-toast-icon"></i><span class="mir-toast-msg">${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'mir-toast-out 200ms ease forwards';
            setTimeout(() => toast.remove(), 210);
        }, 3500);
    }

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', ({ type, message }) => setShowToast(type, message));
    });
})();
</script>
@endpush

</div>