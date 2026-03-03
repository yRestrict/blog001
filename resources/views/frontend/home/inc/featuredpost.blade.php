<div class="col-lg-8 oredoo-content">
    <div class="theiaStickySidebar">
        <div class="section-title">
            <h3>Posts recentes</h3>
        </div>
        
        @forelse ($posts as $recentpost)
            <div class="post-list post-list-style3">
                <div class="post-list-image">
                    <a href="{{ route('frontend.post', $recentpost->slug) }}">
                        <img class="post-thumbnail" 
                             src="{{ asset('uploads/post/' . $recentpost->featured_image) }}" 
                             alt="{{ $recentpost->title }}" />
                    </a>
                </div>
                <div class="post-list-content">
                    <ul class="entry-meta">
                        <li class="post-date">
                            <i class="far fa-calendar-alt"></i>
                            {{ $recentpost->created_at->translatedFormat('d M, Y') }}
                        </li>
                        @if(isset($recentpost->views))
                        <li class="post-views">
                            <span class="line"></span>
                            {{ number_format($recentpost->views) }} &nbsp;
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </li>
                        @endif
                    </ul>

                    <h4 class="entry-title">
                        <a href="{{ route('frontend.post', $recentpost->slug) }}" title="{{ $recentpost->title }}">
                            {{ $recentpost->title }}
                        </a>
                    </h4>

                    @if ($recentpost->content)
                        <p class="entry-excerpt">
                            {{ Str::limit(strip_tags($recentpost->content), 120, '...') }}
                        </p>
                    @endif

                    {{-- Tags (relacionamento many-to-many que você tem no Model) --}}
                    @if ($recentpost->tags && $recentpost->tags->count() > 0)
                        <div class="entry-tags">
                            @foreach ($recentpost->tags->take(3) as $tag)
                                <span class="tag-item" style="font-size: 12px; color: #888;">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="post-bottom">
                        <div class="post-btn">
                            <a href="{{ route('frontend.post', $recentpost->slug) }}" class="btn-read-more">
                                Continue lendo
                                <i class="las la-long-arrow-alt-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">Nenhuma postagem encontrada!</div>
        @endforelse

        <div class="pagination">
            <div class="pagination-area">
                {{-- Certifique-se que o nome da variável aqui é o mesmo do foreach --}}
                {{ $posts->links() }} 
            </div>
        </div>
    </div>
</div>