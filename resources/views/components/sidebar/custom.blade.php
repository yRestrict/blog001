{{-- resources/views/components/sidebar/custom.blade.php --}}
@if(!empty($data['content']))
    <div class="sidebar-custom">

        {{--
            O conteúdo é renderizado dentro de um iframe via srcdoc.
            Isso isola completamente CSS e JS do widget — nada vaza para o site.
            A altura é ajustada automaticamente via ResizeObserver.
        --}}
        <iframe
            id="widget-iframe-{{ $widget->id }}"
            scrolling="no"
            frameborder="0"
            style="width:100%; border:none; display:block; overflow:hidden;">
        </iframe>

        <script>
        (function () {
            var iframe = document.getElementById('widget-iframe-{{ $widget->id }}');
            var content = {!! json_encode($data['content']) !!};

            iframe.addEventListener('load', function () {
                try {
                    var ro = new ResizeObserver(function () {
                        iframe.style.height =
                            iframe.contentDocument.documentElement.scrollHeight + 'px';
                    });
                    ro.observe(iframe.contentDocument.documentElement);

                    iframe.contentDocument.addEventListener('click', function () {
                        setTimeout(function () {
                            iframe.style.height =
                                iframe.contentDocument.documentElement.scrollHeight + 'px';
                        }, 50);
                    });
                } catch (e) {}
            });

            // Escreve o conteúdo no iframe via document.write
            iframe.contentDocument.open();
            iframe.contentDocument.write(content);
            iframe.contentDocument.close();

            // Ajusta altura inicial
            setTimeout(function () {
                iframe.style.height =
                    iframe.contentDocument.documentElement.scrollHeight + 'px';
            }, 100);
        })();
        </script>

        @if(!empty($data['link']))
            <a href="{{ $data['link'] }}"
               class="sidebar-custom__link"
               target="_blank"
               rel="noopener">
                Saiba mais
            </a>
        @endif

    </div>
@endif