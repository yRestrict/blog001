@extends('frontend.master')

@section("title", ($settings->site_title ?? 'Blog') . " - Busca: " . $query)

@section('content')

<div class="section-heading">
    <div class="container-fluid">
        <div class="section-heading-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading-2-title text-left">
                        <h2>Resultados para "{{ $query }}"</h2>
                        <p class="text-muted mt-1">{{ $posts->total() }} resultado(s) encontrado(s)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section-feature-1">
    <div class="container-fluid">
        <div class="row">

            {{-- ── Resultados ────────────────────────────────────────── --}}
            <div class="col-lg-8 oredoo-content">
                <div class="theiaStickySidebar">
                    @forelse ($posts as $post)
                        <div class="post-list post-list-style0">
                            <div class="post-list-image">
                                <a href="{{ route('frontend.post', $post->slug) }}">
                                    <img src="{{ asset('uploads/posts/' . $post->thumbnail) }}"
                                         alt="{{ $post->title }}"
                                         loading="lazy">
                                </a>
                            </div>

                            <div class="post-list-content">
                                <ul class="entry-meta">
                                    @if($post->category)
                                        <li class="entry-cat">
                                            <a href="{{ route('frontend.category', $post->category->slug) }}"
                                               class="category-style-1">
                                                {{ $post->category->name }}
                                            </a>
                                        </li>
                                    @endif
                                    <li class="post-date">
                                        <span class="line"></span>
                                        <i class="far fa-calendar-alt"></i>
                                        {{ $post->created_at->translatedFormat('d M, Y') }}
                                    </li>
                                </ul>

                                <h4 class="entry-title">
                                    <a href="{{ route('frontend.post', $post->slug) }}" title="{{ $post->title }}">
                                        {{ $post->title }}
                                    </a>
                                </h4>

                                @if($post->content)
                                    <p class="entry-excerpt">
                                        {{ Str::limit(html_entity_decode(strip_tags($post->content)), 120, '...') }}
                                    </p>
                                @endif

                                @if($post->tags && $post->tags->count() > 0)
                                    <div class="entry-tags">
                                        @foreach($post->tags->take(3) as $tag)
                                            <a href="{{ route('frontend.tag', ['id' => $tag->slug]) }}"
                                               class="tag-item">
                                                #{{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="post-btn mt-2">
                                    <a href="{{ route('frontend.post', $post->slug) }}" class="btn-read-more">
                                        Continue lendo <i class="las la-long-arrow-alt-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning">
                            Nenhum resultado encontrado para "<strong>{{ $query }}</strong>".
                        </div>
                    @endforelse

                    @if($posts->hasPages())
                        <div class="pagination">
                            <div class="pagination-area">
                                {{ $posts->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Sidebar ───────────────────────────────────────────── --}}
            <div class="col-lg-4 oredoo-sidebar">
                <div class="theiaStickySidebar">
                    @include('components.sidebar.index')
                </div>
            </div>

        </div>
    </div>
</section>

@endsection