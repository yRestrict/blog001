@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Tags')
@section('content')

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Tags</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tags</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<livewire:admin.tags />

@endsection