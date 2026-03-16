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
    @php
        use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus as Status;
    @endphp
    <h1 class="h4">Detalles de ajuste de existencias</h1>

    @include('partials.inventory.raw-material-documents.show.card-details')

    <h2 class="h5">Lista de ajustes</h2>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-dark text-nowrap border-top-0">
                        <tr>
                            <th>Materia prima</th>
                            <th>Almacén</th>
                            <th>Lote</th>
                            <th>Cantidad contada</th>
                            <th>Cantidad teórica</th>
                            <th>Diferencia</th>
                            <th>Total MXN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($document->adjustmentLines as $line)
                            @php
                                $stock = $line->stock;
                                $batch = $stock->batch;
                                $material = $batch->material;
                                $lineCost = bcmul($batch->received_unit_cost, $line->difference_quantity, 2);
                                $diff = bccomp((string) $line->difference_quantity, '0', 3);
                                $diffClass = match (true) {
                                    $diff > 0 => 'text-success',
                                    $diff < 0 => 'text-danger',
                                    default => 'text-muted',
                                };
                            @endphp
                            <tr>
                                <td>{{ $material->name }}</td>
                                <td>{{ $stock->warehouse->name }}</td>
                                <td>{{ $batch->code }}</td>
                                <td title="{{ $material->unit->name }}">
                                    {{ number_format($line->counted_quantity, 3) }}
                                    <span>{{ $material->unit->symbol }}</span>
                                </td>
                                <td title="{{ $material->unit->name }}">
                                    {{ number_format($line->theoretical_quantity, 3) }}
                                    <span>{{ $material->unit->symbol }}</span>
                                </td>
                                <td class="{{ $diffClass }}" title="{{ $material->unit->name }}">
                                    {{ number_format($line->difference_quantity, 3) }}
                                    <span>{{ $material->unit->symbol }}</span>
                                </td>
                                <td class="{{ $diffClass }}">
                                    $ {{ number_format($lineCost, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                        No hay líneas registradas
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="font-weight-bold text-muted">Total MXN</td>
                            <td><strong>$ {{ number_format($document->total_cost, 2) }}</strong></td>
                        </tr>
                    </tfoot>
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
