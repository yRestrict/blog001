{{-- resources/views/components/sidebar/custom.blade.php --}}
@if(!empty($data['content']))
    <div class="sidebar-custom">
        {!! $data['content'] !!}

        @if(!empty($data['link']))
            <a href="{{ $data['link'] }}" class="sidebar-custom__link" target="_blank" rel="noopener">
                Saiba mais
            </a>
        @endif
    </div>
@endif