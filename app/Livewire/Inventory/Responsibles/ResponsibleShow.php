<?php

namespace App\Livewire\Inventory\Responsibles;

use App\Models\Inventory\Responsible;
use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ResponsibleShow extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

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
        $this->toastSuccess(
            $this->responsible()->toggleActive()
                ? 'Responsable activado'
                : 'Responsable desactivado'
        );
    }

    public function delete(): void
    {
        $responsible = $this->responsible();

        if ($responsible->isInUse()) {
            $this->alertError(
                'El responsable está en uso, se recomienda desactivarlo.',
                'Responsable en uso'
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
