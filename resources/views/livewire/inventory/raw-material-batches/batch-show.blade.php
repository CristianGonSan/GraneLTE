<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Código interno</dt>
                    <dd class="col-sm-8 font-weight-bold">{{ $batch->batch_code }}</dd>

                    <dt class="col-sm-4 text-muted">Código externo</dt>
                    <dd class="col-sm-8">
                        {{ $batch->external_batch_code ?? '—' }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Materia prima</dt>
                    <dd class="col-sm-8">{{ $batch->material->name }}</dd>

                    <dt class="col-sm-4 text-muted">Proveedor</dt>
                    <dd class="col-sm-8">{{ $batch->supplier->name }}</dd>

                    <dt class="col-sm-4 text-muted">Fecha de recepción</dt>
                    <dd class="col-sm-8">{{ $batch->received_at->format('d/m/Y') }}</dd>

                    <dt class="col-sm-4 text-muted">Fecha de vencimiento</dt>
                    <dd class="col-sm-8 {{ $batch->isExpired() ? 'text-danger font-weight-bold' : '' }}">
                        {{ $batch->expiration_date?->format('d/m/Y') ?? '--/--/----' }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Cantidad recibida</dt>
                    <dd class="col-sm-8">
                        {{ number_format($batch->received_quantity, 3) }}
                        {{ $batch->material->unit->symbol }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Costo unitario</dt>
                    <dd class="col-sm-8">
                        $ {{ number_format($batch->received_unit_cost, 2) }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Costo total recibido</dt>
                    <dd class="col-sm-8 mb-0">
                        $ {{ number_format($batch->received_total_cost, 2) }}
                    </dd>
                </dl>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-boxes"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">En stock</span>
                        <span class="info-box-number">
                            {{ number_format($batch->current_quantity, 3) }}
                            {{ $batch->material->unit->symbol }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-teal">
                        <i class="fas fa-dollar-sign"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Costo de stock</span>
                        <span class="info-box-number">
                            $ {{ number_format($batch->current_cost, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if ($batch->isExpired())
            <div class="alert alert-danger alert-dismissible shadow-sm">
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Lote vencido
                </h5>
                <span class="text-sm">
                    Este lote venció el {{ $batch->expiration_date->format('d/m/Y') }}
                    ({{ $batch->expiration_date->diffForHumans() }}).
                </span>
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('raw-material-batches.index') }}" class="btn btn-outline-secondary mr-1">
                <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-6 text-muted">Creado</dt>
                    <dd class="col-6" title="{{ $batch->created_at->format('d/m/Y H:i') }}" data-toggle="tooltip"
                        data-placement="left">
                        {{ $batch->created_at->diffForHumans() }}
                    </dd>

                    <dt class="col-6 text-muted">Actualizado</dt>
                    <dd class="col-6 mb-0" title="{{ $batch->updated_at->format('d/m/Y H:i') }}" data-toggle="tooltip"
                        data-placement="left">
                        {{ $batch->updated_at->diffForHumans() }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
