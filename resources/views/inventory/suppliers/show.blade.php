@extends('adminlte::page')

@section('title', 'Proveedores')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Proveedores</a></li>
            <li class="breadcrumb-item active">{{ $supplierId }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de proveedor</h1>
    <livewire:Inventory.Suppliers.SupplierShow :supplierId="$supplierId" />

    <hr class="mt-1">

    <div class="d-block mb-3">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-documents-tab" data-toggle="pill" href="#pills-documents"
                    role="tab" aria-controls="pills-documents" aria-selected="true">
                    <i class="fas fs-fw fa-file-alt mr-1"></i>
                    <span class="d-none d-sm-inline">Documentos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-batches-tab" data-toggle="pill" href="#pills-batches" role="tab"
                    aria-controls="pills-batches" aria-selected="false">
                    <i class="fas fs-fw fa-box mr-1"></i>
                    <span class="d-none d-sm-inline">Lotes</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-documents" role="tabpanel" aria-labelledby="pills-documents-tab">
            <livewire:Inventory.Suppliers.DocumentsTable :supplierId="$supplierId" />
        </div>

        <div class="tab-pane fade" id="pills-batches" role="tabpanel" aria-labelledby="pills-batches-tab">
            <livewire:Inventory.Suppliers.BatchesTable :supplierId="$supplierId" lazy />
        </div>
    </div>

    <livewire:Inventory.RawMaterialBatches.ModalBatchShow />
@endsection
