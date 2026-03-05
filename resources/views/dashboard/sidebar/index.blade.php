@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Gerenciar Sidebars')

@section('content')

    {{-- Cabeçalho da página (igual ao seu padrão) --}}
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Gerenciar Sidebars</h4>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb d-flex justify-content-end mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Sidebars
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @livewire('admin.sidebar')


@endsection