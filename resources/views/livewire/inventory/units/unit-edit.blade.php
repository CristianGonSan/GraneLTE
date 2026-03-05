<div>
    <form wire:submit.prevent='save'>
        <div class="card">
            <div class="card-body">
                <x-adminlte-input id="name" name="name" label="Nombre *" placeholder="ejemplo: kilogramo"
                    type="text" wire:model="name" maxlength="64" required autofocus />

                <x-adminlte-input name="symbol" label="Simbolo *" placeholder="ejemplo: kg" type="text"
                    wire:model="symbol" maxlength="8" required />
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button label="Guardar cambios" type="submit" class="mr-1" />

            <a href="{{ route('units.show', $unitId) }}" class="btn btn-outline-secondary" wire:loading.attr="disabled">
                Cancelar
            </a>
        </div>
    </form>
</div>
