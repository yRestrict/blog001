<div>
    @if($featuredPosts->count() > 0)
        <div class="main-slider"> {{-- Certifique-se que o JS do seu tema use esta classe --}}
            @foreach($featuredPosts as $post)
                <div class="slider-item">
                    <div class="post-featured-image">
                        <a href="{{ route('frontend.post', $post->slug) }}">
                            <img src="{{ asset('uploads/posts/' . $post->featured_image) }}" 
                                 alt="{{ $post->title }}" 
                                 style="width: 100%; height: 450px; object-fit: cover;">
                        </a>
                    </div>
                    
                    <div class="slider-content">
                        @if($post->category)
                            <span class="cat-label">
                                <a href="{{ route('frontend.category', $post->category->slug) }}">
                                    {{ $post->category->name }}
                                </a>
                            </span>
                        @endif
                        
                        <h2>
                            <a href="{{ route('frontend.post', $post->slug) }}">{{ $post->title }}</a>
                        </h2>
                        
                        <div class="post-meta">
                            <span><i class="far fa-calendar-alt"></i> {{ $post->created_at->translatedFormat('d M, Y') }}</span>
                            @if($post->author)
                                <span><i class="far fa-user"></i> {{ $post->author->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Script para reinicializar o slider após renderização do Livewire, se necessário --}}
    <script>
        document.addEventListener('livewire:navigated', () => { 
            // Inicialize seu JS de slider aqui (ex: $('.main-slider').slick())
        });
    </script>
</div>