<div x-data="{ open: @entangle('showModal') }">
    <template x-if="open">
        <div>
            <div class="modal-backdrop show"></div>
            <div class="modal show d-block" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
                    x-on:click.outside="open = false">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="h4 modal-title">Detalles de lote</h1>
                            <button type="button" class="close" x-on:click="open = false">
                                <span>&times;</span>
                            </button>
                        </div>

                        @if ($batch)
                            @php
                                $supplier = $batch->supplier;
                                $material = $batch->material;
                            @endphp
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info">
                                                <i class="fas fa-boxes"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">En stock</span>
                                                <span class="info-box-number">
                                                    {{ number_format($batch->current_quantity, 3) }}
                                                    {{ $material->unit->symbol }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-teal">
                                                <i class="fas fa-dollar-sign"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Costo actual</span>
                                                <span class="info-box-number">
                                                    $ {{ number_format($batch->current_cost, 2) }}
                                                    MXN
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h2 class="h5">
                                    Lote
                                    <a href="{{ route('raw-material-batches.show', $batch->id) }}" target="_blank">
                                        <i class="fas fa-fw fa-arrow-up-right-from-square"></i>
                                    </a>
                                </h2>

                                <div class="card mb-3">
                                    <div class="card-body">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4 text-muted">Código</dt>
                                            <dd class="col-sm-8 font-weight-bold">{{ $batch->code }}</dd>

                                            <dt class="col-sm-4 text-muted">Recibido el</dt>
                                            <dd class="col-sm-8">{{ $batch->received_at->format('d/m/Y') }}</dd>

                                            <dt class="col-sm-4 text-muted">Cantidad recibida</dt>
                                            <dd class="col-sm-8">
                                                {{ number_format($batch->received_quantity, 3) }}
                                                {{ $material->unit->symbol }}
                                            </dd>

                                            <dt class="col-sm-4 text-muted">Costo unitario</dt>
                                            <dd class="col-sm-8">
                                                $ {{ number_format($batch->received_unit_cost, 2) }} MXN /
                                                {{ $material->unit->symbol }}
                                            </dd>

                                            <dt class="col-sm-4 text-muted">Costo total recibido</dt>
                                            <dd class="col-sm-8">
                                                $ {{ number_format($batch->received_total_cost, 2) }} MXN
                                            </dd>

                                            <dt class="col-sm-4 text-muted">
                                                {{ $batch->isExpired() ? 'Vencido el' : 'Vence el' }}
                                            </dt>
                                            <dd
                                                class="col-sm-8 {{ $batch->isExpired() ? 'text-danger font-weight-bold' : '' }} mb-0">
                                                {{ $batch->expiration_date?->format('d/m/Y') ?? '--/--/----' }}
                                            </dd>
                                        </dl>
                                    </div>
                                </div>

                                <h2 class="h5">
                                    Material
                                    <a href="{{ route('raw-materials.show', $material->id) }}" target="_blank">
                                        <i class="fas fa-fw fa-arrow-up-right-from-square"></i>
                                    </a>
                                </h2>

                                <div class="card mb-3">
                                    <div class="card-body">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4 text-muted">Nombre</dt>
                                            <dd class="col-sm-8">{{ $material->mediumText('name') }}</dd>

                                            <dt class="col-sm-4 text-muted">Unidad</dt>
                                            <dd class="col-sm-8">
                                                {{ $material->unit->name }}
                                                ({{ $material->unit->symbol }})
                                            </dd>

                                            <dt class="col-sm-4 text-muted">Categoría</dt>
                                            <dd class="col-sm-8 mb-0">{{ $material->category->mediumText('name') }}
                                            </dd>
                                        </dl>
                                    </div>
                                </div>

                                <h2 class="h5">
                                    Proveedor
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" target="_blank">
                                        <i class="fas fa-fw fa-arrow-up-right-from-square"></i>
                                    </a>
                                </h2>

                                <div class="card mb-0">
                                    <div class="card-body">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4 text-muted">Nombre</dt>
                                            <dd class="col-sm-8">
                                                {{ $supplier->mediumText('name') }}
                                            </dd>

                                            <dt class="col-sm-4 text-muted">Descripción</dt>
                                            <dd class="col-sm-8 text-muted mb-0">
                                                {{ $supplier->longText('description', 'Sin descripción') }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="modal-body">
                                <p class="text-muted text-center py-3">No hay información del lote.</p>
                            </div>
                        @endif

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" x-on:click="open = false">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
