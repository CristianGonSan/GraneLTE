<?php

namespace App\Livewire\Inventory\RawMaterialStocks;

use App\Models\Inventory\RawMaterialBatch;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ModalStockSelector extends Component
{
    use WithPagination, WithoutUrlPagination;

    public bool $showModal = false;
    public string $searchTerm = '';
    public string $order = 'fifo';

    #[Locked]
    public bool $closeAfterSeleted;

    #[Locked]
    public ?string $stockOperator = null;

    public function mount(bool $closeAfterSeleted = false, ?string $stockOperator = '>'): void
    {
        $this->closeAfterSeleted    = $closeAfterSeleted;
        $this->stockOperator        = $stockOperator;
    }

    public function render(): View
    {
        $batches = $this->getQuery()->paginate();

        return view(
            'livewire.inventory.raw-material-stocks.modal-stock-selector',
            [
                'batches' => $batches,
            ]
        );
    }

    public function selectStock(int $stockId): void
    {
        $this->dispatch('stockSelected', [
            'id' => $stockId
        ]);

        if ($this->closeAfterSeleted) {
            $this->closeModal();
        }
    }

    #[On('openStockSelector')]
    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    private function getQuery(): Builder
    {
        $query = RawMaterialBatch::with(['material.unit', 'stocks.warehouse']);

        if ($this->stockOperator) {
            $query->whereHas('stocks', function (Builder $stock) {
                $stock->where('current_quantity', $this->stockOperator, 0);
            });
        }

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term) {
                // Material
                $q->orWhereHas('material', function (Builder $m) use ($term) {
                    $m->where('name', 'like', "%{$term}%");
                });

                // Almacén
                $q->orWhereHas('stocks.warehouse', function (Builder $w) use ($term) {
                    $w->where('name', 'like', "%{$term}%");
                });

                // Lotes
                $q->orWhere('batch_code', 'like', "%{$term}%")
                    ->orWhere('external_batch_code', 'like', "%{$term}%");
            });

            // Priorizamos coincidencia en nombre del material
            $query->withCount([
                'material as material_match' => function (Builder $m) use ($term) {
                    $m->where('name', 'like', "%{$term}%");
                }
            ])->orderByDesc('material_match');
        }

        switch ($this->order) {
            case 'fifo':
                $query->FIFO();
                break;
            case 'fefo':
                $query->FEFO();
                break;
            case 'lifo':
                $query->LIFO();
                break;
        }

        return $query;
    }
}
