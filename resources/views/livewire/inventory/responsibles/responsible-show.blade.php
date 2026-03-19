@php
    $isActive = $responsible->is_active;
    $isInUse = $responsible->isInUse();
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Nombre</dt>
                    <dd class="col-sm-8 font-weight-bold">{{ $responsible->name }}</dd>

                    <dt class="col-sm-4 text-muted">Identificador</dt>
                    <dd class="col-sm-8">
                        {{ $responsible->identifier ?? 'Sin identificador' }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Cargo</dt>
                    <dd class="col-sm-8">
                        {{ $responsible->position ?? 'Sin cargo' }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Departamento</dt>
                    <dd class="col-sm-8">
                        {{ $responsible->department ?? 'Sin departamento' }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Teléfono</dt>
                    <dd class="col-sm-8">
                        {{ $responsible->phone ?? 'Sin teléfono' }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Correo electrónico</dt>
                    <dd class="col-sm-8 mb-0">
                        {{ $responsible->email ?? 'Sin correo electrónico' }}
                    </dd>
                </dl>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-file-alt"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Documentos de materia prima</span>
                        <span class="info-box-number">
                            {{ $responsible->rawMaterialDocuments()->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            @can('responsibles.edit')
                <a href="{{ route('responsibles.edit', $responsible->id) }}" class="btn btn-outline-primary mr-1">
                    <i class="fas fa-edit mr-1"></i> Editar
                </a>
            @endcan

            @can('responsibles.delete')
                <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="mr-1" icon="trash"
                    wire:click="delete" wire:target="delete" wire:swal-confirm="¿Eliminar este responsable?"
                    swal-icon="warning" :disabled="$isInUse" :title="$isInUse ? 'No se puede eliminar: el proveedor está en uso' : ''" />
            @endcan

            <a href="{{ route('responsibles.index') }}" class="btn btn-outline-secondary mr-1">
                <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                @can('responsibles.toggle')
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="toggleActive"
                            {{ $isActive ? 'checked' : '' }} wire:click="toggleActive" />
                        <label class="custom-control-label" for="toggleActive">
                            {{ $isActive ? 'Activo' : 'Inactivo' }}
                        </label>
                    </div>
                @else
                    <span class="badge {{ $isActive ? 'badge-success' : 'badge-secondary' }}">
                        {{ $isActive ? 'Activo' : 'Inactivo' }}
                    </span>
                @endcan

                <hr>

                <dl class="row mb-0">
                    <dt class="col-6 text-muted">Creado</dt>
                    <dd class="col-6" title="{{ $responsible->created_at->format('d/m/Y H:i') }}"
                        data-toggle="tooltip" data-placement="left">
                        {{ $responsible->created_at->diffForHumans() }}
                    </dd>

                    <dt class="col-6 text-muted">Actualizado</dt>
                    <dd class="col-6 mb-0" title="{{ $responsible->updated_at->format('d/m/Y H:i') }}"
                        data-toggle="tooltip" data-placement="left">
                        {{ $responsible->updated_at->diffForHumans() }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
