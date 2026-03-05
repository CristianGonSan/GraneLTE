<form wire:submit.prevent="export">
    <div class="card">
        <div class="card-body">

            {{-- Fila 1: Filtros --}}
            <div class="row">
                <div class="form-group col-md-3">
                    <label>Disponibilidad</label>
                    <div class="mt-1">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="onlyWithStock_{{ $this->getId() }}"
                                wire:model="onlyWithStock">
                            <label class="custom-control-label" for="onlyWithStock_{{ $this->getId() }}">
                                Solo almacenes con stock
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fila 2: Ordenamiento --}}
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
