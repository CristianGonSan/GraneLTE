<div x-data="{ open: @entangle('showModal') }">
    <template x-if="open">
        <div>
            <div class="modal-backdrop show"></div>
            <div class="modal show d-block" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
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
                            <div class="form-row mb-1">
                                <div class="col-md-12">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="search_input" class="form-control"
                                            placeholder="Buscar por material, almacén o lote..."
                                            wire:keydown.enter="search" wire:loading.attr="readonly"
                                            wire:target="search" wire:model="searchTerm">

                                        <div class="input-group-append">
                                            @if (filled($this->searchTerm))
                                                <button class="btn btn-outline-secondary" type="button"
                                                    wire:click="clearSearch" wire:loading.attr="disabled"
                                                    wire:target="search,clearSearch" title="Limpiar búsqueda">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif

                                            <x-livewire.loading-button icon="magnifying-glass" wire:click="search" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 col-6 mb-0">
                                    <label class="text-muted small mb-0">En stock (mín.)</label>
                                    <input type="number" name="quantity_min" class="form-control form-control-sm"
                                        min="0" step="0.001" placeholder="-∞"
                                        wire:model.live.debounce.600ms="filters.quantityMin" />
                                </div>

                                <div class="form-group col-md-2 col-6 mb-0">
                                    <label class="text-muted small mb-0">En stock (máx.)</label>
                                    <input type="number" name="quantity_max" class="form-control form-control-sm"
                                        min="0" step="0.001" placeholder="∞"
                                        wire:model.live.debounce.600ms="filters.quantityMax" />
                                </div>

                                <div class="form-group col-md-4 col-6 mb-0">
                                    <label class="text-muted small mb-0">Caducidad</label>
                                    <div class="input-group input-group-sm">
                                        <select name="expiration_filter" class="custom-select"
                                            wire:model.live="filters.expirationFilter">
                                            <option value="all">Todos</option>
                                            <option value="expiring">Por caducar</option>
                                            <option value="not_expired">No caducados</option>
                                            <option value="expired">Caducados</option>
                                            <option value="non_perishable">Imperecederos</option>
                                        </select>

                                        @if ($filters['expirationFilter'] === 'expiring')
                                            <input type="number" name="expiration_days" class="form-control"
                                                min="1" step="1"
                                                wire:model.live.debounce.600ms="filters.expirationDays"
                                                title="Días hasta caducidad" />
                                            <div class="input-group-append">
                                                <span class="input-group-text">días</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group input-group-sm col-md-4 col-6 mb-0">
                                    <label class="text-muted small mb-0">Ordenamiento</label>
                                    <select name="order_filter" class="custom-select" wire:model.live="filters.order">
                                        <option value="fefo">FEFO — Primero en caducar</option>
                                        <option value="fifo">FIFO — Primero en entrar</option>
                                        <option value="lifo">LIFO — Último en entrar</option>
                                        <option value="stock">Mayor stock</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Lista --}}
                        <div class="modal-body p-0" style="max-height: 420px; overflow-y: auto;">
                            @if ($stocks->isEmpty())
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                    No se encontraron existencias
                                    {{ $searchTerm ? "para {$searchTerm}" : '.' }}
                                </div>
                            @else
                                <div class="list-group list-group-flush pt-1 pl-1">
                                    @foreach ($stocks as $stock)
                                        @php
                                            $batch = $stock->batch;
                                            $material = $batch->material;
                                            $isExpired = $batch->isExpired();
                                            $qty = $stock->current_quantity;
                                            $quantityClass = 'text-success';

                                            if ($qty < 0) {
                                                $statusColor = '#dc3545';
                                                $quantityClass = 'text-danger';
                                            } elseif ($qty == 0) {
                                                $statusColor = '#adb5bd';
                                                $quantityClass = 'text-muted';
                                            } elseif ($isExpired) {
                                                $statusColor = '#dc3545';
                                            } else {
                                                $statusColor = '#28a745';
                                            }
                                        @endphp

                                        <div class="list-group-item list-group-item-action mb-2 rounded shadow-sm cursor-pointer"
                                            style="border-left: 6px solid {{ $statusColor }} !important;"
                                            wire:click="$dispatch('selectedStock', { id: {{ $stock->id }} })"
                                            @if ($closeAfterSelected) x-on:click="open = false" @endif>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="border-right"
                                                    style="flex: 1; min-width: 0; border-right-color: #dee2e6 !important;">
                                                    <div class="mb-1">
                                                        <strong>{{ $material->shortText('name') }}</strong>
                                                    </div>
                                                    <div class="text-muted small">
                                                        <i class="fas fa-fw fa-box mr-1"></i>
                                                        {{ $batch->code }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        <i class="fas fa-fw fa-warehouse mr-1"></i>
                                                        {{ $stock->warehouse->shortText('name') }}
                                                    </div>
                                                </div>

                                                <div class="text-right" style="min-width: 140px;">
                                                    <div>
                                                        <strong class="{{ $quantityClass }}"
                                                            style="font-size: 1.1rem;">
                                                            {{ number_format($qty, 3) }}
                                                        </strong>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted">{{ $material->unit->symbol }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row no-gutters mt-2 pt-2 border-top">
                                                <div class="col-6">
                                                    <small class="text-muted">
                                                        <i class="far fa-calendar-alt mr-1"></i>
                                                        Recibido:
                                                        <strong>{{ $batch->received_at->format('d/m/Y') }}</strong>
                                                    </small>
                                                </div>
                                                <div class="col-6 text-right">
                                                    @if ($batch->expiration_date)
                                                        <small
                                                            class="d-inline-block {{ $isExpired ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                                            <i
                                                                class="fas {{ $isExpired ? 'fa-exclamation-circle' : 'fa-hourglass-half' }} mr-1"></i>
                                                            {{ $isExpired ? 'Venció hace' : 'Vence en' }}
                                                            <strong>{{ $batch->expiration_date->diffForHumans(null, true) }}</strong>
                                                        </small>
                                                    @else
                                                        <small class="text-muted italic">Sin vencimiento</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="modal-footer py-1">
                            <div class="w-100">
                                {{ $stocks->links('vendor.livewire.bootstrap-sm') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
