{{-- resources/views/components/sidebar/popular_downloads.blade.php --}}
@if(!empty($data))
    <ul class="sidebar-posts sidebar-posts--downloads">
        @foreach($data as $post)
            <li class="sidebar-posts__item">
                <a href="{{ route('frontend.post', ['slug' => $post['slug']]) }}" class="sidebar-posts__link">
                    <div class="sidebar-posts__info">
                        <span class="sidebar-posts__title"
                              title="{{ $post['title'] }}"
                              style="display:-webkit-box;-webkit-line-clamp:1;
                                     -webkit-box-orient:vertical;overflow:hidden;">
                            {{ $post['title'] }}
                        </span>
                        <span class="sidebar-posts__meta">
                            <i class="fas fa-download"></i> {{ number_format($post['downloads']) }}
                        </span>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
@endif