{{--
    Status Pill — indicador de status com bolinha colorida
    @props: status (published|draft|private|pending|approved|rejected|active|inactive), label, clickable (bool), tooltip
--}}
@props(['status', 'label' => null, 'clickable' => false, 'tooltip' => null])

@php
    $resolvedLabel = $label ?? match($status) {
        'published' => 'Publicado',
        'draft' => 'Rascunho',
        'private' => 'Privado',
        'pending' => 'Pendente',
        'approved' => 'Aprovado',
        'rejected' => 'Rejeitado',
        'active' => 'Ativo',
        'inactive' => 'Inativo',
        default => ucfirst($status),
    };
@endphp

<div style="width:90px;flex-shrink:0;display:flex;justify-content:center;">
    @if($clickable)
        <button {{ $attributes->merge(['class' => 'mir-status is-' . $status]) }}
            @if($tooltip) data-tooltip="{{ $tooltip }}" @endif>
            <span class="mir-status-ring"></span>
            {{ $resolvedLabel }}
        </button>
    @else
        <span class="mir-status is-{{ $status }}"
            @if($tooltip) data-tooltip="{{ $tooltip }}" @endif
            style="cursor:default;">
            <span class="mir-status-ring"></span>
            {{ $resolvedLabel }}
        </span>
    @endif
</div>
