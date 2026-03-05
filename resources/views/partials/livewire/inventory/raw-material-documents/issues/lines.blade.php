<div class="card">
    <div class="card-body py-3">
        <div class="row">
            <div class="col-md-4">
                <x-livewire.loading-button label="Seleccionar existencias" icon="magnifying-glass"
                    wire:click="$dispatch('openStockSelector')" wire:target="addLine" class="btn-block" />
            </div>
        </div>
    </div>
</div>

@forelse ($lines as $index => $line)
    <div class="card" wire:key="line-{{ $index }}">
        <div class="card-body py-3">
            <div class="row">
                <div class="col">
                    <strong class="mb-0 text-dark">{{ $line['raw_material_name'] }}</strong>
                    <div class="text-muted">{{ $line['batch_code'] }}</div>
                </div>
                <div class="col-auto">
                    <x-livewire.loading-button theme="outline-danger" class="btn-sm" icon="trash-alt"
                        title="Eliminar línea" wire:click="removeLine('{{ $index }}')"
                        wire:target="removeLine('{{ $index }}')" />
                </div>
            </div>

            <hr class="my-2">

            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <x-adminlte-input type="number" name="lines.{{ $index }}.quantity"
                        label="Cantidad * (En stock: {{ number_format($line['current_quantity'], 3) }})" placeholder="0"
                        step="0.001" min="0.001" max="{{ $line['current_quantity'] }}"
                        wire:model.live.debounce.500ms="lines.{{ $index }}.quantity" igroup-size="sm"
                        label-class="{{ $line['invalidQuantity'] ? 'text-danger' : 'text-muted' }} mb-0" required>
                        <x-slot name="appendSlot">
                            <div class="input-group-text" title="{{ $line['unit_name'] }}">
                                {{ $line['unit_symbol'] }}
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <label class="text-muted mb-0">Costo unitario MXN</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <div class="input-group-text">$</div>
                            </div>
                            <input type="text" name="lines.{{ $index }}.total" class="form-control"
                                value="{{ number_format($line['unit_cost'], 2) }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <label class="text-muted mb-0">Total MXN</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <div class="input-group-text">$</div>
                            </div>
                            <input type="text" name="lines.{{ $index }}.total" class="form-control"
                                value="{{ number_format($line['total_cost'], 2) }}" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-2">

            <div class="text-muted">
                {{ $line['warehouse_name'] }}
            </div>
        </div>
    </div>
@empty
    <div class="card">
        <div class="card-body">
            <div class="text-center text-muted py-4">
                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                No hay stocks seleccionados
            </div>
        </div>
    </div>
@endforelse

<div class="card">
    <div class="card-body py-2">
        <div class="d-flex justify-content-between align-items-center">
            <strong class="text-muted">Total MXN</strong>
            <strong>$ {{ number_format($total_cost, 2) }}</strong>
        </div>
    </div>
</div>
