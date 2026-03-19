<?php

namespace App\Livewire\Inventory\Units;

use App\Models\Inventory\Unit;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class UnitCreate extends Component
{
    use Toast, FlashToast;

    public string $name;
    public string $symbol;

    public bool $createAnother = false;

    public function render(): View
    {
        return view('livewire.inventory.units.unit-create');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'      => ['required', 'string', 'max:64', Rule::unique('units')],
            'symbol'    => ['required', 'string', 'max:8', Rule::unique('units')],
        ]);

        $unit = Unit::create($validated);

        if ($this->createAnother) {
            $this->reset([
                'name',
                'symbol'
            ]);
            $this->toastSuccess('Unidad creada');
        } else {
            $this->flashToastSuccess('Unidad creada');
            redirect()->route('units.show', $unit->id);
        }
    }
}
