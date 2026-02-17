<?php

namespace App\Livewire\Inventory\RawMaterials;

use App\Models\Inventory\Category;
use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\Unit;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class RawMaterialCreate extends Component
{
    use Toast, FlashToast;

    public string $name;
    public string $abbreviation;
    public ?string $description = null;
    public ?float $minimum_stock = null;
    public int $unit_id;
    public int $category_id;

    public bool $createAnother = false;

    public function render(): View
    {
        return view('livewire.inventory.raw-materials.raw-material-create');
    }

    public function save(): void
    {
        $this->abbreviation = mb_strtoupper($this->abbreviation, 'UTF-8');

        $validated = $this->validate([
            'name'           => ['required', 'string', 'max:128', Rule::unique('raw_materials')],
            'abbreviation'   => ['required', 'string', 'max:8', Rule::unique('raw_materials')],
            'description'    => ['nullable', 'string', 'max:255'],
            'minimum_stock'  => ['nullable', 'numeric', 'min:0'],
            'unit_id'        => [
                'required',
                Rule::exists('units', 'id')->where('is_active', true)
            ],
            'category_id'    => [
                'required',
                Rule::exists('categories', 'id')->where('is_active', true)
            ]
        ]);

        RawMaterial::create($validated);

        if ($this->createAnother) {
            $this->reset([
                'name',
                'abbreviation',
                'description',
                'minimum_stock',
                'unit_id',
                'category_id',
            ]);

            $this->dispatch('reset');
            $this->toastSuccess('Materia prima creada.');
        } else {
            $this->flashToastSuccess('Materia prima creada.');
            redirect()->route('raw-materials.index');
        }
    }
}
