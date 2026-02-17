<div>
    <h1 class="h4">{{ $responsible->mediumText('name') }}</h1>

    <div class="row">
        <div class="col-md-8">
            <form wire:submit.prevent="save">
                <div class="card mb-3">
                    <div class="card-body form-row">
                        <x-adminlte-input fgroup-class="col-md-6" name="name" label="Nombre *"
                            placeholder="Ingrese el nombre del responsable" type="text" wire:model="name"
                            maxlength="128" required autofocus />

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
                    </div>
                </div>

                <div class="mb-3">
                    <x-livewire.loading-button type="submit" label="Guardar cambios" class="mr-1" />

                    <a href="{{ route('responsibles.index') }}" class="btn btn-outline-secondary"
                        wire:loading.attr="disabled">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <div class="col-md-4">
            @include('partials.livewire.edit.model-status', [
                'model' => $responsible,
                'modelName' => 'Responsable',
            ])
        </div>
    </div>
</div>
