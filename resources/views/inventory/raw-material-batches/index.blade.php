@extends('adminlte::page')

@section('title', 'Lotes MP')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Lotes de materia prima</li>
        </ol>
    </nav>
@endsection

@section('content')
    <livewire:Inventory.RawMaterialBatches.BatchesTable />

    <livewire:Inventory.RawMaterialBatches.ModalBatchShow />
@endsection
