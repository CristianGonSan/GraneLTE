<?php

namespace App\Livewire\Inventory\Responsibles;

use App\Models\Inventory\Responsible;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ResponsibleShow extends Component
{
    use Toast, FlashToast;

    #[Locked]
    public int $responsibleId;

    public function mount(int $responsibleId): void
    {
        $this->responsibleId = $responsibleId;
    }

    public function render(): View
    {
        return view('livewire.inventory.responsibles.responsible-show', [
            'responsible' => $this->responsible()
        ]);
    }

    public function toggleActive(): void
    {
        if (cannot('responsibles.toggle')) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $this->toastSuccess(
            $this->responsible()->toggleActive()
                ? 'Responsable activado'
                : 'Responsable desactivado'
        );
    }

    public function delete(): void
    {
        if (cannot('responsibles.delete')) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $responsible = $this->responsible();

        if ($responsible->isInUse()) {
            $this->toastError(
                'No se puede eliminar: el responsable está en uso'
            );
        } else {
            $responsible->delete();
            $this->flashToastSuccess('Responsable eliminado');
            redirect()->route('responsibles.index');
        }
    }

    private ?Responsible $responsible = null;

    private function responsible(): Responsible
    {
        return $this->responsible ??= Responsible::findOrFail($this->responsibleId);
    }
}
