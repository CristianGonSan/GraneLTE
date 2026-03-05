@extends('adminlte::page')

@section('title', 'Materias prima')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Materias prima</li>
            </ol>
        </nav>

        <a href="{{ route('raw-materials.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-fw fa-plus mr-1"></i>Crear materia prima
        </a>
    </div>
@endsection

@section('content')
    <livewire:Inventory.RawMaterials.RawMaterialsTable />
@endsection
