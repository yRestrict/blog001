@extends('dashboard.master')

@section('pageTitle', $pageTitle ?? 'Categorias')

@section('content')

<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="page-header-title">
                <h4 class="mb-1">Categorias</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb p-0 mb-0 bg-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Categorias</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@livewire('admin.categories-trash')

@endsection

@push('scripts')
<script>
    window.addEventListener('showToastr', function(event) {
        toastr[event.detail.type](event.detail.message);
    });
</script>
@endpush