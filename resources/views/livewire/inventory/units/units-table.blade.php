<x-card-table :pagination="$units">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($units as $unit)
            <tr wire:key="unit-{{ $unit->id }}">
                <td>{{ $unit->mediumText('name') }}</td>
                <td class="text-center">{{ $unit->symbol }}</td>
                <td class="text-center"><i class="{{ $unit->getActiveIconClass() }}"></i></td>
                <td class="text-center">
                    <a href="{{ route('units.show', $unit->id) }}" class="d-block text-reset">
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
