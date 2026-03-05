<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UserShow extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

    #[Locked]
    public int $userId;

    public function mount(int $userId): void
    {
        $this->userId = $userId;
    }

    public function render(): View
    {
        return view('livewire.admin.users.user-show', [
            'user' => $this->user()
        ]);
    }

    public function toggleActive(): void
    {
        $this->toastSuccess(
            $this->user()->toggleActive()
                ? 'Usuario activado'
                : 'Usuario desactivado'
        );
    }

    public function delete(): void
    {
        $user = $this->user();

        if ($user->isInUse()) {
            $this->alertError(
                'El usario está en uso, se recomienda desactivarlo.',
                'Usuario en uso'
            );
        } else {
            $user->delete();
            $this->flashToastSuccess('Usuario eliminado');
            redirect()->route('admin.users.index');
        }
    }

    private ?User $user = null;

    private function user(): User
    {
        return $this->user ??= User::findOrFail($this->userId);
    }
}
