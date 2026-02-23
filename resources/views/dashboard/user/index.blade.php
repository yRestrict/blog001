@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title Here')
@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>{{ $pageTitle ?? 'Usuários' }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Novo Usuário
            </a>
        </div>
    </div>
</div>
<livewire:admin.user-table />





@endsection