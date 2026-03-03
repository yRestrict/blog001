@if ($categories->count() > 0)
<div class="categories">
    <div class="container-fluid">
        <div class="categories-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="categories-items">
                        @foreach ($categories as $category)
                        <a class="category-item" href="{{ route('frontend.category', $category->slug) }}">
                            {{-- CORRECAO: Seu model Category NAO tem campo 'image',
                                 entao removemos a referencia a $category->image.
                                 Se quiser adicionar imagem, crie uma migration:
                                 $table->string('image')->nullable();
                                 Por enquanto usamos um placeholder --}}
                            <div class="image">
                                <img src="{{ asset('uploads/category/default.webp') }}"
                                     alt="{{ $category->name }}"/>
                            </div>
                            {{-- CORRECAO: era ->title, agora e ->name --}}
                            {{-- CORRECAO: era count($category->posts->where('status', true))
                                 Isso carregava TODOS os posts de cada categoria (N+1 query problem).
                                 O HomeController ja passa withCount('posts'), entao use posts_count --}}
                            <p>{{ $category->name }} <span>{{ $category->posts_count }}</span></p>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
