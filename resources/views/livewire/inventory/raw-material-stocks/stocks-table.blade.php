<x-card-table :pagination="$stocks">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($stocks as $stock)
            @php
                $material = $stock->batch->material;
            @endphp
            <tr wire:key="raw-material-stock-{{ $stock->id }}">
                <td class="text-center">
                    {{ number_format($stock->current_quantity, 3) }} {{ $material->unit->symbol }}
                </td>
                <td>{{ $material->shortText('name') }}</td>
                <td>{{ $stock->batch->code() }}</td>
                <td>{{ $stock->warehouse->shortText('name') }}</td>
                <td class="text-center cursor-pointer" wire:click="$dispatch('showStock', { id: {{ $stock->id }} })">
                    <i class="fa-solid fa-chevron-right"></i>
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
