@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Detalles de Ajuste')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Ajustes</li>
            <li class="breadcrumb-item active">{{ $document->id }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de Ajuste de Existencias</h1>

    @include('partials.inventory.raw-material-documents.show.card-details')

    <h2 class="h5">Lista de Ajustes</h2>

    @forelse ($document->adjustmentLines as $line)
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
                        <dt class="text-muted">Cantidad Contada</dt>
                        <dd>
                            {{ number_format($line->counted_quantity, 3) }}
                            <span title="{{ $material->unit->name }}">
                                {{ $material->unit->symbol }}
                            </span>
                        </dd>
                    </div>

                    <div class="col-sm-4 col-md-4 col-6">
                        <dt class="text-muted">Cantidad Teórica</dt>
                        <dd>
                            {{ number_format($line->theoretical_quantity, 3) }}
                            <span title="{{ $material->unit->name }}">
                                {{ $material->unit->symbol }}
                            </span>
                        </dd>
                    </div>

                    <div class="col-sm-4 col-md-4">
                        <dt class="text-muted">Diferencia</dt>
                        <dd>
                            {{ number_format($line->difference_quantity, 3) }}
                            <span title="{{ $material->unit->name }}">
                                {{ $material->unit->symbol }}
                            </span>
                        </dd>
                    </div>
                </dl>

                <hr class="my-2">

                <div class="text-muted">
                    {{ $stock->warehouse->name }}
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body">
                <div class="text-center text-muted py-4">
                    <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                    No hay líneas registradas
                </div>
            </div>
        </div>
    @endforelse

    <h2 class="h5">Estatus</h2>

    <livewire:Inventory.RawMaterialDocuments.ChangeDocumentStatus :documentId="$document->id" />

    @if ($document->status === App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus::ACCEPTED)
        <hr>
        <h2 class="h5">Movimientos generados por este documento</h2>
        <livewire:Inventory.RawMaterialDocuments.MovementsTable :documentId="$document->id" />

        <livewire:Inventory.RawMaterialMovements.ModalMovementShow />
    @endif
@endsection
