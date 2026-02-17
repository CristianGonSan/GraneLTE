<?php

namespace App\Livewire\Admin\Roles;

use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleEdit extends Component
{
    use WithPagination;
    use Toast, FlashToast;

    public int $roleId;
    public string $name;

    public array $permissions = [];
    public array $selectedPermissions = [];

    public function mount(int $roleId): void
    {
        $this->roleId   = $roleId;
        $role           = $this->getRole();

        $this->name     = $role->name;

        foreach (Permission::orderBy('name')->get() as $permission) {
            $this->permissions[$permission->name] = $permission->name;
        }

        foreach ($role->permissions as $permission) {
            $this->selectedPermissions[] = $permission->name;
        }
    }

    public function render(): View
    {
        $role = $this->getRole();

        return view('livewire.admin.roles.role-edit', [
            'role'  => $role
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'                  => ['required', 'string', 'max:64', Rule::unique('roles', 'name')->ignore($this->roleId)],
            'selectedPermissions'   => ['nullable', 'array']
        ]);

        $role = $this->getRole();
        $role->update($validated);

        $role->syncPermissions($validated['selectedPermissions'] ?? []);

        $this->toastSuccess('Información actualizada');
    }

    public function delete(): void
    {
        $this->getRole()->delete();
        $this->flashToastSuccess('Rol eliminado.');
        redirect()->route('admin.roles.index');
    }

    private ?Role $role = null;

    private function getRole(): Role
    {
        return $this->role ??= Role::findOrFail($this->roleId);
    }
}
