<div class="card">
    <div class="card-body py-3">
        <div class="row">
            <div class="col-md-4">
                <x-livewire.loading-button label="Seleccionar origen" icon="magnifying-glass"
                    wire:click="$dispatch('openStockSelector')" wire:target="setLine" class="btn-block" />
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body py-3">
        <div @if (!$stock_origin_id) style="display:none" @endif>
            <div class="row">
                <div class="col">
                    <strong class="mb-0 text-dark">{{ $raw_material_name }}</strong>
                    <div class="text-muted">{{ $batch_code }}</div>
                </div>
            </div>

            <hr class="my-2">

            <div class="text-muted">
                <dl class="mb-0">
                    <dt>Almacén de origen:</dt>
                    <dd class="mb-0">{{ $warehouse_name }}</dd>
                </dl>
            </div>

            <hr class="my-2">

            <div class="row">
                <div class="col-md-9">
                    <x-form.select-wire-ignore igroup-size="sm" label-class="text-muted mb-0" label="Almacén de destino"
                        name="warehouse_dest_id" wire:loading.attr="readonly" wire:target="setLine" />
                </div>

                <div class="col-md-3">
                    <x-adminlte-input type="number" name="quantity"
                        label="Cantidad * (En stock: {{ number_format($current_quantity, 3) }})" placeholder="0"
                        step="0.001" min="0.001" max="{{ $current_quantity }}"
                        wire:model.live.debounce.500ms="quantity" igroup-size="sm"
                        label-class="{{ $invalidQuantity ? 'text-danger' : 'text-muted' }} mb-0" required>
                        <x-slot name="appendSlot">
                            <div class="input-group-text" title="{{ $unit_name }}">
                                {{ $unit_symbol }}
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>
        </div>

        <div @if ($stock_origin_id) style="display:none" @endif>
            <div class="text-center text-muted py-4">
                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                No hay stock de origen seleccionado
            </div>
        </div>
    </div>
</div>
