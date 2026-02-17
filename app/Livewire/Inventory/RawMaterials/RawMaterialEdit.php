<?php

namespace App\Livewire\Inventory\RawMaterials;

use App\Models\Inventory\RawMaterial;
use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class RawMaterialEdit extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

    public int $rawMaterialId;
    public bool $hasBatches = false;

    public string $name;
    public string $abbreviation;
    public ?string $description = null;
    public ?float $minimum_stock = null;
    public int $unit_id;
    public string $unitText;
    public int $category_id;
    public string $categoryText;

    public function mount(int $rawMaterialId): void
    {
        $this->rawMaterialId = $rawMaterialId;

        $rawMaterial = $this->rawMaterial();

        $this->name           = $rawMaterial->name;
        $this->abbreviation   = $rawMaterial->abbreviation;
        $this->description    = $rawMaterial->description;
        $this->minimum_stock  = $rawMaterial->minimum_stock;

        $this->hasBatches     = $rawMaterial->batches()->exists();

        $unit                 = $rawMaterial->unit;
        $this->unit_id        = $unit->id;
        $this->unitText       = $unit->name;

        $category             = $rawMaterial->category;
        $this->category_id    = $category->id;
        $this->categoryText   = $category->name;
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-materials.raw-material-edit', [
            'rawMaterial'   => $this->rawMaterial()
        ]);
    }

    public function save(): void
    {
        $rawMaterial = $this->rawMaterial();

        $rules = [
            'name'           => ['required', 'string', 'max:128', Rule::unique('raw_materials')->ignore($this->rawMaterialId)],
            'description'    => ['nullable', 'string', 'max:255'],
            'minimum_stock'  => ['nullable', 'numeric', 'min:0'],
        ];

        if (!$this->hasBatches) {
            $this->abbreviation = mb_strtoupper($this->abbreviation, 'UTF-8');
            $rules['abbreviation'] = ['required', 'string', 'max:8', Rule::unique('raw_materials')->ignore($this->rawMaterialId)];
        }

        if ($this->unit_id !== $rawMaterial->unit_id) {
            $rules['unit_id'] = [
                'required',
                Rule::exists('units', 'id')->where('is_active', true)
            ];
        }

        if ($this->category_id !== $rawMaterial->category_id) {
            $rules['category_id'] = [
                'required',
                Rule::exists('categories', 'id')->where('is_active', true)
            ];
        }

        $validated = $this->validate($rules);

        $rawMaterial->update($validated);

        $this->toastSuccess('Materia prima actualizada.');
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
            $this->flashToastSuccess('Materia prima eliminada.');
            redirect()->route('raw-materials.index');
        }
    }

    private ?RawMaterial $rawMaterial = null;

    private function rawMaterial(): RawMaterial
    {
        return $this->rawMaterial ??= RawMaterial::findOrFail($this->rawMaterialId);
    }
}
