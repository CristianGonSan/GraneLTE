@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Detalles de Transferencia')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Transferencias</li>
            <li class="breadcrumb-item active">{{ $document->id }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de Transferencia de Materia Prima</h1>

    @include('partials.inventory.raw-material-documents.show.card-details')

    <h2 class="h5">Detalles de Transferencia</h2>

    @if ($line = $document->transferLine)
        @php
            $stock = $line->originStock;
            $batch = $stock->batch;
            $material = $batch->material;
        @endphp
        <div class="card">
            <div class="card-body py-3">
                <div>
                    <strong class="mb-0 text-dark">{{ $material->name }}</strong>
                    <div class="text-muted">{{ $batch->code }}</div>
                </div>

                <hr class="my-2">

                <div>
                    <dt class="text-muted">Almacén de origen:</dt>
                    <dd>{{ $stock->warehouse->name }}</dd>
                </div>

                <hr class="my-2">

                <dl class="row mb-0">
                    <div class="col-md-9">
                        <dt class="text-muted">Almacén de destino:</dt>
                        <dd>{{ $line->warehouseDest->name }}</dd>
                    </div>

                    <div class="col-md-3">
                        <dt class="text-muted">Cantidad</dt>
                        <dd class="mb-0">
                            {{ number_format($line->quantity, 3) }}
                            <span title="{{ $material->unit->name }}">{{ $material->unit->symbol }}</span>
                            @if ($line->quantity > $stock->current_quantity)
                                <small class="text-danger d-block">Stock insuficiente</small>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="text-center text-muted py-4">
                    <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                    No hay líneas registradas
                </div>
            </div>
        </div>
    @endif

    <h2 class="h5">Estatus</h2>

    <livewire:Inventory.RawMaterialDocuments.ChangeDocumentStatus :documentId="$document->id" />

    @if ($document->status === App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus::ACCEPTED)
        <hr>
        <h2 class="h5">Movimientos generados por este documento</h2>
        <livewire:Inventory.RawMaterialDocuments.MovementsTable :documentId="$document->id" />

        <livewire:Inventory.RawMaterialMovements.ModalMovementShow />
    @endif
@endsection
