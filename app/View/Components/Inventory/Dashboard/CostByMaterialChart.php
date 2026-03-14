<?php

namespace App\View\Components\Inventory\Dashboard;

use App\Models\Inventory\RawMaterialBatch;
use Illuminate\Support\Collection;

class CostByMaterialChart extends CostChartComponent
{
    public function __construct()
    {
        $this->title   = 'Costo valorizado por material';
        $this->chartId = 'chart-cost-by-material';

        $this->applyTopNWithOthers($this->resolveData());
    }

    private function resolveData(): Collection
    {
        return RawMaterialBatch::query()
            ->selectRaw('
                raw_materials.name AS label,
                SUM(
                    raw_material_batches.current_quantity *
                    raw_material_batches.received_unit_cost
                )                  AS total_cost
            ')
            ->join('raw_materials', 'raw_materials.id', '=', 'raw_material_batches.material_id')
            ->where('raw_material_batches.current_quantity', '>', 0)
            ->groupBy('raw_materials.id', 'raw_materials.name')
            ->orderByDesc('total_cost')
            ->get();
    }
}
