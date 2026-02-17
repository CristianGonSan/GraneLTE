<div x-data="{ open: @entangle('showModal') }">
    <template x-if="open">
        <div>
            <div class="modal-backdrop show"></div>
            <div class="modal show d-block" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered" x-on:click.outside="open = false">
                    <div class="modal-content">

                        <div class="modal-header">
                            <div class="modal-title">
                                @if ($movement)
                                    <h5 class="mb-1">{{ $movement->type->label() }}</h5>
                                    <div class="text-muted">
                                        {{ $movement->effective_at->format('d/m/Y - h:i a') }}
                                    </div>
                                @else
                                    <h5 class="mb-0">Detalle de movimiento</h5>
                                @endif
                            </div>
                            <button type="button" class="close" x-on:click="open = false">
                                <span>&times;</span>
                            </button>
                        </div>

                        @if ($movement)
                            @php
                                $batch = $movement->batch;
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
                                        <dt>Cantidad</dt>
                                        <dd class="mb-0">
                                            {{ number_format($movement->quantity, 3) }}
                                            {{ $material->unit->symbol }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="modal-body border-bottom">
                                <dl class="row mb-0">
                                    <div class="col-6 mb-2">
                                        <dt>Almacén</dt>
                                        <dd class="mb-0">
                                            <a href="{{ route('warehouses.edit', $movement->warehouse_id) }}"
                                                target="_blank">
                                                {{ $movement->warehouse->name }}
                                            </a>
                                        </dd>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <dt>Lote</dt>
                                        <dd class="mb-0">{{ $batch->code() }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="modal-body">
                                <dl class="row mb-0">
                                    <div class="col-12">
                                        <dt>Documento</dt>
                                        <dd class="mb-0">
                                            <a href="{{ $movement->document->getRoute('show') }}" target="_blank">
                                                {{ $movement->document->reference_number ?? "#{$movement->document_id}" }}
                                            </a>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        @else
                            <div class="modal-body">
                                <p class="text-muted text-center py-3">No hay información del movimiento.</p>
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
