@extends('adminlte::page')

@section('title_prefix', 'Materia Prima |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-materials.index') }}">Materias primas</a></li>
            <li class="breadcrumb-item active">#{{ $rawMaterialId }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de materia prima</h1>
    <livewire:Inventory.RawMaterials.RawMaterialShow :rawMaterialId="$rawMaterialId" />

    <hr class="mt-1">

    <div class="d-block mb-3">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-stocks-tab" data-toggle="pill" href="#pills-stocks" role="tab"
                    aria-controls="pills-stocks" aria-selected="true">
                    <i class="fas fa-fw fa-boxes-stacked"></i>
                    <span class="d-none d-sm-inline ml-1">Existencias</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-batches-tab" data-toggle="pill" href="#pills-batches" role="tab"
                    aria-controls="pills-batches" aria-selected="false">
                    <i class="fas fa-fw fa-box"></i>
                    <span class="d-none d-sm-inline ml-1">Lotes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-movements-tab" data-toggle="pill" href="#pills-movements" role="tab"
                    aria-controls="pills-movements" aria-selected="false">
                    <i class="fas fa-fw fa-cart-flatbed"></i>
                    <span class="d-none d-sm-inline ml-1">Movimientos</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-stocks" role="tabpanel" aria-labelledby="pills-stocks-tab">
            <livewire:Inventory.RawMaterials.StocksTable :rawMaterialId="$rawMaterialId" />
        </div>

        <div class="tab-pane fade" id="pills-batches" role="tabpanel" aria-labelledby="pills-batches-tab">
            <livewire:Inventory.RawMaterials.BatchesTable :rawMaterialId="$rawMaterialId" lazy />
        </div>

        <div class="tab-pane fade" id="pills-movements" role="tabpanel" aria-labelledby="pills-movements-tab">
            <livewire:Inventory.RawMaterials.MovementsTable :rawMaterialId="$rawMaterialId" lazy />
        </div>
    </div>

    <livewire:Inventory.RawMaterialStocks.ModalStockShow />

    <livewire:Inventory.RawMaterialBatches.ModalBatchShow />

    <livewire:Inventory.RawMaterialMovements.ModalMovementShow />
@endsection
