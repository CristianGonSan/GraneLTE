@extends('adminlte::page')

@section('plugins.Select2', true)

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-materials.index') }}">Materias primas</a></li>
            <li class="breadcrumb-item active">Crear</li>
        </ol>
    </nav>
@endsection

@section('content')
    <livewire:Inventory.RawMaterials.RawMaterialCreate />
@endsection
