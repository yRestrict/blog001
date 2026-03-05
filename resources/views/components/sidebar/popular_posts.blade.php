<ul class="widget-popular-posts">
    @forelse($data as $post)
        <li class="small-post">
            <div class="small-post-image">
                <a href="{{ route('frontend.post', ['slug' => $post['slug']]) }}">
                    <img src="{{ asset('uploads/posts/' . $post['thumbnail']) }}"
                         alt="{{ $post['title'] }}">
                    <small class="nb">{{ $loop->iteration }}</small>
                </a>
            </div>
            <div class="small-post-content">
                <p>
                    <a href="{{ route('frontend.post', ['slug' => $post['slug']]) }}">
                        {{ $post['title'] }}
                    </a>
                </p>
                <small>
                    {{ \Carbon\Carbon::parse($post['created_at'])->diffForHumans() }}
                </small>
            </div>
        </li>
    @empty
        <div>Nenhuma postagem encontrada!</div>
    @endforelse
</ul>