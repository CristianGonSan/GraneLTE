@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Detalles de Salida')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Salidas</li>
            <li class="breadcrumb-item active">{{ $document->id }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de Salida de Materia Prima</h1>

    @include('partials.inventory.raw-material-documents.show.card-details')

    <h2 class="h5">Lista de salidas</h2>

    @forelse ($document->issueLines as $line)
        @php
            $stock = $line->stock;
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

                <dl class="row mb-0">
                    <div class="col-sm-4 col-md-4 col-6">
                        <dt class="text-muted">Cantidad</dt>
                        <dd>
                            {{ number_format($line->quantity, 3) }}
                            <span title="{{ $material->unit->name }}">
                                {{ $material->unit->symbol }}
                            </span>
                            @if ($line->quantity > $stock->current_quantity)
                                <br><small class="text-danger">Stock insuficiente</small>
                            @endif
                        </dd>
                    </div>

                    <div class="col-sm-4 col-md-4 col-6">
                        <dt class="text-muted">Costo unitario</dt>
                        <dd>$ {{ number_format($batch->received_unit_cost, 2) }}</dd>
                    </div>

                    <div class="col-sm-4 col-md-4">
                        <dt class="text-muted">Total MXN</dt>
                        <dd>$ {{ number_format($line->totalCost(), 2) }}</dd>
                    </div>
                </dl>

                <hr class="my-2">

                <div class="text-muted">
                    {{ $line->warehouse->name }}
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body">
                <div class="text-center text-muted py-4">
                    <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                    No hay lotes registrados
                </div>
            </div>
        </div>
    @endforelse

    <div class="card">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center">
                <strong class="text-muted">Total MXN</strong>
                <strong>$ {{ number_format($document->total_cost, 2) }}</strong>
            </div>
        </div>
    </div>

    <h2 class="h5">Estatus</h2>

    <livewire:Inventory.RawMaterialDocuments.ChangeDocumentStatus :documentId="$document->id" />

    @if ($document->status === App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus::ACCEPTED)
        <hr>
        <h2 class="h5">Movimientos generados por este documento</h2>
        <livewire:Inventory.RawMaterialDocuments.MovementsTable :documentId="$document->id" />

        <livewire:Inventory.RawMaterialMovements.ModalMovementShow />
    @endif
@endsection
