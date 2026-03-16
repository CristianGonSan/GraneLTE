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
    @php
        use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus as Status;
    @endphp

    <h1 class="h4">Detalles de transferencia de materia prima</h1>

    @include('partials.inventory.raw-material-documents.show.card-details')

    <h2 class="h5">Detalles de transferencia</h2>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-dark text-nowrap border-top-0">
                        <tr>
                            <th>Materia prima</th>
                            <th>Lote</th>
                            <th>Almacén origen</th>
                            <th>Almacén destino</th>
                            <th>Cantidad</th>
                            <th>Costo unitario</th>
                            <th>Total MXN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($line = $document->transferLine)
                            @php
                                $stock = $line->originStock;
                                $batch = $stock->batch;
                                $material = $batch->material;
                                $isInsufficient = bccomp($line->quantity, $stock->current_quantity, 3) > 0;
                            @endphp
                            <tr>
                                <td>{{ $material->name }}</td>
                                <td>{{ $batch->code }}</td>
                                <td>{{ $stock->warehouse->name }}</td>
                                <td>{{ $line->warehouseDest->name }}</td>
                                <td title="{{ $material->unit->name }}">
                                    {{ number_format($line->quantity, 3) }}
                                    <span>{{ $material->unit->symbol }}</span>
                                    @if ($isInsufficient)
                                        <small class="text-danger d-block">
                                            Stock insuficiente
                                            (disponible: {{ number_format($stock->current_quantity, 3) }}
                                            {{ $material->unit->symbol }})
                                        </small>
                                    @endif
                                </td>
                                <td>$ {{ number_format($batch->received_unit_cost, 2) }}</td>
                                <td>$ {{ number_format($line->quantity * $batch->received_unit_cost, 2) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="7">
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                        No hay líneas registradas
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <h2 class="h5">Estatus</h2>

    <livewire:Inventory.RawMaterialDocuments.ChangeDocumentStatus :documentId="$document->id" />

    @if ($document->status === Status::ACCEPTED || $document->status === Status::CANCELED)
        <hr>
        <h2 class="h5">Movimientos generados por este documento</h2>
        <livewire:Inventory.RawMaterialDocuments.MovementsTable :documentId="$document->id" />
        <livewire:Inventory.RawMaterialMovements.ModalMovementShow />
    @endif

@endsection
