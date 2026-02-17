<div x-data="{ open: @entangle('showModal') }">
    <template x-if="open">
        <div>
            <div class="modal-backdrop show"></div>
            <div class="modal show d-block" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered" x-on:click.outside="open = false">
                    <div class="modal-content">

                        <div class="modal-header">
                            <div class="modal-title">{{ $batch?->code() ?? 'Detalle de lote' }}</div>
                            <button type="button" class="close" x-on:click="open = false">
                                <span>&times;</span>
                            </button>
                        </div>

                        @if ($batch)
                            <div class="modal-body bg-light border-bottom">
                                <dt>Material</dt>
                                <dd>
                                    <a href="{{ route('raw-materials.edit', $batch->material_id) }}" target="_blank">
                                        {{ $batch->material->name }}
                                    </a>
                                </dd>

                                <dl class="row mb-0">
                                    <div class="col-6">
                                        <dt>Disponible</dt>
                                        <dd class="mb-0">
                                            {{ number_format($batch->current_quantity, 3) }}
                                            {{ $batch->material->unit->symbol }}
                                        </dd>
                                    </div>
                                    <div class="col-6">
                                        <dt>Valor</dt>
                                        <dd class="mb-0">{{ number_format($batch->currentCost(), 2) }} MXN</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="modal-body">
                                <dl class="row mb-0">
                                    <div class="col-12 mb-2">
                                        <dt>Proveedor</dt>
                                        <dd class="mb-0">
                                            <a href="{{ route('suppliers.edit', $batch->supplier_id) }}"
                                                target="_blank">
                                                {{ $batch->supplier->name }}
                                            </a>
                                        </dd>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <dt>Recepción</dt>
                                        <dd class="mb-0">{{ $batch->received_at->format('d/m/Y') }}</dd>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <dt>Expiración</dt>
                                        <dd class="mb-0">
                                            {{ $batch->expiration_date?->format('d/m/Y') ?? '--/--/----' }}
                                        </dd>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <dt>Cantidad recibida</dt>
                                        <dd class="mb-0">
                                            {{ number_format($batch->received_quantity, 3) }}
                                            {{ $batch->material->unit->symbol }}
                                        </dd>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <dt>Costo unitario</dt>
                                        <dd class="mb-0">
                                            {{ number_format($batch->received_unit_cost, 2) }} MXN /
                                            {{ $batch->material->unit->symbol }}
                                        </dd>
                                    </div>
                                    <div class="col-12">
                                        <dt>Costo total</dt>
                                        <dd class="mb-0">{{ number_format($batch->received_total_cost, 2) }} MXN</dd>
                                    </div>
                                </dl>
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
