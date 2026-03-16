<form wire:submit="update">
    <div class="card">
        <div class="card-header border-0">
            <p class="card-title">CAMBIAR CONTRASEÑA</p>
        </div>
        <div class="card-body pt-0">
            <input type="email" name="email" class="d-none" autocomplete="username">

            <x-adminlte-input fgroup-class="mb-1" name="current_password" placeholder="Contraseña Vieja"
                autocomplete="current-password" type="password" maxlength="64" required wire:model="current_password"
                required />

            <x-adminlte-input fgroup-class="mb-1" name="password" placeholder="Nueva Contraseña"
                autocomplete="new-password" type="password" maxlength="64" wire:model="password" required />

            <x-adminlte-input name="password_confirmation" placeholder="Confirmar Nueva Contraseña"
                autocomplete="new-password" type="password" maxlength="64" wire:model="password_confirmation"
                required />
        </div>
    </div>

    <x-livewire.loading-button type='submit' label="Cambiar contraseña" class="mb-3" />
</form>
