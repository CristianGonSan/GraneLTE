<x-card-table :pagination="$responsibles">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($responsibles as $responsible)
            <tr wire:key="responsible-{{ $responsible->id }}">
                <td>{{ $responsible->shortText('name') }}</td>
                <td>{{ $responsible->shortText('identifier') }}</td>
                <td>{{ $responsible->shortText('department') }}</td>
                <td>{{ $responsible->shortText('position') }}</td>
                <td class="text-center"><i class="{{ $responsible->getActiveIconClass() }}"></i></td>
                <td class="text-center">
                    <a href="{{ route('responsibles.show', $responsible->id) }}" class="d-block text-reset">
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
