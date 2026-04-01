@extends('dashboard.master')

@section('pageTitle', $pageTitle ?? 'Lixeira de Categorias')

@section('content')

<ul class="mir-breadcrumb">
    <li class="mir-breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house" style="font-size:.65rem"></i></a></li>
    <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
    <li class="mir-breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categorias</a></li>
    <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
    <li class="mir-breadcrumb-item active">Lixeira</li>
</ul>

<livewire:admin.categories-trash />

@endsection

@push('scripts')
<script>
    window.addEventListener('showToastr', function(event) {
        toastr[event.detail.type](event.detail.message);
    });
</script>
@endpush
