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
                        <th style="min-width: 200px">Materia prima</th>
                        <th style="min-width: 220px">Origen</th>
                        <th style="min-width: 200px; width: 200px;">Conteo *</th>
                        <th style="min-width: 140px">Teórico</th>
                        <th style="min-width: 140px">Diferencia</th>
                        <th style="width: 160px">Impacto MXN</th>
                        <th class="text-center" style="width: 1%">Quitar</th>
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
                            <td class="align-middle">
                                {{ $line['raw_material_name'] }}
                            </td>
                            <td class="align-middle">
                                <div class="text-nowrap">{{ $line['batch_code'] }}</div>
                                <small class="text-muted">{{ $line['warehouse_name'] }}</small>
                            </td>
                            <!-- Columna Conteo -->
                            <td class="align-middle p-2">
                                <x-adminlte-input type="number" name="lines.{{ $index }}.counted_quantity"
                                    placeholder="0" step="0.001" min="0"
                                    wire:model.live.debounce.500ms="lines.{{ $index }}.counted_quantity"
                                    igroup-size="sm" fgroup-class="mb-0" required label-class="mb-0">
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text" title="{{ $line['unit_name'] }}">
                                            {{ $line['unit_symbol'] }}
                                        </div>
                                    </x-slot>
                                </x-adminlte-input>
                            </td>
                            <!-- Columna Teórico -->
                            <td class="align-middle">
                                {{ number_format($line['theoretical_quantity'], 3) }} {{ $line['unit_symbol'] }}
                            </td>
                            <!-- Columna Diferencia -->
                            <td class="align-middle">
                                <div class="{{ $diffClass }}">
                                    {{ $diffSign > 0 ? '+' : '' }}{{ number_format($line['difference_quantity'], 3) }}
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="{{ $diffClass }}">
                                    $ {{ number_format($line['total_cost'], 2) }}
                                </div>
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
                                No hay stocks seleccionados para ajuste
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
                            <td colspan="5" class="font-weight-bold text-muted">
                                Total MXN
                            </td>
                            <td class="font-weight-bold text-nowrap {{ $totalClass }}">
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
