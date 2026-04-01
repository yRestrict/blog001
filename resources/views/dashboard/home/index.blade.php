@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
@section('content')

<ul class="mir-breadcrumb">
    <li class="mir-breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house" style="font-size:.65rem"></i></a></li>
    <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
    <li class="mir-breadcrumb-item active">Dashboard</li>
</ul>

<livewire:admin.dashboard />

@endsection
