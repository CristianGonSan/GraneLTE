<?php

namespace App\Livewire\Inventory\RawMaterialStocks;

use App\Models\Inventory\RawMaterialStock;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalStockShow extends Component
{
    public ?int $stockId = null;

    public function render(): View
    {
        return view(
            'livewire.inventory.raw-material-stocks.modal-stock-show',
            [
                'stock' => $this->stock(),
            ]
        );
    }

    #[On('openComponentRawMaterialStockShow')]
    public function openModal(int $stockId): void
    {
        abort_if(cannot('raw-material-stocks.view'), 403);

        $this->stockId = $stockId;
        $this->dispatch('showModalRawMaterialStockShow');
    }

    #[On('closeComponentRawMaterialStockShow')]
    public function closeModal(): void
    {
        $this->stockId = null;
        $this->dispatch('hideModalRawMaterialStockShow');
    }

    private ?RawMaterialStock $stock = null;

    private function stock(): ?RawMaterialStock
    {
        if ($this->stockId === null) {
            return null;
        }

        return $this->stock ??= RawMaterialStock::findOrFail($this->stockId);
    }
}
