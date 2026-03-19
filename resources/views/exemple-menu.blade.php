@extends('dashboard.master') 
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title Here')
@section('content')


<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="page-header-title">
                <h4 class="mb-1">Categorias</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb p-0 mb-0 bg-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Categorias</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="cat-section">
    <div class="cat-section-header">
        <div>
            <div class="cat-section-title">Categorias</div>
            <div class="cat-section-sub">Gerencie todas as categorias do sistema</div>
        </div>
        <button class="mir-btn-primary-lg" wire:click="openAddCategory">
            <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Nova Categoria
        </button>
    </div>
</div>



@endsection

@push('stylesheets')
<style>
    /* ── Seções ─────────────────────────────────────────────────── */
        .cat-section {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .cat-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        .cat-section-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1a1d23;
            margin: 0;
        }
        .cat-section-sub {
            font-size: .78rem;
            color: #9ca3af;
            margin-top: 2px;
        }
</style>
@endpush