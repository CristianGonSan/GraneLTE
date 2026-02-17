<x-card-table :pagination="$rawMaterials">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($rawMaterials as $rawMaterial)
            <tr wire:key="raw-material-{{ $rawMaterial->id }}">
                <td class="text-center">{{ $rawMaterial->id }}</td>
                <td>{{ $rawMaterial->shortText('name') }}</td>
                <td class="text-center">{{ $rawMaterial->abbreviation }}</td>
                <td class="text-center">{{ $rawMaterial->current_quantity }} {{ $rawMaterial->unit->symbol }}</td>
                <td>{{ $rawMaterial->category->shortText('name') }}</td>
                <td class="text-center"><i class="{{ $rawMaterial->getActiveIconClass() }}"></i></td>
                <td class="text-center">
                    <a href="{{ route('raw-materials.edit', $rawMaterial->id) }}" class="d-block text-reset">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
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
