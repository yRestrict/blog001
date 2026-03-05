@if(!empty($data))
    <div class="widget-categories">
        @foreach($data as $category)
            <a class="category-item" href="{{ route('frontend.category', ['slug' => $category['slug']]) }}">
                <p>{{ $category['name'] }} ({{ $category['posts_count'] }})</p>
            </a>
        @endforeach
    </div>
@else
    <div>Nenhuma categoria encontrada!</div>
@endif