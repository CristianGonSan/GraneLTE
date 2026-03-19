@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Editar Ajuste de Existencias |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Ajustes</li>
            <li class="breadcrumb-item">
                <a href="{{ route('raw-material-documents.adjustments.show', $documentId) }}">#{{ $documentId }}</a>
            </li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar ajuste de existencias</h1>
    <livewire:Inventory.RawMaterialDocuments.Adjustments.AdjustmentEdit :documentId="$documentId" />

    <livewire:Inventory.RawMaterialStocks.ModalStockSelector />
@endsection
