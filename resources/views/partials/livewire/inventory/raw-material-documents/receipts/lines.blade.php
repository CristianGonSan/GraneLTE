<div class="card">
    <div class="card-body py-3">
        <div class="row align-items-end">
            <x-form.select-wire-ignore fgroup-class="col-md-4 mb-md-0" name="rawMaterialId" label="Materia prima *"
                wire:loading.attr="readonly" wire:target="save,addLine" />

            <x-form.select-wire-ignore fgroup-class="col-md-4 mb-md-0" name="warehouseId" label="Almacén *"
                wire:loading.attr="readonly" wire:target="save,addLine" />

            <div class="col-md-4">
                <x-livewire.loading-button label="Agregar lote" icon="plus" wire:click="addLine"
                    wire:target="addLine" class="btn-block" />
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
                </div>
                <div class="col-auto">
                    <x-livewire.loading-button theme="outline-danger" class="btn-sm" icon="trash-alt"
                        title="Eliminar lote" wire:click="removeLine('{{ $index }}')"
                        wire:target="removeLine('{{ $index }}')" />
                </div>
            </div>

            <hr class="my-2">

            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <x-adminlte-input type="text" name="lines.{{ $index }}.external_batch_code"
                        label="Código de lote externo" placeholder="Código externo" maxlength="128"
                        wire:model="lines.{{ $index }}.external_batch_code" igroup-size="sm"
                        label-class="text-muted mb-0" />
                </div>

                <div class="col-sm-6 col-md-3">
                    <x-adminlte-input type="date" name="lines.{{ $index }}.expiration_date"
                        label="Fecha de expiración" wire:model="lines.{{ $index }}.expiration_date"
                        igroup-size="sm" label-class="text-muted mb-0" />
                </div>

                <div class="col-sm-4 col-md-2 col-6">
                    <x-adminlte-input type="number" name="lines.{{ $index }}.received_quantity"
                        label="Cantidad *" placeholder="0" step="0.001" min="0.001"
                        wire:model.live.debounce.500ms="lines.{{ $index }}.received_quantity" igroup-size="sm"
                        label-class="text-muted mb-0" required>
                        <x-slot name="appendSlot">
                            <div class="input-group-text" title="{{ $line['unit_name'] }}">
                                {{ $line['unit_symbol'] }}
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="col-sm-4 col-md-2 col-6">
                    <x-adminlte-input type="number" name="lines.{{ $index }}.received_unit_cost"
                        label="Costo unitario MXN*" placeholder="0.00" step="0.01" min="0"
                        wire:model.live.debounce.500ms="lines.{{ $index }}.received_unit_cost" igroup-size="sm"
                        label-class="text-muted mb-0" required>
                        <x-slot name="prependSlot">
                            <div class="input-group-text">$</div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="col-sm-4 col-md-2">
                    <div class="form-group">
                        <label for="lines.{{ $index }}.total" class="text-muted mb-0">Total
                            MXN</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <div class="input-group-text" title="{{ $line['unit_name'] }}">
                                    $
                                </div>
                            </div>
                            <input type="text" name="lines.{{ $index }}.total" class="form-control"
                                value="{{ number_format($line['received_total_cost'], 2) }}" disabled>
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
                No hay lotes agregados
            </div>
        </div>
    </div>
@endforelse

<div class="card">
    <div class="card-body py-2">
        <div class="d-flex justify-content-between align-items-center">
            <strong class="text-muted">Total MXN</strong>
            <strong>$ {{ number_format($total_cost, 2) }}</strong>
        </div>
    </div>
</div>
