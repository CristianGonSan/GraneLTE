<?php

namespace App\Livewire\Inventory\Warehouses;

use App\Models\Inventory\Warehouse;
use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class WarehouseEdit extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

    public int $warehouseId;

    public string $name;
    public ?string $location = null;
    public ?string $description = null;

    public function mount(int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;

        $warehouse         = $this->warehouse();

        $this->name        = $warehouse->name;
        $this->location    = $warehouse->location;
        $this->description = $warehouse->description;
    }

    public function render(): View
    {
        return view('livewire.inventory.warehouses.warehouse-edit', [
            'warehouse' => $this->warehouse(),
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'        => ['required', 'string', 'max:128', Rule::unique('warehouses')->ignore($this->warehouseId)],
            'location'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:512'],
        ]);

        $this->warehouse()->update($validated);

        $this->toastSuccess('Almacén actualizado');
    }

    private ?Warehouse $warehouse = null;

    private function warehouse(): Warehouse
    {
        return $this->warehouse ??= Warehouse::findOrFail($this->warehouseId);
    }
}
