@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Editar Transferencia de Materia Prima |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Transferencias</li>
            <li class="breadcrumb-item">
                <a href="{{ route('raw-material-documents.transfers.show', $documentId) }}">#{{ $documentId }}</a>
            </li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar transferencia de materia prima</h1>
    <livewire:Inventory.RawMaterialDocuments.Transfers.TransferEdit :documentId="$documentId" />

    <livewire:Inventory.RawMaterialStocks.ModalStockSelector />
@endsection
