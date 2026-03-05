<?php

namespace App\Livewire\Inventory\Warehouses;

use App\Models\Inventory\Warehouse;
use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class WarehouseShow extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

    #[Locked]
    public int $warehouseId;

    public function mount(int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
    }

    public function render(): View
    {
        return view('livewire.inventory.warehouses.warehouse-show', [
            'warehouse' => $this->warehouse()
        ]);
    }

    public function toggleActive(): void
    {
        $this->toastSuccess(
            $this->warehouse()->toggleActive()
                ? 'Almacén activado'
                : 'Almacén desactivado'
        );
    }

    public function delete(): void
    {
        $warehouse = $this->warehouse();

        if ($warehouse->isInUse()) {
            $this->alertError(
                'El almacén está en uso, se recomienda desactivarlo.',
                'Almacén en uso'
            );
        } else {
            $warehouse->delete();
            $this->flashToastSuccess('Almacén eliminado');
            redirect()->route('warehouses.index');
        }
    }

    private ?Warehouse $warehouse = null;

    private function warehouse(): Warehouse
    {
        return $this->warehouse ??= Warehouse::findOrFail($this->warehouseId);
    }
}
