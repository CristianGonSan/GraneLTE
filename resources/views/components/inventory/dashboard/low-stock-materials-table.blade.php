<div class="card card-danger card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-exclamation-triangle"></i><span class="ml-1">Materiales con stock bajo</span>
        </h3>
    </div>
    <div class="card-body p-0">
        @if ($materials->isEmpty())
            <p class="text-muted text-center p-3 mb-0">No hay materiales con stock bajo.</p>
        @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Mínimo</th>
                            <th>Actual</th>
                            <th>Faltante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($materials as $material)
                            @php
                                $pct = (float) $material->stock_percentage;
                                $badgeCss = match (true) {
                                    $pct <= 25 => 'badge-danger',
                                    $pct <= 60 => 'badge-warning',
                                    default => 'badge-info',
                                };
                            @endphp
                            <tr>
                                <td>
                                    {{ $material->shortText('name') }}
                                </td>
                                <td>
                                    {{ number_format((float) $material->minimum_stock, 2) }}
                                    <small class="text-muted">{{ $material->unit->symbol }}</small>
                                </td>
                                <td>
                                    {{ number_format((float) $material->current_quantity, 2) }}
                                    <small class="text-muted">{{ $material->unit->symbol }}</small>
                                </td>
                                <td class="text-danger">
                                    {{ number_format((float) $material->difference, 2) }}
                                    <small class="text-muted">{{ $material->unit->symbol }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    <i class="fa-solid fa-circle-check mr-1"></i>No hay stock bajo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @if ($materials->isNotEmpty())
        <div class="card-footer text-right">
            <a href="{{ route('raw-materials.index', ['low-stock-filter' => 'low_stock']) }}" target="_blank">
                Ver todos<i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    @endif
</div>
