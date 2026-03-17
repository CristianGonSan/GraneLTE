@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Detalles de Entrada')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('raw-material-documents.index') }}">Documentos de materia prima</a>
            </li>
            <li class="breadcrumb-item active">Entradas</li>
            <li class="breadcrumb-item active">{{ $document->id }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    @php
        use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus as Status;
    @endphp
    <h1 class="h4">Detalles de entrada de materia prima</h1>

    @include('partials.inventory.raw-material-documents.show.card-details')

    <h2 class="h5">Lista de entradas</h2>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="text-nowrap border-top-0">
                        <tr>
                            <th style="min-width: 220px">Ítem / Destino</th>
                            <th style="min-width: 180px">Lote Externo</th>
                            <th style="min-width: 160px">Vencimiento</th>
                            <th style="min-width: 160px; width: 160px;">Cantidad</th>
                            <th style="min-width: 160px; width: 160px;">Costo Unit.</th>
                            <th style="min-width: 120px">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($document->receiptLines as $line)
                            <tr>
                                <td class="align-middle">
                                    <div>{{ $line->material->mediumText('name') }}</div>
                                    <small class="text-muted">{{ $line->warehouse->mediumText('name') }}</small>
                                </td>
                                <td class="align-middle">
                                    {{ $line->external_batch_code ?? 'S/N' }}
                                </td>
                                <td class="align-middle">
                                    {{ $line->expiration_date?->format('d/m/Y') ?? '--/--/----' }}
                                </td>
                                <td class="text-nowrap align-middle">
                                    {{ number_format($line->received_quantity, 3) }}
                                    <span class="text-muted">{{ $line->material->unit->symbol }}</span>
                                </td>
                                <td class="text-nowrap align-middle">
                                    $ {{ number_format($line->received_unit_cost, 2) }}
                                </td>
                                <td class="text-nowrap align-middle">
                                    $ {{ number_format($line->received_total_cost, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                    No hay lotes registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($document->receiptLines->isNotEmpty())
                        <tfoot>
                            <tr>
                                <td colspan="5" class="font-weight-bold text-muted">
                                    Total MXN
                                </td>
                                <td class="font-weight-bold">
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
