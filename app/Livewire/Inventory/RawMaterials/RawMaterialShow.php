<?php

namespace App\Livewire\Inventory\RawMaterials;

use App\Models\Inventory\RawMaterial;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RawMaterialShow extends Component
{
    use Toast, FlashToast;

    #[Locked]
    public int $rawMaterialId;

    public function mount(int $rawMaterialId): void
    {
        $this->rawMaterialId = $rawMaterialId;
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-materials.raw-material-show', [
            'rawMaterial' => $this->rawMaterial()
        ]);
    }

    public function toggleActive(): void
    {
        if (cannot('raw-materials.toggle')) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $this->toastSuccess(
            $this->rawMaterial()->toggleActive()
                ? 'Materia prima activada'
                : 'Materia prima desactivada'
        );
    }

    public function delete(): void
    {
        if (cannot('raw-materials.delete')) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $rawMaterial = $this->rawMaterial();

        if ($rawMaterial->isInUse()) {
            $this->toastError('No se puede eliminar: la materia prima está en uso');
        } else {
            $rawMaterial->delete();
            $this->flashToastSuccess('Materia prima eliminada');
            redirect()->route('raw-materials.index');
        }
    }

    private ?RawMaterial $rawMaterial = null;

    private function rawMaterial(): RawMaterial
    {
        return $this->rawMaterial ??= RawMaterial::findOrFail($this->rawMaterialId);
    }
}
