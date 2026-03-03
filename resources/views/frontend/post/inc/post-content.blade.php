@extends('frontend.master')

@section('title', $post->title . ' - ' . config('app.name'))
@section('description', Str::limit(strip_tags($post->content), 160))

@section('content')

<section class="post-single-layout-2">
    <div class="container-fluid">
        <div class="row">
            <!--content-->
            <div class="col-lg-12 oredoo-content">
                <div class="theiaStickySidebar" style="padding-top: 80px;">
                    <!--post-single-title-->
                    <div class="post-single-title">
                        <h1 class="text-center">{{ $post->title }}</h1>
                        <ul class="entry-meta">
                            <li class="post-author"> <a href="{{ route("frontend.user", $post->user->username) }}">{{ $post->user->name }}</a></li>
                            <li class="entry-cat"> <a href="{{ route("frontend.category", $post->category->slug) }}" class="category-style-1 "><span class="line"></span>{{ $post->category->title }}</a></li>
                            <li class="post-date"> <span class="line"></span>{{ $post->created_at->diffForHumans()}}</li>
                        </ul>
                    </div>
                    
                    
                    <div class="post-single-content">
                        {!! $post->content !!}
                        
                        @if ($post->hasDownload())
                            <div class="mt-4">
                                <form action="{{ route('frontend.post.increment-downloads', $post->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                </form>
                                <small class="d-block text-muted mt-1" data-post-id="{{ $post->id }}">
                                    Total de {{ $post->formatted_downloads }} downloads
                                </small>
                            </div>
                        @endif
                    </div>
                    
                    <!--post-single-bottom-->
                    <div class="post-single-bottom">
                        @if ($post->tags_count > 0)
                        <div class="tags">
                            <p>Tags:</p>
                            <ul class="list-inline">
                                @foreach ($post->tags as $tag)
                                <li>
                                    <a href="{{ route("frontend.tag", $str::slug($tag->name)) }}">{{ $tag->name }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="social-media">
                            <p>Share on :</p>
                            <ul class="list-inline">
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"><i class="fab fa-facebook"></i></a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/?url={{ urlencode(request()->url()) }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="https://www.youtube.com/share?url={{ urlencode(request()->url()) }}" target="_blank"><i class="fab fa-youtube"></i></a>
                                </li>
                                <li>
                                    <a href="https://api.whatsapp.com/send?text={{ urlencode(request()->url()) }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Anúncio após o conteúdo -->
                    
                    @include("frontend.post.inc.post-relacion")
                    
                    @include("frontend.post.inc.comment")
                </div>
            </div>
            <!--sidebar-->
            {{-- @include("frontend.post.inc.sidebar2") --}}
        </div>
    </div>
</section>

@endsection


@push('script')
 <script>
function incrementDownloadCount(postId) {
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.error('❌ CSRF token não encontrado');
        return;
    }
    
    // Use a rota nomeada corretamente
    const url = `{{ route("frontend.post.increment-downloads", "") }}/`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        
        if (data.success) {
            // Atualiza o contador na página
            const counterElement = document.querySelector(`small[data-post-id="${postId}"]`);
            if (counterElement) {
                counterElement.textContent = `Total de ${data.clicks} downloads`;
            }
        } else {
            console.error('❌ Erro do servidor:', data.message);
        }
    })
    .catch(error => {
        console.error('💥 Erro na requisição:', error);
    });
}
</script> 
@endpush