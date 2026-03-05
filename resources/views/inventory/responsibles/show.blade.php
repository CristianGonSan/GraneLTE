@extends('adminlte::page')

@section('title', 'Responsable')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Responsables</a></li>
            <li class="breadcrumb-item active">{{ $responsibleId }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de responsable</h1>
    <livewire:Inventory.Responsibles.ResponsibleShow :responsibleId="$responsibleId" />

    <hr class="mt-1">

    <h2 class="h5">Documentos de materia prima donde es responsable</h2>
    <livewire:Inventory.Responsibles.DocumentsTable :responsibleId="$responsibleId" />
@endsection
