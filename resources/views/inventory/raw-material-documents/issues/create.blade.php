@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Crear Salida')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Salidas</li>
            <li class="breadcrumb-item active">Crear</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Crear salida de materia prima</h1>
    <livewire:Inventory.RawMaterialDocuments.Issues.IssueCreate />

    <livewire:Inventory.RawMaterialStocks.ModalStockSelector />
@endsection
