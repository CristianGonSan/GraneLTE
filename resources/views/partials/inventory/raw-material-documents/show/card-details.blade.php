<div class="card mb-3">
    <div class="card-body">
        <div>
            {{ $document->effective_at->format('d/m/Y - h:i a') }}
        </div>

        <hr>

        <div class="row">
            <div class="col-md-3">
                <strong>{{ $document->reference_type ?? 'Referencia' }}</strong>
                <div>
                    {{ $document->reference_number ?? 'S/N' }}
                </div>
            </div>

            <div class="col-md-3">
                <strong>Responsable</strong>
                <div>{{ $document->responsible?->name ?? 'S/N' }}</div>
            </div>

            @if ($receipt = $document->receipt)
                <div class="col-md-3">
                    <strong>Proveedor</strong>
                    <div>{{ $receipt->supplier->name }}</div>
                </div>
            @endif
        </div>

        <hr>

        @if ($document->description)
            <div>
                <strong>Descripción</strong>
                <div>{{ $document->description }}</div>
            </div>
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
