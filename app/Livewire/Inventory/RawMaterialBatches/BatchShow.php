<?php

namespace App\Livewire\Inventory\RawMaterialBatches;

use App\Models\Inventory\RawMaterialBatch;
use App\Models\Inventory\Unit;
use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class BatchShow extends Component
{
    use Toast, FlashToast, Alert, FlashAlert;

    #[Locked]
    public int $batchId;

    public function mount(int $batchId): void
    {
        $this->batchId = $batchId;
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-material-batches.batch-show', [
            'batch' => $this->batch()
        ]);
    }

    private ?RawMaterialBatch $batch = null;

    private function batch(): RawMaterialBatch
    {
        return $this->batch ??= RawMaterialBatch::findOrFail($this->batchId);
    }
}
