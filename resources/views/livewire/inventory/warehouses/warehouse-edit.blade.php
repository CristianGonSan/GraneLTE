<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-12" name="name" label="Nombre *"
                    placeholder="Ingrese el nombre del almacén" type="text" wire:model="name" maxlength="128" required
                    autofocus />

                <x-adminlte-textarea fgroup-class="col-md-12" name="location" label="Ubicación"
                    placeholder="Ingrese la ubicación del almacén" rows="3" wire:model="location"
                    maxlength="255" />

                <x-adminlte-textarea fgroup-class="col-md-12" name="description" label="Descripción"
                    placeholder="Ingrese una descripción..." rows="3" wire:model="description" maxlength="512" />
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type="submit" label="Guardar cambios" class="mr-1" />

            <a href="{{ route('warehouses.show', $warehouseId) }}" class="btn btn-outline-secondary"
                wire:loading.attr="disabled">
                Cancelar
            </a>
        </div>
    </form>
</div>
