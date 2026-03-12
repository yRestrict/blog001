@extends('frontend.master')
@section("pageTitle", isset($pageTitle) ? $pageTitle : "Home")
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection

@section('content')

<div class="section-heading">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="tag-header-card">
                    {{-- Ícone de Hashtag --}}
                    <div class="tag-icon-badge">
                        #{{ Str::slug($tag->name) }}
                    </div>

                    {{-- Título Principal --}}
                    <h1 class="tag-title-main">Explorando "{{ $tag->name }}"</h1>

                    {{-- Métricas da Tag --}}
                    <div class="tag-metrics-wrapper">
                        <span class="tag-stat-badge">
                            <i class="fas fa-layer-group"></i>
                            {{ $posts->total() }} {{ $posts->total() == 1 ? 'publicação' : 'publicações' }}
                        </span>

                        {{-- Se você tiver o campo de views no seu model Tag --}}
                        @if(isset($tag->views))
                            <span class="tag-stat-badge">
                                <i class="fas fa-eye"></i>
                                {{ number_format($tag->views) }} visualizações
                            </span>
                        @endif

                        <span class="tag-stat-badge">
                            <i class="fas fa-chart-line"></i>
                            Tópico em alta
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<section class="blog-layout-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                @forelse ($posts as $post)
                    <div class="post-list post-list-style0">
                        <div class="post-list-image">
                            <a href="{{ route('frontend.post', $post->slug) }}">
                                <img class="post-thumbnail"
                                        src="{{ asset('uploads/posts/' . $post->thumbnail) }}"
                                        alt="{{ $post->title }}"/>
                            </a>
                        </div>
                        
                        <div class="post-list-content">

                            <h3 class="entry-title">
                                <a href="{{ route('frontend.post', $post->slug) }}">{{ $post->title }}</a>
                            </h3>

                            <ul class="entry-meta">
                                <li class="entry-cat">
                                    <a href="{{ route('frontend.category', $post->category->slug) }}"
                                        class="category-style-2">
                                        {{ $post->category->name }}
                                    </a>
                                </li>
                                
                                <li class="post-author">
                                    <span class="line"></span>
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
                                <p>{{ Illuminate\Support\Str::words(strip_tags($post->content), 20) }}</p>
                            </div>

                            @if ($post->tags && $post->tags->count() > 0)
                                <div class="entry-tags">
                                    @foreach ($post->tags->sortBy('name') as $postTag)
                                        <a href="{{ route('frontend.tag', Str::slug($postTag->name)) }}" 
                                        class="tag-item {{ $postTag->id === $tag->id ? 'active-tag' : '' }}">
                                            #{{ $postTag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            <div class="post-bottom">
                                <a href="{{ route('frontend.post', $post->slug) }}" class="btn-read-more">
                                    Continue lendo <i class="las la-long-arrow-alt-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">Nenhuma postagem encontrada!</div>
                @endforelse
            </div>

            <div class="col-lg-4 oredoo-sidebar">
                <div class="theiaStickySidebar">
                    @include('components.sidebar.index')
                </div>
            </div>
        </div>
    </div>
</section>

<div class="pagination">
    <div class="container-fluid">
        <div class="pagination-area">
            <div class="row">
                <div class="col-lg-12">
                    {{ $posts->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>/* Cabeçalho de Tag Customizado */
.tag-header-card {
    padding: 3rem 2rem;
    margin-bottom: 2.5rem;
    text-align: center;
    position: relative;
}

/* Efeito de hashtag estilizada */
.tag-icon-badge {
    display: inline-block;
    background: rgba(37, 99, 235, 0.1);
    font-size: 1.5rem;
    font-weight: 800;
    padding: 10px 20px;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    border: 1px dashed #444;
}

.tag-title-main {
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
}

/* Container de badges de métricas */
.tag-metrics-wrapper {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}

.tag-stat-badge {
    border: 1px solid #444;
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    transition: all 0.3s;
}

.tag-stat-badge:hover {
    color: #202020;
    transform: translateY(-2px);
}

.dark .tag-stat-badge:hover {
    color: #fff;
    transform: translateY(-2px);
}

.tag-stat-badge i {
    margin-right: 8px;
}</style>

@endsection