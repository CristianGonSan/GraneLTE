<form wire:submit.prevent="export">
    <div class="card">
        <div class="card-body">

            {{-- Fila 1: Identificación y estado --}}
            <div class="row">
                <x-form.select-wire-ignore fgroup-class="col-md-6" name="category_id_{{ $this->getId() }}"
                    label="Categoría" wire:loading.attr="readonly" wire:target="export" />

                <x-adminlte-select name="activeFilter" label="Activo" fgroup-class="col-md-6" class="custom-select"
                    wire:model="activeFilter">
                    <option value="active">Activos</option>
                    <option value="inactive">Inactivos</option>
                    <option value="all">Todos</option>
                </x-adminlte-select>
            </div>

            {{-- Fila 2: Disponibilidad --}}
            <div class="row">
                <x-adminlte-input name="quantityMin" label="En stock (mín.)" type="number" fgroup-class="col-md-3"
                    min="0" step="0.001" placeholder="-∞" wire:model="quantityMin" />

                <x-adminlte-input name="quantityMax" label="En stock (máx.)" type="number" fgroup-class="col-md-3"
                    min="0" step="0.001" placeholder="∞" wire:model="quantityMax" />

                <x-adminlte-select name="lowStockFilter" label="Alerta de stock" fgroup-class="col-md-6"
                    class="custom-select" wire:model="lowStockFilter">
                    <option value="all">Todos</option>
                    <option value="low_stock">Bajo stock</option>
                    <option value="ok">Stock OK</option>
                </x-adminlte-select>
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
    </script>
@endscript
