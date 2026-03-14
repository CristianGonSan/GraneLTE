<x-card-table :pagination="$stocks">
    <x-slot:header>
        <x-livewire.table.search-pane :autofocus="false">
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

                <div class="form-group col-md-4 mb-0">
                    <label class="text-muted mb-0">Caducidad</label>
                    <div class="input-group">
                        <select class="form-control custom-select" wire:model.live="filters.expirationFilter">
                            <option value="all">Todos</option>
                            <option value="expiring">Por caducar</option>
                            <option value="not_expired">No caducados</option>
                            <option value="expired">Caducados</option>
                            <option value="non_perishable">Imperecederos</option>
                        </select>
                        @if ($filters['expirationFilter'] === 'expiring')
                            <input type="number" class="form-control" min="1" step="1"
                                wire:model.live.debounce.600ms="filters.expirationDays" title="Días hasta caducidad" />
                            <div class="input-group-append">
                                <span class="input-group-text">días</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <hr class="mb-0">
        </x-livewire.table.search-pane>
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($stocks as $stock)
            @php
                $material = $stock->batch->material;
                $batch = $stock->batch;
            @endphp
            <tr wire:key="raw-material-stock-{{ $stock->id }}">
                <td>{{ $material->shortText('name') }}</td>
                <td>{{ $batch->code }}</td>
                <td>{{ number_format($stock->current_quantity, 3) }} {{ $material->unit->symbol }}</td>
                <td @if ($batch->isExpired()) class="text-danger" title="Ha caducado" @endif>
                    {{ $batch->expiration_date?->format('d/m/Y') ?? '--/--/----' }}
                </td>
                <td class="text-center cursor-pointer" wire:click="$dispatch('showStock', { id: {{ $stock->id }} })">
                    <i class="fas fa-fw fa-expand"></i>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted">
                    No se encontraron resultados.
                </td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
