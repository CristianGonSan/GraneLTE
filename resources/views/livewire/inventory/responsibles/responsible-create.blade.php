<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-6" name="name" label="Nombre *"
                    placeholder="Ingrese el nombre del responsable" type="text" wire:model="name" maxlength="128"
                    required autofocus />

                <x-adminlte-input fgroup-class="col-md-6" name="identifier" label="Identificador"
                    placeholder="RFC, número de empleado, etc." type="text" wire:model="identifier"
                    maxlength="128" />

                <x-adminlte-input fgroup-class="col-md-6" name="position" label="Puesto"
                    placeholder="Puesto del responsable" type="text" wire:model="position" maxlength="128" />

                <x-adminlte-input fgroup-class="col-md-6" name="department" label="Departamento"
                    placeholder="Departamento" type="text" wire:model="department" maxlength="128" />

                <x-adminlte-input fgroup-class="col-md-6" name="email" label="Correo electrónico"
                    placeholder="correo@ejemplo.com" type="email" wire:model="email" maxlength="191" />

                <x-adminlte-input fgroup-class="col-md-6" name="phone" label="Teléfono"
                    placeholder="Ej: +52 968 123 456" type="text" wire:model="phone" maxlength="20" />

                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <x-checkbox name="createAnother" label="Guardar y crear otro"
                        title="Permite ingresar otro responsable después de guardar" wire:model="createAnother" />
                </div>
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type="submit" label="Guardar" class="mr-1" />

            <a href="{{ route('responsibles.index') }}" class="btn btn-outline-secondary" wire:loading.attr="disabled">
                Cancelar
            </a>
        </div>
    </form>
</div>
