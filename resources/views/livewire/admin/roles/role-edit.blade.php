<div>
    <h1 class="h4">{{ $role->name }}</h1>

    <div class="row">
        <div class="col-md-8">
            <form wire:submit.prevent="save">
                <div class="card">
                    <div class="card-body">
                        <x-adminlte-input name="name" label="Nombre *" placeholder="Nombre del rol" type="text"
                            maxlength="64" wire:model="name" required autofocus />

                        <x-form.select-wire-ignore name="permissions" label="Permisos" wire:loading.attr='disabled'
                            wire:target='save' multiple>
                            <x-adminlte-options :options="$permissions" :selected="$selectedPermissions" />
                        </x-form.select-wire-ignore>
                    </div>

                </div>
                <div class="mb-3">
                    <x-livewire.loading-button type='submit' label=" Guardar cambios" />

                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary ml-1">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Creado:</span>
                        <span>{{ $role->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Actualizado:</span>
                        <span>{{ $role->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <x-livewire.loading-button label="Eliminar" theme="outline-danger" icon="trash" wire:click="delete"
                    wire:target="delete" wire:swal-confirm="¿Eliminar Rol?" swal-icon="warning" />
            </div>
        </div>
    </div>
</div>


@push('js')
    <script>
        document.addEventListener("livewire:initialized", () => {
            let $wire = Livewire.first();

            roles = $('#permissions').select2({
                placeholder: 'Seleccione los permisos...',
                theme: 'bootstrap4',
                allowClear: true,
                language: 'es',
                dropdownAutoWidth: true,
                width: 'resolve'
            });

            roles.on('change', function(e) {
                const value = $(this).val();
                $wire.set('selectedPermissions', value);
            });
        });
    </script>
@endpush
