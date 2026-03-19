<?php

namespace App\Livewire\Inventory\Suppliers;

use App\Models\Inventory\Supplier;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class SupplierShow extends Component
{
    use Toast, FlashToast;

    #[Locked]
    public int $supplierId;

    public function mount(int $supplierId): void
    {
        $this->supplierId = $supplierId;
    }

    public function render(): View
    {
        return view('livewire.inventory.suppliers.supplier-show', [
            'supplier' => $this->supplier()
        ]);
    }

    public function toggleActive(): void
    {
        if (cannot('suppliers.toggle')) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $this->toastSuccess(
            $this->supplier()->toggleActive()
                ? 'Proveedor activado'
                : 'Proveedor desactivado'
        );
    }

    public function delete(): void
    {
        if (cannot('suppliers.delete')) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $supplier = $this->supplier();

        if ($supplier->isInUse()) {
            $this->toastError(
                'No se puede eliminar: el proveedor está en uso'
            );
        } else {
            $supplier->delete();
            $this->flashToastSuccess('Proveedor eliminado');
            redirect()->route('suppliers.index');
        }
    }

    private ?Supplier $supplier = null;

    private function supplier(): Supplier
    {
        return $this->supplier ??= Supplier::findOrFail($this->supplierId);
    }
}
