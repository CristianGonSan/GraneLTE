@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('warehouses.index') }}">Almacenes</a></li>
            <li class="breadcrumb-item active">{{ $warehouseId }}</li>
            <li class="breadcrumb-item active">Edición</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Edición de almacén</h1>
    <livewire:Inventory.Warehouses.WarehouseEdit :warehouseId="$warehouseId" />
@endsection
