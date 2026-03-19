<?php

namespace App\Livewire\Inventory\Warehouses;

use App\Models\Inventory\Warehouse;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class WarehouseShow extends Component
{
    use Toast, FlashToast;

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
        if (cannot('warehouses.toggle')) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $this->toastSuccess(
            $this->warehouse()->toggleActive()
                ? 'Almacén activado'
                : 'Almacén desactivado'
        );
    }

    public function delete(): void
    {
        if (cannot('warehouses.delete')) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $warehouse = $this->warehouse();

        if ($warehouse->isInUse()) {
            $this->toastError(
                'No se puede eliminar: el almacén está en uso'
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
