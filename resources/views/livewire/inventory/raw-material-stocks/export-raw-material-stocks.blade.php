<form wire:submit.prevent="export">
    <div class="card">
        <div class="card-body">

            {{-- Fila 1: Identificación --}}
            <div class="row">
                <x-form.select-wire-ignore fgroup-class="col-md-3" name="material_id_{{ $this->getId() }}" label="Material"
                    wire:loading.attr="readonly" wire:target="export" />

                <x-form.select-wire-ignore fgroup-class="col-md-3" name="category_id_{{ $this->getId() }}"
                    label="Categoría" wire:loading.attr="readonly" wire:target="export" />

                <x-form.select-wire-ignore fgroup-class="col-md-3" name="warehouse_id_{{ $this->getId() }}"
                    label="Almacén" wire:loading.attr="readonly" wire:target="export" />

                <x-form.select-wire-ignore fgroup-class="col-md-3" name="supplier_id_{{ $this->getId() }}"
                    label="Proveedor" wire:loading.attr="readonly" wire:target="export" />
            </div>

            {{-- Fila 2: Disponibilidad, fechas y vencimiento --}}
            <div class="row">
                <x-adminlte-input name="quantityMin" label="En stock (mín.)" type="number" fgroup-class="col-md-2"
                    min="0" step="0.001" placeholder="-∞" wire:model="quantityMin" />

                <x-adminlte-input name="quantityMax" label="En stock (máx.)" type="number" fgroup-class="col-md-2"
                    min="0" step="0.001" placeholder="∞" wire:model="quantityMax" />

                <x-adminlte-input name="receivedFrom" label="Recibido desde" type="date" fgroup-class="col-md-2"
                    wire:model="receivedFrom" />

                <x-adminlte-input name="receivedTo" label="Recibido hasta" type="date" fgroup-class="col-md-2"
                    wire:model="receivedTo" />

                <div class="form-group col-md-4 mb-0">
                    <label>Caducidad</label>
                    <div class="input-group">
                        <select class="form-control custom-select" wire:model.live="expirationFilter">
                            <option value="">Todos</option>
                            <option value="expiring">Por caducar</option>
                            <option value="not_expired">No caducados</option>
                            <option value="expired">Caducados</option>
                            <option value="non_perishable">Imperecederos</option>
                        </select>
                        @if ($expirationFilter === 'expiring')
                            <input type="number" class="form-control" min="1" step="1"
                                wire:model.live.debounce.600ms="expirationDays" title="Días hasta caducidad" />
                            <div class="input-group-append">
                                <span class="input-group-text">días</span>
                            </div>
                        @endif
                    </div>
                </div>
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
                Si utiliza los filtros de material y categoría al mismo tiempo, se dará prioridad al material y se
                ignorará el filtro de categoría.
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

        builder.selector('#material_id_{{ $this->getId() }}').wireModel('materialId')
            .appendConfig({
                placeholder: 'Todos los materiales',
                ajax: {
                    url: "{{ route('lookups.raw-materials.select2') }}",
                    dataType: 'json',
                    delay: 250,
                    cache: true
                }
            })
            .build();

        builder.selector('#category_id_{{ $this->getId() }}').wireModel('categoryId')
            .appendConfig({
                placeholder: 'Todas las categorías',
                ajax: {
                    url: "{{ route('lookups.categories.select2') }}",
                    dataType: 'json',
                    delay: 250,
                    cache: true
                }
            })
            .build();

        builder.selector('#warehouse_id_{{ $this->getId() }}').wireModel('warehouseId')
            .appendConfig({
                placeholder: 'Todos los almacenes',
                ajax: {
                    url: "{{ route('lookups.warehouses.select2') }}",
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
    </script>
@endscript
