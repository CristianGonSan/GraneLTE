@extends('adminlte::page')

@section('title_prefix', 'Nueva Unidad |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('units.index') }}">Unidades</a></li>
            <li class="breadcrumb-item active">Nueva</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Nueva unidad</h1>
    <livewire:Inventory.Units.UnitCreate />
@endsection
