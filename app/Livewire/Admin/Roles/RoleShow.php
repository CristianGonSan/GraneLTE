<?php

namespace App\Livewire\Admin\Roles;

use App\Models\User;
use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleShow extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

    #[Locked]
    public int $roleId;

    public function mount(int $roleId): void
    {
        $this->roleId = $roleId;
    }

    public function render(): View
    {
        return view('livewire.admin.roles.role-show', [
            'role' => $this->role()
        ]);
    }

    public function delete(): void
    {
        $role = $this->role();

        $role->delete();
        $this->flashToastSuccess('Rol eliminado');
        redirect()->route('admin.roles.index');
    }

    public function getTranslatedPermissions(): array
    {
        $permissions = [];
        foreach ($this->role->permissions()->orderBy('id')->get() as $p) {
            $langKey = "permissions.{$p->name}";
            $permissions[$p->name] = __($langKey);
        }
        return $permissions;
    }

    private ?Role $role = null;

    private function role(): Role
    {
        return $this->role ??= Role::findOrFail($this->roleId);
    }
}
