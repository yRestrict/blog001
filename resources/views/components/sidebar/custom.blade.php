{{-- resources/views/components/sidebar/custom.blade.php --}}
@if(!empty($data['content']))
    <div class="sidebar-custom">

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

            // Lê o tema do localStorage antes de qualquer coisa — igual ao site
            var currentTheme = localStorage.getItem('theme') || 'light';

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

                // Envia o tema correto logo após o iframe carregar
                iframe.contentWindow.postMessage({ theme: currentTheme }, '*');
            });

            iframe.contentDocument.open();
            iframe.contentDocument.write(content);
            iframe.contentDocument.close();

            setTimeout(function () {
                iframe.style.height =
                    iframe.contentDocument.documentElement.scrollHeight + 'px';
            }, 100);

            // Atualiza o tema do iframe quando o usuário trocar
            var toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
            if (toggleSwitch) {
                toggleSwitch.addEventListener('change', function (e) {
                    var newTheme = e.target.checked ? 'dark' : 'light';
                    iframe.contentWindow.postMessage({ theme: newTheme }, '*');
                });
            }
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