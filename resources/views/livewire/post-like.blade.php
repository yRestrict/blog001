<div class="d-inline-block">
    <button wire:click="toggle"
            class="btn {{ $liked ? 'btn-danger' : 'btn-outline-danger' }} btn-sm"
            title="{{ $liked ? 'Remover like' : 'Curtir este post' }}">
        <i class="fa fa-heart{{ $liked ? '' : '-o' }}"></i>
        <span class="ml-1">{{ $likesCount }}</span>
        {{ $liked ? 'Curtido' : 'Curtir' }}
    </button>
</div>