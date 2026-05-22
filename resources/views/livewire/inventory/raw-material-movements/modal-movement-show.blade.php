<div>
    <div wire:ignore.self id="modalRawMaterialMovementShow" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="modalRawMaterialMovementShowLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    @if ($movement)
                        <h1 class="h4 modal-title" id="modalRawMaterialMovementShowLabel">
                            {{ $movement->type->label() }}
                        </h1>
                    @else
                        <h1 class="h4 modal-title" id="modalRawMaterialMovementShowLabel">
                            Detalle de movimiento
                        </h1>
                    @endif
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                @if ($movement)
                    @php
                        $batch = $movement->batch;
                        $material = $batch->material;
                        $doc = $movement->document;
                        $warehouse = $movement->warehouse;
                    @endphp

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-fw {{ $movement->type->icon() }}"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Cantidad</span>
                                        <span class="info-box-number">
                                            {{ number_format($movement->quantity, 3) }}
                                            {{ $material->unit->symbol }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Fecha efectiva</span>
                                        <span class="info-box-number">
                                            {{ $movement->effective_at->format('d/m/Y - h:i a') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 1° Almacén: responde "¿dónde ocurrió el movimiento?" --}}
                        <h2 class="h5">
                            Almacén
                            @can('warehouses.edit')
                                <a href="{{ route('warehouses.show', $warehouse->id) }}" target="_blank">
                                    <i class="fas fa-fw fa-arrow-up-right-from-square"></i>
                                </a>
                            @endcan
                        </h2>

                        <div class="card mb-3">
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-4 text-muted">Nombre</dt>
                                    <dd class="col-sm-8">{{ $warehouse->mediumText('name') }}</dd>

                                    <dt class="col-sm-4 text-muted">Descripción</dt>
                                    <dd class="col-sm-8 text-muted mb-0">
                                        {{ $warehouse->longText('description', 'Sin descripción') }}
                                    </dd>
                                </dl>
                            </div>
                        </div>

                        {{-- 2° Material: responde "¿qué material se movió?" --}}
                        <h2 class="h5">
                            Material
                            @can('raw-materials.view')
                                <a href="{{ route('raw-materials.show', $material->id) }}" target="_blank">
                                    <i class="fas fa-fw fa-arrow-up-right-from-square"></i>
                                </a>
                            @endcan
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
                                    <dd class="col-sm-8 mb-0">
                                        {{ $material->category->mediumText('name') }}
                                    </dd>
                                </dl>
                            </div>
                        </div>

                        {{-- 3° Lote: trazabilidad de la mercancía afectada --}}
                        <h2 class="h5">
                            Lote
                            @can('raw-material-batches.view')
                                <a href="{{ route('raw-material-batches.show', $batch->id) }}" target="_blank">
                                    <i class="fas fa-fw fa-arrow-up-right-from-square"></i>
                                </a>
                            @endcan
                        </h2>

                        <div class="card mb-3">
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-4 text-muted">Código</dt>
                                    <dd class="col-sm-8">{{ $batch->code }}</dd>

                                    <dt class="col-sm-4 text-muted">Recibido el</dt>
                                    <dd class="col-sm-8">{{ $batch->received_at->format('d/m/Y') }}</dd>

                                    <dt class="col-sm-4 text-muted">Cantidad recibida</dt>
                                    <dd class="col-sm-8">
                                        {{ number_format($batch->received_quantity, 3) }}
                                        {{ $material->unit->symbol }}
                                    </dd>

                                    <dt class="col-sm-4 text-muted">Costo unitario</dt>
                                    <dd class="col-sm-8">
                                        $ {{ number_format($batch->received_unit_cost, 2) }}
                                        MXN / {{ $material->unit->symbol }}
                                    </dd>

                                    <dt class="col-sm-4 text-muted">Costo total recibido</dt>
                                    <dd class="col-sm-8">
                                        $ {{ number_format($batch->received_total_cost, 2) }}
                                        MXN
                                    </dd>

                                    <dt class="col-sm-4 text-muted">
                                        {{ $batch->isExpired() ? 'Vencido el' : 'Vence el' }}
                                    </dt>
                                    <dd
                                        class="col-sm-8 mb-0 {{ $batch->isExpired() ? 'text-danger font-weight-bold' : '' }}">
                                        {{ $batch->expiration_date?->format('d/m/Y') ?? '--/--/----' }}
                                    </dd>
                                </dl>
                            </div>
                        </div>

                        {{-- 4° Documento origen: referencia de trazabilidad --}}
                        <h2 class="h5">
                            Documento origen
                            @can('raw-material-documents.view')
                                <a href="{{ $doc->getRoute('show') }}" target="_blank">
                                    <i class="fas fa-fw fa-arrow-up-right-from-square"></i>
                                </a>
                            @endcan
                        </h2>

                        <div class="card mb-0">
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-4 text-muted">Referencia</dt>
                                    <dd class="col-sm-8 font-weight-bold mb-0">
                                        {{ $doc->reference_number ?? "#{$doc->id}" }}
                                        @if ($doc->reference_type)
                                            <small class="text-muted">({{ $doc->reference_type }})</small>
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="modal-body">
                        <p class="text-muted text-center py-3">No hay información del movimiento.</p>
                    </div>
                @endif

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        Livewire.on('showModalRawMaterialMovementShow', function() {
            $('#modalRawMaterialMovementShow').modal('show');
        });

        Livewire.on('hideModalRawMaterialMovementShow', function() {
            $('#modalRawMaterialMovementShow').modal('hide');
        });
    </script>
@endpush
