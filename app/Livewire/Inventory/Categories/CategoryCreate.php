<?php

namespace App\Livewire\Inventory\Categories;

use App\Models\Inventory\Category;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class CategoryCreate extends Component
{
    use Toast, FlashToast;

    public string $name;
    public ?string $description;

    public bool $createAnother = false;

    public function render(): View
    {
        return view('livewire.inventory.categories.category-create');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'          => ['required', 'string', 'max:64', Rule::unique('categories')],
            'description'   => ['nullable', 'string', 'max:255']
        ]);

        $category = Category::create($validated);

        if ($this->createAnother) {
            $this->reset([
                'name',
                'description'
            ]);
            $this->toastSuccess('Categoría creada');
        } else {
            $this->flashToastSuccess('Categoría creada');
            redirect()->route('categories.show', $category->id);
        }
    }
}
