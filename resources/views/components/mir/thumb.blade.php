{{--
    Thumbnail 48x48 com fallback de ícone
    @props: src (url da imagem), icon (ícone fallback)
--}}
@props(['src' => null, 'icon' => 'image'])

<div class="mir-thumb">
    @if($src)
        <img src="{{ $src }}" alt="">
    @else
        <i class="fa-solid fa-{{ $icon }}"></i>
    @endif
</div>
