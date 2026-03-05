@extends('adminlte::page')

@section('title', 'Dashboard — Inventario')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Dashboard de Inventario</h1>
        <small class="text-muted">Actualizado: {{ now()->format('d/m/Y H:i') }}</small>
    </div>
@stop

@section('content')

    <div class="row">

        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>$ {{ number_format($totalCost, 2) }}</h3>
                    <p>Costo Total del Inventario</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <a href="{{ route('raw-materials.index') }}" class="small-box-footer">
                    Ver materiales <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $activeMaterials }}</h3>
                    <p>Materiales Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="{{ route('raw-materials.index') }}" class="small-box-footer">
                    Ver materiales <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $lowStockMaterials->count() }}</h3>
                    <p>Materiales con Stock Bajo</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('raw-materials.index') }}" class="small-box-footer">
                    Ver materiales <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $expiringBatches->count() }}</h3>
                    <p>Lotes por Vencer (30 días)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('raw-material-batches.index') }}" class="small-box-footer">
                    Ver lotes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>

    {{-- =========================================================
         Documentos recientes + Stock bajo
    ========================================================== --}}
    <div class="row">

        {{-- Documentos recientes --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt mr-1"></i> Documentos Recientes
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('raw-material-documents.index') }}" class="btn btn-sm btn-outline-secondary">
                            Ver todos
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Fecha efectiva</th>
                                <th>Creado por</th>
                                <th class="text-right">Costo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentDocuments as $doc)
                                @php
                                    $typeBadge = match ($doc->type->value) {
                                        'receipt' => 'badge-primary',
                                        'issue' => 'badge-danger',
                                        'transfer' => 'badge-info',
                                        'adjustment' => 'badge-warning',
                                    };
                                    $statusBadge = match ($doc->status->value) {
                                        'draft' => 'badge-secondary',
                                        'pending' => 'badge-warning',
                                        'accepted' => 'badge-success',
                                        'rejected' => 'badge-danger',
                                        'canceled' => 'badge-dark',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ $doc->getRoute() }}">#{{ $doc->id }}</a>
                                    </td>
                                    <td>
                                        <span class="badge {{ $typeBadge }}">
                                            {{ $doc->type->label() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusBadge }}">
                                            {{ $doc->status->label() }}
                                        </span>
                                    </td>
                                    <td>{{ $doc->effective_at->format('d/m/Y') }}</td>
                                    <td>{{ $doc->creator->name }}</td>
                                    <td class="text-right">
                                        @if ($doc->total_cost !== null)
                                            $ {{ number_format($doc->total_cost, 2) }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        Sin documentos recientes.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Stock bajo --}}
        <div class="col-lg-4">
            <div class="card card-warning card-outline">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Stock Bajo
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Material</th>
                                <th class="text-right">Actual</th>
                                <th class="text-right">Mínimo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lowStockMaterials as $mat)
                                <tr>
                                    <td title="{{ $mat->name }}">
                                        {{ $mat->abbreviation }}
                                    </td>
                                    <td class="text-right text-danger font-weight-bold">
                                        {{ number_format($mat->current_quantity, 3) }}
                                        <small class="text-muted">{{ $mat->unit->symbol }}</small>
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($mat->minimum_stock, 3) }}
                                        <small class="text-muted">{{ $mat->unit->symbol }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        Sin alertas de stock.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- =========================================================
         Lotes por vencer + Movimientos recientes
    ========================================================== --}}
    <div class="row">

        {{-- Lotes por vencer --}}
        <div class="col-lg-6">
            <div class="card card-danger card-outline">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-clock mr-1"></i> Lotes por Vencer (30 días)
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Código</th>
                                <th>Material</th>
                                <th>Vencimiento</th>
                                <th class="text-right">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expiringBatches as $batch)
                                <tr class="{{ $batch->isExpired() ? 'table-danger' : '' }}">
                                    <td>
                                        <code>{{ $batch->code }}</code>
                                    </td>
                                    <td>{{ $batch->material->name }}</td>
                                    <td>
                                        {{ $batch->expiration_date->format('d/m/Y') }}
                                        @if ($batch->isExpired())
                                            <span class="badge badge-danger ml-1">Vencido</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($batch->current_quantity, 3) }}
                                        <small class="text-muted">{{ $batch->material->unit->symbol }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        Sin lotes próximos a vencer.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Movimientos recientes --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-exchange-alt mr-1"></i> Movimientos Recientes
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('raw-material-movements.index') }}" class="btn btn-sm btn-outline-secondary">
                            Ver todos
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Tipo</th>
                                <th>Lote / Material</th>
                                <th>Almacén</th>
                                <th class="text-right">Cantidad</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentMovements as $mov)
                                @php
                                    $isIn = $mov->type->isIncrement();
                                    $colorCls = $isIn ? 'text-success' : 'text-danger';
                                    $sign = $isIn ? '+' : '−';
                                @endphp
                                <tr>
                                    <td>
                                        <i class="fas {{ $mov->type->icon() }} {{ $colorCls }}"
                                            title="{{ $mov->type->label() }}"></i>
                                        <span class="d-none d-xl-inline ml-1 small">
                                            {{ $mov->type->label() }}
                                        </span>
                                    </td>
                                    <td>
                                        <code>{{ $mov->batch->code }}</code><br>
                                        <small class="text-muted">{{ $mov->batch->material->name }}</small>
                                    </td>
                                    <td>{{ $mov->warehouse->name }}</td>
                                    <td class="text-right {{ $colorCls }} font-weight-bold">
                                        {{ $sign }}{{ number_format($mov->quantity, 3) }}
                                        <small class="text-muted font-weight-normal">
                                            {{ $mov->batch->material->unit->symbol }}
                                        </small>
                                    </td>
                                    <td>
                                        <small>{{ $mov->effective_at->format('d/m/Y') }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        Sin movimientos recientes.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@stop
