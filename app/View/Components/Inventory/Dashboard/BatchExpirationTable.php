<?php

namespace App\View\Components\Inventory\Dashboard;

use App\Models\Inventory\RawMaterialBatch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class BatchExpirationTable extends Component
{
    /** @var Collection<int, RawMaterialBatch> */
    public Collection $batches;

    public function __construct()
    {
        $this->batches = $this->resolveBatches();
    }

    public function render(): View
    {
        return view('components.inventory.dashboard.batch-expiration-table');
    }

    /** @return Collection<int, RawMaterialBatch> */
    private function resolveBatches(): Collection
    {
        return RawMaterialBatch::where('current_quantity', '>', 0)->expiring(30, includeExpired: true)
            ->with([
                'material:id,name,abbreviation,unit_id',
                'material.unit:id,symbol'
            ])
            ->orderByDesc('expiration_date')
            ->limit(10)
            ->get();
    }
}
