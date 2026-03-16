@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Editar Transferencia')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Transferencias</li>
            <li class="breadcrumb-item active">{{ $documentId }}</li>
            <li class="breadcrumb-item active">Edición</li>
        </ol>
    </nav>
@endsection

@section('content')Edición de transferencia de Materia Prima</h1>
    <livewire:Inventory.RawMaterialDocuments.Transfers.TransferEdit :documentId="$documentId" />

    <livewire:Inventory.RawMaterialStocks.ModalStockSelector :closeAfterSeleted="true" />
@endsection
