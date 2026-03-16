<div class="card">
    <div class="card-header">
        <div class="row align-items-end">
            <div class="col-md-4">
                <x-livewire.loading-button label="Seleccionar origen" icon="magnifying-glass"
                    wire:click="$dispatch('openStockSelector')" wire:target="setLine" class="btn-block btn-sm" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover mb-0">
                <thead class="thead-dark text-nowrap border-top-0">
                    <tr>
                        <th style="min-width:150px" class="pl-4">Materia prima</th>
                        <th style="min-width:130px">Lote</th>
                        <th style="min-width:150px">Almacén origen</th>
                        <th style="min-width:200px">Almacén destino</th>
                        <th style="min-width:180px">Cantidad</th>
                        <th style="min-width:145px">Costo unit. MXN</th>
                        <th style="min-width:145px">Total MXN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="align-middle small pl-4">
                            {{ $raw_material_name ?? '—' }}
                        </td>
                        <td class="align-middle text-muted small">
                            {{ $batch_code ?? '—' }}
                        </td>
                        <td class="align-middle text-muted small">
                            {{ $warehouse_name ?? '—' }}
                        </td>
                        <td class="align-top p-2">
                            <div @if (!$stock_origin_id) style="display:none" @endif>
                                <x-form.select-wire-ignore igroup-size="sm" label-class="d-none"
                                    label="Almacén de destino" name="warehouse_dest_id" wire:loading.attr="readonly"
                                    wire:target="setLine" />
                            </div>
                        </td>
                        <td class="align-top p-2">
                            <div @if (!$stock_origin_id) style="display:none" @endif>
                                <x-adminlte-input type="number" name="quantity" placeholder="0" step="0.001"
                                    min="0.001" max="{{ $current_quantity ?? 0 }}"
                                    wire:model.live.debounce.500ms="quantity" igroup-size="sm" fgroup-class="mb-0"
                                    required label-class="{{ $invalid_quantity ? 'text-danger' : 'text-muted' }} mb-0">
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text" title="{{ $unit_name }}">
                                            {{ $unit_symbol }}
                                        </div>
                                    </x-slot>
                                </x-adminlte-input>
                                <small class="{{ $invalid_quantity ? 'text-danger' : 'text-muted' }}">
                                    En stock: {{ number_format($current_quantity ?? 0, 3) }}
                                </small>
                            </div>
                        </td>
                        <td class="align-middle">
                            $ {{ number_format($unit_cost ?? 0, 2) }}
                        </td>
                        <td class="align-middle font-weight-bold">
                            $ {{ number_format($total_cost ?? 0, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
