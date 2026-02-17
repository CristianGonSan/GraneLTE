<div class="card">
    @include('partials.livewire.table.search-header')

    <!-- Tabla -->
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped m-0">
            <thead>
                <tr class="text-nowrap">
                    @include('partials.livewire.table.sortable-th', [
                        'field' => 'id',
                        'label' => 'ID',
                        'align' => 'center',
                        'width' => '1%',
                    ])
                    @include('partials.livewire.table.sortable-th', [
                        'field' => 'batch_number',
                        'label' => 'Numero',
                    ])
                    @include('partials.livewire.table.sortable-th', [
                        'field' => 'ingredient_id',
                        'label' => 'Ingrediente',
                    ])
                    @include('partials.livewire.table.sortable-th', [
                        'field' => 'expiration_date',
                        'label' => 'Fecha de expiración',
                    ])
                    <th scope="col" class="text-center">Activo</th>
                    <th scope="col" class="text-center">Ver más</th>
                </tr>
            </thead>
            <tbody>
                @forelse($batches as $batch)
                    <tr wire:key="batch-{{ $batch->id }}">
                        <td scope="row" style="text-align: center;">{{ $batch->id }}</td>
                        <td>{{ $batch->batch_number }}</td>
                        <td>{{ $batch->ingredient->name }}</td>

                        @if ($batchs->expiration_date)
                            @if ($batch->isExpired())
                                <td class="text-danger">{{ $batch->expiration_date->format('d/m/Y') }}</td>
                            @else
                                <td>{{ $batch->expiration_date->format('d/m/Y') }}</td>
                            @endif
                        @else
                            <td>Sin fecha</td>
                        @endif

                        <td style="text-align: center;">
                            @if ($batch->isEnabled())
                                <i class="fa-solid fa-circle-check text-success"></i>
                            @else
                                <i class="fa-solid fa-circle-xmark text-secondary"></i>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('batches.edit', $batch->id) }}" class="d-block text-reset">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No se encontraron resultados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="card-footer border-0 pb-0">
        {{ $batches->links() }}
    </div>
</div>
