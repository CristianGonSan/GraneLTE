@extends('adminlte::page')

@section('title_prefix', 'Documentos de Materia Prima |')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Documentos de materia prima</li>
            </ol>
        </nav>
        @can('raw-material-documents.create')
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                    <i class="fas fa-fw fa-plus mr-1"></i>Nuevo documento
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('raw-material-documents.receipts.create') }}">
                        <i class="fas fa-fw fa-arrow-right-to-bracket mr-2"></i>Entrada
                    </a>
                    <a class="dropdown-item" href="{{ route('raw-material-documents.issues.create') }}">
                        <i class="fas fa-fw fa-arrow-right-from-bracket mr-2"></i>Salida
                    </a>
                    <a class="dropdown-item" href="{{ route('raw-material-documents.transfers.create') }}">
                        <i class="fas fa-fw fa-right-left mr-2"></i>Transferencia
                    </a>
                    <a class="dropdown-item" href="{{ route('raw-material-documents.adjustments.create') }}">
                        <i class="fas fa-fw fa-sliders mr-2"></i>Ajuste
                    </a>
                </div>
            </div>
        @endcan
    </div>
@endsection

@section('content')
    <livewire:Inventory.RawMaterialDocuments.DocumentsTable />
@endsection
