<div>
    <form wire:submit='save'>
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-6" name="name" label="Nombre *"
                    placeholder="Ingrese el nombre del proveedor" type="text" wire:model="name" maxlength="128" required
                    autofocus />

                <x-adminlte-input fgroup-class="col-md-6" name="contact_person" label="Persona de contacto"
                    placeholder="Nombre de la persona de contacto" type="text" wire:model="contact_person"
                    maxlength="128" />

                <x-adminlte-input fgroup-class="col-md-6" name="email" label="Correo electrónico"
                    placeholder="correo@ejemplo.com" type="email" wire:model="email" maxlength="191" />

                <x-adminlte-input fgroup-class="col-md-6" name="phone" label="Teléfono"
                    placeholder="Ej: +52 968 123 456" type="text" wire:model="phone" maxlength="20" />

                <x-adminlte-textarea fgroup-class="col-md-6" name="address" label="Dirección"
                    placeholder="Ingrese la dirección del proveedor..." rows="3" wire:model="address"
                    maxlength="512" />

                <x-adminlte-textarea fgroup-class="col-md-6" name="description" label="Descripción"
                    placeholder="Ingrese una descripción..." rows="3" wire:model="description" maxlength="512" />

                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <x-checkbox name="createAnother" label="Guardar y crear otro"
                        title="Permite ingresar otro proveedor después de guardar" wire:model='createAnother' />
                </div>
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type="submit" label="Guardar" class="mr-1" />

            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary" wire:loading.attr="disabled">
                Cancelar
            </a>
        </div>
    </form>
</div>
