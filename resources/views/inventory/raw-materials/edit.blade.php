@extends('adminlte::page')

@section('plugins.Select2', true)

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-materials.index') }}">Materias prima</a></li>
            <li class="breadcrumb-item active">{{ $rawMaterialId }}</li>
            <li class="breadcrumb-item active">Edición</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Edición de materia prima</h1>
    <livewire:Inventory.RawMaterials.RawMaterialEdit :rawMaterialId="$rawMaterialId" />
@endsection
