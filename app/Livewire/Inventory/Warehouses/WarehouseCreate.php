<?php

namespace App\Livewire\Inventory\Warehouses;

use App\Models\Inventory\Warehouse;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class WarehouseCreate extends Component
{
    use Toast, FlashToast;

    public string $name;
    public ?string $location = null;
    public ?string $description = null;

    public bool $createAnother = false;

    public function render(): View
    {
        return view('livewire.inventory.warehouses.warehouse-create');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'        => ['required', 'string', 'max:128', Rule::unique('warehouses')],
            'location'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:512']
        ]);

        $warehouse = Warehouse::create($validated);

        if ($this->createAnother) {
            $this->reset([
                'name',
                'location',
                'description'
            ]);
            $this->toastSuccess('Almacén creado');
        } else {
            $this->flashToastSuccess('Almacén creado');
            redirect()->route('warehouses.show', $warehouse->id);
        }
    }
}
