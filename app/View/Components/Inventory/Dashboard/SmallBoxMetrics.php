<?php

namespace App\View\Components\Inventory\Dashboard;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\RawMaterialDocument;
use Illuminate\View\Component;
use Illuminate\View\View;

class SmallBoxMetrics extends Component
{
    public float $totalStockCost;
    public int $activeMaterials;
    public int $pendingDocuments;
    public int $lowStockMaterials;

    public function __construct()
    {
        $this->totalStockCost       = $this->resolveTotalStockCost();
        $this->activeMaterials      = $this->resolveActiveMaterials();
        $this->pendingDocuments     = $this->resolvePendingDocuments();
        $this->lowStockMaterials    = $this->resolveLowStockMaterials();
    }

    public function render(): View
    {
        return view('components.inventory.dashboard.small-box-metrics');
    }

    // --- Resolvers ---

    private function resolveTotalStockCost(): float
    {
        return RawMaterial::totalCost();
    }

    private function resolveActiveMaterials(): int
    {
        return RawMaterial::active()->count();
    }

    private function resolvePendingDocuments(): int
    {
        return RawMaterialDocument::where('status', RawMaterialDocumentStatus::PENDING)->count();
    }

    private function resolveLowStockMaterials(): int
    {
        return RawMaterial::active()
            ->where('minimum_stock', '>', 0)
            ->whereColumn('current_quantity', '<', 'minimum_stock')
            ->count();
    }
}
