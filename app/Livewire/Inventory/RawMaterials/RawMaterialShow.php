<?php

namespace App\Livewire\Inventory\RawMaterials;

use App\Exports\Pdf\Inventory\RawMaterialPdfExport;
use App\Models\Inventory\RawMaterial;
use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RawMaterialShow extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

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
        $this->toastSuccess(
            $this->rawMaterial()->toggleActive()
                ? 'Materia prima activada'
                : 'Materia prima desactivada'
        );
    }

    public function delete(): void
    {
        $rawMaterial = $this->rawMaterial();

        if ($rawMaterial->isInUse()) {
            $this->alertError(
                'La materia prima está en uso, se recomienda desactivarla.',
                'Materia prima en uso'
            );
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
