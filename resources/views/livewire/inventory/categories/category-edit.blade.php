<div>
    <form wire:submit.prevent='save'>
        <div class="card">
            <div class="card-body">
                <x-adminlte-input name="name" label="Nombre *" placeholder="ejemplo: suplemento" type="text"
                    wire:model="name" maxlength="64" required autofocus />

                <x-adminlte-textarea name="description" label="Descripción" placeholder="Inserte una description..."
                    wire:model="description" rows="3" maxlength="255">
                </x-adminlte-textarea>
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button label="Guardar cambios" type="submit" class="mr-1" />

            <a href="{{ route('categories.show', $categoryId) }}" class="btn btn-outline-secondary" wire:loading.attr="disabled">
                Cancelar
            </a>
        </div>
    </form>
</div>
