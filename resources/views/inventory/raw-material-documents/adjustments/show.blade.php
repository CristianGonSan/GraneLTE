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
                <table class="table table-hover mb-0">
                    <thead class="text-nowrap border-top-0">
                        <tr>
                            <th style="min-width: 180px">Materia prima</th>
                            <th style="min-width: 220px">Origen</th>
                            <th style="min-width: 150px;">Conteo</th>
                            <th style="min-width: 140px">Teórico</th>
                            <th style="min-width: 140px">Diferencia</th>
                            <th style="width: 160px">Impacto MXN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($document->adjustmentLines as $line)
                            @php
                                $stock = $line->stock;
                                $batch = $stock->batch;
                                $material = $batch->material;
                                $lineCost = bcmul($batch->received_unit_cost, $line->difference_quantity, 2);
                                $diffSign = bccomp($line->difference_quantity, '0', 3);
                                $diffClass = match (true) {
                                    $diffSign > 0 => 'text-success',
                                    $diffSign < 0 => 'text-danger',
                                    default => 'text-muted',
                                };
                                $finalStock = bcadd($stock->quantity, $line->difference_quantity, 3);
                            @endphp
                            <tr>
                                <td class="align-middle">
                                    {{ $material->mediumText('name') }}
                                </td>
                                <td class="align-middle">
                                    <div class="text-nowrap">{{ $batch->code }}</div>
                                    <small class="text-muted">{{ $stock->warehouse->mediumText('name') }}</small>
                                </td>
                                <!-- Conteo -->
                                <td class="align-middle">
                                    {{ number_format($line->counted_quantity, 3) }}
                                    <span class="text-muted">{{ $material->unit->symbol }}</span>
                                </td>
                                <!-- Teórico -->
                                <td class="align-middle">
                                    {{ number_format($line->theoretical_quantity, 3) }} {{ $material->unit->symbol }}
                                </td>
                                <!-- Diferencia -->
                                <td class="align-middle">
                                    <div class="{{ $diffClass }}">
                                        {{ $diffSign > 0 ? '+' : '' }}{{ number_format($line->difference_quantity, 3) }}
                                    </div>
                                    @if (bccomp($finalStock, '0', 3) < 0)
                                        <div class="text-danger small">Error: stock final negativo</div>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="{{ $diffClass }}">
                                        $ {{ number_format($lineCost, 2) }}
                                    </div>
                                    <small class="text-muted">$ {{ number_format($batch->received_unit_cost, 2) }}
                                        c/u</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                    No hay líneas registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($document->adjustmentLines->isNotEmpty())
                        @php
                            $totalSign = bccomp((string) $document->total_cost, '0', 2);
                            $totalClass = match (true) {
                                $totalSign > 0 => 'text-success',
                                $totalSign < 0 => 'text-danger',
                                default => 'text-muted',
                            };
                        @endphp
                        <tfoot>
                            <tr>
                                <td colspan="5" class="font-weight-bold text-muted">
                                    Total MXN
                                </td>
                                <td class="font-weight-bold text-nowrap {{ $totalClass }}">
                                    $ {{ number_format($document->total_cost, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
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
