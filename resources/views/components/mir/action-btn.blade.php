{{--
    Botão de ação 30x30 com ícone
    @props: type (edit|delete|restore), tooltip, href (se link)
--}}
@props(['type', 'tooltip' => null, 'href' => null])

@php
    $icons = [
        'edit'    => 'fa-pen-to-square',
        'delete'  => 'fa-trash-can',
        'restore' => 'fa-rotate-left',
        'view'    => 'fa-eye',
        'approve' => 'fa-circle-check',
        'reject'  => 'fa-circle-xmark',
    ];
    $iconClass = $icons[$type] ?? 'fa-ellipsis';
@endphp

@if($href)
    <a href="{{ $href }}"
       class="mir-action-btn mir-action-{{ $type }}"
       @if($tooltip) data-tooltip="{{ $tooltip }}" @endif>
        <i class="fa-solid {{ $iconClass }}"></i>
    </a>
@else
    <button {{ $attributes->merge(['class' => 'mir-action-btn mir-action-' . $type]) }}
        @if($tooltip) data-tooltip="{{ $tooltip }}" @endif>
        <i class="fa-solid {{ $iconClass }}"></i>
    </button>
@endif
