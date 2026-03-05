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
                <div class="col-sm-6 col-md-4">
                    <x-adminlte-input type="number" name="lines.{{ $index }}.counted_quantity"
                        label="Cantidad Contada *" placeholder="0" step="0.001" min="0"
                        wire:model.live.debounce.500ms="lines.{{ $index }}.counted_quantity" igroup-size="sm"
                        label-class="text-muted mb-0" required>
                        <x-slot name="appendSlot">
                            <div class="input-group-text" title="{{ $line['unit_name'] }}">
                                {{ $line['unit_symbol'] }}
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                        <label class="text-muted mb-0">Cantidad Teórica</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control"
                                value="{{ number_format($line['theoretical_quantity'], 3) }}" disabled>
                            <div class="input-group-append">
                                <div class="input-group-text" title="{{ $line['unit_name'] }}">
                                    {{ $line['unit_symbol'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                        <label class="text-muted mb-0">Diferencia</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control"
                                value="{{ number_format($line['difference_quantity'], 3) }}" disabled>
                            <div class="input-group-append">
                                <div class="input-group-text" title="{{ $line['unit_name'] }}">
                                    {{ $line['unit_symbol'] }}
                                </div>
                            </div>
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
