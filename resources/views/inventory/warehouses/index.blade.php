@extends('adminlte::page')

@section('title', 'Almacenes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Almacenes</li>
            </ol>
        </nav>

        <a href="{{ route('warehouses.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-fw fa-plus mr-1"></i>Crear almacen
        </a>
    </div>
@endsection

@section('content')
    <livewire:Inventory.Warehouses.WarehousesTable />
@endsection
