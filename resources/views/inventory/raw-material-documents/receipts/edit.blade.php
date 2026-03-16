@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Editar Entrada')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Entradas</li>
            <li class="breadcrumb-item active">{{ $documentId }}</li>
            <li class="breadcrumb-item active">Edición</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Edición de entrada de materia prima</h1>
    <livewire:Inventory.RawMaterialDocuments.Receipts.ReceiptEdit :documentId="$documentId" />
@endsection
