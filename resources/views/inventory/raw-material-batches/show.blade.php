@extends('adminlte::page')

@section('title_prefix', 'Lote de Materia Prima |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-material-batches.index') }}">Lotes de materia prima</a></li>
            <li class="breadcrumb-item active">#{{ $batchId }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de lote de materia prima</h1>
    <livewire:Inventory.RawMaterialBatches.BatchShow :batchId="$batchId" />

    <hr class="mt-1">

    <div class="d-block mb-3">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-stocks-tab" data-toggle="pill" href="#pills-stocks" role="tab"
                    aria-controls="pills-stocks" aria-selected="true">
                    <i class="fas fs-fw fa-boxes-stacked mr-1"></i>
                    <span class="d-none d-sm-inline">Existencias</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-movements-tab" data-toggle="pill" href="#pills-movements" role="tab"
                    aria-controls="pills-movements" aria-selected="false">
                    <i class="fas fs-fw fa-cart-flatbed mr-1"></i>
                    <span class="d-none d-sm-inline">Movimientos</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-stocks" role="tabpanel" aria-labelledby="pills-stocks-tab">
            <livewire:Inventory.RawMaterialBatches.StocksTable :batchId="$batchId" />
        </div>

        <div class="tab-pane fade" id="pills-movements" role="tabpanel" aria-labelledby="pills-movements-tab">
            <livewire:Inventory.RawMaterialBatches.MovementsTable :batchId="$batchId" lazy />
        </div>
    </div>

    <livewire:Inventory.RawMaterialStocks.ModalStockShow />

    <livewire:Inventory.RawMaterialMovements.ModalMovementShow />
@endsection
