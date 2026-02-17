@extends('adminlte::page')

@section('plugins.Select2', true)

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('batches.index') }}">Lotes</a></li>
            <li class="breadcrumb-item active">Registrar Lote</li>
        </ol>
    </nav>
@endsection

@section('content')
    <livewire:Inventory.Batches.CreateBatch />
@endsection
