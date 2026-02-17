<x-card-table :pagination="$movements">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($movements as $movement)
            @php
                $material = $movement->batch->material;
            @endphp
            <tr wire:key="raw-material-movement-{{ $movement->id }}">
                <td class="text-center">{{ $movement->type->label() }}</td>
                <td class="text-center">{{ number_format($movement->quantity, 3) }} {{ $material->unit->symbol }} </td>
                <td>{{ $material->shortText('name') }}</td>
                <td>{{ $movement->effective_at->format('d/m/Y h:i a') }}</td>
                <td class="text-center cursor-pointer"
                    wire:click="$dispatch('showMovement', { id: {{ $movement->id }} })">
                    <i class="fa-solid fa-chevron-right"></i>
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
