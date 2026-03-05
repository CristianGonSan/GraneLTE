@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Crear Ajuste')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Ajustes</li>
            <li class="breadcrumb-item active">Crear</li>
        </ol>
    </nav>
@endsection

@section('content')
    <livewire:Inventory.RawMaterialDocuments.Adjustments.AdjustmentCreate />

    <livewire:Inventory.RawMaterialStocks.ModalStockSelector :stockOperator="null" />
@endsection
