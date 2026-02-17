<div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span class="text-muted">Creado:</span>
                <span>{{ $created_at }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Actualizado:</span>
                <span>{{ $updated_at }}</span>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <x-livewire.loading-button label="{{ $label }}" theme="{{ $theme }}" icon="toggle-on"
            wire:click="toggleActive" wire:target="toggleActive" wire:swal-confirm="{{ $confirm }}" />

        <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="ml-1" icon="trash"
            wire:click="delete" wire:target="delete" wire:swal-confirm="¿Eliminar registro?" swal-icon="warning" />
    </div>
</div>
