<li class="
    {{ $depth === 0 
        ? ($item->activeChildren->isNotEmpty() ? 'nav-item dropdown' : 'nav-item')
        : ($item->activeChildren->isNotEmpty() ? 'dropdown-submenu ' : '')
    }}
">

    <a class="
        {{ $depth === 0 ? 'nav-link' : 'dropdown-item' }}
        {{ $item->activeChildren->isNotEmpty() && $depth === 0 ? 'dropdown-toggle' : '' }}
    "
       href="{{ $item->url ?: '#' }}"
       target="{{ $item->target }}"
       @if ($item->activeChildren->isNotEmpty())
           @if ($depth === 0)
               data-toggle="dropdown"
           @endif
           aria-haspopup="true"
           aria-expanded="false"
       @endif>
        {{ $item->title }}
    </a>

    @if ($item->activeChildren->isNotEmpty())
        <ul class="dropdown-menu">
            @foreach ($item->activeChildren as $child)
                @include('components.menu._nav-item', [
                    'item' => $child,
                    'depth' => $depth + 1
                ])
            @endforeach
        </ul>
    @endif
</li>