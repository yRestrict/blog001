<div>
    <div class="tab">
        <ul class="nav nav-tabs customtab" role="tablist">
            <li class="nav-item">
                <a wire:click="selectTab('general_settings')" class="nav-link {{ $tab == 'general_settings' ? 'active' : '' }}" data-toggle="tab" href="#general_settings" role="tab" aria-selected="true">
                    <i class="fa fa-cog mr-1"></i> General Settings
                </a>
            </li>
            <li class="nav-item">
                <a wire:click="selectTab('logo_favicon')" class="nav-link {{ $tab == 'logo_favicon' ? 'active' : '' }}" data-toggle="tab" href="#logo_favicon" role="tab" aria-selected="false">
                    <i class="fa fa-image mr-1"></i> Logo & Favicon
                </a>
            </li>
            <li class="nav-item">
                <a wire:click="selectTab('social_link')" class="nav-link {{ $tab == 'social_link' ? 'active' : '' }}" data-toggle="tab" href="#logo_favicon" role="tab" aria-selected="false">
                    <i class="fa fa-share-alt mr-1"></i> Social Links
                </a>
            </li>
        </ul>

        <div class="tab-content">
            {{-- GENERAL SETTINGS TAB --}}
            <div class="tab-pane fade {{ $tab == 'general_settings' ? 'show active' : '' }}" id="general_settings" role="tabpanel">
                <div class="pd-20">
                    <form wire:submit.prevent="updateGeneralSettings">
                        <div class="row">
                            {{-- Site Title --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b>Site Title</b> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model.defer="site_title" placeholder="Enter site title">
                                    @error('site_title')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Site Email --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b>Site Email</b> <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" wire:model.defer="site_email" placeholder="Enter site email">
                                    @error('site_email')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Site Phone --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b>Site Phone Number</b> <small class="text-muted">(Optional)</small></label>
                                    <input type="text" class="form-control" wire:model.defer="site_phone" placeholder="Enter site phone">
                                    @error('site_phone')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Site Meta Keywords --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b>Site Meta Keywords</b> <small class="text-muted">(Optional)</small></label>
                                    <input type="text" class="form-control" wire:model.defer="site_meta_keywords" placeholder="Eg: ecommerce, free api, laravel">
                                    @error('site_meta_keywords')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="fa fa-info-circle"></i> Separate keywords with commas
                                    </small>
                                </div>
                            </div>

                            {{-- Site Meta Description --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for=""><b>Site Description</b> <small class="text-muted">(Optional)</small></label>
                                    <textarea class="form-control" wire:model.defer="site_description" cols="4" rows="4" placeholder="Descrição completa do seu site (usado no rodapé, página sobre, etc.)"></textarea>
                                    @error('site_description')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="fa fa-info-circle"></i> Descrição longa para uso geral no site (max 1000 caracteres)
                                    </small>
                                </div>
                            </div>

                            {{-- Site Meta Description --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for=""><b>Site Meta Description</b> <small class="text-muted">(Optional)</small></label>
                                    <textarea class="form-control" wire:model.defer="site_meta_description" cols="4" rows="4" placeholder="Type site meta description..."></textarea>
                                    @error('site_meta_description')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="fa fa-info-circle"></i> Brief description for search engines (max 500 characters)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save mr-1"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>

            {{-- LOGO & FAVICON TAB --}}
            <div class="tab-pane fade {{ $tab == 'logo_favicon' ? 'show active' : '' }}" id="logo_favicon" role="tabpanel">
                <div class="pd-20">
                    <form wire:submit.prevent="updateLogoFavicon">

                        <div class="row">

                            {{-- ===== LOGO LIGHT ===== --}}
                            <div class="col-md-4">
                                <div class="card border h-100">
                                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                                        <div>
                                            <b>Logo Dark</b>
                                            <span class="badge badge-light border ml-1" style="font-size:11px;">Tema Claro</span>
                                        </div>
                                        <i class="fa fa-sun-o text-warning"></i>
                                    </div>

                                    {{-- Simulação de fundo claro --}}
                                    <div class="card-body" style="background:#f8f9fa; min-height:120px;" wire:ignore>
                                        <p class="text-muted small mb-2 text-center">
                                            <i class="fa fa-eye"></i> Prévia — como aparece no tema claro
                                        </p>
                                        <div class="d-flex align-items-center justify-content-center" style="min-height:80px; background:#ffffff; border:1px dashed #dee2e6; border-radius:6px;">
                                            <img id="preview_logo_light"
                                                src="{{ $site_logo_light ? asset('uploads/logo/' . $site_logo_light) : '' }}"
                                                alt="Logo Light"
                                                style="max-height:60px; max-width:100%; {{ !$site_logo_light ? 'display:none' : '' }}">
                                            <span id="placeholder_logo_light" class="text-muted small" style="{{ $site_logo_light ? 'display:none' : '' }}">
                                                <i class="fa fa-image fa-2x d-block text-center mb-1"></i>
                                                Nenhuma logo enviada
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-white">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fa fa-info-circle"></i> PNG transparente, 200x50px, Max 2MB
                                        </small>
                                        <input type="file"
                                            class="form-control-file"
                                            wire:model="new_logo_light"
                                            accept="image/*"
                                            onchange="previewImage(event, 'preview_logo_light', 'placeholder_logo_light')">
                                        @error('new_logo_light')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ===== LOGO DARK ===== --}}
                            <div class="col-md-4">
                                <div class="card border h-100">
                                    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
                                        <div>
                                            <b>Logo Light</b>
                                            <span class="badge badge-secondary ml-1" style="font-size:11px;">Tema Escuro</span>
                                        </div>
                                        <i class="fa fa-moon-o text-info"></i>
                                    </div>

                                    {{-- Simulação de fundo escuro --}}
                                    <div class="card-body" style="background:#2c2c2c; min-height:120px;" wire:ignore>
                                        <p class="text-white-50 small mb-2 text-center">
                                            <i class="fa fa-eye"></i> Prévia — como aparece no tema escuro
                                        </p>
                                        <div class="d-flex align-items-center justify-content-center" style="min-height:80px; background:#1a1a1a; border:1px dashed #444; border-radius:6px;">
                                            <img id="preview_logo_dark"
                                                src="{{ $site_logo_dark ? asset('uploads/logo/' . $site_logo_dark) : '' }}"
                                                alt="Logo Dark"
                                                style="max-height:60px; max-width:100%; {{ !$site_logo_dark ? 'display:none' : '' }}">
                                            <span id="placeholder_logo_dark" class="text-white-50 small" style="{{ $site_logo_dark ? 'display:none' : '' }}">
                                                <i class="fa fa-image fa-2x d-block text-center mb-1"></i>
                                                Nenhuma logo enviada
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-white">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fa fa-info-circle"></i> PNG transparente, 200x50px, Max 2MB
                                        </small>
                                        <input type="file"
                                            class="form-control-file"
                                            wire:model="new_logo_dark"
                                            accept="image/*"
                                            onchange="previewImage(event, 'preview_logo_dark', 'placeholder_logo_dark')">
                                        @error('new_logo_dark')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ===== FAVICON ===== --}}
                            <div class="col-md-4">
                                <div class="card border h-100">
                                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                                        <div>
                                            <b>Favicon</b>
                                            <span class="badge badge-light border ml-1" style="font-size:11px;">Aba do Navegador</span>
                                        </div>
                                        <i class="fa fa-chrome text-primary"></i>
                                    </div>

                                    {{-- Simulação de aba do browser --}}
                                    <div class="card-body" style="background:#f0f0f0; min-height:120px;" wire:ignore>
                                        <p class="text-muted small mb-2 text-center">
                                            <i class="fa fa-eye"></i> Prévia — como aparece na aba
                                        </p>
                                        {{-- Simulação de aba do browser --}}
                                        <div class="d-flex justify-content-center">
                                            <div style="background:#e8e8e8; border-radius:8px 8px 0 0; padding:6px 16px; display:inline-flex; align-items:center; gap:6px; border:1px solid #ccc; border-bottom:none; max-width:180px;">
                                                <img id="preview_favicon"
                                                    src="{{ $site_favicon ? asset('uploads/logo/' . $site_favicon) : '' }}"
                                                    alt="Favicon"
                                                    style="width:16px; height:16px; object-fit:contain; {{ !$site_favicon ? 'display:none' : '' }}">
                                                <span id="placeholder_favicon" style="{{ $site_favicon ? 'display:none' : '' }}">
                                                    <i class="fa fa-globe" style="font-size:14px; color:#999;"></i>
                                                </span>
                                                <span style="font-size:12px; color:#444; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Meu Site</span>
                                                <span style="font-size:11px; color:#999; margin-left:4px;">✕</span>
                                            </div>
                                        </div>
                                        <div style="height:4px; background:#fff; border:1px solid #ccc; border-top:none; margin: 0 10px;"></div>
                                    </div>

                                    <div class="card-footer bg-white">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fa fa-info-circle"></i> ICO ou PNG, 32x32px, Max 1MB
                                        </small>
                                        <input type="file"
                                            class="form-control-file"
                                            wire:model="new_favicon"
                                            accept="image/*,.ico"
                                            onchange="previewImage(event, 'preview_favicon', 'placeholder_favicon')">
                                        @error('new_favicon')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>{{-- end row --}}

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" {{ !$new_logo_light && !$new_logo_dark && !$new_favicon ? 'disabled' : '' }}>
                                <i class="fa fa-upload mr-1"></i> Upload & Save
                            </button>
                            <small class="text-muted ml-2">Apenas os campos com novo arquivo serão atualizados.</small>
                        </div>

                    </form>
                </div>
            </div>




            <div class="tab-pane fade {{ $tab == 'social_link' ? 'show active' : '' }}" id="social_link" role="tabpanel">
                <div class="pd-20">
                    <form wire:submit.prevent="updateSocialLinks">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><b>Facebook URL</b></label>
                                    <input type="text" class="form-control" wire:model="site_social_links.facebook_url" placeholder="https://facebook.com/suapagina">
                                    @error('site_social_links.facebook_url') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><b>Instagram URL</b></label>
                                    <input type="text" class="form-control" wire:model="site_social_links.instagram_url" placeholder="https://instagram.com/seuperfil">
                                    @error('site_social_links.instagram_url') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><b>Twitter/X URL</b></label>
                                    <input type="text" class="form-control" wire:model="site_social_links.twitter_url" placeholder="https://twitter.com/seuusuario">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><b>Youtube</b></label>
                                    <input type="text" class="form-control" wire:model="site_social_links.linkedin_url" placeholder="https://youtube.com/seucanal">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Redes Sociais</button>
                    </form>
                </div>
            </div>
        </div>
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
            const placeholder = document.getElementById(placeholderId);

            img.src = e.target.result;
            img.style.display = 'block';

            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush