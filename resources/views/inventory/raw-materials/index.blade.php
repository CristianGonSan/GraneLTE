@extends('adminlte::page')

@section('title_prefix', 'Materias Primas |')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Materias primas</li>
            </ol>
        </nav>

        @can('raw-materials.create')
            <a href="{{ route('raw-materials.create') }}" class="btn btn-outline-primary">
                <i class="fas fa-fw fa-plus mr-1"></i>Nueva materia prima
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <livewire:Inventory.RawMaterials.RawMaterialsTable />
@endsection
