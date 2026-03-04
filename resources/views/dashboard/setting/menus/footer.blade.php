@extends('dashboard.master')

@section('pageTitle', 'Configurações do Footer')

@section('content')

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-1">Configurações do Footer</h2>
                <p class="text-muted mb-0">
                    Controle como o rodapé do site se comporta.
                </p>
            </div>
        </div>

        <div class="card-box p-4">
            <livewire:admin.footer-settings />
        </div>

    </div>
</div>

@endsection