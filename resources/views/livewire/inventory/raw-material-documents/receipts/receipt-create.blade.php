<div>
    <h1 class="h4">Crear Entrada de Materia Prima</h1>

    <form wire:submit.prevent="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-2" name="effective_at" label="Fecha efectiva *" type="datetime-local"
                    wire:model="effective_at" required />

                <x-adminlte-input fgroup-class="col-md-2" name="reference_type" label="Tipo de referencia"
                    placeholder="Factura, pedido, etc." type="text" wire:model="reference_type" maxlength="32" />

                <x-adminlte-input fgroup-class="col-md-2" name="reference_number" label="Número de referencia"
                    placeholder="Número de referencia" type="text" wire:model="reference_number" maxlength="128" />

                <x-form.select-wire-ignore fgroup-class="col-md-3" name="responsible_id" label="Responsable"
                    wire:loading.attr='readonly' wire:target='save'>
                </x-form.select-wire-ignore>

                <x-form.select-wire-ignore fgroup-class="col-md-3" name="supplier_id" label="Proveedor *"
                    wire:loading.attr='readonly' wire:target='save'>
                </x-form.select-wire-ignore>

                <x-adminlte-textarea fgroup-class="col-md-12" name="description" label="Descripción"
                    placeholder="Descripción de la transacción" wire:model="description" rows="2"
                    maxlength="255" />

                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <x-checkbox name="isDraft" label="Guardar como borrador"
                        title="Guarda esta transacción como borrador" wire:model='isDraft' />
                </div>
            </div>
        </div>

        <h2 class="h5">Lista de lotes</h2>

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <x-form.select-wire-ignore fgroup-class="col-md-4" name="rawMaterialId" label="Materia prima *"
                        wire:loading.attr='readonly' wire:target='save,addLine'>
                    </x-form.select-wire-ignore>

                    <x-form.select-wire-ignore fgroup-class="col-md-4" name="warehouseId" label="Almacen *"
                        wire:loading.attr='readonly' wire:target='save,addLine'>
                    </x-form.select-wire-ignore>

                    <div class="form-group col-md-4">
                        <label class="d-none d-md-block">&nbsp;</label>
                        <div class="input-group d-flex">
                            <x-livewire.loading-button label="Agregar lote"  icon="plus"
                                wire:click='addLine' wire:target='addLine' />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover m-0">
                    <thead class="text-nowrap">
                        <tr>
                            <th>Materia Prima</th>
                            <th>Almacén</th>
                            <th>Código Lote</th>
                            <th>Expiración</th>
                            <th class="text-center">Cantidad *</th>
                            <th class="text-center">Costo Unit. *</th>
                            <th class="text-center">Total MXN</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lines as $index => $line)
                            <tr wire:key="line-{{ $index }}">
                                <td class="align-middle small">
                                    {{ $line['raw_material_name'] }}
                                </td>

                                <td class="align-middle small">
                                    {{ $line['warehouse_name'] }}
                                </td>

                                <td class="align-middle">
                                    <x-adminlte-input type="text"
                                        name="lines.{{ $index }}.external_batch_code"
                                        placeholder="Código externo" maxlength="128"
                                        wire:model="lines.{{ $index }}.external_batch_code" igroup-size="sm"
                                        fgroup-class="mb-0" />
                                </td>

                                <td class="align-middle" style="min-width: 130px;">
                                    <x-adminlte-input type="date" name="lines.{{ $index }}.expiration_date"
                                        wire:model="lines.{{ $index }}.expiration_date" igroup-size="sm"
                                        fgroup-class="mb-0" />
                                </td>

                                <td class="align-middle text-center" style="min-width: 140px;">
                                    <x-adminlte-input type="number" name="lines.{{ $index }}.received_quantity"
                                        placeholder="0" step="0.001" min="0"
                                        wire:model="lines.{{ $index }}.received_quantity"
                                        wire:change='recalculateTotals' igroup-size="sm" fgroup-class="mb-0">
                                        <x-slot name="appendSlot">
                                            <div class="input-group-text" title="{{ $line['unit_name'] }}">
                                                {{ $line['unit_symbol'] }}
                                            </div>
                                        </x-slot>
                                    </x-adminlte-input>
                                </td>

                                <td class="align-middle text-center" style="min-width: 110px;">
                                    <x-adminlte-input type="number"
                                        name="lines.{{ $index }}.received_unit_cost" placeholder="0.00"
                                        step="0.01" min="0"
                                        wire:model="lines.{{ $index }}.received_unit_cost"
                                        wire:change='recalculateTotals' igroup-size="sm" fgroup-class="mb-0" />
                                </td>

                                <td class="align-middle text-center">
                                    {{ number_format($line['received_total_cost'], 2) }}
                                </td>

                                <td class="align-middle text-center">
                                    <x-livewire.loading-button theme="outline-danger" class="btn-sm" icon="trash-alt"
                                        title="Eliminar línea" wire:click="removeLine('{{ $index }}')"
                                        wire:target="removeLine('{{ $index }}')" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No hay lotes agregados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6"></th>
                            <th class="text-center">
                                <span>{{ number_format($total_cost, 2) }}</span>
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>


        <div class="mb-3">
            <x-livewire.loading-button type="submit" label="Validar documento" class="mr-1" />

            <a href="{{ route('raw-material-documents.index') }}" class="btn btn-outline-secondary"
                wire:loading.attr="disabled">
                Cancelar
            </a>
        </div>
    </form>
</div>

@push('js')
    <script>
        document.addEventListener("livewire:initialized", () => {
            const $wire = Livewire.first();

            let select2Builder = new LivewireSelect2Builder($wire);

            const responsibleSelect = select2Builder.selector('#responsible_id').wireModel('responsible_id')
                .appendConfig({
                    placeholder: 'Seleccionar responsable',
                    ajax: {
                        url: "{{ route('lookups.responsibles.select2') }}",
                        dataType: 'json',
                        delay: 250,
                        cache: true
                    },
                    templateResult: data => {
                        if (data.loading) return data.text;
                        return $(`
                        <div class="p-1">
                            <strong class="d-block">${data.text}</strong>
                            <small>${data.description}</small>
                        </div>
                        `);
                    }
                }).build();

            const supplierSelect = select2Builder.selector('#supplier_id').wireModel('supplier_id')
                .appendConfig({
                    placeholder: 'Seleccionar proveedor',
                    ajax: {
                        url: "{{ route('lookups.suppliers.select2') }}",
                        dataType: 'json',
                        delay: 250,
                        cache: true
                    }
                }).build();

            const rawMaterialSelect = select2Builder.selector('#rawMaterialId').wireModel('rawMaterialId')
                .appendConfig({
                    placeholder: 'Seleccionar materia prima',
                    ajax: {
                        url: "{{ route('lookups.raw-materials.select2') }}",
                        dataType: 'json',
                        delay: 250,
                        cache: true
                    }
                }).build();

            const warehouseSelect = select2Builder.selector('#warehouseId').wireModel('warehouseId')
                .appendConfig({
                    placeholder: 'Seleccionar almacen',
                    ajax: {
                        url: "{{ route('lookups.warehouses.select2') }}",
                        dataType: 'json',
                        delay: 250,
                        cache: true
                    }
                }).build();
        });
    </script>
@endpush
