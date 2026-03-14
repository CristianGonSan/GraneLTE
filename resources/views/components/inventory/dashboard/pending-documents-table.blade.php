<div class="card card-warning card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-file-alt"></i><span class="ml-1">Documentos pendientes</span>
        </h3>
    </div>
    <div class="card-body p-0">
        @if ($documents->isEmpty())
            <p class="text-muted text-center p-3 mb-0">No hay documentos pendientes.</p>
        @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tipo</th>
                            <th>Fecha efectiva</th>
                            <th>Responsable</th>
                            <th>Creado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $document)
                            <tr>
                                <td>
                                    <a href="{{ $document->getRoute('show') }}" target="_blank">
                                        {{ $document->reference_number ?? $document->id }}
                                    </a>
                                </td>
                                <td>
                                    {{ $document->type->label() }}
                                </td>
                                <td>{{ $document->effective_at->format('d/m/Y') }}</td>
                                <td>{{ $document->responsible?->name ?? '—' }}</td>
                                <td>{{ $document->creator->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    <i class="fa-solid fa-file-circle-check mr-1"></i>No hay documentos pendientes.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @if ($documents->isNotEmpty())
        <div class="card-footer text-right">
            <a href="{{ route('raw-material-documents.index', ['status' => 'pending']) }}" target="_blank">
                Ver todos<i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    @endif
</div>
