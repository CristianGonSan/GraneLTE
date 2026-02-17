<x-card-table :pagination="$warehouses">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($warehouses as $warehouse)
            <tr wire:key="warehouses-{{ $warehouse->id }}">
                <td class="text-center">{{ $warehouse->id }}</td>
                <td>{{ $warehouse->shortText('name') }}</td>
                <td>{{ $warehouse->mediumText('description') }}</td>
                <td class="text-center">
                    <i class="{{ $warehouse->getActiveIconClass() }}"></i>
                </td>
                <td class="text-center">
                    <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="d-block text-reset">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
