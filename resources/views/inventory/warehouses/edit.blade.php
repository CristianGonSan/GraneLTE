@extends('adminlte::page')

@section('title_prefix', 'Editar Almacén |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('warehouses.index') }}">Almacenes</a></li>
            <li class="breadcrumb-item active">
                <a href="{{ route('warehouses.show', $warehouseId) }}">#{{ $warehouseId }}</a>
            </li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar almacén</h1>
    <livewire:Inventory.Warehouses.WarehouseEdit :warehouseId="$warehouseId" />
@endsection
