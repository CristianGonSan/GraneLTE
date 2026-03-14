<?php

namespace App\View\Components\Inventory\Dashboard;

use App\Models\Inventory\RawMaterialStock;
use Illuminate\View\Component;
use Illuminate\View\View;

class NegativeStockAlert extends Component
{
    public int $count;

    public function __construct()
    {
        $this->count = RawMaterialStock::where('current_quantity', '<', 0)->count();
    }

    public function render(): View
    {
        return view('components.inventory.dashboard.negative-stock-alert');
    }
}
