@extends('adminlte::page')

@section('title_prefix', 'Unidades |')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Unidades</li>
            </ol>
        </nav>

        @can('units.create')
            <a href="{{ route('units.create') }}" class="btn btn-outline-primary">
                <i class="fas fa-fw fa-plus mr-1"></i>Nueva unidad
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <livewire:Inventory.Units.UnitsTable />
@endsection
