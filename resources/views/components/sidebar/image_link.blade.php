<div class="widget-image-link">
    @if($data['display_mode'] === 'slide' && !empty($data['slide_images']))

        {{-- MODO CARROSSEL (Bootstrap) --}}
        <div id="carousel-{{ $widget->id }}" class="carousel slide"
             data-ride="{{ $data['slide_autoplay'] ? 'carousel' : 'false' }}"
             data-interval="{{ $data['slide_interval'] ?? 5000 }}">

            @if($data['slide_indicators'])
                <ol class="carousel-indicators">
                    @foreach($data['slide_images'] as $index => $slide)
                        <li data-target="#carousel-{{ $widget->id }}"
                            data-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
            @endif

            <div class="carousel-inner">
                @foreach($data['slide_images'] as $index => $slide)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <a href="{{ $slide['link'] }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ $slide['image'] }}"
                                 class="d-block w-100"
                                 alt="Slide {{ $index + 1 }}"
                                 style="max-width:{{ $data['image_width'] }}px; max-height:{{ $data['image_height'] }}px; object-fit:cover;">
                        </a>
                    </div>
                @endforeach
            </div>

            @if($data['slide_controls'])
                <a class="carousel-control-prev" href="#carousel-{{ $widget->id }}" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#carousel-{{ $widget->id }}" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>
            @endif
        </div>

    @elseif($data['display_mode'] === 'single' && $data['image'])

        {{-- MODO IMAGEM ÚNICA --}}
        @if($data['link'])
            <a href="{{ $data['link'] }}" target="_blank" rel="noopener noreferrer">
                <img src="{{ $data['image'] }}"
                     alt="{{ $widget->title ?? 'Banner' }}"
                     class="img-fluid"
                     style="max-width:100%; height:auto;">
            </a>
        @else
            <img src="{{ $data['image'] }}"
                 alt="{{ $widget->title ?? 'Banner' }}"
                 class="img-fluid"
                 style="max-width:100%; height:auto;">
        @endif

    @endif
</div>

<style>
.widget-image-link .carousel-item img { width: 100%; height: auto; margin: 0 auto; }
.widget-image-link .carousel-control-prev-icon,
.widget-image-link .carousel-control-next-icon { background-color: rgba(0,0,0,.5); border-radius: 50%; padding: 10px; }
.widget-image-link .carousel-indicators li { background-color: rgba(0,0,0,.5); }
.widget-image-link .carousel-indicators .active { background-color: #007bff; }
</style>