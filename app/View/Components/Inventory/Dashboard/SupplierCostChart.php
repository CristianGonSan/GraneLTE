<?php

namespace App\View\Components\Inventory\Dashboard;

use App\Models\Inventory\RawMaterialBatch;
use Illuminate\Support\Collection;

class SupplierCostChart extends CostChartComponent
{
    public array $labels = [];
    public array $values = [];

    public function __construct()
    {
        $this->title   = 'Costo valorizado por proveedor';
        $this->chartId = 'chart-cost-by-supplier';

        $this->applyTopNWithOthers($this->resolveData());
    }

    private function resolveData(): Collection
    {
        return RawMaterialBatch::query()
            ->selectRaw('
                suppliers.name AS label,
                SUM(
                    raw_material_batches.current_quantity *
                    raw_material_batches.received_unit_cost
                )              AS total_cost
            ')
            ->join('suppliers', 'suppliers.id', '=', 'raw_material_batches.supplier_id')
            ->where('raw_material_batches.current_quantity', '>', 0)
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderByDesc('total_cost')
            ->get();
    }
}
