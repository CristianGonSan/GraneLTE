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
    <div>
        <h1 class="h4">Detalles de Entrada de Materia Prima</h1>

        @include('partials.inventory.raw-material-documents.show.card-details')

        <x-card-table>
            <thead class="text-nowrap">
                <tr>
                    <th>Materia Prima</th>
                    <th>Almacén</th>
                    <th>Código Lote</th>
                    <th>Expiración</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Costo Unit.</th>
                    <th class="text-center">Total MXN</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($document->receiptLines as $line)
                    <tr>
                        <td class="align-middle" style="max-width: 130px;">
                            {{ $line->material->name }}
                        </td>

                        <td class="align-middle" style="max-width: 180px;">
                            {{ $line->warehouse->name }}
                        </td>

                        <td class="align-middle">
                            {{ $line->external_batch_code ?? 'S/N' }}
                        </td>

                        <td class="align-middle">
                            {{ $line->expiration_date?->format('d/m/Y') ?? '--/--/----' }}
                        </td>

                        <td class="align-middle text-center">
                            {{ number_format($line->received_quantity, 3) }}
                            {{ $line->material->unit->symbol }}
                        </td>

                        <td class="align-middle text-center">
                            {{ number_format($line->received_unit_cost, 2) }}
                        </td>

                        <td class="align-middle text-center">
                            {{ number_format($line->received_total_cost, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No hay lotes registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="6"></th>
                    <th class="text-center">
                        {{ number_format($document->total_cost, 2) }}
                    </th>
                </tr>
            </tfoot>
        </x-card-table>

        <livewire:Inventory.RawMaterialDocuments.ChangeDocumentStatus :document="$document" />

        <div class="mt-3 mb-3">
            <a href="{{ route('raw-material-documents.index') }}" class="btn btn-outline-secondary">
                Volver
            </a>
        </div>
    </div>
@endsection
