<div class="card">
    <div class="card-header">
        <div class="row align-items-end">
            <x-form.select-wire-ignore fgroup-class="col-md-4 mb-md-0" igroup-size="sm" label-class="text-muted mb-0"
                name="rawMaterialId" label="Materia prima *" wire:loading.attr="readonly" wire:target="save,addLine" />

            <x-form.select-wire-ignore fgroup-class="col-md-4 mb-md-0" igroup-size="sm" label-class="text-muted mb-0"
                name="warehouseId" label="Almacén *" wire:loading.attr="readonly" wire:target="save,addLine" />

            <div class="col-md-4">
                <x-livewire.loading-button label="Agregar lote" icon="plus" wire:click="addLine"
                    wire:target="addLine" class="btn-block btn-sm" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover mb-0">
                <thead class="thead-dark text-nowrap border-top-0">
                    <tr>
                        <th style="min-width:150px" class="pl-4">Materia prima</th>
                        <th style="min-width:150px">Almacén</th>
                        <th style="min-width:150px">Lote externo</th>
                        <th style="min-width:140px">Vencimiento</th>
                        <th style="min-width:130px">Cantidad</th>
                        <th style="min-width:145px">Costo unit. MXN</th>
                        <th style="min-width:100px">Total MXN</th>
                        <th style="width:40px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lines as $index => $line)
                        <tr wire:key="line-{{ $index }}">
                            <td class="align-middle small pl-4">{{ $line['raw_material_name'] }}</td>
                            <td class="align-middle text-muted small">{{ $line['warehouse_name'] }}</td>
                            <td class="align-top p-2">
                                <x-adminlte-input type="text" name="lines.{{ $index }}.external_batch_code"
                                    placeholder="Código externo" maxlength="128"
                                    wire:model="lines.{{ $index }}.external_batch_code" igroup-size="sm"
                                    fgroup-class="mb-0" />
                            </td>
                            <td class="align-top p-2">
                                <x-adminlte-input type="date" name="lines.{{ $index }}.expiration_date"
                                    wire:model="lines.{{ $index }}.expiration_date" igroup-size="sm"
                                    fgroup-class="mb-0" />
                            </td>
                            <td class="align-top p-2">
                                <x-adminlte-input type="number" name="lines.{{ $index }}.received_quantity"
                                    placeholder="0" step="0.001" min="0.001"
                                    wire:model.live.debounce.500ms="lines.{{ $index }}.received_quantity"
                                    igroup-size="sm" fgroup-class="mb-0" required>
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text" title="{{ $line['unit_name'] }}">
                                            {{ $line['unit_symbol'] }}
                                        </div>
                                    </x-slot>
                                </x-adminlte-input>
                            </td>
                            <td class="align-top p-2">
                                <x-adminlte-input type="number" name="lines.{{ $index }}.received_unit_cost"
                                    placeholder="0.00" step="0.01" min="0"
                                    wire:model.live.debounce.500ms="lines.{{ $index }}.received_unit_cost"
                                    igroup-size="sm" fgroup-class="mb-0" required>
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text">$</div>
                                    </x-slot>
                                </x-adminlte-input>
                            </td>
                            <td class="align-middle">
                                $ {{ number_format($line['received_total_cost'], 2) }}
                            </td>
                            <td class="align-middle">
                                <x-livewire.loading-button theme="outline-danger" class="btn-sm" icon="trash-alt"
                                    title="Eliminar lote" wire:click="removeLine({{ $index }})"
                                    wire:target="removeLine({{ $index }})" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                No hay lotes agregados
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if (!empty($lines))
                    <tfoot>
                        <tr>
                            <td colspan="6" class="font-weight-bold text-muted">
                                Total MXN
                            </td>
                            <td class="font-weight-bold">
                                $ {{ number_format($total_cost, 2) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
