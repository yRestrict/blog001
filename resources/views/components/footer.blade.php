<footer class="footer-wrap pd-20 mb-20 card-box">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <ul class="list-inline mb-2">
                    @foreach($menu as $item)
                        <li class="list-inline-item mx-3">
                            <a href="{{ $item->url ?: '#' }}" target="{{ $item->target }}" class="text-dark">{{ $item->title }}</a>
                        </li>
                    @endforeach
                </ul>
                <p class="mb-0">DeskApp &copy; {{ date('Y') }} - Todos os direitos reservados.</p>
            </div>
        </div>
    </div>
</footer>