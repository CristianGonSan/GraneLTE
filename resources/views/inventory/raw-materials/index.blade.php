@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Materias Primas</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="mb-3">
        <a href="{{ route('raw-materials.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-fw fa-plus mr-1"></i>Crear materia prima
        </a>
    </div>

    <livewire:Inventory.RawMaterials.RawMaterialsTable />
@endsection
