<x-card-table :pagination="$warehouses">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($warehouses as $warehouse)
            <tr wire:key="warehouses-{{ $warehouse->id }}">
                <td>{{ $warehouse->shortText('name') }}</td>
                <td>{{ $warehouse->mediumText('description', 'Sin descripción') }}</td>
                <td class="text-center"><i class="{{ $warehouse->getActiveIconClass() }}"></i></td>
                <td class="text-center">
                    <a href="{{ route('warehouses.show', $warehouse->id) }}" class="d-block text-reset">
                        <i class="fas fa-fw fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
