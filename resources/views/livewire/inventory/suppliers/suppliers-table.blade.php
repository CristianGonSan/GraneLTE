<x-card-table :pagination="$suppliers">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($suppliers as $supplier)
            <tr wire:key="supplier-{{ $supplier->id }}">
                <td>{{ $supplier->shortText('name') }}</td>
                <td>{{ $supplier->shortText('contact_person') }}</td>
                <td>{{ $supplier->shortText('email') }}</td>
                <td>{{ $supplier->shortText('phone') }}</td>
                <td class="text-center"><i class="{{ $supplier->getActiveIconClass() }}"></i></td>
                <td class="text-center">
                    <a href="{{ route('suppliers.show', $supplier->id) }}" class="d-block text-reset">
                        <i class="fas fa-fw fa-chevron-right"></i>
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
