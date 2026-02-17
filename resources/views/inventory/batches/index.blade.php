@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Lotes</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <a href="{{ route('batches.create') }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-plus mr-1"></i>Registrar lote
            </a>
        </div>
        <div class="col-12">
            <livewire:Inventory.Batches.BatchTable />
        </div>
    </div>
@endsection
