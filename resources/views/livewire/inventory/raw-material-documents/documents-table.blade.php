<x-card-table :pagination="$documents">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($documents as $document)
            <tr wire:key="raw-material-document-{{ $document->id }}">
                <td class="text-center">{{ $document->id }}</td>
                <td class="text-center">{{ $document->type->label() ?? $document->type->value }}</td>
                <td class="text-center">{{ $document->status->label() }}</td>
                <td>{{ $document->effective_at->format('d/m/Y') }}</td>
                <td>{{ $document->shortText('reference_number') }}</td>
                <td class="text-center">{{ number_format($document->total_cost, 2) }}</td>
                <td>{{ $document->responsible?->shortText('name') ?? 'S/N' }}</td>
                <td>{{ $document->creator->shortText('name') }}</td>
                <td>{{ $document->created_at->format('d/m/Y') }}</td>
                <td class="text-center">
                    <a href="{{ $document->getRoute('show') }}" class="d-block text-reset">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">
                    No se encontraron resultados.
                </td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
