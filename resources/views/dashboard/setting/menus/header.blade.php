@extends('dashboard.master')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title Here')

@section('content')

<ul class="mir-breadcrumb">
    <li class="mir-breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house" style="font-size:.65rem"></i></a></li>
    <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
    <li class="mir-breadcrumb-item"><a href="{{ route('admin.settings') }}">Configurações</a></li>
    <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
    <li class="mir-breadcrumb-item active">Menu</li>
</ul>

<livewire:admin.menus type="header" />

@endsection