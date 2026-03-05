<?php

namespace App\Livewire\Inventory\Units;

use App\Models\Inventory\Unit;
use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UnitShow extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

    #[Locked]
    public int $unitId;

    public function mount(int $unitId): void
    {
        $this->unitId = $unitId;
    }

    public function render(): View
    {
        return view('livewire.inventory.units.unit-show', [
            'unit'   => $this->unit()
        ]);
    }

    public function toggleActive(): void
    {
        $this->toastSuccess(
            $this->unit()->toggleActive()
                ? 'Unidad activada'
                : 'Unidad desactivada'
        );
    }

    public function delete(): void
    {
        $unit = $this->unit();

        if ($unit->isInUse()) {
            $this->alertError(
                'La Unidad está en uso, se recomienda desactivarla.',
                'Unidad en uso'
            );
        } else {
            $unit->delete();
            $this->flashToastSuccess('Unidad eliminada');
            redirect()->route('units.index');
        }
    }

    private ?Unit $unit = null;

    private function unit(): Unit
    {
        return $this->unit ??= Unit::findOrFail($this->unitId);
    }
}
