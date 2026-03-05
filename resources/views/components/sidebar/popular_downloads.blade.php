{{-- resources/views/components/sidebar/popular_downloads.blade.php --}}
@if(!empty($data))
    <ul class="sidebar-posts sidebar-posts--downloads">
        @foreach($data as $post)
            <li class="sidebar-posts__item">
                <a href="{{ route('post.show', $post['slug']) }}" class="sidebar-posts__link">
                    @if($post['thumbnail'])
                        <img src="{{ asset('storage/' . $post['thumbnail']) }}"
                             alt="{{ $post['title'] }}"
                             width="60" height="60"
                             class="sidebar-posts__thumb"
                             loading="lazy">
                    @endif
                    <div class="sidebar-posts__info">
                        <span class="sidebar-posts__title">{{ $post['title'] }}</span>
                        <span class="sidebar-posts__meta">
                            <i class="fas fa-download"></i> {{ number_format($post['downloads']) }}
                        </span>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
@endif