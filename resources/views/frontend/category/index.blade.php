@extends('frontend.master')

@section('title', $category->title . ' - ' . config('app.sitesettings')::first()->site_title)

@section('content')
    <div class="section-heading">
        <div class="container-fluid">
            <div class="section-heading-2">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-heading-2-title">
                            <h1>{{ $category->title }}</h1>
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
    {{-- <--  --> --}}
    <section class="blog-layout-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @forelse ($posts as $post)
                        <div class="post-list post-list-style3">
                            <div class="post-list-image">
                                <a href="{{ route('frontend.post', $post->slug) }}">
                                    <img class="post-thumbnail" src="{{ asset('uploads/post/' . $post->thumbnail) }}" alt="{{ $post->title }}"/>
                                </a>
                            </div>
                            <div class="post-list-content">
                                <h3 class="entry-title">
                                    <a href="{{ route('frontend.post', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                <ul class="entry-meta">
                                    <li class="entry-cat">
                                        <a href="{{ route('frontend.category', $post->category->slug) }} "class="category-style-1">
                                            {{ $post->category->title }}
                                        </a>
                                    </li>
                                    <li class="post-date">
                                        <span class="line"></span>
                                        {{ $post->created_at->translatedFormat('d M, Y') }}
                                    </li>
                                </ul>
                                <div class="post-exerpt">
                                    <p>{{ $str::words(strip_tags($post->content), 20) }}</p>
                                </div>
                                <div class="post-btn">
                                    <a href="{{ route('frontend.post', $post->slug) }}" class="btn-read-more">Continue
                                        lendo <i class="las la-long-arrow-alt-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div>Nenhuma postagem encontrada!</div>
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
