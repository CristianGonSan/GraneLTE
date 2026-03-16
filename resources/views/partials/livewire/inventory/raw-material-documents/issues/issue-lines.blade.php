<div class="card">
    <div class="card-header">
        <div class="row align-items-end">
            <div class="col-md-4">
                <x-livewire.loading-button label="Seleccionar existencias" icon="magnifying-glass"
                    wire:click="$dispatch('openStockSelector')" wire:target="addLine" class="btn-block btn-sm" />
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
                        <th style="min-width:150px">Almacén</th>
                        <th style="min-width:180px">Cantidad</th>
                        <th style="min-width:145px">Costo unit. MXN</th>
                        <th style="min-width:100px">Total MXN</th>
                        <th style="width:40px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lines as $index => $line)
                        <tr wire:key="line-{{ $index }}">
                            <td class="align-middle small pl-4">{{ $line['raw_material_name'] }}</td>
                            <td class="align-middle text-muted small">{{ $line['batch_code'] }}</td>
                            <td class="align-middle text-muted small">{{ $line['warehouse_name'] }}</td>
                            <td class="align-top p-2">
                                <x-adminlte-input type="number" name="lines.{{ $index }}.quantity"
                                    placeholder="0" step="0.001" min="0.001" max="{{ $line['current_quantity'] }}"
                                    wire:model.live.debounce.500ms="lines.{{ $index }}.quantity" igroup-size="sm"
                                    fgroup-class="mb-0" required
                                    label-class="{{ $line['invalid_quantity'] ? 'text-danger' : 'text-muted' }} mb-0">
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text" title="{{ $line['unit_name'] }}">
                                            {{ $line['unit_symbol'] }}
                                        </div>
                                    </x-slot>
                                </x-adminlte-input>
                                <small class="{{ $line['invalid_quantity'] ? 'text-danger' : 'text-muted' }}">
                                    En stock: {{ number_format($line['current_quantity'], 3) }}
                                </small>
                            </td>
                            <td class="align-middle">
                                $ {{ number_format($line['unit_cost'], 2) }}
                            </td>
                            <td class="align-middle">
                                $ {{ number_format($line['total_cost'], 2) }}
                            </td>
                            <td class="align-middle">
                                <x-livewire.loading-button theme="outline-danger" class="btn-sm" icon="trash-alt"
                                    title="Eliminar línea" wire:click="removeLine({{ $index }})"
                                    wire:target="removeLine({{ $index }})" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                No hay stocks seleccionados
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
