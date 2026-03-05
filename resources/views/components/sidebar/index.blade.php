<div class="sidebar">
    @forelse($sidebarWidgets ?? collect() as $widget)
        @if($widget->view_path && view()->exists($widget->view_path))
            <div class="widget">

                @if($widget->title)
                    <h5 class="widget-title">
                        {{ $widget->title }}
                    </h5>
                @endif

                @include($widget->view_path, [
                    'widget' => $widget,
                    'data'   => $widget->resolved_data,
                ])

            </div>
        @endif
    @empty
        {{-- Sem widgets ativos --}}
    @endforelse
</div>