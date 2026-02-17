<x-card-table :pagination="$batches">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($batches as $batch)
            @php
                $material = $batch->material;
            @endphp
            <tr wire:key="raw-material-batch-{{ $batch->id }}">
                <td>{{ $batch->batch_code }}</td>
                <td>{{ $batch->external_batch_code ?? 'S/N' }}</td>
                <td class="text-center">
                    {{ number_format($batch->current_quantity, 3) }} {{ $material->unit->symbol }}
                </td>
                <td class="text-center">{{ number_format($batch->currentCost(), 2) }}</td>
                <td>{{ $batch->received_at->format('d/m/Y') }}</td>
                <td>{{ $batch->expiration_date?->format('d/m/Y') ?? '--/--/----' }}</td>
                <td class="text-center cursor-pointer" wire:click="$dispatch('showBatch', { id: {{ $batch->id }} })">
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
