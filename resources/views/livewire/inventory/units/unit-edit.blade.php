<div>
    <h1 class="h4">{{ $name }}</h1>

    <div class="row">
        <div class="col-md-8">
            <form wire:submit.prevent='save'>
                <div class="card">
                    <div class="card-body">
                        <x-adminlte-input id="name" name="name" label="Nombre *"
                            placeholder="ejemplo: kilogramo" type="text" wire:model="name" maxlength="64" required
                            autofocus />

                        <x-adminlte-input name="symbol" label="Simbolo *" placeholder="ejemplo: kg" type="text"
                            wire:model="symbol" maxlength="8" required />
                    </div>
                </div>

                <div class="mb-3">
                    <x-livewire.loading-button label="Guardar cambios" type="submit" class="mr-1" />

                    <a href="{{ route('units.index') }}" class="btn btn-outline-secondary" wire:loading.attr="disabled">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <div class="col-md-4">
            @include('partials.livewire.edit.model-status', [
                'model' => $unit,
                'modelName' => 'Unidad',
            ])
        </div>
    </div>
</div>
