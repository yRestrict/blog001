<div class="col-lg-8 oredoo-content">
    <div class="theiaStickySidebar">
        <div class="section-title">
            <h3>Posts recentes</h3>
        </div>
        @forelse ($posts as $post)
            <div class="post-list post-list-style3">
                <div class="post-list-image">
                    <a href="{{ route('frontend.post', $post->slug) }}">
                        <img class="post-thumbnail" 
                            src="{{ asset('uploads/posts/' . $post->thumbnail) }}" 
                            alt="{{ $post->title }}" />
                    </a>
                </div>
                <div class="post-list-content">
                    <ul class="entry-meta">
                        <li class="post-author">
                            <a href="{{ route('frontend.user', $post->author->username) }}">
                                {{ $post->author->name }}
                            </a>
                        </li>
                        <li class="post-date">
                            <span class="line"></span>
                            <i class="far fa-calendar-alt"></i>
                            {{ $post->created_at->translatedFormat('d M, Y') }}
                        </li>
                        <li class="post-views">
                            <span class="line"></span>
                            {{ number_format($post->views) }} &nbsp;
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </li>
                    </ul>

                    <h4 class="entry-title">
                        <a href="{{ route('frontend.post', $post->slug) }}" title="{{ $post->title }}">
                            {{ $post->title }}
                        </a>
                    </h4>

                    @if ($post->content)
                        <p class="entry-excerpt">
                            {{ Str::limit(html_entity_decode(strip_tags($post->content)), 120, '...') }}
                        </p>
                    @endif

                    @if ($post->tags && $post->tags->count() > 0)
                        <div class="entry-tags">
                            @foreach ($post->tags->take(3) as $tag)
                                <span class="tag-item">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="post-bottom">
                        <div class="post-btn">
                            <a href="{{ route('frontend.post', $post->slug) }}" class="btn-read-more"
                                aria-label="Continue lendo {{ $post->title }}">
                                Continue lendo
                                <i class="las la-long-arrow-alt-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>


        @empty
            <div>Nenhuma postagem encontrada!</div>
        @endforelse


        <div class="pagination">
            <div class="pagination-area">
                {{ $posts->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>
