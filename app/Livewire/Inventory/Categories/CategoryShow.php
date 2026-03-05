<?php

namespace App\Livewire\Inventory\Categories;

use App\Models\Inventory\Category;
use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CategoryShow extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

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
        $this->toastSuccess(
            $this->category()->toggleActive()
                ? 'Categoría activada'
                : 'Categoría desactivada'
        );
    }

    public function delete(): void
    {
        $category = $this->category();

        if ($category->isInUse()) {
            $this->alertError(
                'La categoría está en uso, se recomienda desactivarla.',
                'Categoría en uso'
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
