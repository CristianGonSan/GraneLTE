<x-card-table :pagination="$movements">
    <x-slot:header>
        <x-livewire.table.search-pane :autofocus="false">
            <div class="row my-1">
                <div class="form-group col-md-2 mb-0">
                    <label class="text-muted mb-0">Cantidad (mín.)</label>
                    <input type="number" class="form-control" min="0" step="0.001" placeholder="-∞"
                        wire:model.live.debounce.600ms="filters.quantityMin" />
                </div>

                <div class="form-group col-md-2 mb-0">
                    <label class="text-muted mb-0">Cantidad (máx.)</label>
                    <input type="number" class="form-control" min="0" step="0.001" placeholder="∞"
                        wire:model.live.debounce.600ms="filters.quantityMax" />
                </div>
            </div>
            <hr class="mb-0">
        </x-livewire.table.search-pane>
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($movements as $movement)
            @php
                $material = $movement->batch->material;
            @endphp
            <tr wire:key="raw-material-movement-{{ $movement->id }}">
                <td class="text-center">{{ number_format($movement->id) }}</td>
                <td>{{ $movement->type->label() }}</td>
                <td>{{ $material->shortText('name') }}</td>
                <td>{{ $movement->batch->code }}</td>
                <td>{{ $movement->warehouse->shortText('name') }}</td>
                <td>{{ number_format($movement->quantity, 3) }} {{ $material->unit->symbol }}</td>
                <td>{{ $movement->effective_at->format('d/m/Y - h:i a') }}</td>
                <td class="text-center cursor-pointer"
                    wire:click="$dispatch('showMovement', { id: {{ $movement->id }} })">
                    <i class="fas fa-fw fa-expand"></i>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center text-muted">
                    No se encontraron resultados.
                </td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
