@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Editar Entrada de Materia Prima |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Entradas</li>
            <li class="breadcrumb-item">
                <a href="{{ route('raw-material-documents.receipts.show', $documentId) }}">#{{ $documentId }}</a>
            </li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar entrada de materia prima</h1>
    <livewire:Inventory.RawMaterialDocuments.Receipts.ReceiptEdit :documentId="$documentId" />
@endsection
