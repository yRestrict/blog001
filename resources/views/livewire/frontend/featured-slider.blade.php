<div>
    @if ($featuredPosts->count() > 0)
    <section class="blog blog-home4 d-flex align-items-center justify-content-center">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-carousel featured-slider">
                        @foreach ($featuredPosts as $featuredPost)
                        <div class="blog-item" style="background-image: url('{{ asset("uploads/posts/" . $featuredPost->thumbnail) }}')">
                            <div class="blog-banner">
                                <div class="post-overly">
                                    <div class="post-overly-content">
                                        <div class="entry-cat">
                                            <a href="{{ route('frontend.category', $featuredPost->category->slug) }}" class="category-style-2">
                                                {{ $featuredPost->category->name }}
                                            </a>
                                        </div>
                                        <h2 class="entry-title">
                                            <a href="{{ route('frontend.post', $featuredPost->slug) }}">
                                                {{ $featuredPost->title }}
                                            </a>
                                        </h2>
                                        <ul class="entry-meta">
                                            <li class="post-author">
                                                <a href="{{ route('frontend.user', $featuredPost->author->username) }}">
                                                    {{ $featuredPost->author->name }}
                                                </a>
                                            </li>
                                            <li class="post-date">
                                                <span class="line"></span>
                                                {{ $featuredPost->created_at->translatedFormat('d M, Y') }}
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

@push('scripts')
<script>
    $(document).ready(function () {
        $(".featured-slider").owlCarousel({
            loop: true,
            margin: 0,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            responsive: {
                0:    { items: 1 },
                768:  { items: 1 },
                1024: { items: 1 }
            }
        });
    });
</script>
@endpush