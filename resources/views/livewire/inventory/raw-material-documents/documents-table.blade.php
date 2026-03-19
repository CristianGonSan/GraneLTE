<x-card-table :pagination="$documents">
    <x-slot:header>
        <x-livewire.table.search-pane>
            <div class="row mt-1 mb-1">
                <x-adminlte-select fgroup-class="col-md-4 mb-0" class="custom-select" name="types"
                    label="Filtrar por tipo" wire:model.live="filters.type" label-class="text-muted mb-0">
                    <option value="all">Todos</option>
                    <x-adminlte-options :options="$typeOptions" />
                </x-adminlte-select>

                <x-adminlte-select fgroup-class="col-md-4 mb-0" class="custom-select" name="status"
                    label="Filtrar por estado" wire:model.live="filters.status" label-class="text-muted mb-0">
                    <option value="all">Todos</option>
                    <x-adminlte-options :options="$statusOptions" />
                </x-adminlte-select>
            </div>
            <hr class="mb-0">
        </x-livewire.table.search-pane>
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($documents as $document)
            <tr wire:key="raw-material-document-{{ $document->id }}">
                <td class="text-center">{{ number_format($document->id) }}</td>
                <td>{{ $document->shortText('reference_number') }}</td>
                <td>{{ $document->type->label() }}</td>
                <td>{{ $document->status->label() }}</td>
                <td>{{ $document->responsible?->shortText('name') ?? 'S/N' }}</td>
                <td>{{ $document->creator->shortText('name') }}</td>
                <td>{{ number_format($document->total_cost, 2) }}</td>
                <td>{{ $document->effective_at->format('d/m/Y') }}</td>
                <td>{{ $document->created_at->format('d/m/Y') }}</td>
                <td class="text-center">
                    <a href="{{ $document->getRoute('show') }}" class="d-block text-reset">
                        <i class="fas fa-fw fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center text-muted">
                    No se encontraron resultados.
                </td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
