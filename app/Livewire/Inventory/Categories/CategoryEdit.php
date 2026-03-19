<?php

namespace App\Livewire\Inventory\Categories;

use App\Models\Inventory\Category;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class CategoryEdit extends Component
{
    use Toast, FlashToast;

    public int $categoryId;

    public string $name;
    public ?string $description;

    public function mount(int $categoryId): void
    {
        $this->categoryId   = $categoryId;

        $category           = $this->category();

        $this->name         = $category->name;
        $this->description  = $category->description;
    }

    public function render(): View
    {
        return view('livewire.inventory.categories.category-edit');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'          => ['required', 'string', 'max:64', Rule::unique('categories')->ignore($this->categoryId)],
            'description'   => ['nullable', 'string', 'max:255']
        ]);

        $this->category()->update($validated);

        $this->toastSuccess('Categoria actualizada');
    }

    private ?Category $category = null;

    private function category(): Category
    {
        return $this->category ??= Category::findOrFail($this->categoryId);
    }
}
