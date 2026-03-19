@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Editar Materia Prima |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-materials.index') }}">Materias primas</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('raw-materials.show', $rawMaterialId) }}">#{{ $rawMaterialId }}</a>
            </li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar materia prima</h1>
    <livewire:Inventory.RawMaterials.RawMaterialEdit :rawMaterialId="$rawMaterialId" />
@endsection
