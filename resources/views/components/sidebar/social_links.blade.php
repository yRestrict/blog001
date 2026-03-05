<div class="widget-stay-connected">
    <div class="list">
        @forelse($data as $social)
            @if(isset($social['name'], $social['icon'], $social['color'], $social['link']))
                <a href="{{ $social['link'] }}" target="_blank" rel="noopener noreferrer">
                    <div class="item" style="background-color: {{ $social['color'] }}">
                        <i class="{{ $social['icon'] }} text-white"></i>
                    </div>
                </a>
            @endif
        @empty
            <div class="text-muted">Nenhuma rede social configurada!</div>
        @endforelse
    </div>
</div>