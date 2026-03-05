<x-card-table :pagination="$materials">
    <x-slot:header>
        <x-livewire.table.search-pane>
            <div class="row my-1">
                <div class="form-group col-md-2 mb-0">
                    <label class="text-muted mb-0">En stock (mín.)</label>
                    <input type="number" class="form-control" min="0" step="0.001" placeholder="-∞"
                        wire:model.live.debounce.600ms="filters.quantityMin" />
                </div>

                <div class="form-group col-md-2 mb-0">
                    <label class="text-muted mb-0">En stock (máx.)</label>
                    <input type="number" class="form-control" min="0" step="0.001" placeholder="∞"
                        wire:model.live.debounce.600ms="filters.quantityMax" />
                </div>

                <x-adminlte-select fgroup-class="col-md-4 mb-0" class="custom-select" name="lowStockFilter"
                    label="Alerta de stock" wire:model.live="filters.lowStockFilter" label-class="text-muted mb-0">
                    <option value="all">Todos</option>
                    <option value="low_stock">Stock bajo</option>
                    <option value="ok">Stock OK</option>
                </x-adminlte-select>
            </div>
            <hr class="mb-0">
        </x-livewire.table.search-pane>
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($materials as $material)
            <tr wire:key="raw-material-{{ $material->id }}">
                <td>{{ $material->shortText('name') }}</td>
                <td>{{ $material->abbreviation }}</td>
                <td>{{ $material->category->shortText('name') }}</td>
                <td @if ($material->isLowStock()) class="text-danger" title="Stock bajo" @endif>
                    {{ number_format($material->current_quantity, 3) }}
                    {{ $material->unit->symbol }}
                </td>
                <td class="text-center"><i class="{{ $material->getActiveIconClass() }}"></i></td>
                <td class="text-center">
                    <a href="{{ route('raw-materials.show', $material->id) }}" class="d-block text-reset">
                        <i class="fas fa-fw fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No se encontraron resultados.
                </td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
