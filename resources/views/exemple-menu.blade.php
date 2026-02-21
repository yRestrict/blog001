@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title Here')
@section('content')
    <div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Menu </h4>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb d-flex justify-content-end mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Menu
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="bg-white pd-20 card-box mb-30" style="position: relative;">
    
    <div class="resize-triggers">
        <div class="expand-trigger">
            <div style="width: 1538px; height: 441px;"></div>
        </div>
        <div class="contract-trigger"></div>
    </div>
</div>

@endsection