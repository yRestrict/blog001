@if($relatedPosts->count() > 0)
<div class="post-single-next-previous">
    <div class="section-header">
        <h3 class="section-title">Posts Relacionados</h3>
        <div class="section-divider"></div>
    </div>

    <div class="row">
        @foreach($relatedPosts as $related)
        <div class="col-md-4">
            <div class="related-post-card">
                <a href="{{ route('frontend.post', $related->slug) }}" class="related-post-image-link">
                    <div class="related-post-image">
                        <img src="{{ asset('uploads/posts/'.$related->thumbnail) }}" alt="{{ $related->title }}" class="img-fluid" />
                    </div>
                </a>
                
                <div class="related-post-text-content">
                    <h6 class="related-post-title">
                        <a href="{{ route('frontend.post', $related->slug) }}">
                            {{ Str::limit($related->title, 60) }} {{-- ← título --}}
                        </a>
                    </h6>
                    
                    @if($related->content)
                    <p class="related-post-excerpt">
                        {{ Str::limit(html_entity_decode(strip_tags($related->content)), 15) }}
                    </p>

                    
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif