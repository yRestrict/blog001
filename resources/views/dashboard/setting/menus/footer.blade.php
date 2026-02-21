@extends('dashboard.master')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title Here')

@section('content')

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-1">Menu do Footer</h2>
                <p class="text-muted mb-0">
                    Gerencie os links exibidos no rodapé do site.
                    Arraste para reordenar ou criar submenus.
                </p>
            </div>
        </div>

        <div class="card-box p-3">
            <livewire:admin.menus type="footer" />
        </div>

    </div>
</div>

@endsection