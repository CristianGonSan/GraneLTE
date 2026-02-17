<?php

namespace App\Livewire\Inventory\RawMaterialStocks;

use App\Models\Inventory\RawMaterialBatch;
use App\Models\Inventory\RawMaterialMovement;
use App\Models\Inventory\RawMaterialStock;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalStockSelector extends Component
{
    public bool $showModal = false;

    public function render(): View
    {
        return view(
            'livewire.inventory.raw-material-stocks.modal-stock-selector',
            [
                'stock' => $this->stock(),
            ]
        );
    }


    public function selectStock(): void
    {
        $this->dispatch('stockSeleted', [
            'id' => 1
        ]);
    }

    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    private ?RawMaterialStock $stock = null;

    private function stock(): RawMaterialStock|null
    {
        if ($this->stockId === null) {
            return null;
        }
        return $this->stock ??= RawMaterialStock::findOrFail($this->stockId);
    }
}
