@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('units.index') }}">Proveedores</a></li>
            <li class="breadcrumb-item active">{{ $supplierId }}</li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <livewire:Inventory.Suppliers.SupplierEdit :supplierId="$supplierId" />
@endsection
