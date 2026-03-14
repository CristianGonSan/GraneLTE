<div class="card card-warning card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-times"></i>
            <span class="ml-1">Lotes próximos a vencer en los próximos 30 días.</span>
        </h3>
    </div>
    <div class="card-body p-0">
        @if ($batches->isEmpty())
            <div class="p-3 text-muted text-center">
                No hay lotes vencidos ni próximos a vencer en los próximos 30 días.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Lote</th>
                            <th>Material</th>
                            <th>Cantidad</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($batches as $batch)
                            <tr>
                                <td>
                                    {{ $batch->code }}
                                </td>
                                <td>{{ $batch->material->shortText('name') }}</td>
                                <td>
                                    {{ number_format($batch->current_quantity, 3) }}
                                    <small class="text-muted">{{ $batch->material->unit->symbol }}</small>
                                </td>
                                <td class="text-center">
                                    @if ($batch->isExpired())
                                        <span class="badge badge-danger">Vencido</span>
                                    @else
                                        <span class="badge badge-warning">
                                            Vence en {{ (int) now()->diffInDays($batch->expiration_date) }}d
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    <i class="fa-solid fa-circle-check mr-1"></i>No hay lotes por caducar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @if ($batches->isNotEmpty())
        <div class="card-footer text-right">
            <a href="{{ route('raw-material-batches.index', ['filter' => 'expiring']) }}" target="_blank">
                Ver todos<i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    @endif
</div>
