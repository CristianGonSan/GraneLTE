<?php

namespace App\Livewire\Inventory\Categories;

use App\Models\Inventory\Category;

use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class CategoryEdit extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

    public int $categoryId;

    public string $name;
    public ?string $description;


    public function mount(int $categoryId): void
    {
        $this->categoryId   = $categoryId;

        $category = $this->category();

        $this->name         = $category->name;
        $this->description  = $category->description;
    }


    public function render(): View
    {
        return view('livewire.inventory.categories.category-edit', [
            'category' => $this->category()
        ]);
    }


    public function save(): void
    {
        $categoryId = $this->categoryId;

        $validated = $this->validate([
            'name'          => ['required', 'string', 'max:64', Rule::unique('categories')->ignore($categoryId)],
            'description'   => ['nullable', 'string', 'max:255']
        ]);

        $this->category()->update($validated);

        $this->toastSuccess('Categoria actualizada.');
    }

    public function toggleActive(): void
    {
        $this->toastSuccess($this->category()->toggleActive() ?
            'Categoria activada' :
            'Categoria desactivada');
    }

    public function delete(): void
    {
        $category = $this->category();

        if ($category->isInUse()) {
            $this->alertError('La Categoria esta en uso, sugerimos desactivarla.', 'Categoria en Uso.');
        } else {
            $category->delete();
            $this->flashToastSuccess('Categoria eliminada.');
            redirect()->route('categories.index');
        }
    }

    private ?Category $category = null;

    private function category(): Category
    {
        return $this->category ??= Category::findOrFail($this->categoryId);
    }
}
