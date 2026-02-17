<div x-data="{ open: @entangle('showModal') }">
    <template x-if="open">
        <div>
            <div class="modal-backdrop show"></div>
            <div class="modal show d-block" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered" x-on:click.outside="open = false">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Detalle de stock</h5>
                            <button type="button" class="close" x-on:click="open = false">
                                <span>&times;</span>
                            </button>
                        </div>

                        @if ($stock)
                            @php
                                $batch = $stock->batch;
                                $material = $batch->material;
                            @endphp

                            <div class="modal-body bg-light border-bottom">
                                <dt>Material</dt>
                                <dd>
                                    <a href="{{ route('raw-materials.edit', $material->id) }}" target="_blank">
                                        {{ $material->name }}
                                    </a>
                                </dd>
                                <dl class="row mb-0">
                                    <div class="col-6">
                                        <dt>Cantidad actual</dt>
                                        <dd class="mb-0">
                                            {{ number_format($stock->current_quantity, 3) }}
                                            {{ $material->unit->symbol }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="modal-body border-bottom">
                                <div class="col-6 mb-2">
                                    <dt>Almacén</dt>
                                    <dd class="mb-0">
                                        <a href="{{ route('warehouses.edit', $stock->warehouse_id) }}" target="_blank">
                                            {{ $stock->warehouse->name }}
                                        </a>
                                    </dd>
                                </div>
                                <dl class="row mb-0">
                                    <div class="col-6 mb-2">
                                        <dt>Lote</dt>
                                        <dd class="mb-0">{{ $batch->code() }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="modal-body">
                                <dl class="row mb-0">
                                    <div class="col-6 mb-2">
                                        <dt>Recibido el</dt>
                                        <dd class="mb-0">{{ $batch->received_at->format('d/m/Y') }}</dd>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <dt>Expiración</dt>
                                        <dd class="mb-0">
                                            {{ $batch->expiration_date?->format('d/m/Y') ?? '--/--/----' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        @else
                            <div class="modal-body">
                                <p class="text-muted text-center py-3">No hay información del stock.</p>
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
