<x-card-table :pagination="$categories">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($categories as $category)
            <tr wire:key="category-{{ $category->id }}">
                <td class="text-center">{{ $category->id }}</td>
                <td>{{ $category->shortText('name') }}</td>
                <td>{{ $category->mediumText('description') }}</td>

                <td class="text-center">
                    <i class="{{ $category->getActiveIconClass() }}"></i>
                </td>

                <td class="text-center cursor-pointer">
                    <a href="{{ route('categories.edit', $category->id) }}" class="d-block text-reset">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
