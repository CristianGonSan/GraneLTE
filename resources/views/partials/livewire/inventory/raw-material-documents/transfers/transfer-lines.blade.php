<div class="card">
    <div class="card-header">
        <div class="form-row align-items-end">
            <div class="col-md-4">
                <div class="form-group mb-0">
                    <label class="text-muted mb-0">Stock de origen</label>
                    <x-livewire.loading-button label="Seleccionar existencias" icon="magnifying-glass"
                        wire:click="$dispatch('openStockSelector')" wire:target="setLine" class="btn-block" />
                </div>
            </div>

            <div class="col-md-4">
                <x-form.select-wire-ignore label-class="text-muted mb-0" label="Almacén de destino *"
                    name="warehouse_dest_id" wire:loading.attr="readonly" wire:target="setLine" fgroup-class="mb-0" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="text-nowrap border-top-0">
                    <tr>
                        <th style="min-width: 220px">Materia prima</th>
                        <th style="min-width: 230px">Origen</th>
                        <th style="min-width: 200px; width: 200px;">Cantidad a mover</th>
                        <th style="width: 160px">Total MXN</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($stock_origin_id)
                        <tr>
                            <td class="align-middle">
                                {{ $raw_material_name }}
                            </td>
                            <td class="align-middle">
                                <div class="text-nowrap">{{ $batch_code }}</div>
                                <small class="text-muted">{{ $warehouse_name }}</small>
                            </td>
                            <td class="align-middle p-2">
                                <x-adminlte-input type="number" name="quantity" placeholder="0" step="0.001"
                                    min="0.001" max="{{ $current_quantity }}"
                                    wire:model.live.debounce.500ms="quantity" igroup-size="sm" fgroup-class="mb-0"
                                    required label-class="{{ $invalid_quantity ? 'text-danger' : 'text-muted' }} mb-0">
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text" title="{{ $unit_name }}">
                                            {{ $unit_symbol }}
                                        </div>
                                    </x-slot>
                                </x-adminlte-input>
                                <small class="text-nowrap {{ $invalid_quantity ? 'text-danger' : 'text-muted' }}">
                                    En stock: {{ number_format($current_quantity, 3) }}
                                </small>
                            </td>
                            <td class="text-nowrap align-middle">
                                <div>$ {{ number_format($total_cost, 2) }}</div>
                                <small class="text-muted">$ {{ number_format($unit_cost, 2) }} c/u</small>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                No hay un stock de origen seleccionado
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
