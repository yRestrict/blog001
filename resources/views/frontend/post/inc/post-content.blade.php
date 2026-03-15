@extends('frontend.master')

@section('title', $post->title . ' - ' . ($settings->site_title ?? config('app.name')))

@section('content')

<section class="post-single-layout-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 oredoo-content">
                <div class="theiaStickySidebar" style="padding-top: 20px;">

                    {{-- ── Título e meta ────────────────────────────────────── --}}
                    <div class="post-single-title">
                        <h1 class="text-center">{{ $post->title }}</h1>
                        <ul class="entry-meta">
                            <li class="post-author">
                                <a href="{{ route('frontend.user', $post->author->username) }}">
                                    {{ $post->author->name }}
                                </a>
                            </li>
                            <li class="entry-cat">
                                <a href="{{ route('frontend.category', $post->category->slug) }}"
                                   class="category-style-1">
                                    <span class="line"></span>{{ $post->category->name }}
                                </a>
                            </li>
                            <li class="post-date">
                                <span class="line"></span>{{ $post->created_at->diffForHumans() }}
                            </li>
                        </ul>
                    </div>

                    {{-- ── Conteúdo ─────────────────────────────────────────── --}}
                    <div class="post-single-content"><pre>
                        {!! $post->content !!}</pre>
                    </div>

                    {{-- ── Botões de download (só aparece se tiver configurado) --}}
                    @if($post->hasDownloads())
                        <div class="post-downloads mt-4">
                            @foreach($post->downloadButtons as $btn)
                                <div class="{{ $btn->alignment_class }} mb-2">
                                    <a href="{{ route('frontend.post.download', $btn->id) }}"
                                       class="btn btn-primary"
                                       target="_blank"
                                       rel="noopener noreferrer">
                                        <i class="fas fa-download"></i> {{ $btn->label }}
                                    </a>
                                </div>
                            @endforeach
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-download"></i>
                                {{ number_format($post->downloads) }} downloads
                            </small>
                        </div>
                    @endif

                    {{-- ── Tags e compartilhar ─────────────────────────────── --}}
                    <div class="post-single-bottom">

                        @if($post->tags_count > 0)
                            <div class="tags">
                                <p>Tags:</p>
                                <ul class="list-inline">
                                    @foreach($post->tags as $tag)
                                        <li>
                                            <a href="{{ route('frontend.tag', ['id' => $tag->slug]) }}">
                                                {{ $tag->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="social-media">
                            <p>Share on:</p>
                            <ul class="list-inline">
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                       target="_blank"><i class="fab fa-facebook"></i></a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/?url={{ urlencode(request()->url()) }}"
                                       target="_blank"><i class="fab fa-instagram"></i></a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}"
                                       target="_blank"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="https://api.whatsapp.com/send?text={{ urlencode(request()->url()) }}"
                                       target="_blank"><i class="fab fa-whatsapp" style="background-color: green;"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @include('frontend.post.inc.post-relacion')

                    <livewire:post-like :post="$post" />
                    <livewire:post-comments :post="$post" />                      

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

@endsection

{{--
    Adicione no final do blade do post, fora do @section('content')
--}}

@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">

    <style>
        /* ── Container do code-block (Quill 2.0) ───────────────────────── */
        .post-single-content .ql-code-block-container {
            background: #282c34;
            border-radius: 6px;
            padding: 16px;
            margin: 16px 0;
            overflow-x: auto;
            font-family: 'Fira Code', 'Consolas', 'Monaco', monospace;
            font-size: 14px;
            line-height: 1.6;
        }

        /* ── Cada linha de código ───────────────────────────────────────── */
        .post-single-content .ql-code-block {
            color: #abb2bf;
            white-space: pre;
            min-height: 1.4em;
        }

        /* ── Esconde o <select> de linguagem no frontend ────────────────── */
        /* remove seletor de linguagem do Quill no frontend */
        .post-single-content .ql-code-block-container > .ql-ui,
        .post-single-content .ql-code-block-container select.ql-ui {
            display: none !important;
        }

        /* ── Cores do highlight (classes ql-token hljs-*) ───────────────── */
        .post-single-content .ql-token.hljs-keyword   { color: #c678dd; }
        .post-single-content .ql-token.hljs-string     { color: #98c379; }
        .post-single-content .ql-token.hljs-variable   { color: #e06c75; }
        .post-single-content .ql-token.hljs-title      { color: #61afef; }
        .post-single-content .ql-token.hljs-function   { color: #61afef; }
        .post-single-content .ql-token.hljs-class      { color: #e5c07b; }
        .post-single-content .ql-token.hljs-meta       { color: #56b6c2; }
        .post-single-content .ql-token.hljs-comment    { color: #5c6370; font-style: italic; }
        .post-single-content .ql-token.hljs-number     { color: #d19a66; }
        .post-single-content .ql-token.hljs-built_in   { color: #56b6c2; }
        .post-single-content .ql-token.hljs-attr       { color: #e06c75; }
        .post-single-content .ql-token.hljs-selector-tag { color: #e06c75; }

        /* ── Imagens responsivas ────────────────────────────────────────── */
        .post-single-content img {
            max-width: 100%;
            height: auto;
        }

        /* ── Iframes (YouTube) responsivos ──────────────────────────────── */
        .post-single-content iframe {
            max-width: 100%;
        }
    </style>

    
@endpush