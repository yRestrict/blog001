{{-- livewire/admin/social-links.blade.php --}}
<div>
<style>
    /* Input com ícone prefixo (padrão mir-) */
    .social-input-wrap {
        display: flex;
        align-items: center;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        transition: border-color .15s, box-shadow .15s;
    }
    .social-input-wrap:focus-within {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.12);
    }
    .social-input-wrap.is-invalid { border-color: #ef4444; }

    .social-icon-prefix {
        width: 42px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9fafb;
        border-right: 1px solid #e5e7eb;
        font-size: .95rem;
        align-self: stretch;
    }
    .social-input-inner {
        flex: 1;
        padding: 8px 10px;
        border: none;
        outline: none;
        font-size: .84rem;
        color: #1a1d23;
        background: transparent;
    }
    .social-test-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        flex-shrink: 0;
        border: none;
        border-left: 1px solid #e5e7eb;
        background: #f9fafb;
        cursor: pointer;
        color: #9ca3af;
        font-size: .8rem;
        align-self: stretch;
        transition: background .15s, color .15s;
        text-decoration: none;
    }
    .social-test-btn:hover { background: #ede9fe; color: #6366f1; }

    .mir-label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .mir-field-error { font-size: .78rem; color: #ef4444; margin-top: 5px; display:flex; align-items:center; gap:4px; }
    .mir-field-hint  { font-size: .75rem; color: #9ca3af; margin-top: 5px; display:flex; align-items:center; gap:4px; }

    .mir-btn-primary-lg {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 20px; border-radius: 8px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff; font-size: .83rem; font-weight: 600;
        border: none; cursor: pointer; transition: opacity .15s;
        box-shadow: 0 2px 8px rgba(99,102,241,.35);
    }
    .mir-btn-primary-lg:hover { opacity: .9; }

    /* Cores oficiais das redes */
    .si-facebook  { color: #3b5998; }
    .si-instagram { color: #e1306c; }
    .si-youtube   { color: #ff0000; }
    .si-twitter   { color: #1da1f2; }
    .si-whatsapp  { color: #25d366; }
    .si-steam     { color: #171a21; }
</style>

<form wire:submit.prevent="updateSocialLinks">
    <div class="row">

        @php
            $socials = [
                [
                    'field'       => 'facebook_username',
                    'label'       => 'Facebook',
                    'icon'        => 'fa-facebook',
                    'color'       => 'si-facebook',
                    'placeholder' => 'Ex: meuperfil',
                    'hint'        => 'Apenas o nome de usuário (ex: meuperfil)',
                    'maxlength'   => 50,
                    'href'        => isset($facebook_username) && $facebook_username
                                        ? 'https://facebook.com/' . $facebook_username
                                        : null,
                ],
                [
                    'field'       => 'instagram_username',
                    'label'       => 'Instagram',
                    'icon'        => 'fa-instagram',
                    'color'       => 'si-instagram',
                    'placeholder' => 'Ex: @meuperfil ou meuperfil',
                    'hint'        => 'Digite seu @usuario (ex: @meuperfil)',
                    'maxlength'   => 30,
                    'href'        => isset($instagram_username) && $instagram_username
                                        ? 'https://instagram.com/' . ltrim($instagram_username, '@')
                                        : null,
                ],
                [
                    'field'       => 'youtube_channel',
                    'label'       => 'YouTube',
                    'icon'        => 'fa-youtube',
                    'color'       => 'si-youtube',
                    'placeholder' => 'Ex: @meucanal ou UCxxxxx',
                    'hint'        => 'Digite @seucanal ou o ID do canal',
                    'maxlength'   => 100,
                    'href'        => isset($youtube_channel) && $youtube_channel
                                        ? 'https://youtube.com/' . (str_starts_with($youtube_channel, '@') ? $youtube_channel : '@' . $youtube_channel)
                                        : null,
                ],
                [
                    'field'       => 'twitter_username',
                    'label'       => 'Twitter / X',
                    'icon'        => 'fa-twitter',
                    'color'       => 'si-twitter',
                    'placeholder' => 'Ex: @seunome ou seunome',
                    'hint'        => 'Digite seu @usuario (ex: @meuperfil)',
                    'maxlength'   => 15,
                    'href'        => isset($twitter_username) && $twitter_username
                                        ? 'https://twitter.com/' . ltrim($twitter_username, '@')
                                        : null,
                ],
                [
                    'field'       => 'whatsapp_number',
                    'label'       => 'WhatsApp',
                    'icon'        => 'fa-whatsapp',
                    'color'       => 'si-whatsapp',
                    'placeholder' => 'Ex: 11999999999',
                    'hint'        => 'Número com DDD, sem espaços ou traços',
                    'maxlength'   => 20,
                    'href'        => isset($whatsapp_number) && $whatsapp_number
                                        ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp_number)
                                        : null,
                ],
                [
                    'field'       => 'steam_username',
                    'label'       => 'Steam',
                    'icon'        => 'fa-steam',
                    'color'       => 'si-steam',
                    'placeholder' => 'Ex: meuusuario',
                    'hint'        => 'Seu ID do Steam (ex: meuusuario)',
                    'maxlength'   => 50,
                    'href'        => isset($steam_username) && $steam_username
                                        ? 'https://steamcommunity.com/id/' . $steam_username
                                        : null,
                ],
            ];
        @endphp

        @foreach($socials as $social)
            <div class="col-md-6 mb-3">
                <label class="mir-label">{{ $social['label'] }}</label>
                <div class="social-input-wrap @error($social['field']) is-invalid @enderror">

                    {{-- Ícone prefixo --}}
                    <span class="social-icon-prefix {{ $social['color'] }}">
                        <i class="fa {{ $social['icon'] }}"></i>
                    </span>

                    {{-- Input --}}
                    <input type="text"
                           class="social-input-inner"
                           wire:model.defer="{{ $social['field'] }}"
                           placeholder="{{ $social['placeholder'] }}"
                           maxlength="{{ $social['maxlength'] }}">

                    {{-- Botão testar link (só aparece se tiver valor) --}}
                    @if($social['href'])
                        <a href="{{ $social['href'] }}"
                           target="_blank"
                           class="social-test-btn"
                           title="Testar link">
                            <i class="fa fa-external-link"></i>
                        </a>
                    @endif

                </div>

                {{-- Erro ou hint --}}
                @error($social['field'])
                    <div class="mir-field-error">
                        <i class="fa fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @else
                    <div class="mir-field-hint">
                        <i class="fa fa-info-circle"></i> {{ $social['hint'] }}
                    </div>
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
            Atualizar Redes Sociais
        </button>
    </div>
</form>

</div>