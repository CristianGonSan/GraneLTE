<?php

namespace App\Livewire\Inventory\Suppliers;

use App\Models\Inventory\Responsible;
use App\Models\Inventory\Supplier;
use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class SupplierShow extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

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
        $this->toastSuccess(
            $this->supplier()->toggleActive()
                ? 'Proveedor activado'
                : 'Proveedor desactivado'
        );
    }

    public function delete(): void
    {
        $supplier = $this->supplier();

        if ($supplier->isInUse()) {
            $this->alertError(
                'El proveedor está en uso, se recomienda desactivarlo.',
                'Proveedor en uso'
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
