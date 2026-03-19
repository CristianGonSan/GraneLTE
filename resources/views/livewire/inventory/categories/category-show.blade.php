@php
    $isActive = $category->is_active;
    $isInUse = $category->isInUse();
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Nombre</dt>
                    <dd class="col-sm-8 font-weight-bold">{{ $category->name }}</dd>

                    <dt class="col-sm-4 text-muted">Descripción</dt>
                    <dd class="col-sm-8 text-muted mb-0">
                        {{ $rawMaterial->description ?? 'Sin descripción' }}
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
                        <span class="info-box-text">Materias primas</span>
                        <span class="info-box-number">
                            {{ $category->rawMaterials()->count() }}
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
                        <span class="info-box-text">Costo de categoria</span>
                        <span class="info-box-number">
                            $ {{ number_format($category->current_cost, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            @can('categories.edit')
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-primary mr-1">
                    <i class="fas fa-edit mr-1"></i> Editar
                </a>
            @endcan

            @can('categories.delete')
                <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="mr-1" icon="trash"
                    wire:click="delete" wire:target="delete" wire:swal-confirm="¿Eliminar esta categoría?"
                    swal-icon="warning" :disabled="$isInUse" :title="$isInUse ? 'No se puede eliminar: la categoría está en uso' : ''" />
            @endcan

            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary mr-1">
                <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                @can('categories.toggle')
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
                    <dd class="col-6" title="{{ $category->created_at->format('d/m/Y H:i') }}" data-toggle="tooltip"
                        data-placement="left">
                        {{ $category->created_at->diffForHumans() }}
                    </dd>

                    <dt class="col-6 text-muted">Actualizado</dt>
                    <dd class="col-6 mb-0" title="{{ $category->updated_at->format('d/m/Y H:i') }}"
                        data-toggle="tooltip" data-placement="left">
                        {{ $category->updated_at->diffForHumans() }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
