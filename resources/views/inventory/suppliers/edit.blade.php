@extends('adminlte::page')

@section('title_prefix', 'Editar Proveedor |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('units.index') }}">Proveedores</a></li>
            <li class="breadcrumb-item"><a href="{{ route('units.show', $supplierId) }}">#{{ $supplierId }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar proveedor</h1>
    <livewire:Inventory.Suppliers.SupplierEdit :supplierId="$supplierId" />
@endsection
