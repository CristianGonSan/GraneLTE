@extends('adminlte::page')

@section('title', 'Existencias')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Existencias de materia prima</li>
        </ol>
    </nav>
@endsection

@section('content')
    <livewire:Inventory.RawMaterialStocks.StocksTable />

    <livewire:Inventory.RawMaterialStocks.ModalStockShow />
@endsection
