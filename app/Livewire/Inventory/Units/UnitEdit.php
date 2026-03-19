<?php

namespace App\Livewire\Inventory\Units;

use App\Models\Inventory\Unit;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UnitEdit extends Component
{
    use Toast, FlashToast;

    #[Locked]
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
        return view('livewire.inventory.units.unit-edit');
    }

    public function save(): void
    {
        $unitId = $this->unitId;
        $validated = $this->validate([
            'name'      => ['required', 'string', 'max:64', Rule::unique('units')->ignore($unitId, 'id')],
            'symbol'    => ['required', 'string', 'max:8', Rule::unique('units')->ignore($unitId, 'id')],
        ]);

        $this->unit()->update($validated);

        $this->toastSuccess('Unidad actualizada');
    }

    private ?Unit $unit = null;

    private function unit(): Unit
    {
        return $this->unit ??= Unit::findOrFail($this->unitId);
    }
}
