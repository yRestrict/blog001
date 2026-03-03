<div class="post-single-author">
    <div class="authors-info">
        <div class="image">
            <a href="{{ route("frontend.user", $post->user->username) }}" class="image">
                <img src="{{ asset("uploads/author/".($post->user->profile ?? "default.webp")) }}" alt="{{ $post->user->name }}"/>
            </a>
        </div>
        <div class="content">
            <a href="{{ route("frontend.user", $post->user->username) }}"><h4>{{ $post->user->name }}</h4></a>
            @if ($post->user->about)
            <p>{{ $post->user->about }}</p>
            @endif
        </div>
    </div>
</div>
