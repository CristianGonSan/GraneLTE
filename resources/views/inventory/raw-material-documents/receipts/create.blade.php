@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Crear Entrada')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a></li>
            <li class="breadcrumb-item active">Entradas</li>
            <li class="breadcrumb-item active">Crear</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Crear entrada de materia prima</h1>
    <livewire:Inventory.RawMaterialDocuments.Receipts.ReceiptCreate />
@endsection
