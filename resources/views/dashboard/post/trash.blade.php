@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Lixeira - Posts')
@section('content')

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Lixeira — Posts</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lixeira</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> Voltar para Posts
            </a>
        </div>
    </div>
</div>

<livewire:admin.posts-trash />

@endsection