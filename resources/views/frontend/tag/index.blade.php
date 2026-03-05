@extends('frontend.master')
@section("title", ($settings->site_title ?? 'Blog') . " - " . ($settings->site_description ?? 'Slogan'))

@section('content')

<div class="section-heading">
    <div class="container-fluid">
        <div class="section-heading-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading-2-title text-center">
                        <h2>Postagens marcadas com "{{ $tag->name }}"</h2>
                        <div class="section-subtitle mt-2">
                            <span class="badge badge-primary">{{ $posts->total() }} posts encontrados</span>
                            <span class="badge badge-secondary ml-2">{{ $tag->views }} visualizações</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section-feature-1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 oredoo-content">
                <div class="theiaStickySidebar">
                    @forelse ($posts as $post)
                    <div class="post-list post-list-style3">
                        <div class="post-list-image">
                            <a href="{{ route("frontend.post", $post->slug) }}">
                                <img class="post-thumbnail" src="{{ asset("uploads/posts/".$post->thumbnail) }}" alt="{{ $post->title }}">
                            </a>
                        </div>

                        <div class="post-list-content">
                            <h2 class="entry-title">
                                <a href="{{ route('frontend.post', $post->slug) }}" title="{{ $post->title }}">
                                    {{ $post->title }}
                                </a>
                            </h2>

                            <div class="post-date">
                                <i class="far fa-calendar-alt" style="color: #2563eb;"></i>
                                {{ $post->created_at->translatedFormat('d M, Y') }}
                            </div>

                            @if ($post->content)
                                <p class="entry-excerpt">
                                    {{ Str::limit(html_entity_decode(strip_tags($post->content)), 120, '...') }}
                                </p>
                            @endif

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
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i>
                        Nenhum post encontrado com esta tag.
                    </div>
                    @endforelse
                    
                    <div class="pagination">
                        <div class="pagination-area">
                            <div class="row">
                                <div class="col-lg-12">
                                    {{ $posts->links("vendor.pagination.custom") }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection