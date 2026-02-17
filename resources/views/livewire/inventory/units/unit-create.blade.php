<div>
    <h1 class="h4">Crear Unidad</h1>

    <form wire:submit.prevent='save'>
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-12" id="name" name="name" label="Nombre *"
                    placeholder="ejemplo: kilogramo" type="text" wire:model="name" maxlength="64" required autofocus />

                <x-adminlte-input fgroup-class="col-md-12" name="symbol" label="Simbolo *" placeholder="ejemplo: kg"
                    type="text" wire:model="symbol" maxlength="8" required />

                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <x-checkbox name="createAnother" label="Guardar y crear otra"
                        title="Permite ingresar otra categoria tras guardar" wire:model='createAnother' />
                </div>
            </div>

        </div>
        <div class="mb-3">
            <x-livewire.loading-button type="submit" label="Guardar" class="mr-1" />

            <a href="{{ route('units.index') }}" class="btn btn-outline-secondary" wire:loading.attr="disabled">
                Cancelar
            </a>
        </div>
    </form>
</div>
