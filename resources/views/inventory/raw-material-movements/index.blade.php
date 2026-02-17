@extends('adminlte::page')

@section('title', 'Movimientos')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Movimientos de materia prima</li>
        </ol>
    </nav>
@endsection

@section('content')
    <livewire:Inventory.RawMaterialMovements.MovementsTable />

    <livewire:Inventory.RawMaterialMovements.ModalMovementShow />
@endsection
