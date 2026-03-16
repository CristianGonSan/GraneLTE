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
                        <th style="min-width:180px">Cantidad contada</th>
                        <th style="min-width:130px">Cantidad teórica</th>
                        <th style="min-width:130px">Diferencia</th>
                        <th style="min-width:145px">Costo unit. MXN</th>
                        <th style="min-width:120px">Subtotal MXN</th>
                        <th style="width:40px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lines as $index => $line)
                        @php
                            $diffSign = bccomp((string) ($line['difference_quantity'] ?? '0'), '0', 3);
                            $diffClass = match (true) {
                                $diffSign > 0 => 'text-success',
                                $diffSign < 0 => 'text-danger',
                                default => 'text-muted',
                            };
                        @endphp
                        <tr wire:key="line-{{ $index }}">
                            <td class="align-middle small pl-4">{{ $line['raw_material_name'] }}</td>
                            <td class="align-middle text-muted small">{{ $line['batch_code'] }}</td>
                            <td class="align-middle text-muted small">{{ $line['warehouse_name'] }}</td>
                            <td class="align-top p-2">
                                <x-adminlte-input type="number" name="lines.{{ $index }}.counted_quantity"
                                    placeholder="0" step="0.001" min="0"
                                    wire:model.live.debounce.500ms="lines.{{ $index }}.counted_quantity"
                                    igroup-size="sm" fgroup-class="mb-0" required label-class="text-muted mb-0">
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text" title="{{ $line['unit_name'] }}">
                                            {{ $line['unit_symbol'] }}
                                        </div>
                                    </x-slot>
                                </x-adminlte-input>
                            </td>
                            <td class="align-middle text-muted">
                                {{ number_format($line['theoretical_quantity'], 3) }}
                                <small>{{ $line['unit_symbol'] }}</small>
                            </td>
                            <td class="align-middle font-weight-bold {{ $diffClass }}">
                                {{ number_format($line['difference_quantity'], 3) }}
                                <small>{{ $line['unit_symbol'] }}</small>
                            </td>
                            <td class="align-middle">
                                $ {{ number_format($line['unit_cost'], 2) }}
                            </td>
                            <td class="align-middle font-weight-bold {{ $diffClass }}">
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
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                No hay stocks seleccionados
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if (!empty($lines))
                    @php
                        $totalSign = bccomp((string) $total_cost, '0', 2);
                        $totalClass = match (true) {
                            $totalSign > 0 => 'text-success',
                            $totalSign < 0 => 'text-danger',
                            default => 'text-muted',
                        };
                    @endphp
                    <tfoot>
                        <tr>
                            <td colspan="7" class="font-weight-bold text-muted">
                                Total MXN
                            </td>
                            <td class="font-weight-bold {{ $totalClass }}">
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
