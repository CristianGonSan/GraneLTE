@php
    $isActive = $supplier->is_active;
    $isInUse = $supplier->isInUse();
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Nombre</dt>
                    <dd class="col-sm-8 font-weight-bold">{{ $supplier->name }}</dd>

                    <dt class="col-sm-4 text-muted">Persona de contacto</dt>
                    <dd class="col-sm-8">
                        {{ $supplier->contact_person ?? 'Sin persona de contacto' }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Correo electrónico</dt>
                    <dd class="col-sm-8">
                        {{ $supplier->email ?? 'Sin correo electrónico' }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Teléfono</dt>
                    <dd class="col-sm-8">
                        {{ $supplier->phone ?? 'Sin teléfono' }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Dirección</dt>
                    <dd class="col-sm-8 text-muted">
                        {{ $supplier->address ?? 'Sin dirección' }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Descripción</dt>
                    <dd class="col-sm-8 text-muted mb-0">
                        {{ $supplier->description ?? 'Sin descripción' }}
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
                        <span class="info-box-text">Lotes de materia prima</span>
                        <span class="info-box-number">
                            {{ $supplier->rawMaterialBatches()->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-warning">
                        <i class="fas fa-file-alt"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Documentos de materia prima</span>
                        <span class="info-box-number">
                            {{ $supplier->rawMaterialDocuments()->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            @can('suppliers.edit')
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-primary mr-1">
                    <i class="fas fa-edit mr-1"></i> Editar
                </a>
            @endcan

            @can('suppliers.delete')
                <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="mr-1" icon="trash"
                    wire:click="delete" wire:target="delete" wire:swal-confirm="¿Eliminar este proveedor?"
                    swal-icon="warning" :disabled="$isInUse" :title="$isInUse ? 'No se puede eliminar: el proveedor está en uso' : ''" />
            @endcan

            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary mr-1">
                <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                @can('suppliers.toggle')
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
                    <dd class="col-6" title="{{ $supplier->created_at->format('d/m/Y H:i') }}" data-toggle="tooltip"
                        data-placement="left">
                        {{ $supplier->created_at->diffForHumans() }}
                    </dd>

                    <dt class="col-6 text-muted">Actualizado</dt>
                    <dd class="col-6 mb-0" title="{{ $supplier->updated_at->format('d/m/Y H:i') }}"
                        data-toggle="tooltip" data-placement="left">
                        {{ $supplier->updated_at->diffForHumans() }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
