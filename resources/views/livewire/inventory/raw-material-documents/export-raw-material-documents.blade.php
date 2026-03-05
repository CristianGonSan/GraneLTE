<form wire:submit.prevent="export">
    <div class="card">
        <div class="card-body">

            {{-- Fila 1: Tipo, Estado --}}
            <div class="row">
                <x-adminlte-select name="documentType" label="Tipo de documento" fgroup-class="col-md-2"
                    class="custom-select" wire:model="documentType">
                    <option value="">Todos los tipos</option>
                    @foreach ($documentTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </x-adminlte-select>

                <x-adminlte-select name="documentStatus" label="Estado" fgroup-class="col-md-2" class="custom-select"
                    wire:model="documentStatus">
                    <option value="">Todos los estados</option>
                    @foreach ($documentStatuses as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </x-adminlte-select>

                <x-form.select-wire-ignore fgroup-class="col-md-2" name="responsible_id_{{ $this->getId() }}"
                    label="Responsable" wire:loading.attr="readonly" wire:target="export" />

                <x-form.select-wire-ignore fgroup-class="col-md-3" name="supplier_id_{{ $this->getId() }}"
                    label="Proveedor" wire:loading.attr="readonly" wire:target="export" />

                <x-form.select-wire-ignore fgroup-class="col-md-3" name="created_by_id_{{ $this->getId() }}"
                    label="Creado por" wire:loading.attr="readonly" wire:target="export" />
            </div>

            {{-- Fila 2: Fechas efectivas --}}
            <div class="row">
                <x-adminlte-input name="effectiveFrom" label="Fecha efectiva desde" type="datetime-local"
                    fgroup-class="col-md-3" wire:model="effectiveFrom" />

                <x-adminlte-input name="effectiveTo" label="Fecha efectiva hasta" type="datetime-local"
                    fgroup-class="col-md-3" wire:model="effectiveTo" />

                <x-adminlte-input name="validatedFrom" label="Fecha validación desde" type="datetime-local"
                    fgroup-class="col-md-3" wire:model="validatedFrom" />

                <x-adminlte-input name="validatedTo" label="Fecha validación hasta" type="datetime-local"
                    fgroup-class="col-md-3" wire:model="validatedTo" />
            </div>

            {{-- Fila 3: Ordenamiento --}}
            <div class="row">
                <x-adminlte-select name="orderBy" label="Ordenar por" fgroup-class="col-md-6" class="custom-select"
                    wire:model="orderBy">
                    @foreach ($sortableColumns as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </x-adminlte-select>

                <x-adminlte-select name="orderDirection" label="Dirección" fgroup-class="col-md-3" class="custom-select"
                    wire:model="orderDirection">
                    <option value="asc">Ascendente (A → Z)</option>
                    <option value="desc">Descendente (Z → A)</option>
                </x-adminlte-select>
            </div>

            <p class="text-muted mb-0">
                Si utiliza el filtro de proveedor, el filtro de tipo de documento será ignorado y se exportarán
                únicamente entradas.
            </p>
        </div>
    </div>

    <div class="mb-3">
        <x-livewire.loading-button type="submit" label="Exportar Excel" icon="file-excel" theme="outline-success" />
    </div>
</form>

@script
    <script>
        const builder = new LivewireSelect2Builder($wire);

        builder.appendConfig({
            allowClear: true,
            templateResult: data => {
                if (data.loading) return data.text;
                return $(`
                <div class="p-1">
                    <strong class="d-block">${data.text}</strong>
                    <small>${data.description ?? ''}</small>
                </div>
            `);
            },
        });

        builder.selector('#responsible_id_{{ $this->getId() }}').wireModel('responsibleId')
            .appendConfig({
                placeholder: 'Todos los responsables',
                ajax: {
                    url: "{{ route('lookups.responsibles.select2') }}",
                    dataType: 'json',
                    delay: 250,
                    cache: true
                }
            })
            .build();

        builder.selector('#supplier_id_{{ $this->getId() }}').wireModel('supplierId')
            .appendConfig({
                placeholder: 'Todos los proveedores',
                ajax: {
                    url: "{{ route('lookups.suppliers.select2') }}",
                    dataType: 'json',
                    delay: 250,
                    cache: true
                }
            })
            .build();

        builder.selector('#created_by_id_{{ $this->getId() }}').wireModel('createdById')
            .appendConfig({
                placeholder: 'Todos los usuarios',
                ajax: {
                    url: "{{ route('lookups.users.select2') }}",
                    dataType: 'json',
                    delay: 250,
                    cache: true
                }
            })
            .build();
    </script>
@endscript
