@php
    $receipt = $document->receipt;
    $col = $receipt ? 'col-md-3' : 'col-md-4';
@endphp

<div class="card mb-3">
    <div class="card-body">
        <dl class="row mb-0">
            <div class="{{ $col }}">
                <dt>Fecha efectiva</dt>
                <dd>{{ $document->effective_at->format('d/m/Y - h:i a') }}</dd>
            </div>
            <div class="{{ $col }}">
                <dt>{{ $document->reference_type ?? 'Referencia' }}</dt>
                <dd>{{ $document->reference_number ?? 'S/N' }}</dd>
            </div>

            <div class="{{ $col }}">
                <dt>Responsable</dt>
                <dd>{{ $document->responsible?->name ?? 'S/N' }}</dd>
            </div>

            @if ($receipt)
                <div class="{col-md-3">
                    <dt>Proveedor</dt>
                    <dd>{{ $receipt->supplier->name }}</dd>
                </div>
            @endif
        </dl>

        @if ($document->description)
            <dl class="mb-0">
                <dt>Descripción</dt>
                <dd class="text-muted">{{ $document->description }}</dd>
            </dl>
        @endif

        @if ($attachment = $document->getAttachment())
            <div class="d-flex align-items-center border rounded px-3 py-2 mt-3">
                <i
                    class="fas fa-fw fa-2x mr-3 {{ str_starts_with($attachment->mime_type, 'image/') ? 'fa-file-image' : 'fa-file-pdf' }} text-secondary"></i>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="font-weight-bold text-truncate">{{ $attachment->file_name }}</div>
                    <div class="text-muted" style="font-size: .8rem">{{ $attachment->human_readable_size }}</div>
                </div>
                <div class="ml-3 text-nowrap">
                    <a href="{{ route('media.show', $attachment->id) }}" target="_blank"
                        class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-fw fa-arrow-up-right-from-square"></i>
                    </a>
                    <a href="{{ route('media.download', $attachment->id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-fw fa-download"></i>
                    </a>
                </div>
            </div>
        @endif

        <hr>

        <div>
            Creado por
            @can('users.view')
                <a href="{{ route('users.show', $document->created_by) }}" target="_blank">
                    {{ $document->creator->name }}
                </a>
            @else
                {{ $document->creator->name }}
            @endcan
            el {{ $document->created_at->format('d/m/Y - h:i a') }}
        </div>
    </div>
</div>
