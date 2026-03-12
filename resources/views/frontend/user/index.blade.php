@extends("frontend.master")

@section("pageTitle", isset($pageTitle) ? $pageTitle : "user")
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection

@section("content")

<section class="author-page-header section-padding-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                
                <div class="author-card-custom">
                    {{-- Foto usando o Acessor do Model User --}}
                    <div class="author-avatar-wrapper">
                        <img src="{{ $user->picture }}" alt="{{ $user->name }}">
                    </div>

                    <div class="author-bio-content ms-md-4">
                        <div class="author-name-row">
                            <h2 class="author-name-title">{{ $user->name }}</h2>

                            @if($user->isActive())
                                <span class="author-verified" title="Conta verificada">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @endif
                        </div>
                        
                        <p class="author-handle">
                            @<span>{{ $user->username }}</span> 
                            <span class="mx-2">|</span> 
                            <span class="author-role-tag">{{ $user->role->name }}</span>
                        </p>

                        <p class="author-description">
                            {{ $user->bio ?? 'Este autor ainda não definiu uma biografia.' }}
                        </p>

                        {{-- Redes Sociais via Relacionamento --}}
                        <div class="author-social-links">
                            @if($user->socialLinks)
                                @php $social = $user->socialLinks; @endphp
                                
                                @if($social->facebook_url)
                                    <a href="{{ $social->facebook_url }}" target="_blank" class="social-btn-round"><i class="fab fa-facebook-f"></i></a>
                                @endif

                                @if($social->instagram_url)
                                    <a href="{{ $social->instagram_url }}" target="_blank" class="social-btn-round"><i class="fab fa-instagram"></i></a>
                                @endif

                                @if($social->whatsapp_url)
                                    <a href="{{ $social->whatsapp_url }}" target="_blank" class="social-btn-round"><i class="fab fa-whatsapp"></i></a>
                                @endif

                                @if($social->steam_url)
                                    <a href="{{ $social->steam_url }}" target="_blank" class="social-btn-round"><i class="fab fa-steam"></i></a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>






