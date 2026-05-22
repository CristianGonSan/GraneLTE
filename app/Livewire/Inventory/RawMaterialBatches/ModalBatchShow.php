<?php

namespace App\Livewire\Inventory\RawMaterialBatches;

use App\Models\Inventory\RawMaterialBatch;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalBatchShow extends Component
{
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

    #[On('openComponentRawMaterialBatchShow')]
    public function openModal(int $batchId): void
    {
        abort_if(cannot('raw-material-batches.view'), 403);

        $this->batchId = $batchId;
        $this->dispatch('showModalRawMaterialBatchShow');
    }

    #[On('closeComponentRawMaterialBatchShow')]
    public function closeModal(): void
    {
        $this->batchId = null;
        $this->dispatch('hideModalRawMaterialBatchShow');
    }

    private ?RawMaterialBatch $batch = null;

    private function batch(): ?RawMaterialBatch
    {
        if ($this->batchId === null) {
            return null;
        }

        return $this->batch ??= RawMaterialBatch::findOrFail($this->batchId);
    }
}
