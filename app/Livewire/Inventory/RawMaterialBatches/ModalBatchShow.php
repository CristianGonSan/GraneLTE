<?php

namespace App\Livewire\Inventory\RawMaterialBatches;

use App\Models\Inventory\RawMaterialBatch;
use App\Models\Inventory\RawMaterialMovement;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalBatchShow extends Component
{
    public bool $showModal = false;
    public ?int $batchId = null;


    public function render(): View
    {
        return view(
            'livewire.inventory.raw-material-batches.modal-batch-show',
            [
                'batch' => $this->batch(),
            ]
        );
    }

    #[On('showBatch')]
    public function openModal(?int $id): void
    {
        $this->batchId      = $id;
        $this->showModal    = true;
    }

    public function closeModal(): void
    {
        $this->batchId      = null;
        $this->showModal    = false;
    }

    private ?RawMaterialBatch $batch = null;

    private function batch(): RawMaterialBatch|null
    {
        if ($this->batchId === null) {
            return null;
        }
        return $this->batch ??= RawMaterialBatch::findOrFail($this->batchId);
    }
}
