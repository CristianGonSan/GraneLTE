<?php

namespace App\Livewire\Inventory\Categories;

use App\Models\Inventory\Category;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CategoryShow extends Component
{
    use Toast, FlashToast;

    #[Locked]
    public int $categoryId;

    public function mount(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function render(): View
    {
        return view('livewire.inventory.categories.category-show', [
            'category' => $this->category()
        ]);
    }

    public function toggleActive(): void
    {
        if (cannot('categories.toggle')) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $this->toastSuccess(
            $this->category()->toggleActive()
                ? 'Categoría activada'
                : 'Categoría desactivada'
        );
    }

    public function delete(): void
    {
        if (cannot('categories.delete')) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $category = $this->category();

        if ($category->isInUse()) {
            $this->toastError(
                'No se puede eliminar: la categoría está en uso'
            );
        } else {
            $category->delete();
            $this->flashToastSuccess('Categoría eliminada');
            redirect()->route('categories.index');
        }
    }

    private ?Category $category = null;

    private function category(): Category
    {
        return $this->category ??= Category::findOrFail($this->categoryId);
    }
}
