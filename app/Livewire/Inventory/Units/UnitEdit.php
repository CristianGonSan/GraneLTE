<?php

namespace App\Livewire\Inventory\Units;

use App\Models\Inventory\Unit;

use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class UnitEdit extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

    public int $unitId;

    public string $name;
    public string $symbol;



    public function mount(int $unitId): void
    {
        $this->unitId   = $unitId;

        $unit           = $this->unit();

        $this->name     = $unit->name;
        $this->symbol   = $unit->symbol;
    }

    public function render(): View
    {
        return view('livewire.inventory.units.unit-edit', [
            'unit' => $this->unit()
        ]);
    }

    public function save(): void
    {
        $unitId = $this->unitId;
        $validated = $this->validate([
            'name'      => ['required', 'string', 'max:64', Rule::unique('units')->ignore($unitId, 'id')],
            'symbol'    => ['required', 'string', 'max:8', Rule::unique('units')->ignore($unitId, 'id')],
        ]);

        $this->unit()->update($validated);

        $this->toastSuccess('Unidad actualizada.');
    }

    public function toggleActive(): void
    {
        $this->toastSuccess($this->unit()->toggleActive() ?
            'Unidad activada' :
            'Unidad desactivada');
    }

    public function delete(): void
    {
        $unit = $this->unit();

        if ($unit->isInUse()) {
            $this->alertError('La unidad esta en uso, sugerimos desactivarla.', 'Unidad en Uso.');
        } else {
            $unit->delete();
            $this->flashToastSuccess('Unidad eliminada.');
            redirect()->route('units.index');
        }
    }


    private ?Unit $unit = null;

    private function unit(): Unit
    {
        return $this->unit ??= Unit::findOrFail($this->unitId);
    }
}
