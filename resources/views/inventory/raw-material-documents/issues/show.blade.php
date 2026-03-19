@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Salida de Materia Prima |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Salidas</li>
            <li class="breadcrumb-item active">#{{ $document->id }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    @php
        use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus as Status;
    @endphp
    <h1 class="h4">Detalles de salida de materia prima</h1>

    @include('partials.inventory.raw-material-documents.show.card-details')

    <h2 class="h5">Lista de salidas</h2>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="text-nowrap border-top-0">
                        <tr>
                            <th style="min-width: 220px">Materia prima</th>
                            <th style="min-width: 230px">Origen</th>
                            <th style="min-width: 200px; width: 200px;">Cantidad</th>
                            <th style="width: 160px">Total MXN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($document->issueLines as $line)
                            @php
                                $stock = $line->stock;
                                $batch = $stock->batch;
                                $material = $batch->material;
                                $isInsufficient = bccomp($line->quantity, $stock->current_quantity, 3) > 0;
                            @endphp
                            <tr>
                                <td class="align-middle">
                                    {{ $material->mediumText('name') }}
                                </td>
                                <td class="align-middle">
                                    <div class="text-nowrap">{{ $batch->code }}</div>
                                    <small class="text-muted">{{ $line->warehouse->mediumText('name') }}</small>
                                </td>
                                <td class="align-middle">
                                    <div>
                                        {{ number_format($line->quantity, 3) }}
                                        <span class="text-muted">{{ $material->unit->symbol }}</span>
                                    </div>
                                    @if ($isInsufficient)
                                        <small class="text-danger">
                                            Stock insuficiente (Disp: {{ number_format($stock->current_quantity, 3) }})
                                        </small>
                                    @endif
                                </td>
                                <td class="text-nowrap align-middle">
                                    <div>$ {{ number_format($line->totalCost(), 2) }}</div>
                                    <small class="text-muted">$ {{ number_format($batch->received_unit_cost, 2) }}
                                        c/u</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                    No hay lotes registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($document->issueLines->isNotEmpty())
                        <tfoot>
                            <tr>
                                <td colspan="3" class="font-weight-bold text-muted">
                                    Total MXN
                                </td>
                                <td class="font-weight-bold text-nowrap">
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
