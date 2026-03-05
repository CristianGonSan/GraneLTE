<div x-data="{ open: @entangle('showModal') }">
    <template x-if="open">
        <div>
            <div class="modal-backdrop show"></div>
            <div class="modal show d-block" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg" x-on:click.outside="open = false">
                    <div class="modal-content">

                        {{-- Header --}}
                        <div class="modal-header py-2">
                            <h6 class="modal-title font-weight-bold mb-0">
                                <i class="fas fa-boxes mr-2 text-muted"></i>Seleccionar existencias
                            </h6>
                            <button type="button" class="close" x-on:click="open = false">
                                <span>&times;</span>
                            </button>
                        </div>

                        {{-- Filtros --}}
                        <div class="modal-body border-bottom py-2">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <input type="text" class="form-control"
                                        placeholder="Buscar por material, lote o almacén..."
                                        wire:model.live.debounce.400ms="searchTerm">
                                </div>
                                <div class="col-md-4">
                                    <select class="custom-select" wire:model.live="order">
                                        <option value="fifo">FIFO — Primero en entrar</option>
                                        <option value="fefo">FEFO — Primero en caducar</option>
                                        <option value="lifo">LIFO — Último en entrar</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Lista --}}
                        <div class="modal-body p-0" style="max-height: 420px; overflow-y: auto;">
                            @if ($batches->isEmpty())
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                    No se encontraron existencias{{ $searchTerm ? ' para "' . $searchTerm . '"' : '.' }}
                                </div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach ($batches as $batch)
                                        @foreach ($batch->stocks as $stock)
                                            @php
                                                $isExpired = $batch->isExpired();
                                            @endphp
                                            <div class="list-group-item list-group-item-action d-flex align-items-center cursor-pointer"
                                                wire:click="$dispatch('selectedStock', { id: {{ $stock->id }} })"
                                                @if ($closeAfterSeleted) x-on:click="open = false" @endif>

                                                {{-- Indicador de estado --}}
                                                <div class="flex-shrink-0 text-center mr-3" style="width: 28px;">
                                                    @if ($isExpired)
                                                        <i class="fas fa-exclamation-circle fa-lg text-danger"
                                                            title="Caducado"></i>
                                                    @else
                                                        <i class="fas fa-check-circle fa-lg text-success"></i>
                                                    @endif
                                                </div>

                                                {{-- Cuerpo del ítem --}}
                                                <div class="flex-grow-1 mr-3" style="min-width: 0;">

                                                    {{-- Material --}}
                                                    <div class="font-weight-bold text-truncate">
                                                        {{ $batch->material->name }}
                                                    </div>

                                                    {{-- Lote + fechas --}}
                                                    <div class="d-flex flex-wrap align-items-center mt-1">
                                                        <span class="badge badge-secondary font-weight-normal mr-1">
                                                            {{ $batch->code }}
                                                        </span>
                                                        @if ($batch->received_at)
                                                            <small class="text-muted mr-2">
                                                                <i
                                                                    class="fas fa-sign-in-alt mr-1"></i>{{ $batch->received_at->format('d/m/Y') }}
                                                            </small>
                                                        @endif
                                                        @if ($batch->expiration_date)
                                                            <small
                                                                class="{{ $isExpired ? 'text-danger' : 'text-muted' }}">
                                                                <i
                                                                    class="fas fa-calendar-times mr-1"></i>{{ $batch->expiration_date->format('d/m/Y') }}
                                                            </small>
                                                        @endif
                                                    </div>

                                                    {{-- Almacén --}}
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            <i
                                                                class="fas fa-warehouse mr-1"></i>{{ $stock->warehouse->name }}
                                                        </small>
                                                    </div>
                                                </div>

                                                {{-- Cantidad --}}
                                                <div class="flex-shrink-0 text-right mr-3" style="min-width: 72px;">
                                                    <span class="font-weight-bold h6 mb-0">
                                                        {{ number_format($stock->current_quantity, 2) }}
                                                    </span>
                                                    <small class="text-muted d-block">
                                                        {{ $batch->material->unit->symbol }}
                                                    </small>
                                                </div>

                                                {{-- Chevron --}}
                                                <div class="flex-shrink-0 text-muted">
                                                    <i class="fas fa-chevron-right fa-xs"></i>
                                                </div>

                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="modal-footer pb-0">
                            <div class="w-100">
                                {{ $batches->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
