<div class="widget-tags">
    <ul class="list-inline">
        @forelse($data as $tag)
            <li>
                <a href="{{ route('frontend.tag', ['id' => $tag['slug']]) }}">
                    {{ $tag['name'] }} ({{ $tag['posts_count'] }})
                </a>
            </li>
        @empty
            <div>Nenhuma tag encontrada!</div>
        @endforelse
    </ul>
</div>