<div class="card">
    <div class="card-header">
        <x-livewire.loading-button label="Seleccionar existencias" icon="magnifying-glass"
            wire:click="$dispatch('openStockSelector')" wire:target="addLine" />
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="text-nowrap border-top-0">
                    <tr>
                        <th style="min-width: 220px">Materia prima</th>
                        <th style="min-width: 230px">Origen</th>
                        <th style="min-width: 200px; width: 200px;">Cantidad</th>
                        <th style="width: 160px">Total MXN</th>
                        <th class="text-center" style="width: 1%">Quitar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lines as $index => $line)
                        <tr wire:key="line-{{ $index }}">
                            <td class="align-middle">{{ $line['raw_material_name'] }}</td>
                            <td class="align-middle">
                                <div class="text-nowrap">{{ $line['batch_code'] }}</div>
                                <small class="text-muted">{{ $line['warehouse_name'] }}</small>
                            </td>
                            <td class="align-middle p-2">
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
                                <small
                                    class="text-nowrap {{ $line['invalid_quantity'] ? 'text-danger' : 'text-muted' }}">
                                    En stock: {{ number_format($line['current_quantity'], 3) }}
                                </small>
                            </td>
                            <td class="text-nowrap align-middle">
                                <div>$ {{ number_format($line['total_cost'], 2) }}</div>
                                <small class="text-muted">$ {{ number_format($line['unit_cost'], 2) }} c/u</small>
                            </td>
                            <td class="align-middle text-center">
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
                            <td colspan="3" class="font-weight-bold text-muted">
                                Total MXN
                            </td>
                            <td class="font-weight-bold text-nowrap">
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
