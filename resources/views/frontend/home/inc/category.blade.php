@if ($categories->count() > 0)
<div class="categories">
    <div class="container-fluid">
        <div class="categories-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="categories-items">
                        @foreach ($categories as $category)
                            <a class="category-item" href="{{ route('frontend.category', $category->slug) }}">
                                {{-- Category não tem campo image no seu model, usando placeholder --}}
                                <div class="image">
                                    <img src="{{ asset('uploads/category/default.webp') }}"
                                         alt="{{ $category->name }}" />
                                </div>
                                <p>
                                    {{ $category->name }}
                                    <span>{{ $category->posts_count }}</span>
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif