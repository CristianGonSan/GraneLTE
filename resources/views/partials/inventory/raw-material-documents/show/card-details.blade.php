<div class="card mb-3">
    <div class="card-body">
        <dl class="row">
            <div class="col-md-3">
                <dt>Fecha efectiva</dt>
                <dd>{{ $document->effective_at->format('d/m/Y - h:i a') }}</dd>
            </div>
            <div class="col-md-3">
                <dt>{{ $document->reference_type ?? 'Referencia' }}</dt>
                <dd>{{ $document->reference_number ?? 'S/N' }}</dd>
            </div>

            <div class="col-md-3">
                <dt>Responsable</dt>
                <dd>{{ $document->responsible?->name ?? 'S/N' }}</dd>
            </div>

            @if ($receipt = $document->receipt)
                <div class="col-md-3">
                    <dt>Proveedor</dt>
                    <dd>{{ $receipt->supplier->name }}</dd>
                </div>
            @endif
        </dl>

        <hr>

        @if ($document->description)
            <dl class="mb-0">
                <dt>Descripción</dt>
                <dd>{{ $document->description }}</dd>
            </dl>
            <hr>
        @endif

        <div>
            Creado por
            <a href="{{ route('admin.users.edit', $document->creator->id) }}" target="_blank">
                {{ $document->creator->name }}
            </a> el
            {{ $document->created_at->format('d/m/Y - h:i a') }}
        </div>
    </div>
</div>
