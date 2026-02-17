<?php

namespace App\Livewire\Inventory\RawMaterialStocks;

use App\Models\Inventory\RawMaterialBatch;
use App\Models\Inventory\RawMaterialMovement;
use App\Models\Inventory\RawMaterialStock;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalStockShow extends Component
{
    public bool $showModal = false;
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

    #[On('showStock')]
    public function openModal(?int $id): void
    {
        $this->stockId      = $id;
        $this->showModal    = true;
    }

    public function closeModal(): void
    {
        $this->stockId      = null;
        $this->showModal    = false;
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
