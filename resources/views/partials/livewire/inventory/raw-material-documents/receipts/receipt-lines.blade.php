<div class="card">
    <div class="card-header">
        <div class="form-row align-items-end">
            <x-form.select-wire-ignore fgroup-class="col-md-4 mb-0" label-class="text-muted mb-0"
                name="rawMaterialId" label="Materia prima *" wire:loading.attr="readonly" wire:target="save,addLine" />

            <x-form.select-wire-ignore fgroup-class="col-md-4 mb-0" label-class="text-muted mb-0"
                name="warehouseId" label="Almacén *" wire:loading.attr="readonly" wire:target="save,addLine" />

            <div class="col-md-4">
                <x-livewire.loading-button label="Agregar lote" icon="plus" wire:click="addLine"
                    wire:target="addLine" class="btn-block mt-2" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="text-nowrap border-top-0">
                    <tr>
                        <th style="min-width: 220px">Ítem / Destino</th>
                        <th style="min-width: 180px">Lote Externo</th>
                        <th style="min-width: 160px">Vencimiento</th>
                        <th style="min-width: 160px; width: 160px;">Cantidad *</th>
                        <th style="min-width: 160px; width: 160px;">Costo Unit. *</th>
                        <th style="min-width: 120px">Subtotal</th>
                        <th class="text-center" style="width: 1%">Quitar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lines as $index => $line)
                        <tr wire:key="line-{{ $index }}">
                            <td class="align-middle">
                                <div>{{ $line['raw_material_name'] }}</div>
                                <small class="text-muted">{{ $line['warehouse_name'] }}</small>
                            </td>
                            <td class="align-middle p-2">
                                <x-adminlte-input name="lines.{{ $index }}.external_batch_code"
                                    placeholder="Código" maxlength="128"
                                    wire:model="lines.{{ $index }}.external_batch_code" igroup-size="sm"
                                    fgroup-class="mb-0" />
                            </td>
                            <td class="align-middle p-2">
                                <x-adminlte-input type="date" name="lines.{{ $index }}.expiration_date"
                                    wire:model="lines.{{ $index }}.expiration_date" igroup-size="sm"
                                    fgroup-class="mb-0" />
                            </td>
                            <td class="align-middle p-2">
                                <x-adminlte-input type="number" name="lines.{{ $index }}.received_quantity"
                                    placeholder="0" step="0.001" min="0.001"
                                    wire:model.live.debounce.500ms="lines.{{ $index }}.received_quantity"
                                    igroup-size="sm" fgroup-class="mb-0" required>
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text">{{ $line['unit_symbol'] }}</div>
                                    </x-slot>
                                </x-adminlte-input>
                            </td>
                            <td class="align-middle p-2">
                                <x-adminlte-input type="number" name="lines.{{ $index }}.received_unit_cost"
                                    placeholder="0.00" step="0.01" min="0"
                                    wire:model.live.debounce.500ms="lines.{{ $index }}.received_unit_cost"
                                    igroup-size="sm" fgroup-class="mb-0" required>
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text">$</div>
                                    </x-slot>
                                </x-adminlte-input>
                            </td>
                            <td class="text-nowrap align-middle">
                                $ {{ number_format($line['received_total_cost'], 2) }}
                            </td>
                            <td class="align-middle text-center">
                                <x-livewire.loading-button theme="outline-danger" class="btn-sm" icon="trash-alt"
                                    title="Eliminar lote" wire:click="removeLine({{ $index }})"
                                    wire:target="removeLine({{ $index }})" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                                Agregue materia prima y almacén para comenzar
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if (!empty($lines))
                    <tfoot>
                        <tr>
                            <td colspan="5" class="font-weight-bold text-muted">
                                Total MXN
                            </td>
                            <td colspan="2" class="font-weight-bold">
                                $ {{ number_format($total_cost, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
