@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="h4">Dashboard</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-boxes"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Materiales activos</span>
                    <span class="info-box-number">
                        {{ number_format($rawMaterialsCount) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-warehouse"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Almacenes activos</span>
                    <span class="info-box-number">
                        {{ number_format($warehousesCount) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-dollar-sign"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Valor de inventario</span>
                    <span class="info-box-number">
                        $ {{ number_format($totalCost, 2) }} MXN
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <h2 class="h5">Stocks bajos</h2>
            <div class="card">
                <div class="card-body">

                </div>
            </div>
        </div>

        <div class="col-md-12">
            <h2 class="h5">Documentos recientes</h2>
            <x-card-table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th class="d-none d-md-table-cell">Responsable</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lastDocuments as $doc)
                        <tr>
                            <td>
                                <a href="{{ $doc->getRoute('show') }}" class="font-weight-bold">
                                    DOC-{{ str_pad($doc->id, 4, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>{{ $doc->type->label() }}</td>
                            <td>{{ $doc->status->label() }}</td>
                            <td class="d-none d-md-table-cell text-muted">
                                {{ $doc->responsible?->name ?? '—' }}
                            </td>
                            <td class="text-muted">
                                {{ $doc->effective_at->format('d/m/Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="fas fa-inbox mr-1"></i>Sin documentos recientes
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-card-table>
        </div>
    </div>
@endsection
