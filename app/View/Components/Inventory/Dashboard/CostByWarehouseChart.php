<?php

namespace App\View\Components\Inventory\Dashboard;

use App\Models\Inventory\RawMaterialStock;
use Illuminate\Support\Collection;

class CostByWarehouseChart extends CostChartComponent
{
    public function __construct()
    {
        $this->title   = 'Costo valorizado por almacén';
        $this->chartId = 'chart-cost-by-warehouse';

        $this->applyTopNWithOthers($this->resolveData());
    }

    private function resolveData(): Collection
    {
        return RawMaterialStock::query()
            ->selectRaw('
                warehouses.name AS label,
                SUM(
                    raw_material_stocks.current_quantity *
                    batches.received_unit_cost
                )               AS total_cost
            ')
            ->join('raw_material_batches as batches', 'batches.id', '=', 'raw_material_stocks.batch_id')
            ->join('warehouses', 'warehouses.id', '=', 'raw_material_stocks.warehouse_id')
            ->where('raw_material_stocks.current_quantity', '>', 0)
            ->groupBy('warehouses.id', 'warehouses.name')
            ->orderByDesc('total_cost')
            ->get();
    }
}
