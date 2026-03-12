@extends('frontend.master')
@section("pageTitle", isset($pageTitle) ? $pageTitle : "Categorias")
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection

@section('content')


<div class="section-heading">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                
                <div class="category-header-card">
                    <h1 class="category-title-main">{{ $category->name }}</h1>

                    @if ($category->description)
                        <p class="category-description-text">
                            {{ $category->description }}
                        </p>
                    @endif

                    <div class="category-stats-flex">
                        <span class="stat-item-modern">
                            <i class="fas fa-file-alt"></i>
                            <strong>{{ $posts->total() }}</strong> &nbsp; {{ $posts->total() == 1 ? 'publicação' : 'publicações' }}
                        </span>

                        @if ($category->created_at)
                            <span class="stat-item-modern">
                                <i class="fas fa-calendar-plus"></i>
                                Criada em {{ $category->created_at->translatedFormat('F \d\e Y') }}
                            </span>
                        @endif
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
@endsection