<section class="blog-author mt-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 oredoo-content">
                <div class="theiaStickySidebar">
                    
                    <div class="author-post-list">
                        @forelse ($posts as $post)
                        <div class="post-list-modern {{ $loop->first ? 'mt-0' : '' }}">
                            
                            {{-- Imagem do Post --}}
                            <div class="post-list-image">
                                <a href="{{ route('frontend.post', $post->slug) }}">
                                    <img src="{{ asset('uploads/posts/'.$post->thumbnail) }}" alt="{{ $post->title }}"/>
                                </a>
                            </div>

                            {{-- Conteúdo do Post --}}
                            <div class="post-list-content">
                                <ul class="entry-meta mb-2" style="padding: 3px 5px;">
                                    <li class="entry-cat">
                                        <a href="{{ route('frontend.category', $post->category->slug) }}" class="category-style-1">
                                            {{ $post->category->name }}
                                        </a>
                                    </li>
                                    <li class="post-date" > 
                                        <span class="line ms-2 me-2"></span>
                                        <i class="far fa-calendar-alt" style="color: #007bff;"></i> {{ $post->created_at->translatedFormat('d M, Y') }}
                                    </li>
                                </ul>

                                <h5 class="entry-title" style="padding: 3px 5px;">
                                    <a href="{{ route('frontend.post', $post->slug) }}">{{ $post->title }}</a>
                                </h5>

                                {{-- Pequeno resumo para não ficar vazio na lista --}}
                                <p class="text-muted small mt-2" style="padding: 3px 5px;">
                                    {{ Str::limit(strip_tags($post->content), 100) }}
                                </p>

                                @if ($post->tags && $post->tags->count() > 0)
                                    <div class="entry-tags">
                                        @foreach ($post->tags->take(3) as $tag)
                                            <span class="tag-item">#{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="post-bottom mt-3">
                                    <a href="{{ route('frontend.post', $post->slug) }}" class="btn-read-more">
                                        Continue lendo <i class="las la-long-arrow-alt-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 border border-secondary rounded" style="background: #1a1a1a;">
                            <p class="text-muted">Nenhuma publicação encontrada!</p>
                        </div>
                        @endforelse
                    </div>

                    <div class="pagination mt-4">
                        <div class="pagination-area text-left">
                            {{ $posts->links("vendor.pagination.custom") }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 oredoo-sidebar">
                <div class="theiaStickySidebar">
                    @include('components.sidebar.index')
                </div>
            </div>
        </div>
    </div>
</section>


<style>

    /* ═══════════════════════════════════════════════════════════
       AUTOR — Card do perfil
    ═══════════════════════════════════════════════════════════ */

    .author-card-custom {
        border-radius: 15px;
        display: flex;
        align-items: center;
        padding: 2rem;
        margin-bottom: 2rem;
        gap: 30px;
        border-bottom: 1px solid rgb(230, 231, 231);
    }

    .dark .author-card-custom {
        border-bottom: 1px solid rgba(153, 153, 153, 0.15);
    }

    /* Foto */
    .author-avatar-wrapper img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border: 3px solid #007bff;
        border-radius: 50%;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
    }

    /* Nome + verificado */
    .author-name-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
    }

    .author-verified {
        color: #007bff;
        font-size: 18px;
        display: flex;
        align-items: center;
    }

    .author-name-title {
        font-weight: 700;
        margin-bottom: 0;
    }

    /* Handle / role */
    .author-handle {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .author-role-tag {
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    /* Bio */
    .author-description {
        max-width: 700px;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    /* Redes sociais */
    .social-btn-round {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid #444;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .social-btn-round:hover {
        transform: translateY(-3px);
    }

    .dark .social-btn-round:hover {
        color: #E6E7E7;
        background: #fff;
        transform: translateY(-3px);
    }

    .social-btn-round i:hover       { color: #191B1D; }
    .dark .social-btn-round i:hover { color: rgb(230, 231, 231); }

    /* Mobile */
    @media (max-width: 768px) {
        .author-card-custom {
            flex-direction: column;
            text-align: center;
        }
        .author-bio-content {
            margin-left: 0 !important;
            margin-top: 1.5rem;
        }
    }


    /* ═══════════════════════════════════════════════════════════
       AUTOR — Lista de posts
    ═══════════════════════════════════════════════════════════ */

    .author-post-list {
        margin-top: 2rem;
    }

    /* Card do post */
    .post-list-modern {
        border: 1px solid #E6E7E7;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .dark .post-list-modern {
        border: 1px solid rgba(153, 153, 153, 0.15);
    }

    .post-list-modern:hover {
        transform: translateX(10px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .dark .post-list-modern:hover {
        box-shadow: 0 5px 15px rgba(77, 76, 76, 0.3);
    }

    /* Imagem */
    .post-list-modern .post-list-image {
        width: 280px;
        min-width: 280px;
        height: 180px;
        overflow: hidden;
        border-radius: 10px;
        flex-shrink: 0;
    }

    .post-list-modern .post-list-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .post-list-modern:hover .post-list-image img {
        transform: scale(1.1);
    }

    /* Conteúdo */
    .post-list-modern .post-list-content {
        padding-left: 25px;
    }

    /* Título — light */
    .post-list-modern .entry-title a {
        font-size: 1.4rem;
        font-weight: 700;
        text-decoration: none;
        color: #191B1D;
        transition: color 0.3s;
    }

    /* Título — dark */
    .dark .post-list-modern .entry-title a {
        color: #e6e7e7;
    }

    .dark .post-list-modern .entry-title a:hover {
        color: #ffffff;
    }

    /* Remove o ::after que causava a linha azul */
    .post-list-modern .entry-title a::after {
        display: none !important;
        background: none !important;
        content: none !important;
    }

    /* Data */
    .post-list-modern .post-date {
        color: #888;
        font-size: 0.85rem;
    }

    /* Categoria */
    .post-list-modern .category-style-1 {
        border-radius: 20px;
        font-size: 0.95rem;
        font-weight: 600;
    }

    /* Leia mais — remove linha azul herdada */
    .post-list-modern .btn-read-more {
        text-decoration: none;
        border-bottom: none;
        box-shadow: none;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .post-list-modern {
            flex-direction: column;
            align-items: flex-start;
        }

        .post-list-modern .post-list-image {
            width: 100%;
            min-width: 100%;
            margin-bottom: 20px;
        }

        .post-list-modern .post-list-content {
            padding-left: 0;
        }
    }

</style>
@endsection
