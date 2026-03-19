@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Nueva Transferencia de Materia Prima |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Transferencias</li>
            <li class="breadcrumb-item active">Nueva</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Nueva transferencia de materia prima</h1>
    <livewire:Inventory.RawMaterialDocuments.Transfers.TransferCreate />

    <livewire:Inventory.RawMaterialStocks.ModalStockSelector />
@endsection
