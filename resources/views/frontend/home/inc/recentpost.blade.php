@extends("frontend.master")

@section("title", ($settings->site_title ?? 'Blog') . " - " . ($settings->site_description ?? 'Slogan'))

@section("content")

    {{-- CORRECAO: era <x-frontend.featured-slider /> (Blade component)
         mas voce tem um LIVEWIRE component, entao usa @livewire --}}
    @livewire('frontend.featured-slider')

    {{-- Secao de posts recentes --}}
    <section class="section-feature-1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="section-title">
                        <h3>Posts Recentes</h3>
                    </div>

                    @forelse ($posts as $post)
                    <div class="post-list post-list-style3">
                        <div class="post-list-image">
                            <a href="{{ route('frontend.post', $post->slug) }}">
                                {{-- CORRECAO: era $post->thumbnail, agora e $post->featured_image --}}
                                <img class="post-thumbnail"
                                     src="{{ asset('uploads/post/' . $post->featured_image) }}"
                                     alt="{{ $post->title }}"/>
                            </a>
                        </div>
                        <div class="post-list-content">
                            <div class="entry-cat">
                                <a href="{{ route('frontend.category', $post->category->slug) }}"
                                   class="category-style-1">
                                    {{-- CORRECAO: era ->title, agora e ->name --}}
                                    {{ $post->category->name }}
                                </a>
                            </div>
                            <h3 class="entry-title">
                                <a href="{{ route('frontend.post', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            <ul class="entry-meta">
                                <li class="post-author">
                                    {{-- CORRECAO: era $post->user, agora e $post->author --}}
                                    <a href="{{ route('frontend.user', $post->author->username) }}">
                                        {{ $post->author->name }}
                                    </a>
                                </li>
                                <li class="post-date">
                                    <span class="line"></span>
                                    {{ $post->created_at->translatedFormat('d M, Y') }}
                                </li>
                            </ul>
                            <div class="post-exerpt">
                                {{-- CORRECAO: era $str::words(), agora e Illuminate\Support\Str::words() --}}
                                <p>{{ Illuminate\Support\Str::words(strip_tags($post->content), 20) }}</p>
                            </div>
                            <div class="post-btn">
                                <a href="{{ route('frontend.post', $post->slug) }}" class="btn-read-more">
                                    Continue lendo <i class="las la-long-arrow-alt-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-info">Nenhuma postagem encontrada!</div>
                    @endforelse

                    {{-- Paginacao --}}
                    <div class="pagination-area">
                        {{ $posts->links() }}
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4 col-md-12">
                    <aside class="sidebar">
                        {{-- Categorias --}}
                        @if ($categories->count() > 0)
                        <div class="sidebar-widget">
                            <div class="widget-title">
                                <h3>Categorias</h3>
                            </div>
                            <div class="widget-categories">
                                <ul>
                                    @foreach ($categories as $category)
                                    <li>
                                        <a href="{{ route('frontend.category', $category->slug) }}">
                                            {{-- CORRECAO: era ->title, agora e ->name --}}
                                            {{ $category->name }}
                                            <span>({{ $category->posts_count }})</span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif

                        {{-- Posts em Destaque --}}
                        @if ($featuredPosts->count() > 0)
                        <div class="sidebar-widget">
                            <div class="widget-title">
                                <h3>Em Destaque</h3>
                            </div>
                            @foreach ($featuredPosts as $featured)
                            <div class="post-list-small">
                                <div class="post-list-image">
                                    <a href="{{ route('frontend.post', $featured->slug) }}">
                                        <img src="{{ asset('uploads/post/' . $featured->featured_image) }}"
                                             alt="{{ $featured->title }}"/>
                                    </a>
                                </div>
                                <div class="post-list-content">
                                    <h4 class="entry-title">
                                        <a href="{{ route('frontend.post', $featured->slug) }}">
                                            {{ $featured->title }}
                                        </a>
                                    </h4>
                                    <ul class="entry-meta">
                                        <li class="post-date">
                                            {{ $featured->created_at->translatedFormat('d M, Y') }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </aside>
                </div>
            </div>
        </div>
    </section>

@endsection
