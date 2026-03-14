<?php

namespace App\View\Components\Inventory\Dashboard;

use App\Models\Inventory\RawMaterial;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class LowStockMaterialsTable extends Component
{
    /** @var Collection<int, RawMaterial> */
    public Collection $materials;

    public function __construct()
    {
        $this->materials = $this->resolveMaterials();
    }

    public function render(): View
    {
        return view('components.inventory.dashboard.low-stock-materials-table');
    }

    /** @return Collection<int, RawMaterial> */
    private function resolveMaterials(): Collection
    {
        return RawMaterial::active()
            ->selectRaw('
                raw_materials.*,
                (minimum_stock - current_quantity)            AS difference,
                (current_quantity / minimum_stock * 100)      AS stock_percentage
            ')
            ->with([
                'unit:id,symbol',
                'category:id,name',
            ])
            ->where('minimum_stock', '>', 0)
            ->whereColumn('current_quantity', '<', 'minimum_stock')
            ->orderByRaw('current_quantity - minimum_stock ASC')
            ->limit(10)
            ->get();
    }
}
