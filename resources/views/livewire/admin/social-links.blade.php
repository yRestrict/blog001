<div>
    <div class="pd-20 profile-task-wrap">
        <form wire:submit.prevent="updateSocialLinks">
            <div class="row">
                {{-- Facebook --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for=""><b>Facebook</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-facebook text-primary"></i>
                                </span>
                            </div>
                            <input 
                                type="text" 
                                class="form-control" 
                                wire:model.defer='facebook_username' 
                                placeholder="Ex: seunome ou seuperfil"
                                maxlength="50">
                            
                            @if($facebook_username)
                            <div class="input-group-append">
                                <a href="https://facebook.com/{{ $facebook_username }}" 
                                target="_blank" 
                                class="btn btn-outline-primary"
                                title="Testar link">
                                    <i class="fa fa-external-link"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                        
                        <div class="field-message">
                            @error('facebook_username')
                                <span class="error">{{ $message }}</span>
                            @else
                                <span class="hint">
                                    <i class="fa fa-info-circle"></i>
                                    Apenas o usuário (ex: meuperfil)
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Instagram --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for=""><b>Instagram</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-instagram text-danger"></i>
                                </span>
                            </div>
                            <input 
                                type="text" 
                                class="form-control" 
                                wire:model.defer='instagram_username' 
                                placeholder="Ex: @seunome ou seunome"
                                maxlength="30">
                            
                            @if($instagram_username)
                            <div class="input-group-append">
                                <a href="https://instagram.com/{{ ltrim($instagram_username, '@') }}" 
                                target="_blank" 
                                class="btn btn-outline-danger"
                                title="Testar link">
                                    <i class="fa fa-external-link"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="field-message">
                            @error('steam_username')
                                <span class="error">{{ $message }}</span>
                            @else
                                <span class="hint">
                                    <i class="fa fa-info-circle"></i>
                                    Digite seu @usuario (ex: @meuperfil)
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- YouTube --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for=""><b>YouTube</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-youtube" style="color: #FF0000;"></i>
                                </span>
                            </div>
                            <input 
                                type="text" 
                                class="form-control" 
                                wire:model.defer='youtube_channel' 
                                placeholder="Ex: @meucanal ou UCxxxxx"
                                maxlength="100">
                            
                            @if($youtube_channel)
                            <div class="input-group-append">
                                <a href="https://youtube.com/{{ str_starts_with($youtube_channel, '@') ? $youtube_channel : '@' . $youtube_channel }}" 
                                target="_blank" 
                                class="btn btn-outline-danger"
                                title="Testar link">
                                    <i class="fa fa-external-link"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="field-message">
                            @error('steam_username')
                                <span class="error">{{ $message }}</span>
                            @else
                                <span class="hint">
                                    <i class="fa fa-info-circle"></i>
                                    Digite @seucanal ou ID do canal
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Twitter --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for=""><b>Twitter / X</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-twitter" style="color: #1da1f2;"></i>
                                </span>
                            </div>
                            <input 
                                type="text" 
                                class="form-control" 
                                wire:model.defer='twitter_username' 
                                placeholder="Ex: @seunome ou seunome"
                                maxlength="15">
                            
                            @if($twitter_username)
                            <div class="input-group-append">
                                <a href="https://twitter.com/{{ ltrim($twitter_username, '@') }}" 
                                target="_blank" 
                                class="btn btn-outline-info"
                                title="Testar link">
                                    <i class="fa fa-external-link"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="field-message">
                            @error('steam_username')
                                <span class="error">{{ $message }}</span>
                            @else
                                <span class="hint">
                                    <i class="fa fa-info-circle"></i>
                                    Digite seu @usuario (ex: @meuperfil)
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- WhatsApp --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for=""><b>WhatsApp</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-whatsapp" style="color: #25D366;"></i>
                                </span>
                            </div>
                            <input 
                                type="text" 
                                class="form-control" 
                                wire:model.defer='whatsapp_number' 
                                placeholder="Ex: 11999999999"
                                maxlength="20">
                            
                            @if($whatsapp_number)
                            <div class="input-group-append">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp_number) }}" 
                                target="_blank" 
                                class="btn btn-outline-success"
                                title="Testar número">
                                    <i class="fa fa-external-link"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="field-message">
                            @error('whatsapp_number')
                                <span class="error">{{ $message }}</span>
                            @else
                                <span class="hint">
                                    <i class="fa fa-info-circle"></i>
                                    Digite o número com DDD (ex: 11999999999)
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Steam --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for=""><b>Steam</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-steam" style="color: #171a21;"></i>
                                </span>
                            </div>
                            <input 
                                type="text" 
                                class="form-control" 
                                wire:model.defer='steam_username' 
                                placeholder="Ex: seuid"
                                maxlength="50">
                            
                            @if($steam_username)
                            <div class="input-group-append">
                                <a href="https://steamcommunity.com/id/{{ $steam_username }}" 
                                target="_blank" 
                                class="btn btn-outline-dark"
                                title="Testar link">
                                    <i class="fa fa-external-link"></i>
                                </a>
                            </div>
                            @endif
                        </div>
 
                        <div class="field-message">
                            @error('steam_username')
                                <span class="error">{{ $message }}</span>
                            @else
                                <span class="hint">
                                    <i class="fa fa-info-circle"></i>
                                    Digite seu ID do Steam (ex: meuusuario)
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save mr-1"></i> Atualizar Redes Sociais
            </button>
        </form>
    </div>
</div>


