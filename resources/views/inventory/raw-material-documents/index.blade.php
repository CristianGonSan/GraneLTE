@extends('adminlte::page')

@section('title', 'Documentos MP')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Documentos de materia prima</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="mb-3">
        <a href="{{ route('raw-material-documents.receipts.create') }}" class="btn btn-outline-primary mr-1">
            <i class="fas fa-fw fa-arrow-right-to-bracket mr-1"></i>Crear entrada
        </a>
        <a href="{{ route('raw-material-documents.issues.create') }}" class="btn btn-outline-primary mr-1">
            <i class="fas fa-fw fa-arrow-right-from-bracket mr-1"></i>Crear salida
        </a>
        <a href="{{ route('raw-materials.create') }}" class="btn btn-outline-primary mr-1">
            <i class="fas fa-fw fa-right-left mr-1"></i>Crear transferencia
        </a>
        <a href="{{ route('raw-materials.create') }}" class="btn btn-outline-primary mr-1">
            <i class="fas fa-fw fa-sliders mr-1"></i>Crear ajuste
        </a>
    </div>

    <livewire:Inventory.RawMaterialDocuments.DocumentsTable />
@endsection
