@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Widgets')

@section('content')

    {{-- Cabeçalho da página --}}
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>{{ $sidebar->name }}</h4>
                    <small class="text-muted">
                        <code>{{ $sidebar->slug }}</code>
                    </small>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb d-flex justify-content-end mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.sidebars') }}">Sidebars</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $sidebar->name }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{--
        Passa $sidebar para o componente Livewire.
        O mount() do WidgetManager recebe e armazena como propriedade.
    --}}
    <div class="pd-20 card-box mb-4">
        @livewire('admin.sidebar.widget-manager', ['sidebar' => $sidebar])
    </div>

@endsection