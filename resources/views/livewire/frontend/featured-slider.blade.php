<div>
    @if (featuredPosts->count() > 0)
    <section class="blog blog-home4 d-flex align-items-center justify-content-center">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-carousel">
                        @foreach (featuredPosts as $post)
                        <div class="blog-item"
                             style="background-image: url('{{ asset('uploads/post/' . $post->featured_image) }}')">
                            {{-- CORRECAO: era $post->thumbnail, agora e $post->featured_image --}}

                            <div class="blog-banner">
                                <div class="post-overly">
                                    <div class="post-overly-content">

                                        <div class="entry-cat">
                                            <a href="{{ route('frontend.category', $post->category->slug) }}"
                                               class="category-style-2">
                                                {{ $post->category->name }}
                                                {{-- CORRECAO: era ->title, agora e ->name --}}
                                            </a>
                                        </div>

                                        <h2 class="entry-title">
                                            <a href="{{ route('frontend.post', $post->slug) }}">
                                                {{ $post->title }}
                                            </a>
                                        </h2>

                                        <ul class="entry-meta">
                                            <li class="post-author">
                                                <a href="{{ route('frontend.user', $post->author->username) }}">
                                                    {{ $post->author->name }}
                                                    {{-- CORRECAO: era $post->user, agora e $post->author --}}
                                                </a>
                                            </li>
                                            <li class="post-date">
                                                <span class="line"></span>
                                                {{ $post->created_at->translatedFormat('d M, Y') }}
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
</div>
