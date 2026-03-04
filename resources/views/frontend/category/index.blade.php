@extends('frontend.master')

{{-- CORRECAO: era $category->title, agora e $category->name --}}
@section('title', $category->name . ' - ' . (Setting::title()))

@section('content')
    <div class="section-heading">
        <div class="container-fluid">
            <div class="section-heading-2">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-heading-2-title">
                            {{-- CORRECAO: era $category->title, agora e $category->name --}}
                            <h1>{{ $category->name }}</h1>
                            @if ($category->description)
                                <div class="category-description">
                                    <p>{{ $category->description }}</p>
                                </div>
                            @endif
                            <div class="category-stats">
                                <span class="posts-count">
                                    <i class="fas fa-file-alt" style="color: #2563eb"></i>
                                    {{ $posts->total() }} {{ $posts->total() == 1 ? 'post' : 'posts' }}
                                </span>
                                @if ($category->created_at)
                                    <span class="category-date">
                                        <i class="fas fa-calendar-plus" style="color: #2563eb"></i>
                                        Criada em {{ $category->created_at->translatedFormat('F Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="blog-layout-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @forelse ($posts as $post)
                        <div class="post-list post-list-style3">
                            <div class="post-list-image">
                                <a href="{{ route('frontend.post', $post->slug) }}">
                                    {{-- CORRECAO: era $post->thumbnail, agora e $post->thumbnail --}}
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
                                           class="category-style-1">
                                            {{-- CORRECAO: era ->title, agora e ->name --}}
                                            {{ $post->category->name }}
                                        </a>
                                    </li>
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
                                    {{-- CORRECAO: era $str::words(), agora usa Str helper --}}
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
                </div>
            </div>
        </div>
    </section>

    <div class="pagination">
        <div class="container-fluid">
            <div class="pagination-area">
                <div class="row">
                    <div class="col-lg-12">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
