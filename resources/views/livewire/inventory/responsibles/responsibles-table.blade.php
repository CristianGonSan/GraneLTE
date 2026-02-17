<x-card-table :pagination="$responsibles">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($responsibles as $responsible)
            <tr wire:key="responsible-{{ $responsible->id }}">
                <td class="text-center">{{ $responsible->id }}</td>
                <td>{{ $responsible->shortText('name') }}</td>
                <td>{{ $responsible->mediumText('email') }}</td>
                <td>{{ $responsible->shortText('phone') }}</td>
                <td class="text-center">
                    <i class="{{ $responsible->getActiveIconClass() }}"></i>
                </td>
                <td class="text-center">
                    <a href="{{ route('responsibles.edit', $responsible->id) }}" class="d-block text-reset">
                        <i class="fa-solid fa-chevron-right"></i>
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
